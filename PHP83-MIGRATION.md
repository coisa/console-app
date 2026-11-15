# Migration to a Fast Forward console sandbox

This previously unused application is becoming a PHP 8.3 template and a real
integration sandbox for developing the framework's console classes.

- Runtime: PHP 8.3 and Composer 2; dependency installation failures fail the build.
- Configuration and PSR-11 composition: published `fast-forward/config ^1.7.4`
  and `fast-forward/container ^1.10.1`, not local imitations.
- Console: Symfony 7.4 and Silly expression parsing are temporary adapters.
  The Fast Forward console and logger repositories are empty at migration time;
  this project does not pretend that installable implementations exist there.
- Logging: replaces the PHP-7-only `coisa/monolog` provider with Monolog 3 to stderr.
  Logger aliases and command LoggerAware injection remain available. Custom
  handlers/processors from that old provider are not automatically migrated.
- Scheduler: existing configured jobs and callbacks are retained through GO v4.
  This overlaps the version bump in Dependabot #3; that PR is not merged here.
- Routes, event dispatching and lazy PDO construction remain available. No database
  migration or deployment occurs. MySQL #2 is independent.
- Configuration local files override global files. Legacy generated Zend cache is
  not reused; bootstrapping reconstructs the configuration. Future cache support
  should use the Fast Forward config cache API and explicit invalidation tests.

## Validation

`composer test` checks real composition, logging, command execution, and scheduler
construction without executing scheduled commands or connecting to a database.
CI additionally resolves dependencies, lints PHP and builds the production image.
No platform/security ignores are used. Local PHP, Composer and Docker were absent
when this work started; only actual CI results establish runtime compatibility.
