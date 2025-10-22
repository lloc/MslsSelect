# Repository Guidelines

## Project Structure & Module Organization
- `MslsSelect.php` bootstraps the plugin and stays the single PHP entry point.
- `js/mslsselect.js` (with the minified twin) powers the UI; update both when tweaking frontend behavior.
- `tests/` houses PHPUnit specs and Brain Monkey helpers; keep fixtures beside the tests that use them.
- `bin/` contains contributor utilities (`git-release.sh`, githooks); call these scripts instead of duplicating logic.
- `mslsselect/` and `mslsselect.zip` are release artifacts regenerated only through the build script.

## Build, Test, and Development Commands
- `composer install` installs PHP tooling; rerun after dependency bumps or fresh clones.
- `composer test` executes the PHPUnit suite defined by `phpunit.xml`.
- `composer phpstan` runs static analysis; clear warnings before opening a PR.
- `composer coverage` writes HTML reports to `coverage/` (requires Xdebug).
- `composer githooks` copies the pre-commit hook into `.git/hooks/`.
- `composer build` invokes `bin/git-release.sh` and refreshes the distributable.

## Coding Style & Naming Conventions
- Follow WordPress PHP style: tabs for indentation, PascalCase classes, `snake_case` functions prefixed with `mslsselect_`.
- Lint PHP via `vendor/bin/phpcs --standard=WordPress MslsSelect.php tests` before committing.
- Mirror the spacing and semicolon usage already present in `js/mslsselect.js` for JavaScript updates.

## Testing Guidelines
- Name PHPUnit classes `*Test.php` and mirror the namespace or class they cover.
- Use Brain Monkey helpers for WordPress hooks instead of creating globals manually.
- Run `composer test` before pushing; prefer regression cases that assert rendered HTML or hook behavior.

## Commit & Pull Request Guidelines
- Write concise, imperative commit subjects (`Add select placeholder`) and reference issues in the body (`Refs #123`) when relevant.
- Summarize scope, manual testing, and screenshots (for UI changes) in every PR.
- Confirm local parity with CI by running `composer install`, `composer phpstan`, and `composer test` prior to review.

## Release & Packaging Notes
- Use `composer build` to regenerate `mslsselect/` and `mslsselect.zip`; avoid manual edits to packaged files.
- Before tagging, check the zip for `MslsSelect.php`, the JavaScript bundle, and required `vendor/` dependencies.
