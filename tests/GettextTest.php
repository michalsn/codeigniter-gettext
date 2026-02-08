<?php

declare(strict_types=1);

namespace Tests;

use CodeIgniter\Test\CIUnitTestCase;
use Config\App;
use Michalsn\CodeIgniterGettext\Config\Gettext as GettextConfig;
use Michalsn\CodeIgniterGettext\Exceptions\GettextException;
use Michalsn\CodeIgniterGettext\Gettext;

/**
 * @internal
 */
final class GettextTest extends CIUnitTestCase
{
    private Gettext $gt;
    private GettextConfig $gtConfig;
    private App $appConfig;

    protected function setUp(): void
    {
        parent::setUp();

        $this->gtConfig = config(GettextConfig::class);
        $this->gtConfig->dir = SUPPORTPATH . 'Gettext/';
        $this->gtConfig->domain = 'messages';
        $this->gtConfig->allowedDomains = ['messages', 'other'];
        $this->gtConfig->locales = [
            'en' => 'en_US.utf8',
            'pl' => 'pl_PL.utf8',
        ];

        $this->appConfig = config(App::class);
        $this->appConfig->defaultLocale = 'en';
        $this->appConfig->supportedLocales = ['en', 'pl'];

        $this->gt = new Gettext($this->gtConfig, $this->appConfig);
    }

    public function testSetLocale(): void
    {
        $this->gt->setLocale('pl');
        $this->assertSame('pl_PL.utf8', getenv('LC_ALL'));
    }

    public function testSetLocaleUnsupported(): void
    {
        $this->expectException(GettextException::class);
        $this->gt->setLocale('xx');
    }

    public function testSetDomain(): void
    {
        $result = $this->gt->setLocale('pl')->setDomain('messages');
        $this->assertInstanceOf(Gettext::class, $result);
    }

    public function testSetDomainUnsupported(): void
    {
        $this->expectException(GettextException::class);
        $this->gt->setLocale('pl')->setDomain('nonexistent');
    }

    public function testSetDomainDirDoesNotExist(): void
    {
        $this->gtConfig->dir = '/nonexistent/path/';
        $gt = new Gettext($this->gtConfig, $this->appConfig);

        $this->expectException(GettextException::class);
        $gt->setLocale('pl')->setDomain('messages');
    }

    public function testPgettext(): void
    {
        $this->gt->setLocale('pl')->setDomain('messages');
        $this->assertSame('Plik', $this->gt->pgettext('menu', 'File'));
    }

    public function testPgettextFallback(): void
    {
        $this->gt->setLocale('pl')->setDomain('messages');
        $this->assertSame('Missing', $this->gt->pgettext('menu', 'Missing'));
    }

    public function testNpgettextSingular(): void
    {
        $this->gt->setLocale('pl')->setDomain('messages');
        $this->assertSame('%d plik', $this->gt->npgettext('menu', '%d file', '%d files', 1));
    }

    public function testNpgettextPlural(): void
    {
        $this->gt->setLocale('pl')->setDomain('messages');
        $this->assertSame('%d pliki', $this->gt->npgettext('menu', '%d file', '%d files', 3));
    }

    public function testNpgettextPluralMany(): void
    {
        $this->gt->setLocale('pl')->setDomain('messages');
        $this->assertSame('%d plików', $this->gt->npgettext('menu', '%d file', '%d files', 5));
    }

    public function testNpgettextFallbackSingular(): void
    {
        $this->gt->setLocale('pl')->setDomain('messages');
        $this->assertSame('%d missing', $this->gt->npgettext('menu', '%d missing', '%d missings', 1));
    }

    public function testNpgettextFallbackPlural(): void
    {
        $this->gt->setLocale('pl')->setDomain('messages');
        $this->assertSame('%d missings', $this->gt->npgettext('menu', '%d missing', '%d missings', 5));
    }

    public function testDpgettext(): void
    {
        $this->gt->setLocale('pl')->setDomain('messages');
        $this->assertSame('Zapisz', $this->gt->dpgettext('other', 'action', 'Save'));
    }

    public function testDpgettextUnsupportedDomain(): void
    {
        $this->gt->setLocale('pl')->setDomain('messages');

        $this->expectException(GettextException::class);
        $this->gt->dpgettext('forbidden', 'action', 'Save');
    }

    public function testDnpgettextSingular(): void
    {
        $this->gt->setLocale('pl')->setDomain('messages');
        $this->assertSame('%d element', $this->gt->dnpgettext('other', 'action', '%d item', '%d items', 1));
    }

    public function testDnpgettextPlural(): void
    {
        $this->gt->setLocale('pl')->setDomain('messages');
        $this->assertSame('%d elementy', $this->gt->dnpgettext('other', 'action', '%d item', '%d items', 3));
    }

    public function testDnpgettextUnsupportedDomain(): void
    {
        $this->gt->setLocale('pl')->setDomain('messages');

        $this->expectException(GettextException::class);
        $this->gt->dnpgettext('forbidden', 'action', '%d item', '%d items', 1);
    }

    public function testFluentInterface(): void
    {
        $result = $this->gt->setLocale('pl');
        $this->assertInstanceOf(Gettext::class, $result);

        $result = $result->setDomain('messages');
        $this->assertInstanceOf(Gettext::class, $result);
    }
}
