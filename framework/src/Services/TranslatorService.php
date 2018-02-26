<?php

namespace Bow\Services;

use Bow\Config\Config;
use Bow\Support\Translator;
use Bow\Application\Services as BowService;

class TranslatorService extends BowService
{
    /**
     * __
     *
     * @param Config $config
     * @return void
     */
    public function make(Config $config)
    {
        /**
         * Configuration de translator
         */
        $this->app(Translator::class, function () use ($config) {
            return Translator::configure(
                $config['trans.lang'],
                $config['trans.directory']
            );
        });
    }

    /**
     * Démarrage du service
     *
     * @return void
     */
    public function start()
    {
        $this->app(Translator::class);
    }
}
