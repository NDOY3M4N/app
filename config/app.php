<?php
/**
 * Configuration de l'application
 * On retourne la configuration que php utilisera pour
 * lancer la configuration initiale de vap
 */

return [

    /**
     * Nom de l'Application
     */
    'name' => 'The Bow Framework',

    /**
     * Racine de l'application
     * e.g '/app'
     */
    'root' => '',

    /**
     * La Zone local
     */
    'timezone' => 'Africa/Abidjan',

    /**
     * Le chemin vers les fichiers statics
     */
    'static' => '/',

    /**
     * Liste des classes
     * concernant les middleware et autre
     */
    'classes' => require __DIR__.'/classes.php',

    /**
     * Le mode de débogage de l'application
     * development | production
     */
    'debug' => 'development',
];
