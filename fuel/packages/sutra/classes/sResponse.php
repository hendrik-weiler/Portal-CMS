<?php
/**
 * Common non-file related HTTP response helper class.
 *
 * @copyright Copyright (c) 2012 Poluza.
 * @author Andrew Udvare [au] <andrew@poluza.com>
 * @license http://www.opensource.org/licenses/mit-license.php
 *
 * @package Sutra
 * @link https://github.com/tatsh/sutra
 *
 * @version 1.3
 */

namespace Sutra;

class sResponse {
  const encode                  = 'sResponse::encode';
  const printData               = 'sResponse::printData';
  const sendCreatedHeader       = 'sResponse::sendCreatedHeader';
  const sendHeader              = 'sResponse::sendHeader';
  const sendForbiddenHeader     = 'sResponse::sendForbiddenHeader';
  const sendNotAcceptableHeader = 'sResponse::sendNotAcceptableHeader';
  const sendNotFoundHeader      = 'sResponse::sendNotFoundHeader';
  const sendNotModifiedHeader   = 'sResponse::sendNotModifiedHeader';
  const sendPlainTextResponse   = 'sResponse::sendPlainTextResponse';
  const sendServerErrorHeader   = 'sResponse::sendServerErrorHeader';
  const setEncodeCallback       = 'sResponse::setEncodeCallback';

  /**
   * Encoding callbacks.
   *
   * @var array
   */
  private static $encode_callbacks = array(
    'application/json' => 'fJSON::encode',
    'text/html' => 'fHTML::encode',
    'application/x-www-form-urlencoded' => 'http_build_query',
  );

  /**
   * Send a 304 not modified header, if the content hasn't changed according to
   *   the headers sent in by the client.
   *
   * @param fTimestamp|integer $last_modified Time the file was last
   *   modified (fTimestamp object or UNIX timestamp).
   * @param string $etag Etag to use for this request.
   * @param integer $cache_time Time in seconds to cache for. Default is 2
   *   weeks.
   * @param boolean $accept_encoding Send Vary: Accept-Encoding header.
   *   Default is TRUE.
   * @return void
   */
  public static function sendNotModifiedHeader($last_modified, $etag, $cache_time = 1209600, $accept_encoding = TRUE) {
    $cache_time = (int)$cache_time;

    if ($last_modified instanceof fTimestamp) {
      $last_modified = $last_modified->format('U');
    }
    else {
      $last_modified = (int)$last_modified;
    }
    $last_modified = gmdate('D, d M Y H:i:s', $last_modified).' GMT';

    if ($accept_encoding) {
      header('Vary: Accept-Encoding');
    }

    header('Cache-Control: max-age='.$cache_time);
    header('Last-Modified: '.$last_modified);
    header('Etag: '.$etag);

    $modified = isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) && strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) == $last_modified;
    $none = isset($_SERVER['HTTP_IF_NONE_MATCH']) && trim($_SERVER['HTTP_IF_NONE_MATCH']) == $etag;
    if ($modified || $none) {
      header('HTTP/1.1 304 Not Modified');
    }
  }

  /**
   * Recursively encodes a keyed array to string.
   *
   * @param array $data Data to encode.
   * @return string The encoded data.
   */
  private static function encodeKeyedArray(array $data) {
    $ret = array();

    foreach ($data as $key => $value) {
      if (is_array($value)) {
        $ret[] = self::encodeKeyedArray($value);
      }
      else {
        $ret[] = $key.'='.$value;
      }
    }

    return implode(',', $ret);
  }

  /**
   * Encodes data in a specified content type. Default is
   *   application/x-www-form-urlencoded.
   *
   * @param mixed $data Data to encode. Note that if the callback can only
   *   handle strings and the data is of type array, 'Array' will be printed.
   * @param string $content_type Content type to encode to.
   * @return string The encoded data.
   */
  public static function encode($data, $content_type = NULL) {
    $ret = $data;

    if (isset(self::$encode_callbacks[$content_type])) {
      $ret = fCore::call(self::$encode_callbacks[$content_type], array($data));
    }
    else {
      $ret = is_array($data) ? http_build_query($data) : $data;
    }

    return $ret;
  }

  /**
   * Prints the data and sends the appropriate Content-Type header.
   *
   * @param mixed $data Data to print.
   * @param string $content_type Content type of the data. This is only the
   *   mimetype. It should NOT have other parts such as 'charset=utf-8'.
   * @return void
   */
  public static function printData($data, $content_type = 'application/x-www-form-urlencoded') {
    sResponse::sendHeader('Content-Type', $content_type.'; charset=utf-8');
    print self::encode($data, $content_type);
  }

  /**
   * Set the encoding callback for a particular content type.
   *
   * @param string $content_type Content type such as 'application/xml'.
   * @param string|array $cb Callback.
   * @return void
   */
  public static function setEncodeCallback($content_type, $cb) {
    self::$encode_callbacks[$content_type] = $cb;
  }

  /**
   * Send a plain text response. Accepts variable arguments.
   *
   * @param string $text Text to send.
   * @return void
   * @see fText::compose()
   */
  public static function sendPlainTextResponse($text) {
    header('Content-Type: text/plain; charset=utf-8');
    print fCore::call(fText::compose, func_get_args());
  }

  // @codeCoverageIgnoreStart
  /**
   * Sends a 403 restricted content header.
   *
   * @return void
   */
  public static function sendForbiddenHeader() {
    header('HTTP/1.1 403 Forbidden');
  }

  /**
   * Sends a custom header.
   *
   * @param string $key Header name.
   * @param string $value Value.
   * @return void
   */
  public static function sendHeader($key, $value) {
    header($key.': '.$value);
  }

  /**
   * Sends a 201 created HTTP header.
   *
   * @return void
   */
  public static function sendCreatedHeader() {
    header('HTTP/1.1 201 Created');
  }

  /**
   * Sends a 406 not acceptable header. This is for when the client sends an
   *   Accept header of types none of which can be satisfied by the server.
   *
   * @return void
   */
  public static function sendNotAcceptableHeader() {
    header('HTTP/1.1 406 Not Acceptable');
  }

  /**
   * Sends a 404 not found header.
   *
   * @return void
   */
  public static function sendNotFoundHeader() {
    header('HTTP/1.1 404 Not Found');
  }

  /**
   * Sends a 500 server error header.
   *
   * @return void
   */
  public static function sendServerErrorHeader() {
    header('HTTP/1.1 500 Internal Server Error');
  }

  /**
   * Forces use as a static class.
   *
   * @return sResponse
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
