<?php
/**
 * An object-oriented interface to numerically indexed arrays.
 *
 * @copyright Copyright (c) 2012 bne1.
 * @author Andrew Udvare [au] <andrew@bne1.com>
 * @license http://www.opensource.org/licenses/mit-license.php
 *
 * @package Sutra
 * @link https://github.com/tatsh/sutra
 *
 * @version 1.3
 */

namespace Sutra;

class sArray implements Countable, ArrayAccess, IteratorAggregate {
  /**
   * The data.
   *
   * @var array
   */
  protected $data = array();

  /**
   * Constructor. Accepts multiple arguments or only a single array argument.
   *
   * @param mixed $arg First item of the array.
   * @param mixed ...
   * @return sArray
   */
  public function __construct($arg = NULL) {
    if (is_array($arg)) {
      $this->data = array_values($arg);
      return;
    }

    $this->data = func_get_args();
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
   * Returns the size of array.
   *
   * @return integer The size of the array.
   */
  public function count() {
    return sizeof($this->data);
  }

  /**
   * So the object can be used with foreach.
   *
   * @internal
   *
   * @return ArrayIterator Iterator object.
   */
  public function getIterator() {
    return new ArrayIterator($this->data);
  }

  /**
   * Checks if the offset exists.
   *
   * @internal
   *
   * @throws fProgrammerException If the offset is not an integer.
   *
   * @param integer $offset Offset.
   * @return boolean If the offset exists.
   */
  public function offsetExists($offset) {
    if (!is_numeric($offset) || is_float($offset)) {
      throw new fProgrammerException('Offsets can only be integer. Given: "%s"', $offset);
    }

    $offset = (int)$offset;
    return isset($this->data[$offset]);
  }

  /**
   * Gets the value at a specific offset.
   *
   * @internal
   *
   * @throws fProgrammerException If the offset is not an integer.
   *
   * @param integer $offset Offset.
   * @return mixed The value or NULL.
   */
  public function offsetGet($offset) {
    if (!is_numeric($offset) || is_float($offset)) {
      throw new fProgrammerException('Offsets can only be integer. Given: "%s"', $offset);
    }

    $offset = (int)$offset;
    return isset($this->data[$offset]) ? $this->data[$offset] : NULL;
  }

  /**
   * Sets the value at an offset.
   *
   * @internal
   *
   * @param integer $offset Offset to set to.
   * @param mixed $value Value to set.
   * @return void
   */
  public function offsetSet($offset, $value) {
    if (isset($this->data[$offset])) {
      $this->data[$offset] = $value;
    }
    else {
      $this->data[] = $value;
    }
  }

  /**
   * Unsets the value at an offset.
   *
   * @internal
   *
   * @throws fProgrammerException If the offset is not an integer.
   *
   * @param integer $offset Offset.
   * @return void
   */
  public function offsetUnset($offset) {
    if (!is_numeric($offset) || is_float($offset)) {
      throw new fProgrammerException('Offsets can only be integer. Given: "%s"', $offset);
    }

    $offset = (int)$offset;
    unset($this->data[$offset]);
  }

  /**
   * This is only for getting the 'length' attribute, to be similar to
   *   JavaScript.
   *
   * @internal
   *
   * @param string $key Key to get value of. Only 'length' is accepted.
   * @return mixed The length of the array or NULL if the key is invalid.
   */
  public function __get($key) {
    if ($key == 'length') {
      return count($this);
    }
    return NULL;
  }

  // Mutators
  /**
   * Pops the last element of the array and returns its value.
   *
   * @return mixed The value at the last index.
   */
  public function pop() {
    return array_pop($this->data);
  }

  /**
   * Pushes the value into the last position of the array.
   *
   * @param mixed $var Variable to push.
   * @return sArray The object to allow method chaining.
   */
  public function push($var) {
    $this->data[] = $var;
    return $this;
  }

  /**
   * Fills the array $num times with $value.
   *
   * @param integer $num Number of times to fill.
   * @param mixed $value Value to fill with.
   * @return sArray The object to allow method chaining.
   */
  public function fill($num, $value) {
    for ($i = 0; $i < $num; $i++) {
      $this->data[] = $value;
    }
    return $this;
  }

  /**
   * Shifts the first value off the array and returns it.
   *
   * @return mixed The value at the first index of the array.
   */
  public function shift() {
    return array_shift($this->data);
  }

  /**
   * Puts a new element at the beginning of the array.
   *
   * @param mixed $var Variable to unshift.
   * @return sArray The object to allow method chaining.
   */
  public function unshift($var) {
    array_unshift($this->data, $var);
    return $this;
  }

  /**
   * Merges this array with another array or sArray object.
   *
   * @param array|sArray $array1 Array to shift with.
   * @param array|sArray ...
   * @return sArray The object to allow method chaining.
   */
  public function merge($array1) {
    $args = func_get_args();

    foreach ($args as $key => $arg) {
      if ($arg instanceof self) {
        $args[$key] = $arg->getData();
      }
    }

    array_unshift($args, $this->data);
    $this->data = array_values(call_user_func_array('array_merge', $args));

    return $this;
  }

  /**
   * Applies a user-defined function to each element of this object.
   *
   * @param string|array $func Callback function. The callback takes two
   *   parameters: the value of the key, and the key second. If the value must
   *   be changed, then it should be specified as a reference.
   * @param mixed $user_data If specified, this will be passed to the callback
   *   as the third parameter.
   * @return sArray The object to allow method chaining.
   * @SuppressWarnings(PHPMD.UnusedLocalVariable)
   */
  public function walk($func, $user_data = NULL) {
    $func = fCore::callback($func);
    foreach ($this->data as $key => $value) {
      call_user_func_array($func, array(&$value, $key, $user_data));
      $this->data[$key] = $value;
    }
    $this->data = array_values($this->data);
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
   * @param sArray $instance Object instance.
   * @param mixed $array_like Mixed variable, checked if is array-like.
   * @param string $func Function to call on each item.
   * @param mixed $user_data User data to add as third argument to callback.
   * @return void
   */
  private static function walkRecursiveCallback(sArray $instance, &$array, $func, $user_data = NULL) {
    if (!self::isArrayLike($array)) {
      return;
    }
    
    $func = fCore::callback($func);

    foreach ($array as $key => $value) {
      call_user_func_array($func, array(&$value, $key, $user_data));
      if (self::isArrayLike($value)) {
        self::walkRecursiveCallback($instance, $value, $func, $user_data);
      }
    }
  }

  /**
   * Applies a user-defined function to each element of this object. This
   *   method will recurse into deeper arrays. Any key sets will be ignored.
   *
   * @param string|array $func Callback function. The callback takes two
   *   parameters: the value of the key, and the key second. If the value must
   *   be changed, then it should be specified as a reference.
   * @param mixed $user_data If specified, this will be passed to the callback
   *   as the third parameter.
   * @return sArray The object to allow method chaining.
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
   * Sorts the elements of the array.
   *
   * @param integer $flags Flags for sorting.
   * @return sArray The object to allow method chaining.
   * @see sort()
   */
  public function sort($flags = SORT_STRING) {
    sort($this->data, $flags);
    return $this;
  }

  /**
   * Sorts the elements of the array reversed.
   *
   * @param integer $flags Flags for sorting.
   * @return sArray The object to allow method chaining.
   * @see rsort()
   */
  public function reverseSort($flags = SORT_STRING) {
    rsort($this->data, $flags);
    return $this;
  }

  // Non-mutators
  /**
   * Recursively converts an array to an array of strings.
   *
   * @param array|sArray Array or array-like object.
   * @return array Array of strings.
   */
  private static function convertToStrings($values) {
    foreach ($values as $key => $value) {
      if (self::isArrayLike($value)) {
        $values[$key] = implode(',', self::convertToStrings($value));
      }
      else {
        $values[$key] = (string)$value;
      }
    }

    return $values;
  }

  /**
   * Implements __toString(). Like JavaScript, returns the elements separated
   *   by a comma. Internal arrays and array-like objects are also handled, but
   *   are not separated by any symbol (like JavaScript).
   *
   * @internal
   *
   * @return string
   */
  public function __toString() {
    $arr = self::convertToStrings($this->data);
    return implode(',', $arr);
  }

  /**
   * Prints the JSON-encoded array.
   *
   * @return void
   */
  public function printJSON() {
    print fJSON::encode($this->data);
  }

  /**
   * Returns the JSON-encoded array.
   *
   * @return string JSON string.
   */
  public function toJSON() {
    return fJSON::encode($this->data);
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
   * @return array Array of numeric keys.
   */
  public function rand($num_req = 1) {
    fCore::startErrorCapture();
    $ret = array_rand($this->data, $num_req);
    fCore::stopErrorCapture();
    if (!is_array($ret)) {
      $ret = array($ret);
    }
    return $ret;
  }

  /**
   * Compares this object's data with associative arrays.
   *
   * @param array|sArray $array1 Array or array-like object.
   * @param array|sArray ...
   * @return sArray Array containing all the entries from $array1 that are
   *   not present in any of the other arrays.
   */
  public function diff($array1) {
    $args = func_get_args();

    foreach ($args as $key => $arg) {
      if ($arg instanceof self) {
        $args[$key] = $arg->getData();
      }
    }

    array_unshift($args, $this->data);

    return new self(call_user_func_array('array_diff', $args));
  }

  /**
   * Returns a copy of this array reversed.
   *
   * @return sArray The array, reversed.
   */
  public function reverse() {
    return new self(array_reverse($this->data));
  }

  /**
   * Extract a slice of the array.
   *
   * @param integer $offset If offset is non-negative, the sequence will start
   *   at that offset in the array. If offset is negative, the sequence will
   *   start that far from the end of the array.
   * @param integer $length If length is given and is positive, then the
   *   sequence will have up to that many elements in it. If the array is
   *   shorter than the length, then only the available array elements will be
   *   present. If length is given and is negative then the sequence will stop
   *   that many elements from the end of the array. If it is omitted, then the
   *   sequence will have everything from offset up until the end of the array.
   * @return sArray Array slice.
   */
  public function slice($offset, $length = NULL) {
    return new self(array_slice($this->data, $offset, $length));
  }

  /**
   * Calls a callback on each item in the object. If the callback returns TRUE,
   *   then the value will be returned in the resulting sArray instance of this
   *   method.
   *
   * @param string $cb Callback to call on each key.
   * @return sArray New filtered sArray.
   * @see array_filter()
   */
  public function filter($cb) {
    return new self(array_filter($this->data, $cb));
  }

  /**
   * Applies the callback to the elements of this array.
   *
   * @param string $cb Callback to use.
   * @return sArray
   * @see array_map()
   */
  public function map($cb) {
    return new self(array_map($cb, $this->data));
  }

  /**
   * Pad array to the specified length with a value.
   *
   * @param integer $pad_size New size of the array.
   * @param mixed $pad_value Value to pad if the array is less than $pad_size.
   * @return sArray Returns a copy of the input padded to size specified by
   *   $pad_size with value $pad_value.
   * @see array_pad()
   */
  public function pad($pad_size, $pad_value) {
    return new self(array_pad($this->data, $pad_size, $pad_value));
  }

  /**
   * Removes duplicate values from the array.
   *
   * @param integer $sort_flags One of the SORT_* constants.
   * @return sArray Copy of this array with duplicate values removed.
   * @see array_unique
   */
  public function unique($sort_flags = SORT_STRING) {
    return new self(array_unique($this->data, $sort_flags));
  }

  /**
   * Returns the values of this array. Alias to getData().
   *
   * @return array Array of data.
   */
  public function values() {
    return $this->data;
  }

  /**
   * Returns an sObject instance with the indexes of this array being the
   *   values and the values becoming the keys.
   * @return sObject
   */
  public function flip() {
    return new sObject(array_flip($this->data));
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
