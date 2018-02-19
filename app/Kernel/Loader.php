<?php

namespace App\Kernel;

use Bow\Auth\Auth;
use Bow\Mail\Mail;
use Bow\View\View;
use Bow\Http\Cache;
use Bow\Config\Config;
use Bow\Security\Crypto;
use Bow\Resource\Storage;
use Bow\Support\DateAccess;
use Bow\Database\Database;
use Bow\Translate\Translator;
use Bow\Application\Actionner;

class Loader extends Config
{
    /**
     * Get app namespace
     *
     * @return array
     */
    public function namespaces()
    {
        return [
            'controller' => 'App\\Controllers',
            'middleware' => 'App\\Middleware'
        ];
    }

    /**
     * The middleware lists
     *
     * @return array
     */
    public function middlewares()
    {
        return [
            'csrf' => \Bow\Middleware\CsrfMiddleware::class,
            'trim' => \Bow\Middleware\TrimMiddleware::class,
            'auth' => \App\Middleware\Authenticate::class,
            'guest' => \App\Middleware\Guest::class,
        ];
    }

    /**
     * All app services register
     *
     * @return array
     */
    public function services()
    {
        return [
            \Bow\Logger\LoggerService::class
        ];
    }

    /**
     * Load configuration
     *
     * @return Configuration
     */
    public function boot()
    {
        /**
         * Configuration de Mail.
         */
        Mail::configure($this['mail']);

        /**
         * Initialisation du token
         * et Configuration de la Sécurité
         */
        Crypto::setkey(
            $this['security.key'],
            $this['security.cipher']
        );

        /**
         * Configuration de la base de donnée
         */
        Database::configure($this['db']);

        /**
         * Configuration du systeme de cache
         */
        Cache::confirgure($this['resource.cache'].'/bow');

        /**
         * Configuration de la resource de l'application
         */
        Storage::configure($this['resource']);

        /**
         * Configuration du charger de vue
         */
        View::configure($this);

        /**
         * Configuration de translator
         */
        Translator::configure(
            $this['trans.lang'],
            $this['trans.directory']
        );

        /**
         * Configuration de l'auth
         */
        Auth::configure($this['auth']);

        /**
         * Configuration du lanceur de controleur
         */
        Actionner::configure($this->namespaces(), $this->middlewares());

        return $this;
    }
}
