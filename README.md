# CodeIgniter Gettext

This library gives users the ability to use [gettext](https://www.php.net/manual/en/book.gettext.php) more friendly way.

[![PHPUnit](https://github.com/michalsn/codeigniter-gettext/actions/workflows/phpunit.yml/badge.svg)](https://github.com/michalsn/codeigniter-gettext/actions/workflows/phpunit.yml)
[![PHPStan](https://github.com/michalsn/codeigniter-gettext/actions/workflows/phpstan.yml/badge.svg)](https://github.com/michalsn/codeigniter-gettext/actions/workflows/phpstan.yml)
[![Deptrac](https://github.com/michalsn/codeigniter-gettext/actions/workflows/deptrac.yml/badge.svg)](https://github.com/michalsn/codeigniter-gettext/actions/workflows/deptrac.yml)

![PHP](https://img.shields.io/badge/PHP-%5E8.0-blue)
![CodeIgniter](https://img.shields.io/badge/CodeIgniter-%5E4.3-blue)

### Installation

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

### Example

```php

service('gettext')->setLocale('pl');

echo _('Hello');

```
