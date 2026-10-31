var hm_googlemap = null;

if (eval("typeof acf") != undefined) {
  console.log('ACF IS HERE!');  
  
  acf.addAction('google_map_init', function(map, marker, field) {
    hm_googlemap = map;

    //Override the searchPosition function on the 
    //map ACF Field which normally does a geocode search
    field.__proto__.searchPosition = function(lat, lng) {	      
      var zoom = (hm_googlemap !== null) ? hm_googlemap.zoom : 15;
      var val = {
        address: "Custom Location: (" + lat + ", " + lng + ")",
        lat: lat,
        lng: lng,
        zoom: zoom,
        country: "", 
        country_short: "", 
        place_id: "",
        post_code: "", 
        state: "", 
        street_name: "", 
        street_name_short: "",
      };
      
//console.log(val);
	  this.val(val);				
    };    
  });  
}

// The SER ID and NPI are identifiers, not quantities. A focused number input
// steps its value when the mouse wheel turns over it, so scrolling the edit
// screen could change one without anyone typing. Blur the input on wheel so the
// page scrolls and the value stays put.
document.addEventListener(
  'wheel',
  function (event) {
    var input = event.target;
    if (
      input &&
      input.matches &&
      input.matches(
        '.acf-field[data-name="physician_pid"] input[type="number"], .acf-field[data-name="physician_npi"] input[type="number"]'
      ) &&
      document.activeElement === input
    ) {
      input.blur();
    }
  },
  { passive: true }
);
