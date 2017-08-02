<?php
/**
 * Vella
 *
 * An open source modular application development framework for PHP built for Minimalism!
 *
 * This content is released under the MIT License (MIT)
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package	Vella
 * @author	Precious Opusunju
 * @copyright	Copyright (c) 2017
 * @license	http://opensource.org/licenses/MIT	MIT License
 * @link	https://vellaframework.io
 * @since	Version 1.0.0
 * @filesource
 */

namespace Vella;
class Router {
    protected static $allow_query = true;
    protected static $routes = array();
    public static function add($src, $dest = null) {
        // TODO: Validate the routes?
        if (is_array($src)) {
            foreach ($src as $key => $val) {
                static::$routes[$key] = $val;
            }
        }
        elseif ($dest) {
            static::$routes[$src] = $dest;
        }
    }
    public static function route($uri) {
        $qs = '';
        if (static::$allow_query && strpos($uri, '?') !== false) {
            // Break the query string off and attach later
            $qs = '?' . parse_url($uri, PHP_URL_QUERY);
            $uri = str_replace($qs, '', $uri);
        }
        // Is there a literal match?
        if (isset(static::$routes[$uri])) {
            return static::$routes[$uri] . $qs;
        }
        // Loop through the route array looking for wild-cards
        foreach (static::$routes as $key => $val) {
            // Convert wild-cards to RegEx
            $key = str_replace(':any', '.+', $key);
            $key = str_replace(':num', '[0-9]+', $key);
            $key = str_replace(':nonum', '[^0-9]+', $key);
            $key = str_replace(':alpha', '[A-Za-z]+', $key);
            $key = str_replace(':alnum', '[A-Za-z0-9]+', $key);
            $key = str_replace(':hex', '[A-Fa-f0-9]+', $key);
            // Does the RegEx match?
            if (preg_match('#^' . $key . '$#', $uri)) {
                // Do we have a back-reference?
                if (strpos($val, '$') !== false && strpos($key, '(') !== false) {
                    $val = preg_replace('#^' . $key . '$#', $val, $uri);
                }
                return $val . $qs;
            }
        }
        return $uri . $qs;
    }
    public static function reverseRoute($controller, $root = "/") {
        $index = array_search($controller, static::$routes);
        if($index === false)
            return null;
        return $root . static::$routes[$index];
    }
}