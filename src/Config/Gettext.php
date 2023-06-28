<?php

namespace Michalsn\CodeIgniterGettext\Config;

use CodeIgniter\Config\BaseConfig;

class Gettext extends BaseConfig
{
    public string $dir           = APPPATH . 'Gettext' . DIRECTORY_SEPARATOR;
    public string $domain        = 'messages';
    public array $allowedDomains = ['messages'];
    public string $codeset       = 'UTF-8';
    public array $locales        = [
        'en' => 'en_US.utf8',
        'pl' => 'pl_PL.utf8',
        'de' => 'de_DE.utf8',
        'fr' => 'fr_FR.utf8',
    ];
}
