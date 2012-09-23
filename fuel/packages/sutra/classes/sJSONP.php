<?php
/**
 * Extension to Flourish fJSON class to provide methods for JSONP support.
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

class sJSONP extends fJSON {
  const encode = 'sJSONP::encode';
  const sendHeader = 'sJSONP::sendHeader';

  /**
   * JavaScript reserved words, as per the ECMA 3 standard.
   *
   * @var array
   */
  private static $reserved_words = array(
    'break',
    'do',
    'instanceof',
    'typeof',
    'case',
    'else',
    'new',
    'var',
    'catch',
    'finally',
    'return',
    'void',
    'continue',
    'for',
    'switch',
    'while',
    'debugger',
    'function',
    'this',
    'with',
    'default',
    'if',
    'throw',
    'delete',
    'in',
    'try',
    'class',
    'enum',
    'extends',
    'super',
    'const',
    'export',
    'import',
    'implements',
    'let',
    'private',
    'public',
    'yield',
    'interface',
    'package',
    'protected',
    'static',
    'null',
    'true',
    'false',
  );

  /**
   * The JavaScript identifier syntax regular expression.
   *
   * @var string
   * @credit http://www.geekality.net/2010/06/27/php-how-to-easily-provide-json-and-jsonp/
   */
  private static $identifier_syntax = '/^[$_\p{L}][$_\p{L}\p{Mn}\p{Mc}\p{Nd}\p{Pc}\x{200C}\x{200D}]*+$/u';

  /**
   * Overrides encode method to wrap around the callback received.
   *
   * @throws fValidationException If the callback is invalid (an example would
   *   include any reserved keyword, including future use ones).
   *
   * @param mixed $data Data to encode.
   * @param string $callback Optional. If not passed, will be retrieved from GET
   *   paramater 'callback'. If no such parameter is found, 'fn' will be used.
   * @param boolean $check_callback If FALSE is passed, then the callback will
   *   not be validated.
   * @return string Encoded JSONP data.
   */
  public static function encode($data, $callback = NULL, $check_callback = TRUE) {
    if (is_null($callback)) {
      $callback = fRequest::get('callback', 'string', 'fn');
    }

    if ($check_callback && !self::isValidCallback($callback)) {
      throw new fValidationException('Invalid callback "%s" passed.', $callback);
    }

    return $callback.'('.parent::encode($data).');';
  }

  /**
   * Validate a callback name is not a reserved word in JavaScript and does not have
   *   invalid characters.
   *
   * @param string $subject The callback name.
   * @return boolean TRUE if the callback can be used, FALSE otherwise.
   */
  private static function isValidCallback($subject) {
    return preg_match(self::$identifier_syntax, $subject) && !in_array(fUTF8::lower($subject), self::$reserved_words);
  }

  // @codeCoverageIgnoreStart
  /**
   * Overrides sendHeader to send a text/javascript response instead.
   *
   * @return void
   */
  public static function sendHeader() {
    header('Content-Type: text/javascript; charset=utf-8');
  }

  /**
   * Force use as a static class.
   *
   * @return sJSONP
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
