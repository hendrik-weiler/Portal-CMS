<?php
/**
 * Extension to sCache. This is almost the same but invisibly provides
 *   site-unique keys so that one site's cache entry does not conflict with
 *   another.
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

class sCache extends fCache {
  /**
   * The current working directory.
   *
   * @var string
   */
  private static $cwd = '';

  /**
   * Initialises the class.
   *
   * @return void
   */
  private static function initialize() {
    if (!self::$cwd) {
      self::$cwd = getcwd();
    }
  }

  /**
   * Get a key unique to the site.
   *
   * @param string $key Key to use.
   * @return string Key that can be used for cache storage.
   */
  private static function getSiteUniqueKey($key) {
    self::initialize();
    return __CLASS__.'::'.self::$cwd.'::'.$key;
  }

  /**
   * Tries to set a value to the cache, but stops if a value already exists.
   *
   * @param string $key The key to store as, this should not exceed 250
   *   characters.
   * @param mixed $value The value to store, this will be serialized.
   * @param integer $ttl The number of seconds to keep the cache valid for, 0
   *   for no limit.
   * @return boolean  If the key/value pair were added successfully.
   */
  public function add($key, $value, $ttl = 0) {
    $key = self::getSiteUniqueKey($key);
    return parent::add($key, $value, $ttl);
  }

  /**
   * Deletes a value from the cache.
   *
   * @param string $key The key to delete.
   * @return boolean If the delete succeeded.
   */
  public function delete($key) {
    $key = self::getSiteUniqueKey($key);
    return parent::delete($key);
  }

  /**
   * Returns a value from the cache.
   *
   * @param string $key The key to return the value for.
   * @param mixed  $default The value to return if the key did not exist.
   * @return mixed The cached value or the default value if no cached value
   *   was found.
   */
  public function get($key, $default = NULL) {
    $key = self::getSiteUniqueKey($key);
    return parent::get($key, $default);
  }

  /**
   * Sets a value to the cache, overriding any previous value.
   *
   * @param string $key The key to store as, this should not exceed 250
   *   characters.
   * @param mixed $value The value to store, this will be serialized.
   * @param integer $ttl The number of seconds to keep the cache valid for, 0
   *   for no limit.
   * @return boolean If the value was successfully saved.
   */
  public function set($key, $value, $ttl = 0) {
    $key = self::getSiteUniqueKey($key);
    return parent::set($key, $value, $ttl);
  }
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
