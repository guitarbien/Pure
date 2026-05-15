# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0/).

## [Unreleased]

### Changed

- **⚠️ BREAKING:** Minimum PHP version raised from 7.2 to **8.4**
- `symfony/http-foundation` upgraded `^4.0` → `^7.0`
- `twig/twig` upgraded `^2.4` → `^3.0`
- `doctrine/dbal` upgraded `^2.6` → `^4.0`
- `ramsey/uuid` upgraded `^3.7` → `^4.0`
- `phpunit/phpunit` (dev) upgraded `^7.1` → `^11.0`
- `tracy/tracy` (dev) upgraded `^2.4` → `^3.0`

### Added

- PHP 8.x native type declarations added across all `src/` classes

### Removed

- `rdlowrey/auryn` removed (abandoned, PHP 8.x incompatible); replaced by `php-di/php-di ^7.0`

### Migration Notes

If you are upgrading from a PHP 7.x environment:

1. Upgrade your PHP runtime to **8.4 or later**.
2. Run `composer update` to regenerate `composer.lock`.
3. The DI container API has changed — `Bootstrap.php` and `Dependencies.php` now use PHP-DI `ContainerBuilder` instead of Auryn `Injector`.
4. Doctrine DBAL 4.x removed several legacy methods; see commit history for the full API migration list.
