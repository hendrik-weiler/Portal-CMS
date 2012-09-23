<?php
/**
 * Provides an object-oriented interface to associative arrays. This class is
 *   not concerned with the order of the keys.
 *
 * @copyright Copyright (c) 2012 bne1.
 * @author Andrew Udvare [au] <andrew@bne1.com>
 * @license http://www.opensource.org/licenses/mit-license.php
 *
 * @package Sutra
 * @link https://github.com/tatsh/sutra
 *
 * @version 1.3
 *
 * @todo Create sOrderedObject class.
 */

namespace Sutra;

class sObject implements ArrayAccess, IteratorAggregate, Countable {
  /**
   * The actual 'array' of data this object manages.
   *
   * @var array
   */
  private $data = array();

  /**
   * The last missing key, checked by checkRequiredKeys().
   *
   * @var string
   */
  private $last_missing_key = NULL;

  /**
   * Constructor.
   *
   * @throws fProgrammerException If any keys are false-like. This
   *   includes 0, FALSE, NULL, '', and others but not strings like '0'.
   *
   * @param array $data Data to use. Keys should all be non-empty strings.
   * @return sObject
   * @SuppressWarnings(PHPMD.UnusedLocalVariable)
   */
  public function __construct(array $data = array()) {
    foreach ($data as $key => $value) {
      if (!$key) {
        throw new fProgrammerException('All keys must be non-empty strings. Error at key: "%s"', $key);
      }
    }

    $this->data = $data;
  }

  /**
   * Gets the data as a regular array.
   *
   * @return array Array of data.
   */
  public function getData() {
    return $this->data;
  }

  /**
   * Whether an offset exists.
   *
   * @internal
   *
   * @param mixed $offset Offset value.
   * @return boolean If the offset exists.
   */
  public function offsetExists($offset) {
    return isset($this->data[$offset]);
  }

  /**
   * Gets the item at an offset.
   *
   * @internal
   *
   * @param mixed $offset Offset value.
   * @return mixed The value at the offset.
   */
  public function offsetGet($offset) {
    return isset($this->data[$offset]) ? $this->data[$offset] : NULL;
  }

  /**
   * Sets the item at an offset.
   *
   * @internal
   *
   * @throws fProgrammerException If the offset is a false-like value.
   *
   * @param mixed $offset Offset to set.
   * @param mixed $value Value to set.
   * @return void
   */
  public function offsetSet($offset, $value) {
    if (!$offset) {
      throw new fProgrammerException('Key must be a non-empty string.');
    }

    $this->data[$offset] = $value;
  }

  /**
   * Returns the amount of items in the object.
   *
   * @return integer The amount of items.
   */
  public function count() {
    return sizeof($this->data);
  }

  /**
   * Unsets the item at a specified offset.
   *
   * @internal
   *
   * @param mixed $offset Offset to unset.
   * @return void
   */
  public function offsetUnset($offset) {
    unset($this->data[$offset]);
  }

  /**
   * Gets the ArrayIterator instance for use with foreach.
   *
   * @internal
   *
   * @return ArrayIterator Iterator for use with foreach.
   */
  public function getIterator() {
    return new ArrayIterator($this->data);
  }

  /**
   * Gets the keys to this object.
   *
   * @param boolean $sorted If the keys should be sorted.
   * @return array Array of keys.
   */
  public function keys($sorted = FALSE) {
    if ($sorted) {
      $keys = array_keys($this->data);
      sort($keys, SORT_STRING);
      return $keys;
    }

    return array_keys($this->data);
  }

  /**
   * Returns the class name, to be similar to how printing an array results in
   *   the text 'Array'.
   *
   * @internal
   *
   * @return string The class name.
   */
  public function __toString() {
    return __CLASS__;
  }

  /**
   * Utilized for reading data for inaccessible properties.
   *
   * @internal
   *
   * @param string $key Key to get the value of.
   * @return mixed The value or NULL.
   */
  public function __get($key) {
    return isset($this->data[$key]) ? $this->data[$key] : NULL;
  }

