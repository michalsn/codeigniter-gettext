<?php

namespace Michalsn\CodeIgniterGettext\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use CodeIgniter\Publisher\Publisher;
use Throwable;

/**
 * @codeCoverageIgnore
 */
class GettextPublish extends BaseCommand
{
    protected $group       = 'Gettext';
    protected $name        = 'gettext:publish';
    protected $description = 'Publish Gettext config file into the current application.';

    /**
     * @return void
     */
    public function run(array $params)
    {
        $source = service('autoloader')->getNamespace('Michalsn\\CodeIgniterGettext')[0];

        $publisher = new Publisher($source, APPPATH);

        try {
            $publisher->addPaths([
                'Config/Gettext.php',
            ])->merge(false);
        } catch (Throwable $e) {
            $this->showError($e);

            return;
        }

        foreach ($publisher->getPublished() as $file) {
            $contents = file_get_contents($file);
            $contents = str_replace('namespace Michalsn\\CodeIgniterGettext\\Config', 'namespace Config', $contents);
            $contents = str_replace('use CodeIgniter\\Config\\BaseConfig', 'use Michalsn\\CodeIgniterGettext\\Config\\Gettext as BaseGettext', $contents);
            $contents = str_replace('class Gettext extends BaseConfig', 'class Gettext extends BaseGettext', $contents);
            file_put_contents($file, $contents);
        }

        CLI::write(CLI::color('  Published! ', 'green') . 'You can customize the configuration by editing the "app/Config/Gettext.php" file.');
    }
}
