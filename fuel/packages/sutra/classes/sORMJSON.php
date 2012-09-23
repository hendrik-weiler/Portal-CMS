<?php
/**
 * Extension to fORMJSON.
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

class sORMJSON extends fORMJSON {
  const configureJSONSerializedColumn = 'sORMJSON::configureJSONSerializedColumn';

  /**
   * Columns => class names registered.
   *
   * @var array
   */
  private static $json_columns = array();

  /**
   * If the callbacks are registered.
   *
   * @var boolean
   */
  private static $callbacks_registered = FALSE;

  /**
   * Configure a column string column (VARCHAR or TEXT) to store a JSON
   *   serialised object. Conversion to an array and back is transparent. If
   *   there are validations to perform on these columns when they are in their
   *   original data type, those callbacks should be registered before calling
   *   this method.
   *
   * @param string $class Class name or instance of the class.
   * @param string $column The column name.
   * @param boolean $force_array Force the JSON to be converted to be an array.
   * @return void
   */
  public static function configureJSONSerializedColumn($class, $column, $force_array = TRUE) {
    $class = fORM::getClass($class);
    $table = fORM::tablize($class);
    $schema = fORMSchema::retrieve($class);
    $data_type = $schema->getColumnInfo($table, $column, 'type');

    $valid_types = array('varchar', 'text');
    if (!in_array($data_type, $valid_types)) {
      throw new fProgrammerException('The column specified, %1$s, is a %2$s column. Must be one of %3$s to be set as a JSON serialised column.', $column, $data_type, join(', ', $valid_types));
    }

    if (!self::$callbacks_registered) {
      fORM::registerHookCallback($class, 'post::loadFromIdentityMap()', array(__CLASS__, 'JSONToValue'));
      fORM::registerHookCallback($class, 'post::loadFromResult()', array(__CLASS__, 'JSONToValue'));
      fORM::registerHookCallback($class, 'pre::validate()', array(__CLASS__, 'valueToJSON'));
      self::$callbacks_registered = TRUE;
    }

    if (!isset(self::$json_columns[$class])) {
      self::$json_columns[$class] = array();
    }

    self::$json_columns[$class][] = array(
      'force_array' => $force_array ? TRUE : FALSE,
      'column' => $column,
    );
  }

  /**
   * Converts the JSON string to the value after getting retrieved from the database.
   *
   * @internal
   *
   * @param fActiveRecord $object The fActiveRecord instance.
   * @param array $values Current values array reference.
   * @param array $old_values Old values array reference.
   * @param array $related_records Related records array reference.
   * @param array $cache Cache array reference.
   * @return void
   *
   * @SuppressWarnings(PHPMD.UnusedFormalParameter)
   */
  public static function JSONToValue($object, &$values, &$old_values, &$related_records, &$cache) {
    $class = get_class($object);

    if (!isset(self::$json_columns[$class])) {
      return;
    }
    
    foreach (self::$json_columns[$class] as $info) {
      $content = fJSON::decode($values[$info['column']], $info['force_array']);
      fActiveRecord::assign($values, $old_values, $info['column'], $content);
    }
  }

  /**
   * Converts the value to a JSON string before validation.
   *
   * @internal
   *
   * @param fActiveRecord $object The fActiveRecord instance.
   * @param array $values Current values array reference.
   * @param array $old_values Old values array reference.
   * @param array $related_records Related records array reference.
   * @param array $cache Cache array reference.
   * @return void
   *
   * @SuppressWarnings(PHPMD.UnusedFormalParameter)
   */
  public static function valueToJSON($object, &$values, &$old_values, &$related_records, &$cache) {
    $class = get_class($object);

    if (!isset(self::$json_columns[$class])) {
      return;
    }

    foreach (self::$json_columns[$class] as $info) {
      $content = fJSON::encode($values[$info['column']]);
      fActiveRecord::assign($values, $old_values, $info['column'], $content);
    }
  }

  // @codeCoverageIgnoreStart
  /**
   * Forces use as a static class.
   *
   * @return sORMJSON
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
