<?php
/**
 * Manages loading of classes.
 *
 * @copyright Copyright (c) 2012 bne1.
 * @author Andrew Udvare [au] <andrew@bne1.com>
 * @license http://www.opensource.org/licenses/mit-license.php
 *
 * @package Sutra
 * @link http://www.sutralib.com/
 *
 * @version 1.2
 */

namespace Sutra;

class sLoader extends fLoader {
  const best  = 'sLoader::best';
  const eagar = 'sLoader::eagar';
  const lazy  = 'sLoader::lazy';

  /**
   * Path where libraries are installed.
   *
   * @var string
   */
  private static $path = '';

  /**
   * All the Sutra classes.
   *
   * @var array
   */
  private static $classes = array(
    'sArray',
    'sAuthorization',
    'sCache',
    'sCore',
    'sCRUDForm',
    'sGrammar',
    'sHTML',
    'sHTTPRequest',
    'sImage',
    'sJSONP',
    'sNumber',
    'sObject',
    'sORMJSON',
    'sProcess',
    'sProcessException',
    'sRequest',
    'sResponse',
    'sString',
    'sTemplate',
    'sTimestamp',
  );

  /**
   * Override best() method.
   *
   * @return void
   * @see sLoader::eagar()
   */
  public static function best() {
    if (self::hasOpcodeCache()) {
      return sLoader::eagar();
    }

    self::lazy();
  }

  /**
   * Creates constructor functions. This makes it possible to write:
   *
   * <code>new sProcess('node myscript')->execute();</code>
   *
   * @return void
   */
  private static function createConstructorFunctions() {
    if (function_exists('sHTTPRequest')) {
      return;
    }

    function sHTTPRequest($url, $method = 'GET', $timeout = NULL) {
      return new sHTTPRequest($url, $method, $timeout);
    }

    function sImage($file_path, $skip_checks = FALSE) {
      return new sImage($file_path, $skip_checks);
    }

    function sNumber($value, $scale = NULL) {
      return new sNumber($value, $scale);
    }

    function sObject(array $arg) {
      return new sObject($arg);
    }

    // Limited signature support
    function sProcess($name) {
      return new sProcess($name);
    }

    function sTimestamp($datetime, $timezone = NULL) {
      return new sTimestamp($datetime, $timezone);
    }
  }

  /**
   * Override eager() method to load Sutra classes after Flourish's.
   *
   * @return void
   */
  public static function eagar() {
    parent::eager();

    self::setPath();
    self::createConstructorFunctions();

    foreach (self::$classes as $class) {
      require self::$path.$class.'.php';
    }
  }

  /**
   * Determines where Sutra is installed.
   *
   * @return void
   */
  private static function setPath() {
    if (!self::$path) {
      self::$path = realpath(dirname(__FILE__)).'/';
    }
  }

  /**
   * Registers a class auto-loader to load Sutra classes.
   *
   * @return void
   */
  public static function lazy() {
    parent::lazy();

    self::setPath();
    self::createConstructorFunctions();

    spl_autoload_register(array('sLoader', 'autoload'));
  }

  /**
   * Tries to load a Sutra class.
   *
   * @internal
   *
   * @param  string $class The class to load.
   * @return void
   */
  public static function autoload($class) {
    if ($class[0] != 's' || ord($class[1]) < 65 || ord($class[1]) > 90) {
      return;
    }

    if (!in_array($class, self::$classes)) {
      return;
    }

    require self::$path.$class.'.php';
  }

  // @codeCoverageIgnoreStart
  /**
   * Forces use as a static class.
   *
   * @return sLoader
   */
  private function __construct() {}
  // @codeCoverageIgnoreEnd
}

/**
 * Copyright (c) 2012 Andrew Udvare <andrew@bne1.com>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to
 * deal in the Software without restriction, including without limitation the
 * rights to use, copy, modify, merge, publish, distribute, sublicense, and/or
 * sell copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
 * FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS
 * IN THE SOFTWARE.
 */