  /**
   * Utilized for setting data for inaccessible properties.
   *
   * @internal
   *
   * @param string $key Key to set the value of.
   * @param mixed $value Value to set.
   * @return void
   */
  public function __set($key, $value) {
    $this->data[$key] = $value;
  }

  /**
   * Prints the data in JSON format. Does not send a JSON header.
   *
   * @return void
   * @see fJSON::sendHeader
   */
  public function printJSON() {
    print fJSON::encode($this->data);
  }

  /**
   * Returns the data in JSON format.
   *
   * @return string The data in JSON format.
   * @see fJSON::sendHeader
   */
  public function toJSON() {
    return fJSON::encode($this->data);
  }

  /**
   * Applies a user-defined function to each element of this object.
   *
   * @param string $func Callback function. The callback takes two parameters:
   *   the sObject parameter first (can be a reference) and the key second.
   * @param mixed $user_data If specified, this will be passed to the callback
   *   as the third parameter.
   * @return sObject The object to allow method chaining.
   * @SuppressWarnings(PHPMD.UnusedLocalVariable)
   */
  public function walk($func, $user_data = NULL) {
    foreach ($this->data as $key => $value) {
      fCore::call($func, array($this, $key, $user_data));
    }
    return $this;
  }

  /**
   * Checks if value is an array or is array-like (implementing the correct
   *   interfaces).
   *
   * To be array-like, a class must implement both ArrayAccess and
   *   IteratorAggregate. Optionally, it can implement the Countable interface.
   *
   * @param mixed $value Value to check.
   * @return boolean If the value is an array or is array-like.
   */
  private static function isArrayLike($value) {
    if (is_array($value)) {
      return TRUE;
    }

    if ($value instanceof self) {
      return TRUE;
    }

    if (is_object($value)) {
      $reflection = new ReflectionClass($value);
      if ($reflection->implementsInterface('IteratorAggregate') &&
          $reflection->implementsInterface('ArrayAccess')) {
        return TRUE;
      }
    }

    return FALSE;
  }

  /**
   * Callback used with walkRecursive.
   *
   * @param sObject $instance Object instance.
   * @param mixed $array_like Mixed variable, checked if is array-like.
   * @param string $func Function to call on each item.
   * @param mixed $user_data User data to add as third argument to callback.
   * @return void
   */
  private static function walkRecursiveCallback(sObject $instance, &$array_like, $func, $user_data = NULL) {
    if (!self::isArrayLike($array_like)) {
      return;
    }

    $func = fCore::callback($func);

    foreach ($array_like as $key => $value) {
      call_user_func_array($func, array(&$value, $key, $user_data));
      if (self::isArrayLike($value)) {
        self::walkRecursiveCallback($instance, $value, $func, $user_data);
      }
    }
  }

  /**
   * Applies a user-defined function to each element of this object. This
   *   method will recurse into deeper arrays.
   *
   * @param string $func Callback function. The callback takes two parameters:
   *   the sObject parameter first (can be a reference) and the key second.
   * @param mixed $user_data If specified, this will be passed to the callback
   *   as the third parameter.
   * @return sObject The object to allow method chaining.
   */
  public function walkRecursive($func, $user_data = NULL) {
    $func = fCore::callback($func);
    foreach ($this->data as $key => $value) {
      call_user_func_array($func, array(&$value, $key, $user_data));
      self::walkRecursiveCallback($this, $value, $func, $user_data);
    }
    return $this;
  }

  /**
   * Gets the values of the object as normal, numerically-index array.
   *
   * @return array Array of values.
   */
  public function values() {
    return array_values($this->data);
  }

  /**
   * Searches the array for a given value and returns the corresponding key if
   *   successful. Can return boolean FALSE.
   *
   * @param mixed $needle Value to search for.
   * @param boolean $strict If the value should be identical.
   * @return boolean|string If the key is found, a string will be returned.
   *   Otherwise boolean FALSE will be returned.
   */
  public function search($needle, $strict = FALSE) {
    return array_search($needle, $this->data, $strict);
  }

