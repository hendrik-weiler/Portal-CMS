<?php
/**
 * Manages POST requests.
 *
 * @copyright Copyright (c) 2011 Poluza.
 * @author Andrew Udvare [au] <andrew@poluza.com>
 * @license http://www.opensource.org/licenses/mit-license.php
 *
 * @package Sutra
 * @link http://www.sutralib.com/
 *
 * @version 1.2
 */

namespace Sutra;

class sRequest extends fRequest {
  const checkCSRFToken     = 'sRequest::checkCSRFToken';
  const savePostValues     = 'sRequest::savePostValues';
  const setPostValues      = 'sRequest::setPostValues';
  const retrievePostValues = 'sRequest::setPostValues';
  const deletePostValues   = 'sRequest::deletePostValues';

  /**
   * The key in session that holds the last POST values. The last POST values
   *   are only stored when a validation error occurs.
   *
   * @var string
   */
  const LAST_POST_SESSION_KEY_PREFIX = 'sPostRequest::last_post';
  /**
   * Non-throwing check of a CSRF token. Compatible with fValidation as a
   *   callback but the URL must be the same as the current page.
   *
   * @param string $csrf CSRF token string.
   * @param string $url URL to use.
   * @return boolean If the CSRF token is valid for the URL.
   * @SuppressWarnings(PHPMD.UnusedLocalVariable)
   */
  public static function checkCSRFToken($csrf, $url = NULL) {
    try {
      self::validateCSRFToken($csrf, $url);
      return TRUE;
    }
    catch (fValidationException $e) {}
    return FALSE;
  }

  /**
   * Saves the POST values to session.
   *
   * @param string $id Unique ID of the values.
   * @return void
   * @SuppressWarnings(PHPMD.UnusedLocalVariable)
   */
  public static function savePostValues($id = 'default') {
    $safe_post = array();
    foreach ($_POST as $key => $value) {
      $safe_post[$key] = fRequest::get($key);
    }
    fSession::set(self::LAST_POST_SESSION_KEY_PREFIX.'::'.$id, $safe_post);
  }

  /**
   * Restores the last POST values for a specified ID to $_GET or $_POST.
   *
   * @param string $id Unique ID of the values.
   * @return void
   */
  public static function setPostValues($id = 'default') {
    $values = fSession::get(self::LAST_POST_SESSION_KEY_PREFIX.'::'.$id, array());
    foreach ($values as $key => $value) {
      fRequest::set($key, $value);
    }
  }

  /**
   * Gets the saved POST values for a unique ID.
   *
   * @param string $id Unique ID of the values.
   * @return array Array of values. Can return an empty array.
   */
  public static function retrievePostValues($id = 'default') {
    $value = fSession::get(self::LAST_POST_SESSION_KEY_PREFIX.'::'.$id, array());
    if (!is_array($value)) {
      $value = array();
    }
    return $value;
  }

  /**
   * Deletes all POST values stored for a particular ID.
   *
   * @param string $id Unique ID of the values.
   * @return void
   */
  public static function deletePostValues($id = 'default') {
    fSession::delete(self::LAST_POST_SESSION_KEY_PREFIX.'::'.$id);
  }

  /**
   * Get the request method.
   *
   * @return string GET, DELETE, PUT, HEAD, or POST. Always upper-case.
   */
  public static function getMethod() {
    return strtoupper($_SERVER['REQUEST_METHOD']);
  }

  /**
   * Get the content type of the data sent.
   *
   * @return string The mimetype. This is always in lower-case.
   */
  public static function getContentType() {
    return strtolower($_SERVER['CONTENT_TYPE']);
  }

  /**
   * Checks if the browser is Internet Explorer. This is solely based on the
   *   user-agent and is to be used as a last resort.
   *
   * @param string|array $version Version number to check for.
   * @return boolean If the browser is IE.
   */
  public static function isInternetExplorer($version = NULL) {
    if (!is_null($version)) {
      if (is_array($version)) {
        $versions = implode('', $version);
        $regex = '/^Mozilla\/4\.0\s+\(compatible\;\sMSIE\s+['.$versions.']\.0\;/';
        return (bool)preg_match($regex, $_SERVER['HTTP_USER_AGENT']);
      }

      $regex = '/^Mozilla\/4\.0\s+\(compatible\;\s+MSIE\s+'.(int)$version.'\.0\;/';
      return (bool)preg_match($regex, $_SERVER['HTTP_USER_AGENT']);
    }

    return (bool)preg_match('/^Mozilla\/4\.0\s+\(compatible;\s+MSIE\s+\d\.0;/', $_SERVER['HTTP_USER_AGENT']);
  }

  // @codeCoverageIgnoreStart
  /**
   * Forces use as a static class.
   *
   * @return sRequest
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
