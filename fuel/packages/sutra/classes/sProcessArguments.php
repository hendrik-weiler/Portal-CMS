<?php
/**
 * Process argument handling code.
 *
 * @internal
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

class sProcessArguments {
  private $program = NULL;

  private $arguments = array();

  /**
   * Strips double and singular surrounding quotes out of all arguments.
   *
   * @return void
   */
  private function stripQuotes() {
    foreach ($this->arguments as $key => $arg) {
      $end = strlen($arg) - 1;
      if (($arg[0] === '"' && $arg[$end] === '"') || ($arg[0] === '\'' && $arg[$end] === '\'')) {
        $this->arguments[$key] = substr($arg, 1, $end - 1);
      }
    }
  }

  /**
   * Parses the name and arguments from the arguments given to __construct().
   *
   * @param array $arguments Arguments passed.
   * @return void
   */
  private function parseNameAndArguments(array $arguments) {
    $count = count($arguments);
    $is_string = is_string($arguments[0]);

    // new sProcess(array('curl', 'a', 'b', 'c'))
    if (is_array($arguments[0]) &&  $count == 1) {
      $this->program = trim($arguments[0][0]);
      $this->arguments = array_slice($arguments[0], 1);
    }
    // new sProcess('curl', array('a', 'b', 'c'))
    // new sProcess('curl', 'a')
    else if ($is_string && $count == 2) {
      $this->program = trim($arguments[0]);

      if (is_array($arguments[1])) {
        $this->arguments = $arguments[1];
      }
      else {
        $this->arguments = array((string)$arguments[1]);
      }
    }
    // new sProcess('curl a b c d')
    else if ($is_string && $count == 1) {
      $args = explode(' ', $arguments[0]);
      $this->program = trim($args[0]);
      $this->arguments = array_slice($args, 1);
    }
    // new sProcess('curl', 'a', 'b', 'c','d')
    else {
      $this->program = trim($arguments[0]);
      $this->arguments = array_slice($arguments, 1);
    }

    $this->stripQuotes();
  }

  public function __construct($args) {
    $this->parseNameAndArguments(func_get_args());

    if (sProcess::checkOS('windows') && substr($this->program, -4) === '.exe') {
      $this->program = substr($this->program, 0, -4);
    }
  }

  private static function unquote($value, $delimiter) {
    switch ($delimiter) {
      case '=':
        $value = substr($value, 1, -1);
        break;
    }

    return $value;
  }

  public function add($key, $value = NULL, $delimiter = ' ') {
    $this->arguments[$key] = array(
      'value' => self::unquote($value, $delimiter),
      'delimiter' => $delimiter,
    );
    return $this;
  }

  public function makeCommandLine() {
    $cmd = $this->program.' ';
    foreach ($this->arguments as $key => $value) {
      $delimiter = $value['delimiter'];
      $val = $value['value'];

      if (is_null($val)) {
        $cmd .= $key.' ';
      }
      else {
        $cmd .= $key.$delimiter.$val;
      }
    }

    return $cmd;
  }

  public function getArgument($index) {
    if ($index == 0) {
      return $this->program;
    }

    $index--;

    if (!isset($this->arguments[$index])) {
      throw new sProcessException('No argument at index %d. Arguments: ', $index, implode(', ', $this->arguments));
    }

    return $this->arguments[$index];
  }
}