  /**
   * Picks one or more random keys.
   *
   * @param integer $num_req Number of items to get.
   * @return array Array of keys.
   */
  public function rand($num_req = 1) {
    fCore::startErrorCapture(E_WARNING);
    $ret = array_rand($this->data, $num_req);
    fCore::stopErrorCapture();
    if (!is_array($ret)) {
      $ret = array($ret);
    }
    return $ret;
  }

  /**
   * Merges the values of the arguments with the values of this object.
   *
   * @param sObject|array Array or sObject instance.
   * @param mixed ...
   * @return sObject The object to allow method chaining.
   */
  public function merge($array_like) {
    $args = func_get_args();

    foreach ($args as $key => $arg) {
      if ($arg instanceof self) {
        $args[$key] = $arg->getData();
      }
    }

    array_unshift($args, $this->data);

    $this->data = call_user_func_array('array_merge', $args);
    return $this;
  }

  /**
   * Checks that the object has the required keys specified. The first missing
   *   key will be retrievable by using getLastMissingKey().
   *
   * @throws fValidationException If any key is missing.
   *
   * @param string $key Key to check.
   * @param string ...
   * @return boolean If all required keys are present.
   */
  public function checkRequiredKeys($key) {
    $keys = func_get_args();
    $ret = TRUE;

    foreach ($keys as $key) {
      if (!array_key_exists($key, $this->data)) {
        $this->last_missing_key = $key;
        $ret = FALSE;
        break;
      }
    }

    return $ret;
  }

  /**
   * Gets the last missing key. NULL is the default value.
   *
   * @return string String of the last missing key, or NULL.
   */
  public function getLastMissingKey() {
    return $this->last_missing_key;
  }

  /**
   * Validates that the object has the required keys specified.
   *
   * If the first argument is an array, that will be considered the set of
   *   keys.
   *
   * @throws fValidationException If any key is missing.
   *
   * @param string|array $key Key to check, or array of keys.
   * @param string ...
   * @return sObject The object to allow method chaining.
   */
  public function validateRequiredKeys($key) {
    $cb = array($this, 'checkRequiredKeys');
    $args = array();

    if (is_array($key)) {
      $args = $key;
    }
    else {
      $args = func_get_args();
    }

    if (!fCore::call($cb, $args)) {
      throw new fValidationException('The object is missing a key: "%s"', $this->last_missing_key);
    }

    return $this;
  }

  /**
   * Calls a callback on each item in the object. If the callback returns TRUE,
   *   then the value will be returned in the resulting sObject instance of this
   *   method.
   *
   * @param string $cb Callback to call on each key.
   * @return sObject New filtered sObject.
   * @see array_filter()
   */
  public function filter($cb) {
    return new self(array_filter($this->data, $cb));
  }

  /**
   * Fills the object with keys (replacing old ones if present) with the same
   *   value.
   *
   * @param array $keys Keys to add.
   * @param mixed $value Value to set.
   * @return sObject The object to allow method chaining.
   */
  public function fill(array $keys, $value) {
    $this->data = array_merge($this->data, array_fill_keys($keys, $value));
    return $this;
  }

  /**
   * Compares this object's data with associative arrays.
   *
   * @param mixed $array1 Array or array-like object.
   * @param mixed ...
   * @return sObject Object containing all the entries from $array1 that are
   *   not present in any of the other arrays.
   */
  public function diff($array1) {
    $args = func_get_args();
    array_unshift($args, $this->data);
    return new self(call_user_func_array('array_diff', $args));
  }

  /**
   * Returns a new sObject instance with keys changed to the case specified.
   *
   * @param integer $case One of: CASE_LOWER, CASE_UPPER.
   * @return sObject New sObject instance with keys changed.
   */
  public function convertKeyCase($case = CASE_LOWER) {
    if ($case !== CASE_LOWER && $case !== CASE_UPPER) {
      throw new fProgrammerException('Case argument must be one of: "%s"', implode(', ', array('CASE_LOWER', 'CASE_UPPER')));
    }

    return new self(array_change_key_case($this->data, $case));
  }
}
