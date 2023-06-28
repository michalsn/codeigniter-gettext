<?php

namespace Tests;

use CodeIgniter\Test\CIUnitTestCase;
use Config\App;
use Michalsn\CodeIgniterGettext\Config\Gettext as GettextConfig;
use Michalsn\CodeIgniterGettext\Gettext;

/**
 * @internal
 */
final class GettextTest extends CIUnitTestCase
{
    protected Gettext $gt;

    protected function setUp(): void
    {
        parent::setUp();

        $gtConfig = config(GettextConfig::class);
        $appConfig = config(App::class);
        $appConfig->supportedLocales = ['en', 'pl'];

        $this->gt = new Gettext($gtConfig, $appConfig);
    }

    public function testSetLocale()
    {
        $this->gt->setLocale('pl');
        $this->assertSame('pl_PL.utf8', getenv('LC_ALL'));
    }
}
