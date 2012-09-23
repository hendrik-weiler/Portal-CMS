<?php
/**
 * Manages processes external to PHP, including interactive processes.
 *
 * @copyright Copyright (c) 2012 Poluza.
 * @author Andrew Udvare [au] <andrew@bne1.com>
 * @license http://www.opensource.org/licenses/mit-license.php
 *
 * @package Sutra
 * @link http://www.sutralib.com/
 *
 * @version 1.2
 */

namespace Sutra;

class sProcess {
  const checkOS = 'sProcess::checkOS';
  const setPath = 'sProcess::setPath';
  const getPath = 'sProcess::getPath';

  /**
   * The process that will be run.
   *
   * @var string
   */
  private $program;

  /**
   * Array of arguments for the process.
   *
   * @var array
   */
  private $arguments = array();

  /**
   * Paths for the system delimited by : (Linux and similar) or ; (Windows).
   *
   * @var string
   */
  private static $path = NULL;

  /**
   * Toss if the program returns an unexpected exit code.
   *
   * @var boolean
   */
  private $toss = FALSE;

  /**
   * Handle to popen().
   *
   * @var resource
   */
  private $popen_handle = NULL;

  /**
   * File to pipe output to when using mode 'w' with popen().
   *
   * @var fFile
   */
  private $pipe_file;

  /**
   * Working directory. Defaults to current directory.
   *
   * @var fDirectory
   */
  private $work_dir = NULL;

  /**
   * Current directory before going into working directory.
   *
   * @var fDirectory
   */
  private $prior_dir = NULL;

  /**
   * Redirect standard error.
   *
   * @var boolean
   */
  private $redirect_standard_error = FALSE;

  /**
   * Where standard error gets sent to.
   *
   * @var string
   */
  private $stderr_target = '/dev/null';

  /**
   * Strips double and singular surrounding quotes out of all arguments.
   *
   * @return void
   */
  private function stripQuotesInArguments() {
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

    $this->stripQuotesInArguments();
  }

  /**
   * Constructor.
   *
   * On Windows, can include the .exe but this will be removed.
   *
   * You may also pass arguments to this instead of an array.
   *   Example: new sProcess('app', '--help').
   *
   * @throws fProgrammerException If the binary is invalid.
   *
   * @param array|string $name If string, the program to run, optionally with
   *   path and arguments. If array, each part of the command line separated.
   *   These will be implode()'d with spaces.
   * @return sProcess The object.
   */
  public function __construct($name) {
    $this->parseNameAndArguments(func_get_args());

    if (self::checkOS('windows') && substr($this->program, -4) === '.exe') {
      $this->program = substr($this->program, 1, -4);
    }

    if (!self::exists($this->program)) {
      throw new fProgrammerException('The executable specified, "%s", does not exist or is not in the path.', $this->program);
    }

    $this->work_dir = new fDirectory('.');
    $this->prior_dir = new fDirectory('.');
  }

  /**
   * Set the working directory. Note that this changes into the specified
   *   directory, so you may want to save the current directory before calling
   *   this method.
   *
   * @throws fProgrammerException If the working directory is not writable or does not exist.
   *
   * @param string $dir Path.
   * @return void
   * @see getcwd()
   */
  public function setWorkingDirectory($dir) {
    $dir = new fDirectory($dir);
    if (!$dir->isWritable()) {
      throw new fProgrammerException('Working directory "%s" is not writable.', $dir->getName());
    }
    $this->work_dir = $dir;
    chdir($this->work_dir->getPath());
  }

  /**
   * Check the current operating system. Alias for fCore::checkOS().
   *
   * @param string $os One of: windows, linux, mac.
   * @return boolean Whether or not the system matches.
   *
   * @see fCore::checkOS()
   */
  public static function checkOS($os) {
    return fCore::call(fCore::checkOS, func_get_args());
  }

  /**
   * Set the PATH or in the case of Windows 'Path' variable for all objects.
   *
   * By default, this class will use the environment variable PATH
   *   (Windows: 'Path').
   *
   * @param string $path Optional. Delimited paths to search for the binary.
   *   If not passed, will use environment variables.
   * @return void
   */
  public static function setPath($path = NULL) {
    if (is_null($path)) {
      if (self::checkOS('windows')) {
        self::$path = getenv('Path');
      }
      else {
        self::$path = getenv('PATH');
      }
    }
    else if (!is_null($path)) {
      if (self::checkOS('windows') && strpos($path, ':') !== FALSE) {
        $path = str_replace(':', ';', $path);
      }
      self::$path = $path;
    }
  }

  /**
   * Get the list of paths from the environment variables PATH (UNIX and
   *   UNIX-like) or Path (Windows).
   *
   * @param boolean $array Return array if set to TRUE.
   * @return mixed
   */
  public static function getPath($array = FALSE) {
    self::setPath();
    $delimiter = self::checkOS('windows') ? ';' : ':';
    return $array ? explode($delimiter, self::$path) : self::$path;
  }

  /**
   * Find out if a binary exists on the system in PATH. The binary is NOT
   *   tested for executability.
   *
   * @param string $bin_name Binary without any path. Can include .exe on
   *   Windows but that is not required.
   * @return boolean TRUE If the binary is found, FALSE otherwise.
   */
  private static function exists($bin_name) {
    if (self::checkOS('windows') && substr(strtolower($bin_name), -4, 4) !== '.exe') {
      $bin_name .= '.exe';
    }

    $paths = self::getPath(TRUE);
    foreach ($paths as $path) {
      if (is_file($path.'/'.$bin_name)) {
        return TRUE;
      }
    }

    return FALSE;
  }

