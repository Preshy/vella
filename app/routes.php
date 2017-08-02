<?php
/**
 * Vella
 *
 * An open source modular application development framework for PHP built for Minimalism!
 *
 * Define your routes.
 */

\Vella\Router::add([
    'vella/index/(:any)' => 'vella/index/$1',
]);
