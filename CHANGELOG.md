# Changelog

All notable changes to `aliaser` will be documented in this file.

## [0.1.2] - 2025-12-19

### Added

- Automatic Livewire version detection
- Support for projects without Livewire installed

### Changed

- **Breaking**: Livewire 2.x is temporarily not supported
- Livewire synthesizers now only register for Livewire 3.x or High
- Improved service provider registration logic

### Fixed

- Prevented errors when Livewire 2.x is installed
- Package no longer crashes in projects without Livewire

## [0.1.1] - 2025-12-18

### Fixed

- Minor bug fixes and improvements

## [0.1.0] - 2025-12-17

### Added

- Initial release
- Model aliases with Entity facade
- Form aliases for Livewire
- Object aliases (DTOs, Value Objects)
- Collection aliases
- Enum aliases
- Livewire Synthesizers for all types
- Console commands: install, list, help