  /**
   * Throw an sProcessException if the return value does not match expected
   *   return value.
   *
   * @return void
   */
  public function tossIfUnexpected() {
    $this->toss = TRUE;
  }

  /**
   * Execute the program. This is for non-interactive processes.
   *
   * @throws sProcessException If tossing is enabled, and the return value
   *   does not match the one passed.
   *
   * @param int $rv Return value expected. Default is 0. Ignored if the class
   *   is not set to throw an exception on an invalid return value.
   * @return string Output of the program.
   */
  public function execute($rv = 0) {
    $output = array();
    $ret = $rv;
    $cmd = $this->commandLine();
    fCore::debug('Executing: '.$cmd);
    exec($cmd, $output, $ret);

    if ($this->toss && $ret !== $rv) {
      throw new sProcessException('Return value incorrect');
    }

    return implode("\n", $output);
  }

  /**
   * Get the temporary file name to write to.
   *
   * @return string File name to write to, including full path.
   */
  private function getTemporaryFileName() {
    if ($this->pipe_file) {
      return $this->pipe_file->getPath();
    }

    $this->pipe_file = new fFile(tempnam($this->work_dir, 'flourish__'));
    return $this->pipe_file->getPath();
  }

  /**
   * Get the complete command line, escaped, including any piping.
   *
   * @param boolean $popen Whether or not popen() is being used.
   * @return string The complete command line.
   */
  private function commandLine($popen = FALSE) {
    $args = array();
    foreach ($this->arguments as $arg) {
      if ($arg[0] !== '-' && $arg[0] !== '|') {
        $args[] = escapeshellarg($arg);
      }
      else {
        $args[] = $arg;
      }
    }

    array_unshift($args, $this->program);

    if ($this->redirect_standard_error) {
      $args[] = '2>'.$this->stderr_target;
    }

    if ($popen) {
      $args[] = '>';
      $args[] = escapeshellarg($this->getTemporaryFileName());
    }

    return implode(' ', $args);
  }

  /**
   * Begin an interactive session with the process.
   *
   * @return sProcess The object to allow for method chaining.
   *
   * @see popen()
   */
  public function beginInteractive() {
    if (!is_null($this->popen_handle)) {
      throw new fProgrammerException('Attempted to open an interactive session when there is already one active.');
    }

    $cmd = $this->commandLine(TRUE);
    fCore::debug('Executing: '.$cmd);
    $this->popen_handle = popen($cmd, 'w');

    return $this;
  }

  /**
   * Redirect standard error.
   *
   * @param boolean $bool Defauls to TRUE. Class instantiates with this set to
   *   FALSE.
   * @param string $where Where the output should go to. Example could be '&1'
   *   or a file name.
   * @return void
   */
  public function redirectStandardError($bool = TRUE, $where = NULL) {
    if (!is_null($this->popen_handle)) {
      throw new fProgrammerException('Attempted to set setting to program already running.');
    }

    if (is_null($where)) {
      if (self::checkOS('windows')) {
        $where = 'nul';
      }
      else {
        $where = '/dev/null';
      }
    }

    $this->redirect_standard_error = $bool;
    $this->stderr_target = $where;
  }

  /**
   * Redirect standard error. Convenience alias for redirectStandardError().
   *
   * @param boolean $bool Defauls to TRUE.
   * @param string $where Where the output should go to. Example could be '&1'
   *   or a file name.
   * @return void
   *
   * @see sProcess::redirectStandardError()
   */
  public function redirectStdErr($bool = TRUE, $where = NULL) {
    self::redirectStandardError($bool, $where);
  }

  /**
   * Write to the interactive process.
   *
   * @throws sProcessException If the handle cannot be written to or if the
   *   string passed was of zero-length.
   *
   * @param $format,... A formatted string and arguments. Example: "%s", 'string'.
   * @return sProcess The object to allow for method chaining.
   *
   * @see fprintf()
   */
  public function write($format) {
    if (is_null($this->popen_handle)) {
      throw new fProgrammerException('Attempted to write to non-existent handle.');
    }

    $args = func_get_args();
    $string = substr(call_user_func_array('sprintf', $args), 0, 100) . '...';
    fCore::debug('Writing '.$string.' to handle.');

    array_unshift($args, $this->popen_handle);
    call_user_func_array('fprintf', $args);
  }

  /**
   * End the interactive session.
   *
   * @throws sProcessException If tossing is enabled and the return value does
   *   not match the one passed; if attempting to close a non-existent popen
   *   handle.
   *
   * @param int $rv Return value expected. Defaults to 0.
   * @return string Output of the session.
   */
  public function EOF($rv = 0) {
    if (is_null($this->popen_handle)) {
      throw new fProgrammerException('Attempted to close non-existent handle.');
    }

    $ret = pclose($this->popen_handle);
    $this->popen_handle = NULL;

    if ($this->toss && $ret !== $rv) {
      throw new sProcessException('Return value was not expected value: (got: %d, wanted: %d).', $ret, $rv);
    }

    $output = $this->pipe_file->read();
    $this->pipe_file->delete();
    $this->pipe_file = NULL;

    chdir($this->prior_dir->getPath());

    return $output;
  }

  /**
   * Add argument (or arguments) to the current set of arguments.
   *
   * @throws sProcessException If attempting to add arguments to a process
   *   already running.
   *
   * @param string,... $arg Arguments to add.
   * @return sProcess The object to allow for method chaining.
   */
  public function addArgument($arg) {
    if (!is_null($this->popen_handle)) {
      throw new fProgrammerException('Attempted to add arguments to a program already running.');
    }

    $args = explode(' ', implode(' ', func_get_args()));
    foreach ($args as $arg) {
      $this->arguments[] = trim($arg);
    }

    return $this;
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
