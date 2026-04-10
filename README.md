<p align="center">
  <img src="https://www.seven.io/wp-content/uploads/Logo.svg" width="250" />
</p>

<h1 align="center">seven.io TYPO3 Extension</h1>

<p align="center">
  Send SMS, voice messages and perform number lookups directly from the TYPO3 backend.
  <br />
  <a href="https://www.seven.io"><strong>seven.io</strong></a> &middot;
  <a href="https://docs.seven.io/en">API Docs</a> &middot;
  <a href="https://extensions.typo3.org/extension/seventypo3">TER</a>
</p>

<p align="center">
  <img src="https://img.shields.io/badge/TYPO3-12%20|%2013-orange" alt="TYPO3 12 | 13" />
  <img src="https://img.shields.io/badge/PHP-8.2%2B-blue" alt="PHP 8.2+" />
  <img src="https://img.shields.io/badge/license-MIT-green" alt="MIT License" />
</p>

---

## Installation

**Composer (recommended)**

```bash
composer require seven.io/typo3
```

**TYPO3 Extension Repository (TER)**

Download via the Extension Manager from the [extension page](https://extensions.typo3.org/extension/seventypo3).

## Configuration

1. Install the extension
2. Go to **Admin Tools > Settings > Extension Configuration > seventypo3**
3. Enter your [seven.io API key](https://app.seven.io/developer)

## Features

| Feature | Description |
|---------|-------------|
| **SMS** | Send single or bulk SMS to frontend users or custom recipients |
| **Voice** | Send text-to-speech voice messages |
| **Lookup** | Number lookups: CNAM, Format, HLR, MNP |
| **History** | Full message and lookup history with config/response details |

## Requirements

| Dependency | Version |
|------------|---------|
| TYPO3 | 12.4+ or 13.4+ |
| PHP | 8.2+ |
| seven.io API key | [Sign up](https://www.seven.io) |

## Changelog

### 1.0.0

- TYPO3 12/13 LTS compatibility
- Migrated to new backend module registration API
- Updated to seven.io PHP SDK v8 (`seven.io/api`)
- PHP 8.2+ with typed properties and modern language features
- Replaced deprecated APIs (`ObjectManagerInterface`, `BackendTemplateView`, `FlashMessage` constants)
- Controllers return PSR-7 `ResponseInterface`
- Icon registration via `Configuration/Icons.php`
- Removed obsolete SMS parameters (`unicode`, `utf8`, `details`, `json`, `no_reload`, `return_msg_id`)

### 0.4.0

- Initial seven.io branded release (TYPO3 10.x)

## License

[MIT](LICENSE.txt)
