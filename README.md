# PHPStan SensitiveParameter Detector

[![CI](https://github.com/built-fast/phpstan-sensitive-parameter/workflows/CI/badge.svg)](https://github.com/built-fast/phpstan-sensitive-parameter/actions)
[![Latest Stable Version](https://poser.pugx.org/built-fast/phpstan-sensitive-parameter/v/stable)](https://packagist.org/packages/built-fast/phpstan-sensitive-parameter)
[![Total Downloads](https://poser.pugx.org/built-fast/phpstan-sensitive-parameter/downloads)](https://packagist.org/packages/built-fast/phpstan-sensitive-parameter)
[![License](https://poser.pugx.org/built-fast/phpstan-sensitive-parameter/license)](https://packagist.org/packages/built-fast/phpstan-sensitive-parameter)

A PHPStan extension that detects parameters that might contain sensitive information and should be marked with the `#[\SensitiveParameter]` attribute (added in PHP 8.2+).

## About SensitiveParameter

The `#[\SensitiveParameter]` attribute was introduced in PHP 8.2 to mark sensitive data that should be hidden from stack traces and debugging output. This extension helps you identify parameters that should use this attribute for better security.

Learn more: [PHP RFC: Redact parameters in back traces](https://wiki.php.net/rfc/redact_parameters_in_back_traces)

## Requirements

- PHP 8.2 or higher
- PHPStan 2.0 or higher

## Installation

```bash
composer require --dev built-fast/phpstan-sensitive-parameter
```

## Usage

The extension will be automatically registered if you use [PHPStan's extension installer](https://github.com/phpstan/extension-installer).

Alternatively, include the extension in your PHPStan configuration:

```neon
includes:
    - vendor/built-fast/phpstan-sensitive-parameter/extension.neon
```

## What it detects

The rule detects parameters with names containing common sensitive keywords:

- Authentication: `password`, `secret`, `token`, `credential`, `auth`, `bearer`
- API Security: `apikey` (matches `apisecret`, `clientsecret` via `secret`)
- Financial: `credit`, `card`, `ccv`, `cvv`, `ssn`, `pin`
- Security: `private`, `signature`, `hash`, `salt`, `nonce`, `otp`, `passcode`, `csrf`

Note: Due to substring matching, `secret` catches `apisecret`/`clientsecret` and `token` catches `refreshtoken`/`accesstoken`.

It works with:

- Regular functions
- Class methods (public, private, protected, static)
- Constructors
- Case-insensitive matching (`Password`, `SECRET`, etc.)
- Partial matches (`userPassword`, `secretKey`, etc.)

## Examples

### ❌ Will trigger warnings:

```php
function login(string $username, string $password) {
    // Parameter $password should use #[\SensitiveParameter]
}

class AuthService {
    public function setCredentials(string $apikey, string $secret) {
        // Both $apikey and $secret should be marked sensitive
    }
}
```

### ✅ Properly protected:

```php
// Function-level protection
#[\SensitiveParameter]
function login(string $username, string $password) {
    // All parameters are protected
}

// Parameter-level protection
function authenticate(
    string $username,
    #[\SensitiveParameter] string $password
) {
    // Only $password is protected
}

// Mixed protection
class AuthService {
    public function verify(
        #[\SensitiveParameter] string $token,
        string $userId,
        string $apikey  // This will still trigger a warning
    ) {
        // $token is protected, $apikey needs protection
    }
}
```

## Advanced Configuration

To use custom sensitive keywords instead of the defaults, override the service:

```neon
includes:
    - vendor/built-fast/phpstan-sensitive-parameter/extension.neon

services:
    # Override the default service with custom keywords
    -
        class: BuiltFast\Rules\SensitiveParameterDetectorRule
        arguments:
            - ['password', 'apikey', 'token', 'banking', 'medical']  # Your custom keywords
        tags:
            - phpstan.rules.rule
```

This completely replaces the default keyword list with your own.

## Suppressing Warnings

You can suppress warnings using PHPStan's ignore comments:

```php
// @phpstan-ignore-next-line sensitiveParameter.missing
function legacyFunction(string $password) {
    // Legacy code that cannot be updated
}

// @phpstan-ignore-next-line sensitiveParameter.missing
function anotherLegacyFunction(string $secret) {
    // Another legacy function
}

function modernFunction(string $password): void // @phpstan-ignore-line sensitiveParameter.missing
{
    // Function with inline ignore comment
}
```

## Reporting Issues

Found a bug or have a feature request? Please [report it on GitHub](https://github.com/built-fast/phpstan-sensitive-parameter/issues).

When reporting issues, please include:

- PHP version
- PHPStan version
- Code sample that demonstrates the issue
- Expected vs actual behavior

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request. For major changes, please open an issue first to discuss what you would like to change.

**Development setup:**

```bash
git clone https://github.com/built-fast/phpstan-sensitive-parameter.git
cd phpstan-sensitive-parameter
composer install
```

**Running tests:**

```bash
vendor/bin/pest             # Run tests
vendor/bin/phpstan analyze  # Static analysis
vendor/bin/pint --test      # Code style check
```

## License

MIT License - see [`LICENSE`](./LICENSE) for details.
