<?php
/**
 * Provides getting ordinal numbers as an extension to fNumber.
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

class sNumber extends fNumber {
  const ordinalSuffix     = 'sNumber::ordinalSuffix';
  const addCallback       = 'sNumber::addCallback';
  const removeLocale      = 'sNumber::removeLocale';
  const setLocale         = 'sNumber::setLocale';
  const setFallbackLocale = 'sNumber::setFallbackLocale';
  const ordinal           = 'sNumber::ordinal';
  const isEqualToIntCast  = 'sNumber::isEqualToIntCast';

  /**
   * Common list of locales.
   *
   * @see http://www.iana.org/assignments/language-subtag-registry
   * @var array
   */
  private static $valid_locales = array(
    'af-ZA' => TRUE,
    'am-ET' => TRUE,
    'ar-AE' => TRUE,
    'ar-BH' => TRUE,
    'ar-DZ' => TRUE,
    'ar-EG' => TRUE,
    'ar-IQ' => TRUE,
    'ar-JO' => TRUE,
    'ar-KW' => TRUE,
    'ar-LB' => TRUE,
    'ar-LY' => TRUE,
    'ar-MA' => TRUE,
    'arn-CL' => TRUE,
    'ar-OM' => TRUE,
    'ar-QA' => TRUE,
    'ar-SA' => TRUE,
    'ar-SY' => TRUE,
    'ar-TN' => TRUE,
    'ar-YE' => TRUE,
    'as-IN' => TRUE,
    'az-Cyrl-AZ' => TRUE,
    'az-Latn-AZ' => TRUE,
    'ba-RU' => TRUE,
    'be-BY' => TRUE,
    'bg-BG' => TRUE,
    'bn-BD' => TRUE,
    'bn-IN' => TRUE,
    'bo-CN' => TRUE,
    'br-FR' => TRUE,
    'bs-Cyrl-BA' => TRUE,
    'bs-Latn-BA' => TRUE,
    'ca-ES' => TRUE,
    'co-FR' => TRUE,
    'cs-CZ' => TRUE,
    'cy-GB' => TRUE,
    'da-DK' => TRUE,
    'de-AT' => TRUE,
    'de-CH' => TRUE,
    'de-DE' => TRUE,
    'de-LI' => TRUE,
    'de-LU' => TRUE,
    'dsb-DE' => TRUE,
    'dv-MV' => TRUE,
    'el-GR' => TRUE,
    'en-029' => TRUE,
    'en-AU' => TRUE,
    'en-BZ' => TRUE,
    'en-CA' => TRUE,
    'en-GB' => TRUE,
    'en-IE' => TRUE,
    'en-IN' => TRUE,
    'en-JM' => TRUE,
    'en-MY' => TRUE,
    'en-NZ' => TRUE,
    'en-PH' => TRUE,
    'en-SG' => TRUE,
    'en-TT' => TRUE,
    'en-US' => TRUE,
    'en-ZA' => TRUE,
    'en-ZW' => TRUE,
    'es-AR' => TRUE,
    'es-BO' => TRUE,
    'es-CL' => TRUE,
    'es-CO' => TRUE,
    'es-CR' => TRUE,
    'es-DO' => TRUE,
    'es-EC' => TRUE,
    'es-ES' => TRUE,
    'es-GT' => TRUE,
    'es-HN' => TRUE,
    'es-MX' => TRUE,
    'es-NI' => TRUE,
    'es-PA' => TRUE,
    'es-PE' => TRUE,
    'es-PR' => TRUE,
    'es-PY' => TRUE,
    'es-SV' => TRUE,
    'es-US' => TRUE,
    'es-UY' => TRUE,
    'es-VE' => TRUE,
    'et-EE' => TRUE,
    'eu-ES' => TRUE,
    'fa-IR' => TRUE,
    'fi-FI' => TRUE,
    'fil-PH' => TRUE,
    'fo-FO' => TRUE,
    'fr-BE' => TRUE,
    'fr-CA' => TRUE,
    'fr-CH' => TRUE,
    'fr-FR' => TRUE,
    'fr-LU' => TRUE,
    'fr-MC' => TRUE,
    'fy-NL' => TRUE,
    'ga-IE' => TRUE,
    'gd-GB' => TRUE,
    'gl-ES' => TRUE,
    'gsw-FR' => TRUE,
    'gu-IN' => TRUE,
    'ha-Latn-NG' => TRUE,
    'he-IL' => TRUE,
    'hi-IN' => TRUE,
    'hr-BA' => TRUE,
    'hr-HR' => TRUE,
    'hsb-DE' => TRUE,
    'hu-HU' => TRUE,
    'hy-AM' => TRUE,
    'id-ID' => TRUE,
    'ig-NG' => TRUE,
    'ii-CN' => TRUE,
    'is-IS' => TRUE,
    'it-CH' => TRUE,
    'it-IT' => TRUE,
    'iu-Cans-CA' => TRUE,
    'iu-Latn-CA' => TRUE,
    'ja-JP' => TRUE,
    'ka-GE' => TRUE,
    'kk-KZ' => TRUE,
    'kl-GL' => TRUE,
    'km-KH' => TRUE,
    'kn-IN' => TRUE,
    'kok-IN' => TRUE,
    'ko-KR' => TRUE,
    'ky-KG' => TRUE,
    'lb-LU' => TRUE,
    'lo-LA' => TRUE,
    'lt-LT' => TRUE,
    'lv-LV' => TRUE,
    'mi-NZ' => TRUE,
    'mk-MK' => TRUE,
    'ml-IN' => TRUE,
    'mn-MN' => TRUE,
    'mn-Mong-CN' => TRUE,
    'moh-CA' => TRUE,
    'mr-IN' => TRUE,
    'ms-BN' => TRUE,
    'ms-MY' => TRUE,
    'mt-MT' => TRUE,
    'nb-NO' => TRUE,
    'ne-NP' => TRUE,
    'nl-BE' => TRUE,
    'nl-NL' => TRUE,
    'nn-NO' => TRUE,
    'nso-ZA' => TRUE,
    'oc-FR' => TRUE,
    'or-IN' => TRUE,
    'pa-IN' => TRUE,
    'pl-PL' => TRUE,
    'prs-AF' => TRUE,
    'ps-AF' => TRUE,
    'pt-BR' => TRUE,
    'pt-PT' => TRUE,
    'qut-GT' => TRUE,
    'quz-BO' => TRUE,
    'quz-EC' => TRUE,
    'quz-PE' => TRUE,
    'rm-CH' => TRUE,
    'ro-RO' => TRUE,
    'ru-RU' => TRUE,
    'rw-RW' => TRUE,
    'sah-RU' => TRUE,
    'sa-IN' => TRUE,
    'se-FI' => TRUE,
    'se-NO' => TRUE,
    'se-SE' => TRUE,
    'si-LK' => TRUE,
    'sk-SK' => TRUE,
    'sl-SI' => TRUE,
    'sma-NO' => TRUE,
    'sma-SE' => TRUE,
    'smj-NO' => TRUE,
    'smj-SE' => TRUE,
    'smn-FI' => TRUE,
    'sms-FI' => TRUE,
    'sq-AL' => TRUE,
    'sr-Cyrl-BA' => TRUE,
    'sr-Cyrl-CS' => TRUE,
    'sr-Cyrl-ME' => TRUE,
    'sr-Cyrl-RS' => TRUE,
    'sr-Latn-BA' => TRUE,
    'sr-Latn-CS' => TRUE,
    'sr-Latn-ME' => TRUE,
    'sr-Latn-RS' => TRUE,
    'sv-FI' => TRUE,
    'sv-SE' => TRUE,
    'sw-KE' => TRUE,
    'syr-SY' => TRUE,
    'ta-IN' => TRUE,
    'te-IN' => TRUE,
    'tg-Cyrl-TJ' => TRUE,
    'th-TH' => TRUE,
    'tk-TM' => TRUE,
    'tn-ZA' => TRUE,
    'tr-TR' => TRUE,
    'tt-RU' => TRUE,
    'tzm-Latn-DZ' => TRUE,
    'ug-CN' => TRUE,
    'uk-UA' => TRUE,
    'ur-PK' => TRUE,
    'uz-Cyrl-UZ' => TRUE,
    'uz-Latn-UZ' => TRUE,
    'vi-VN' => TRUE,
    'wo-SN' => TRUE,
    'xh-ZA' => TRUE,
    'yo-NG' => TRUE,
    'zh-CN' => TRUE,
    'zh-HK' => TRUE,
    'zh-MO' => TRUE,
    'zh-SG' => TRUE,
    'zh-TW' => TRUE,
    'zu-ZA' => TRUE,
  );

  /**
   * The locale for the class. Defaults to US English.
   *
   * @var string
   */
  private static $locale = 'en-US';

  /**
   * The fallback to use if the locale has no callback for the locale in use.
   *   Defaults to US English.
   *
   * @var string
   */
  private static $fallback_locale = 'en-US';

  /**
   * Callbacks for other languages.
   *
   * @var array
   */
  private static $callbacks = array(
    'en-AU' => array( // Australia
      'ordinal' => 'sNumber::ordinalNumberPrefixedCallback',
      'ordinalSuffix' => 'sNumber::ordinalSuffix',
    ),
    'en-CA' => array( // Canada
      'ordinal' => 'sNumber::ordinalNumberPrefixedCallback',
      'ordinalSuffix' => 'sNumber::ordinalSuffix',
    ),
    'en-GB' => array( // United Kingdom
      'ordinal' => 'sNumber::ordinalNumberPrefixedCallback',
      'ordinalSuffix' => 'sNumber::ordinalSuffix',
    ),
    'en-IE' => array( // Ireland
      'ordinal' => 'sNumber::ordinalNumberPrefixedCallback',
      'ordinalSuffix' => 'sNumber::ordinalSuffix',
    ),
    'en-IN' => array( // India
      'ordinal' => 'sNumber::ordinalNumberPrefixedCallback',
      'ordinalSuffix' => 'sNumber::ordinalSuffix',
    ),
    'en-MT' => array( // Malta
      'ordinal' => 'sNumber::ordinalNumberPrefixedCallback',
      'ordinalSuffix' => 'sNumber::ordinalSuffix',
    ),
    'en-NZ' => array( // New Zealand
      'ordinal' => 'sNumber::ordinalNumberPrefixedCallback',
      'ordinalSuffix' => 'sNumber::ordinalSuffix',
    ),
    'en-PH' => array( // Philippines
      'ordinal' => 'sNumber::ordinalNumberPrefixedCallback',
      'ordinalSuffix' => 'sNumber::ordinalSuffix',
    ),
    'en-SG' => array( // Singapore
      'ordinal' => 'sNumber::ordinalNumberPrefixedCallback',
      'ordinalSuffix' => 'sNumber::ordinalSuffix',
    ),
    'en-US' => array( // United States
      'ordinal' => 'sNumber::ordinalNumberPrefixedCallback',
      'ordinalSuffix' => 'sNumber::ordinalSuffix',
    ),
    'en-ZA' => array( // South Africa
      'ordinal' => 'sNumber::ordinalNumberPrefixedCallback',
      'ordinalSuffix' => 'sNumber::ordinalSuffix',
    ),
  );

  /**
   * Checks if a locale is valid.
   *
   * @throws fProgrammerException If the locale is invalid.
   *
   * @param string $locale Locale to check.
   * @return void
   */
  private static function tossIfInvalidLocale($locale) {
    if (!isset(self::$valid_locales[$locale])) {
      throw new fProgrammerException('The locale specified, "%s", is not a valid locale. It must be one of: %s. If you think this is a bug or a locale must be added, please file an issue at %s.',
        $locale,
        implode(', ', array_keys(self::$valid_locales)),
        'https://github.com/tatsh/sutra/issues'
      );
    }
  }

  /**
   * Adds an array of callbacks with the default methods in this class for a
   *   specified locale.
   *
   * @throws fProgrammerException If the locale is invalid.
   *
   * @param string $locale Locale name, such as fr-FR.
   * @return void
   */
  private static function addDefaultCallbacks($locale) {
    $locale = str_replace('_', '-', $locale);
    self::tossIfInvalidLocale($locale);

    if (!isset(self::$callbacks[$locale])) {
      self::$callbacks[$locale] = array(
        'oridinal' => __CLASS__.'::ordinal',
        'ordinalSuffix' => __CLASS__.'::ordinalSuffix',
      );
    }
  }

  /**
   * Add a callback that will be used in place of the ones here.
   *
   * For use with different languages and locales.
   *
   * All callbacks must receive one value, an integer, and return a string.
   *
   * @throws fProgrammerException If the method name or locale is invalid.
   *
   * @param string $locale The locale name. Should be a standard locale
   *   name such as en-GB, fr-FR, etc.
   * @param string $method_name Method name in this class to override. One of:
   *   ordinal, ordinalSuffix. The instance methods will also use this
   *   callback.
   * @param string|array $callback Callback to use.
   * @return void
   */
  public static function addCallback($locale, $method_name, $callback) {
    $locale = str_replace('_', '-', $locale);

    self::tossIfInvalidLocale($locale);

    $valid_methods = array(
      'ordinal',
      'ordinalSuffix',
    );

    if (!in_array($method_name, $valid_methods)) {
      throw new fProgrammerException('Invalid method name "%s" specified. Must be one of: %s.', $method_name, implode(', ', $valid_methods));
    }

    self::$callbacks[$locale][$method_name] = $callback;
    $check = $method_name != 'ordinal' ? 'ordinal' : 'ordinalSuffix';

    if (!isset(self::$callbacks[$locale][$check])) {
      self::$callbacks[$locale][$check] = __CLASS__.'::'.$check;
    }
  }

  /**
   * Remove a locale's set of callbacks.
   *
   * @param string $locale_name Locale name.
   * @return void
   */
  public static function removeLocale($locale_name) {
    $locale_name = str_replace('_', '-', $locale_name);

    if (isset(self::$callbacks[$locale_name])) {
      unset(self::$callbacks[$locale_name]);
    }

    if (self::$locale == $locale_name) {
      self::$locale = 'en_US';
      self::$fallback_locale = 'en_US';
    }
  }

  /**
   * Set the current locale in use for this class. If no callbacks yet exist,
   *   the defaults in this class will be assigned.
   *
   * @throws fProgrammerException If the locale is invalid.
   *
   * @param string $locale The locale name. Should be a standard locale name
   *   such as en-GB, fr-FR, etc.
   * @return void
   */
  public static function setLocale($locale) {
    $locale = str_replace('_', '-', $locale);

    self::tossIfInvalidLocale($locale);

    if(!isset(self::$callbacks[$locale])) {
      self::addDefaultCallbacks($locale);
    }
    self::$locale = $locale;
  }

  /**
   * Set the fallback locale if the current locale set does not have a
   *   callback for the method requested. If no callbacks yet exist,
   *   the defaults in this class will be assigned.
   *
   * @param string $locale Locale name.
   * @return void
   */
  public static function setFallbackLocale($locale) {
    $locale = str_replace('_', '-', $locale);

    self::tossIfInvalidLocale($locale);

    if(!isset(self::$callbacks[$locale])) {
      self::addDefaultCallbacks($locale);
    }
    self::$fallback_locale = $locale;
  }

  /**
   * Get the correct callback based on the locale and fallback locale set in
   *   the class.
   *
   * @param string $fn Method name to check for.
   * @return string Callback name.
   */
  private static function getValidCallback($fn) {
    if (!isset(self::$callbacks[self::$locale][$fn])) {
      return self::$callbacks[self::$fallback_locale][$fn];
    }

    return self::$callbacks[self::$locale][$fn];
  }

  /**
   * Format a number to be ordinal.
   *
   * @param int $value Number to use.
   * @return string Number with proper English suffix.
   */
  public static function ordinal($value) {
    return fCore::call(self::getValidCallback(__FUNCTION__), array($value));
  }

  /**
   * Callback for English ordinal numbers (where numbers come before the
   *   ordinal keyword).
   *
   * @internal
   *
   * @param integer $value
   * @return string The value, formatted.
   */
  public static function ordinalNumberPrefixedCallback($value) {
    $cb = self::getValidCallback('ordinalSuffix');
    return $value.fCore::call($cb, array($value));
  }

  /**
   * Get the correct oridinal suffix for a number.
   *
   * @param integer $value Number to use.
   * @return string Correct suffix.
   */
  public static function ordinalSuffix($value) {
    $cb = self::getValidCallback('ordinalSuffix');
    if ($cb != __CLASS__.'::'.__FUNCTION__) {
      return fCore::call($cb, array($value));
    }

    $suffix = 'th';

    if (!(substr($value, -2, 2) == 11 ||
          substr($value, -2, 2) == 12 ||
          substr($value, -2, 2) == 13)) {
      if (substr($value, -1, 1) == 1) {
        $suffix = 'st';
      }
      else if (substr($value, -1, 1) == 2) {
        $suffix = 'nd';
      }
      else if (substr($value, -1, 1) == 3) {
        $suffix = 'rd';
      }
    }

    return $suffix;
  }

  /**
   * Checks if a number is equal to its integer-casted counterpart.
   *
   * @param mixed $value Value to check.
   * @return boolean If the int-casted value is the same.
   */
  public static function isEqualToIntCast($value) {
    if (!is_numeric($value)) {
      return FALSE;
    }

    return $value == intval($value);
  }

  /**
   * Get the correct suffix for the current number.
   *
   * @return string Correct English suffix.
   */
  public function getOrdinalSuffix() {
    return self::ordinalSuffix((int)$this->__toString());
  }

  /**
   * Get the number formatted with the oridinal suffix.
   *
   * @param boolean $remove_zero_fraction If TRUE and all digits after the
   *   decimal place are 0, the decimal place and all zeros are removed.
   * @return string Number with proper English suffix.
   */
  public function formatWithOrdinalSuffix($remove_zero_fraction = FALSE) {
    $formatted = $this->format($remove_zero_fraction);
    return $formatted.$this->getOrdinalSuffix();
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
