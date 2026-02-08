<?php

if (! function_exists('pgettext')) {
    /**
     * Context-aware gettext translation
     *
     * @param string $msgctxt The message context
     * @param string $msgid   The message identifier
     *
     * @return string The translated message
     */
    function pgettext(string $msgctxt, string $msgid): string
    {
        return service('gettext')->pgettext($msgctxt, $msgid);
    }
}

if (! function_exists('npgettext')) {
    /**
     * Context-aware plural gettext translation
     *
     * @param string $msgctxt     The message context
     * @param string $msgid       Singular form
     * @param string $msgidPlural Plural form
     * @param int    $n           The count to determine singular/plural
     *
     * @return string The translated message
     */
    function npgettext(string $msgctxt, string $msgid, string $msgidPlural, int $n): string
    {
        return service('gettext')->npgettext($msgctxt, $msgid, $msgidPlural, $n);
    }
}

if (! function_exists('dpgettext')) {
    /**
     * Context-aware dgettext (with domain)
     *
     * @param string $domain  The text domain
     * @param string $msgctxt The message context
     * @param string $msgid   The message identifier
     *
     * @return string The translated message
     */
    function dpgettext(string $domain, string $msgctxt, string $msgid): string
    {
        return service('gettext')->dpgettext($domain, $msgctxt, $msgid);
    }
}

if (! function_exists('dnpgettext')) {
    /**
     * Context-aware dngettext (with domain and plural)
     *
     * @param string $domain      The text domain
     * @param string $msgctxt     The message context
     * @param string $msgid       Singular form
     * @param string $msgidPlural Plural form
     * @param int    $n           The count to determine singular/plural
     *
     * @return string The translated message
     */
    function dnpgettext(string $domain, string $msgctxt, string $msgid, string $msgidPlural, int $n): string
    {
        return service('gettext')->dnpgettext($domain, $msgctxt, $msgid, $msgidPlural, $n);
    }
}
