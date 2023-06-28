<?php

namespace Michalsn\CodeIgniterGettext\Config;

use CodeIgniter\Config\BaseService;
use Michalsn\CodeIgniterGettext\Config\Gettext as GettextConfig;
use Michalsn\CodeIgniterGettext\Gettext;

class Services extends BaseService
{
    /**
     * Return the gettext.
     *
     * @return Gettext
     */
    public static function gettext(?string $locale, ?string $domain, ?GettextConfig $gettext, bool $getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('gettext', $locale, $domain, $gettext);
        }

        $gettext = config('Gettext');
        $app     = config('App');
        $locale ??= $app->defaultLocale;
        $domain ??= $gettext->domain;

        return (new Gettext($gettext, $app))->setLocale($locale)->setDomain($domain);
    }
}
