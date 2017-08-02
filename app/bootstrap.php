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



##################
# Define Constants
##################

// Vella's verison
define('VELLA_VERSION', '0.1');

// Vella's Full Path
define('VELLA_DIR', $_SERVER['DOCUMENT_ROOT'].'/');

// Vella's Core
define('VELLA_CORE', VELLA_DIR.'/core');

// Current environment
define('VELLA_ENVIRONMENT', 'development'); // Change to production if ready.

// Vella's minimum required PHP version
define('VELLA_PHPVERSION', '5.4');

// Controllers folder, you can change this.
define('VELLA_CONTROLLERS', 'app/Controllers'); // ./app/Controllers

// Models folder ^
define('VELLA_MODELS', 'app/Models'); // ./app/Models

// Views Folder ^
define('VELLA_VIEWS', 'app/Views'); // ./app/Views

// Modules folder
define('VELLA_MODULEs', 'app/modules'); // ./app/Modules


##################
# Environments
##################

switch (VELLA_ENVIRONMENT)
{
    case 'development':
        error_reporting(-1);
        ini_set('display_errors', 1);
        break;
    case 'testing':
    case 'production':
        ini_set('display_errors', 0);
        error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT & ~E_USER_NOTICE & ~E_USER_DEPRECATED);
        break;
    default:
        header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
        echo 'The application environment is not set correctly.';
        exit(1); // EXIT_ERROR
}

#############################################################
# Vella would only work for atleast PHP 5.4, so lets check
#############################################################

$current_version = phpversion();
if($current_version < VELLA_PHPVERSION) {
    die("Vella requires atleast PHP 5.4 or more..");
}

##################
# Load Core files
##################

$scan_core_files = array_diff(scandir(VELLA_DIR.'core'), array('..', '.')); //ignore folders with a dot.
foreach ($scan_core_files as $scf) {
    try {
        require_once VELLA_DIR.'/core/'.$scf;
    } catch (Exception $e) {
        die("Failed to autoload core files");
    }
}


#############################################################
# Load config and routes
#############################################################

try {
    require_once 'config.php';
    require_once 'routes.php';
} catch (Exception $e) {
    throw new Exception('Could not load config and routes');
}


#############################################################
# Autoload Controllers
#############################################################

// now scan through all files
$scan_controllers_dir = array_diff(scandir(VELLA_DIR.VELLA_CONTROLLERS), array('..', '.')); //ignore folders with a dot.
// loop through and include them..

foreach ($scan_controllers_dir as $scd) {
    try {
        require_once VELLA_DIR.VELLA_CONTROLLERS.'/'.$scd;
    } catch (Exception $e) {
        die("Failed to autoload controllers");
    }
}


#############################################################
# Autoload Models
#############################################################

// now scan through all files
$scan_models_dir = array_diff(scandir(VELLA_DIR.VELLA_MODELS), array('..', '.')); //ignore folders with a dot.
// loop through and include them..

foreach ($scan_models_dir as $smd) {
    try {
        require_once VELLA_DIR.VELLA_MODELS.'/'.$smd;
    } catch (Exception $e) {
        die("Failed to autoload models");
    }
}


#############################################################
# Autoload Modules
#############################################################

// now scan through all files
$scan_modules_dir = array_diff(scandir(VELLA_DIR.VELLA_MODELS), array('..', '.')); //ignore folders with a dot.
// loop through and include them..

foreach ($scan_modules_dir as $smd) {
    try {
        require_once VELLA_DIR.VELLA_MODULES.'/'.$smd;
    } catch (Exception $e) {
        die("Failed to autoload modules");
    }
}


#############################################################
# Composer
#############################################################
try {
    if ($CONFIG['composer_autoload'] == true):
            require_once VELLA_DIR . '/vendor/autoload.php';
    endif;
} catch (Exception $e) {
    throw new Exception('Failed to load composer');
}


