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

        // If no translation found, gettext returns the input string
        // In that case, return just the msgid
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

        // If no translation found, ngettext returns one of the input strings
        // In that case, return the appropriate form without context
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
        $contextString = $msgctxt . "\x04" . $msgid;
        $translation   = dgettext($domain, $contextString);

        // If no translation found, dgettext returns the input string
        // In that case, return just the msgid
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
        $contextString       = $msgctxt . "\x04" . $msgid;
        $contextStringPlural = $msgctxt . "\x04" . $msgidPlural;
        $translation         = dngettext($domain, $contextString, $contextStringPlural, $n);

        // If no translation found, dngettext returns one of the input strings
        // In that case, return the appropriate form without context
        if ($translation === $contextString || $translation === $contextStringPlural) {
            return ($n === 1) ? $msgid : $msgidPlural;
        }

        return $translation;
    }
}
