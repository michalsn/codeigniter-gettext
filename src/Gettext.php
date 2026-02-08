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

        $localeValue = $this->config->locales[$locale] ?? $this->defaultLocale;

        putenv('LC_ALL=' . $localeValue);
        putenv('LANGUAGE=' . $localeValue);
        setlocale(LC_ALL, $localeValue);

        return $this;
    }

    public function setDomain(string $domain)
    {
        if (! is_dir($this->config->dir)) {
            throw GettextException::forDirDoesNotExist();
        }

        $this->verifyDomain($domain);
        textdomain($domain);

        return $this;
    }

    /**
     * Context-aware gettext
     *
     * @param string $msgctxt The message context
     * @param string $msgid   The message identifier
     *
     * @return string The translated string
     */
    public function pgettext(string $msgctxt, string $msgid): string
    {
        $contextString = $msgctxt . "\x04" . $msgid;
        $translation   = gettext($contextString);

        return ($translation === $contextString) ? $msgid : $translation;
    }

    /**
     * Context-aware ngettext (plural)
     *
     * @param string $msgctxt     The message context
     * @param string $msgid       The singular message identifier
     * @param string $msgidPlural The plural message identifier
     * @param int    $n           The number for determining plural form
     *
     * @return string The translated string
     */
    public function npgettext(string $msgctxt, string $msgid, string $msgidPlural, int $n): string
    {
        $contextString       = $msgctxt . "\x04" . $msgid;
        $contextStringPlural = $msgctxt . "\x04" . $msgidPlural;
        $translation         = ngettext($contextString, $contextStringPlural, $n);

        if ($translation === $contextString || $translation === $contextStringPlural) {
            return ($n === 1) ? $msgid : $msgidPlural;
        }

        return $translation;
    }

    /**
     * Context-aware dgettext (with domain)
     *
     * @param string $domain  The text domain
     * @param string $msgctxt The message context
     * @param string $msgid   The message identifier
     *
     * @return string The translated string
     */
    public function dpgettext(string $domain, string $msgctxt, string $msgid): string
    {
        $this->verifyDomain($domain);

        $contextString = $msgctxt . "\x04" . $msgid;
        $translation   = dgettext($domain, $contextString);

        return ($translation === $contextString) ? $msgid : $translation;
    }

    /**
     * Context-aware dngettext (with domain and plural)
     *
     * @param string $domain      The text domain
     * @param string $msgctxt     The message context
     * @param string $msgid       The singular message identifier
     * @param string $msgidPlural The plural message identifier
     * @param int    $n           The number for determining plural form
     *
     * @return string The translated string
     */
    public function dnpgettext(string $domain, string $msgctxt, string $msgid, string $msgidPlural, int $n): string
    {
        $this->verifyDomain($domain);

        $contextString       = $msgctxt . "\x04" . $msgid;
        $contextStringPlural = $msgctxt . "\x04" . $msgidPlural;
        $translation         = dngettext($domain, $contextString, $contextStringPlural, $n);

        if ($translation === $contextString || $translation === $contextStringPlural) {
            return ($n === 1) ? $msgid : $msgidPlural;
        }

        return $translation;
    }

    /**
     * Verify if the domain is allowed
     *
     * @param string $domain The text domain to verify
     *
     * @throws GettextException
     */
    private function verifyDomain(string $domain): void
    {
        if (! in_array($domain, $this->config->allowedDomains, true)) {
            throw GettextException::forDomainNotSupported();
        }

        bindtextdomain($domain, $this->config->dir);
        bind_textdomain_codeset($domain, $this->config->codeset);
    }
}
