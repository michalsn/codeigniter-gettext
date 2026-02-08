# CodeIgniter Gettext

This library gives users the ability to use [gettext](https://www.php.net/manual/en/book.gettext.php) in a friendlier way.

[![PHPUnit](https://github.com/michalsn/codeigniter-gettext/actions/workflows/phpunit.yml/badge.svg)](https://github.com/michalsn/codeigniter-gettext/actions/workflows/phpunit.yml)
[![PHPStan](https://github.com/michalsn/codeigniter-gettext/actions/workflows/phpstan.yml/badge.svg)](https://github.com/michalsn/codeigniter-gettext/actions/workflows/phpstan.yml)
[![Deptrac](https://github.com/michalsn/codeigniter-gettext/actions/workflows/deptrac.yml/badge.svg)](https://github.com/michalsn/codeigniter-gettext/actions/workflows/deptrac.yml)

![PHP](https://img.shields.io/badge/PHP-%5E8.2-blue)
![CodeIgniter](https://img.shields.io/badge/CodeIgniter-%5E4.3-blue)

## Requirements

- PHP 8.2 or higher
- CodeIgniter 4.3 or higher
- PHP gettext extension enabled

## Installation

#### Composer

    composer require michalsn/codeigniter-gettext

#### Manually

In the example below we will assume, that files from this project will be located in `app/ThirdParty/gettext` directory.

Download this project and then enable it by editing the `app/Config/Autoload.php` file and adding the `Michalsn\CodeIgniterGettext` namespace to the `$psr4` array, like in the below example:

```php
<?php

namespace Config;

use CodeIgniter\Config\AutoloadConfig;

class Autoload extends AutoloadConfig
{
    // ...
    public $psr4 = [
        APP_NAMESPACE => APPPATH, // For custom app namespace
        'Config'      => APPPATH . 'Config',
        'Michalsn\CodeIgniterGettext' => APPPATH . 'ThirdParty/gettext/src',
    ];

    // ...
```

## Configuration

Run the publish command to create the configuration file:

```bash
php spark gettext:publish
```

This will create a `Gettext.php` config file in the `app/Config/` folder with the following options:

### Config Options

```php
<?php

namespace Config;

use Michalsn\CodeIgniterGettext\Config\Gettext as BaseGettext;

class Gettext extends BaseGettext
{
    // Directory where translation files are stored
    public string $dir = APPPATH . 'Gettext' . DIRECTORY_SEPARATOR;

    // Default domain name (usually 'messages')
    public string $domain = 'messages';

    // List of allowed domains
    public array $allowedDomains = ['messages'];

    // Character encoding
    public string $codeset = 'UTF-8';

    // Locale mapping - maps CodeIgniter locales to system locales
    public array $locales = [
        'en' => 'en_US.utf8',
        'pl' => 'pl_PL.utf8',
        'de' => 'de_DE.utf8',
        'fr' => 'fr_FR.utf8',
    ];
}
```

### Important: Configure Supported Locales

You also need to configure supported locales in `app/Config/App.php`:

```php
public array $supportedLocales = ['en', 'pl', 'de', 'fr'];
```

### Folder structure

This is how your folder structure should look like.

```
app/
├── Gettext/                  # Base directory for all gettext locales
│   ├── pl_PL/                # Polish locale
│   │   └── LC_MESSAGES/
│   │       ├── messages.po   # Editable source file
│   │       └── messages.mo   # Compiled binary file for PHP
│   │
│   └── de_DE/                # Example: German locale
│       └── LC_MESSAGES/
│           ├── messages.po
│           └── messages.mo
│
├── Controllers/
│   └── Home.php
├── Views/
│   └── welcome_message.php
└── ...
```


## Usage

### Basic Example

```php
// In your controller
service('gettext')->setLocale('pl');

echo _('Hello');  // Outputs: Cześć
```

### Using with Different Locales

```php
// Set locale based on user preference
service('gettext')->setLocale('de');

echo _('Hello');  // Outputs: Hallo
echo _('Goodbye');  // Outputs: Auf Wiedersehen
```

### Using with context

```php
service('gettext')->setLocale('de');

echo pgettext('verb', 'Post');      // "Veröffentlichen"
echo pgettext('noun', 'Post');      // "Beitrag"
```

### Handling plural forms with context

```php
// npgettext($context, $singular, $plural, $count)

$count = 3;
// Insert values with sprintf()
echo sprintf(npgettext('email', '%d message', '%d messages', intval($count)), $count);
// Email context: "3 messages"

echo sprintf(npgettext('chat', '%d message', '%d messages', intval($count)), $count);
// Chat context: "3 chats" or "3 texts"

```

### Translates with both domain and context

```php
// dpgettext($domain, $context, $message)

// Using different translation domains
echo dpgettext('admin', 'button', 'Delete');    // "Remove permanently"
echo dpgettext('frontend', 'button', 'Delete'); // "Move to trash"
```

### Combines domain, context, and plural handling.

```php
// dnpgettext($domain, $context, $singular, $plural, $count)

$files = 5;
// Insert values with sprintf()
echo sprintf(dnpgettext('filesystem', 'trash', '%d file', '%d files', intval($files)), $files);
// Result: "5 files in trash"

echo sprintf(dnpgettext('filesystem', 'upload', '%d file', '%d files', intval($files)), $files);
// Result: "5 files uploaded"
```

### Setting Locale and Domain

```php
// If you need to change both locale and domain
service('gettext')->setLocale('pl')->setDomain('messages');

echo _('Hello');
```

### Using Multiple Domains

If you want to organize translations into different domains (e.g., 'messages', 'errors', 'emails'):

1. Update `app/Config/Gettext.php`:
```php
public array $allowedDomains = ['messages', 'errors', 'emails'];
```

2. Create corresponding `.po` and `.mo` files:
```
app/Gettext/pl_PL/LC_MESSAGES/
├── messages.po
├── messages.mo
├── errors.po
├── errors.mo
├── emails.po
└── emails.mo
```

3. Switch domains as needed:
```php
service('gettext')->setLocale('pl')->setDomain('errors');
echo _('File not found');
```

## Translation Workflow

### 1. Create Translation Files

Create a `.po` (Portable Object) file for each locale in the appropriate directory:

```
app/Gettext/pl_PL/LC_MESSAGES/messages.po
```

### 2. Edit `.po` Files

You can edit `.po` files manually or use tools like Poedit, which provides a user-friendly interface.

Example `.po` file structure:

```po
msgid ""
msgstr ""
"Project-Id-Version: MyApp 1.0\n"
"Language: pl_PL\n"
"MIME-Version: 1.0\n"
"Content-Type: text/plain; charset=UTF-8\n"
"Content-Transfer-Encoding: 8bit\n"

msgid "Hello"
msgstr "Cześć"

msgid "Goodbye"
msgstr "Do widzenia"
```

### 3. Compile to `.mo` Files

After editing `.po` files, compile them to binary `.mo` (Machine Object) files for PHP to use.

#### Using msgfmt (Command Line)
```bash
msgfmt app/Gettext/pl_PL/LC_MESSAGES/messages.po -o app/Gettext/pl_PL/LC_MESSAGES/messages.mo
```

#### Compile All Locales at Once
```bash
find app/Gettext -name "*.po" -execdir msgfmt {} -o messages.mo \;
```

### 4. Restart Your Application

After compiling new translations, you may need to restart your web server or PHP-FPM to clear the gettext cache.

## Troubleshooting

### Translations Not Working

1. **Check if gettext extension is installed:**
   ```bash
   php -m | grep gettext
   ```

2. **Verify locale is installed on your system:**
   ```bash
   locale -a | grep pl_PL
   ```
   If not found, install the locale package for your OS.

3. **Check locale format:** Ensure your locale strings in `app/Config/Gettext.php` match your system's locale format. Common formats:
    - Linux: `pl_PL.utf8` or `pl_PL.UTF-8`
    - macOS: `pl_PL.UTF-8`

4. **Verify .mo files exist and are up-to-date:** Ensure you've compiled `.po` files to `.mo` files after making changes.

5. **Clear gettext cache:** Restart your web server or PHP-FPM after updating translations.
