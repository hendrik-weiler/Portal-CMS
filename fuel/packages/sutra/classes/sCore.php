<?php
/**
 * This class is optional to use. It is generally for use as the core class of
 *   the site.
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

abstract class sCore extends fCore {
  const main        = 'sCore::main';
  const getDatabase = 'sCore::getDatabase';
  const getCache    = 'sCore::getCache';

  /**
   * The cache.
   *
   * @var sCache|fCache
   */
  protected static $cache = NULL;

  /**
   * The fDatabase instance.
   *
   * @var fDatabase
   */
  protected static $db = NULL;

  /**
   * Get the site cache. Can use sCache or fCache. They are both compatible.
   *
   * @return sCache|fCache The fCache/sCache instance.
   */
  public static function getCache() {
    throw new fProgrammerException('The function, "%s", must be implemented.', __CLASS__.'::'.__FUNCTION__);
  }

  /**
   * Get the site database instance. This is useful for getting database
   *   information or using SQL queries with methods such as
   *   translatedExcecute.
   *
   * This method can also be used to configure fORM if that is desired.
   *
   * @return fDatabase The fDatabase instance.
   * @see fDatabase::translatedExcecute()
   * @see fORMDatabase::attach()
   */
  public static function getDatabase() {
    throw new fProgrammerException('The function, "%s", must be implemented.', __CLASS__.'::'.__FUNCTION__);
  }

  /**
   * Configures session settings.
   *
   * @return void
   * @see fSession::setLength()
   * @see fSession::setBackend()
   */
  protected static function configureSession() {
    $method = get_called_class().'::getCache';
    fSession::setLength('30 minutes', '1 week');
    fSession::setBackend(fCore::call($method));
  }

  /**
   * Configures authorisation.
   *
   * @return void
   * @see fAuthorization::setAuthLevels()
   * @see fAuthorization::setLoginPage()
   */
  protected static function configureAuthorization() {
    sAuthorization::setAuthLevels(array('admin' => 100, 'user' => 50, 'guest' => 25));
    sAuthorization::setLoginPage('/login/');
  }

  /**
   * Example entry point. This would be called from index.php or similar file.
   *
   * Calls to set up the database, configure session, configure authorisation,
   *   and set up the exception handler.
   *
   * After these calls, it's expected that the template (sTemplate) will be
   *   set up and that a router (such as Moor) will be used to continue the
   *   request.
   *
   * @return void
   * @see sCore::getDatabase()
   * @see sCore::configureSession()
   * @see sCore::configureAuthorization()
   */
  public static function main() {
    $class = get_called_class();
    self::call($class.'::getDatabase');
    self::call($class.'::configureSession');
    self::call($class.'::configureAuthorization');
  }

  // @codeCoverageIgnoreStart
  /**
   * Forces use as a static class.
   *
   * @return sCore
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
