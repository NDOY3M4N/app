<?php

namespace App\Kernel;

use \Bow\Mail\Mail;
use Bow\Support\DateAccess;
use \Bow\View\View;
use \Bow\Http\Cache;
use \Bow\Security\Crypto;
use \Bow\Resource\Storage;
use \Bow\Database\Database;
use \Bow\Translate\Translator;
use \Bow\Application\Configuration;

class Loader extends Configuration
{
    /**
     * Get app namespace
     *
     * @return array
     */
    public function namespaces() {
        return [
            'controller' => 'App\\Controllers',
            'middleware' => 'App\\Middleware'
        ];
    }

    /**
     * Loader constructor.
     * @param string $this_dir
     */
    public function __construct($this_dir)
    {
        parent::__construct($this_dir);
    }

    /**
     * @return array
     */
    public function middlewares()
    {
        return [
            'csrf' => \Bow\Middleware\ApplicationCsrfMiddleware::class
        ];
    }

    /**
     * @return array
     */
    public function services()
    {
        return [
            //
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
         * Application de la timezone
         */
        DateAccess::setTimezone($this['app.timezone']);

        return $this;
    }
}