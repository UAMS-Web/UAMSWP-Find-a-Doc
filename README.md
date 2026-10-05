# UAMSWP-Find-a-Doc

## Tests

```bash
composer install
composer test:unit
```

The supported PHP floor is 7.4, so this repository uses PHPUnit 9 from the shared `tests` skill templates (Pest requires PHP 8.2+).

## Continuous integration

Pull requests are gated by the GitHub Actions workflow in [`.github/workflows/ci.yml`](.github/workflows/ci.yml). It runs Pint, Rector (dry-run), PHPStan, and the Unit suite on every push and pull request. A red check fails the workflow.
