# Fast Forward console sandbox

PHP 8.3 application template and integration sandbox for the future Fast Forward
console components. Uses real Fast Forward config/container packages; Symfony
Console is the current execution adapter, not a claimed Fast Forward console API.

```sh
composer install
composer test
php bin/console list
php bin/console console:index hello --yell
```

Create commands under `src/Command`, register their expressions in
`config/routes.php`, and keep framework experiments behind factories/providers.
Configuration comes from `config/settings.php` and `config/autoload/*.global.php`,
then local overrides. Do not commit credentials. Logs go to stderr.

`bin/scheduler` executes configured scheduled jobs. Only run it intentionally.
`composer test` constructs the scheduler without executing those jobs.

See [migration notes](PHP83-MIGRATION.md) for behavior changes, validation limits,
and the boundary between this sandbox and libraries that do not yet exist.
Repository-template status is a GitHub setting, separate from this source tree.
