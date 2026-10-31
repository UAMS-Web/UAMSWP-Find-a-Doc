'use strict';

/**
 * Pins the behavior of admin/js/acf-admin.js.
 *
 * The script is loaded unchanged into a jsdom window that carries a stub of ACF's `acf` global,
 * so what is asserted is what the real file does when ACF runs it.
 *
 * The script replaces the Google Map field's `searchPosition`, and blurs a focused SER ID or NPI
 * number input when the mouse wheel turns over it, so the wheel scrolls the page instead of
 * stepping the identifier. Other number inputs are left to the browser.
 *
 * Run: npm test
 */

const assert = require('node:assert/strict');
const fs = require('node:fs');
const path = require('node:path');
const { test } = require('node:test');
const { JSDOM } = require('jsdom');

const scriptPath = path.resolve(__dirname, '..', '..', 'admin', 'js', 'acf-admin.js');
const source = fs.readFileSync(scriptPath, 'utf8');

/**
 * Runs acf-admin.js in a fresh window and returns the window plus the actions it registered.
 */
function load(markup = '') {
    const dom = new JSDOM(`<!doctype html><body>${markup}</body>`, { runScripts: 'outside-only' });
    const { window } = dom;
    const actions = {};

    window.console.log = () => {};
    window.acf = {
        addAction(name, callback) {
            actions[name] = callback;
        },
    };
    window.eval(source);

    return { window, actions };
}

/**
 * A stand-in for an ACF Google Map field. The script replaces searchPosition on the field's
 * prototype, so the prototype is a fresh object per call.
 */
function mapField() {
    const proto = {};
    const field = Object.create(proto);
    field.__proto__ = proto;
    field.stored = null;
    field.val = function (value) {
        this.stored = value;
    };

    return field;
}

test('the script registers one handler, for the Google Map init action', () => {
    const { actions } = load();

    assert.deepEqual(Object.keys(actions), ['google_map_init']);
});

test('the map init handler replaces searchPosition so a coordinate is stored without a geocode search', () => {
    const { actions } = load();
    const field = mapField();

    actions.google_map_init({ zoom: 12 }, {}, field);
    field.searchPosition(34.7465, -92.2896);

    assert.deepEqual(JSON.parse(JSON.stringify(field.stored)), {
        address: 'Custom Location: (34.7465, -92.2896)',
        lat: 34.7465,
        lng: -92.2896,
        zoom: 12,
        country: '',
        country_short: '',
        place_id: '',
        post_code: '',
        state: '',
        street_name: '',
        street_name_short: '',
    });
});

test('the stored zoom is 15 until the map has been initialized', () => {
    const { actions } = load();
    const field = mapField();

    actions.google_map_init(null, {}, field);
    field.searchPosition(1, 2);

    assert.equal(field.stored.zoom, 15);
});

function wheelOver(markup, focused) {
    const { window } = load(markup);
    const input = window.document.querySelector('input');

    if (focused) {
        input.focus();
    }

    const event = new window.WheelEvent('wheel', { deltaY: 100, bubbles: true, cancelable: true });
    input.dispatchEvent(event);

    return { window, input, event };
}

for (const name of ['physician_pid', 'physician_npi']) {
    const field = `<div class="acf-field" data-name="${name}"><input type="number" value="1234567890"></div>`;

    test(`a wheel event over a focused ${name} number field blurs it`, () => {
        const { window, input, event } = wheelOver(field, true);

        assert.notEqual(window.document.activeElement, input, 'focus is moved off the field');
        assert.equal(event.defaultPrevented, false, 'the page still scrolls');
        assert.equal(input.value, '1234567890');
    });

    test(`a wheel event over an unfocused ${name} number field changes nothing`, () => {
        const { window, input, event } = wheelOver(field, false);

        assert.notEqual(window.document.activeElement, input);
        assert.equal(event.defaultPrevented, false);
        assert.equal(input.value, '1234567890');
    });
}

test('a focused number field outside the SER ID and NPI fields keeps its focus', () => {
    const { window, input } = wheelOver(
        '<div class="acf-field" data-name="physician_rating"><input type="number" value="4"></div>',
        true
    );

    assert.equal(window.document.activeElement, input);
});

test('a focused text input inside the NPI field keeps its focus', () => {
    const { window, input } = wheelOver(
        '<div class="acf-field" data-name="physician_npi"><input type="text" value="1234567890"></div>',
        true
    );

    assert.equal(window.document.activeElement, input);
});
