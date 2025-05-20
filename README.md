# PHPStan Sensitive Parameter Detector

This PHPStan extension helps detect parameters that might contain sensitive information and should be marked with the `#[\SensitiveParameter]` attribute.

## Installation

```bash
composer require built-fast/phpstan-sensitive-parameter
```

## Usage

The extension will be automatically registered if you use [PHPStan's Composer plugin](https://github.com/phpstan/extension-installer).

Alternatively, include the extension in your PHPStan configuration:

```neon
includes:
    - vendor/built-fast/phpstan-sensitive-parameter/rules.neon
```

## Configuration

The rule detects parameters with names containing common sensitive keywords like 'password', 'secret', 'token', etc.

You can customize the list of sensitive keywords by adding them in your `phpstan.neon` file:

```neon
parameters:
    sensitiveParameterDetector:
        additionalKeywords:
            - client
            - customer
            - account
            - user
```

## Example

```php
// This will raise a PHPStan error
function login(string $username, string $password) {
    // ...
}

// This is fine
function login(string $username, #[\SensitiveParameter] string $password) {
    // ...
}
```
