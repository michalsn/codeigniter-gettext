<?php

namespace Michalsn\CodeIgniterGettext\Exceptions;

use RuntimeException;

final class GettextException extends RuntimeException
{
    public static function forLocaleNotSupported(): static
    {
        return new self(lang('Gettext.localeNotSupported'));
    }

    public static function forDirDoesNotExist(): static
    {
        return new self(lang('Gettext.dirDoesNotExist'));
    }

    public static function forDomainNotSupported(): static
    {
        return new self(lang('Gettext.domainNotSupported'));
    }
}
