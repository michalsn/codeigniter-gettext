<?php

namespace Michalsn\CodeIgniterGettext;

use Config\App as AppConfig;
use Michalsn\CodeIgniterGettext\Config\Gettext as GettextConfig;
use Michalsn\CodeIgniterGettext\Exceptions\GettextException;

class Gettext
{
    protected string $defaultLocale;
    protected array $supportedLocales;

    public function __construct(protected GettextConfig $config, AppConfig $app)
    {
        $this->defaultLocale    = $app->defaultLocale;
        $this->supportedLocales = $app->supportedLocales;
    }

    public function setLocale(string $locale)
    {
        if (! in_array($locale, $this->supportedLocales, true)) {
            throw GettextException::forLocaleNotSupported();
        }

        putenv('LC_ALL=' . ($this->config->locales[$locale] ?? $this->defaultLocale));
        setlocale(LC_ALL, $this->config->locales[$locale] ?? $this->defaultLocale);

        return $this;
    }

    public function setDomain(string $domain)
    {
        if (! is_dir($this->config->dir)) {
            throw GettextException::forDirDoesNotExist();
        }

        if (! in_array($domain, $this->config->allowedDomains, true)) {
            throw GettextException::forDomainNotSupported();
        }

        bindtextdomain($domain, $this->config->dir);
        textdomain($domain);
        bind_textdomain_codeset($domain, $this->config->codeset);

        return $this;
    }
}
