<?php
/**
 * Manages templating.
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

class sTemplate {
  const addBodyClass                   = 'sTemplate::addBodyClass';
  const addCDN                         = 'sTemplate::addCDN';
  const addCSSFile                     = 'sTemplate::addCSSFile';
  const addJavaScriptFile              = 'sTemplate::addJavaScriptFile';
  const addMinifiedJavaScriptFile      = 'sTemplate::addMinifiedJavaScriptFile';
  const buffer                         = 'sTemplate::buffer';
  const enableQueryStrings             = 'sTemplate::enableQueryStrings';
  const getACDN                        = 'sTemplate::getACDN';
  const getBodyClasses                 = 'sTemplate::getBodyClasses';
  const getCDNs                        = 'sTemplate::getCDNs';
  const getCache                       = 'sTemplate::getCache';
  const getJavaScriptFiles             = 'sTemplate::getJavaScriptFiles';
  const getMode                        = 'sTemplate::getMode';
  const registerCallback               = 'sTemplate::registerCallback';
  const removeCDN                      = 'sTemplate::removeCDN';
  const removeCDNs                     = 'sTemplate::removeCDNs';
  const render                         = 'sTemplate::render';
  const setActiveTemplate              = 'sTemplate::setActiveTemplate';
  const setCDNs                        = 'sTemplate::setCDNs';
  const setCSSMediaOrder               = 'sTemplate::setCSSMediaOrder';
  const setCache                       = 'sTemplate::setCache';
  const setMinifiedCSSPath             = 'sTemplate::setMinifiedCSSPath';
  const setMode                        = 'sTemplate::setMode';
  const setProductionModeTemplatesPath = 'sTemplate::setProductionModeTemplatesPath';
  const setSiteName                    = 'sTemplate::setSiteName';
  const setSiteSlogan                  = 'sTemplate::setSiteSlogan';
  const setTemplatesPath               = 'sTemplate::setTemplatesPath';
  const templateExists                 = 'sTemplate::templateExists';

  /**
   * The sCache instance.
   *
   * @var sCache
   */
  protected static $cache = NULL;

  /**
   * The template name. Matches directory name in './template'. Defaults to
   *   'default'.
   *
   * @var string
   */
  private static $template_name = 'default';

  /**
   * The fallback template.
   *
   * @var string
   */
  private static $template_fallback = 'default';

  /**
   * The templates path without any ending directory separator (like /).
   *
   * @var string
   */
  private static $templates_path = './template';

  /**
   * The templates path used when production mode is enabled.
   *
   * @var string
   */
  private static $production_mode_template_path = './template';

  /**
   * The JavaScript files (which appear normally at the bottom of the page).
   *
   * @var array
   */
  private static $javascript_files = array(
    'head' => array(),
    'body' => array(),
  );

  /**
   * The minified/compiled JavaScript files used in production mode.
   *
   * @var array
   */
  private static $compiled_javascript_files = array(
    'head' => array(),
    'body' => array(),
  );

  /**
   * Whether or not the site is in production mode or not.
   *
   * @var boolean
   */
  protected static $in_production_mode = FALSE;

  /**
   * Array of strings of class names to apply to the body element.
   *
   * @var array
   */
  protected static $body_classes = array();

  /**
   * Array of CDN URL prefixes.
   *
   * @var array
   */
  private static $cdns = array();

  /**
   * If resources such as CSS and JavaScript while not in production mode
   *   should be printed with query strings added to prevent caching (in
   *   particular with IE).
   *
   * @var boolean
   */
  private static $query_strings_enabled = TRUE;

  /**
   * Registered callbacks.
   *
   * @var array
   */
  private static $registered_callbacks = array('*' => array());

  /**
   * CSS file paths. The keys are the media types.
   *
   * @var array
   */
  private static $css_files = array(
    'all' => array(),
    'screen' => array(),
    'print' => array(),
  );

  /**
   * CSS media order.
   *
   * @var array
   */
  private static $css_media_order = array('all', 'screen', 'print');

  /**
   * The language of the page.
   *
   * @var string
   */
  protected static $language = 'en';

  /**
   * The text direction of the page.
   *
   * @var string
   */
  protected static $text_direction = 'ltr';

  /**
   * The site name.
   *
   * @var string
   */
  private static $site_name = 'No Site Name';

  /**
   * The site slogan.
   *
   * @var string
   */
  private static $site_slogan = '';

  /**
   * Where minified CSS should be stored.
   *
   * @var string
   */
  private static $minifed_css_path = 'files';

  /**
   * Set the sCache instance sTemplate will use.
   *
   * @param sCache $cache The cache object.
   * @return void
   */
  public static function setCache(sCache $cache) {
    self::$cache = $cache;
  }

  /**
   * Gets the sCache instance this is using.
   *
   * @throws fProgrammerException If cache is NULL.
   *
   * @return sCache The sCache instance.
   * @see sTemplate::setCache()
   */
  public static function getCache() {
    if (!self::$cache) {
      throw new fProgrammerException('Cache must be set by calling %s.', __CLASS__.'::setCache()');
    }
    return self::$cache;
  }

  /**
   * Set where minified CSS should be stored. This directory will be in the site root.
   *
   * @throws fProgrammerException If the directory is not writable.
   *
   * @param string $path Path in which to store minified CSS. Should not have
   *    a leading '/', or './' at the beginning.
   * @return void
   */
  public static function setMinifiedCSSPath($path) {
    $dir = new fDirectory($path);

    if (!$dir->isWritable()) {
      throw new fProgrammerException('The directory specified, "%s", does exist but is not writable.', $path);
    }

    self::$minifed_css_path = $path;
  }

  /**
   * Set the site slogan.
   *
   * @param string $slogan Slogan string.
   * @return void
   */
  public static function setSiteSlogan($slogan) {
    self::$site_slogan = (string)$slogan;
  }

  /**
   * Set the site name.
   *
   * @param string $name Name for the site.
   * @return void
   */
  public static function setSiteName($name) {
    self::$site_name = (string)$name;
  }

  /**
   * Set the current mode. In production mode, the site will use minified CSS
   *   and only minified JavaScript files which are added using
   *   sTemplate::addMinifiedJavaScriptFile().
   *
   * In development mode, the site will use the CSS and JavaScript files, and
   *   will append a query string to each resource to prevent caching by
   *   default. This can be disabled by calling:
   *   sTemplate::enableQueryStrings() with FALSE as the first argument.
   *
   * @param string $mode One of 'development' or 'production'.
   * @return void
   * @see sTemplate::addMinifiedJavaScriptFile()
   * @see sTemplate::enableQueryStrings()
   */
  public static function setMode($mode = 'development') {
    $valid_modes = array('development', 'production');
    $mode = strtolower($mode);

    if (!in_array($mode, $valid_modes)) {
      throw new fProgrammerException('Invalid mode, "%s", specified. Must be one of: %s.', $mode, implode(', ', $valid_modes));
    }

    self::$in_production_mode = $mode != 'development' ? TRUE : FALSE;
  }

  /**
   * Get the current working mode.
   *
   * @return string One of: 'development', 'production'.
   */
  public static function getMode() {
    return self::$in_production_mode ? 'production' : 'development';
  }

  /**
   * Enable or disable query strings on resource URLs such as CSS while in
   *   development mode.
   *
   * @param boolean $bool Value to set. TRUE or FALSE.
   * @return void
   */
  public static function enableQueryStrings($bool = TRUE) {
    self::$query_strings_enabled = $bool ? TRUE : FALSE;
  }

  /**
   * Register a callback to be called when the template name specified is about
   *   to be rendered.
   *
   * @param callback $callback Callback. All callbacks must return an array of
   *   keys to string values. They must be registered before the template will
   *   be used with sTemplate::buffer().
   * @param string $template_name Template name (without .tpl.php) to listen
   *   for.
   * @return void
   * @see sTemplate::buffer()
   */
  public static function registerCallback($callback, $template_name = '*') {
    self::$registered_callbacks[$template_name][] = $callback;
  }

  /**
   * Calls all the registered callback for * and this template.
   *
   * @param string $template_name Template name.
   * @return array Array of key => value pairs for use in the template.
   */
  private static function callCallbacks($template_name) {
    $variables = array();

    if (isset(self::$registered_callbacks[$template_name])) {
      foreach (self::$registered_callbacks[$template_name] as $callback) {
        $ret = fCore::call($callback);
        if (!is_array($ret)) {
          throw new fProgrammerException('Callback "%s" for template "%s" did not return an array.', $callback, $template_name);
        }
        $variables = array_merge($variables, $ret);
      }
    }

    return $variables;
  }

  /**
   * Set the templates path.
   *
   * The path is run through fDirectory. If it is not useable, then
   *   fDirectory::__construct() will throw an fValidationException.
   *
   * @param string $path Path without ending separator, such as / or \\.
   * @return void
   * @see fDirectory::__construct()
   */
  public static function setTemplatesPath($path) {
    new fDirectory($path);
    self::$templates_path = str_replace('\\', '/', $path);
  }

  /**
   * Add a JavaScript file.
   *
   * @param string $filename File name. Should be relative to site root or can
   *   be full URIs.
   * @param string $where Where the script should go. One of: 'head', 'body'.
   * @return void
   */
  public static function addJavaScriptFile($filename, $where = 'body') {
    $valid_where = array('head', 'body');
    $where = strtolower($where);
    $filename = preg_replace('/^\.?\//', '', $filename);

    if (!in_array($where, $valid_where)) {
      throw new fProgrammerException('The $where argument specified, "%s", is invalid. It must be one of: %s.', $where, implode(', ', $valid_where));
    }

    self::$javascript_files[$where][] = $filename;
  }

  /**
   * Add a minified JavaScript file. Should be relative to site path, or can be
   *   full URIs. These are only added during production mode.
   *
   * @param string $filename File name. Example: '/files/themin.min.js'
   * @param string $where Where the script should go. One of: 'head', 'body'.
   * @return void
   */
  public static function addMinifiedJavaScriptFile($filename, $where = 'body') {
    $valid_where = array('head', 'body');
    $where = strtolower($where);
    $filename = preg_replace('/^\.?\//', '', $filename);

    if (!in_array($where, $valid_where)) {
      throw new fProgrammerException('The $where argument specified, "%s", is invalid. It must be one of: %s.', $where, implode(', ', $valid_where));
    }

    self::$compiled_javascript_files[$where][] = $filename;
  }

  /**
   * Load/get all JavaScript files in an array. If no argument is specified,
   *   then all JavaScript file paths will be in the array, with first level
   *   keys being 'head' and 'body'.
   *
   * @param string $where Which to get. One of: 'head', 'body'.
   * @return array
   */
  public static function getJavaScriptFiles($where = NULL) {
    $arr = self::$compiled_javascript_files;

    if (!self::$in_production_mode) {
      $arr = self::$javascript_files;
    }

    if ($where == 'head') {
      return $arr['head'];
    }
    else if ($where == 'body') {
      return $arr['body'];
    }

    return $arr;
  }

  /**
   * Gets the correct templates path.
   *
   * @return string The path.
   */
  protected static function getTemplatesPath() {
    return self::$in_production_mode ? self::$production_mode_template_path : self::$templates_path;
  }

  /**
   * Add a CSS file path.
   *
   * @param string $path Path to the CSS file. Should be in the site root.
   * @param string $media Media type.
   * @param boolean $prepend If this CSS file sould be first.
   * @return void
   * @see sTemplate::setCSSMediaOrder()
   */
  public static function addCSSFile($path, $media = 'all', $prepend = FALSE) {
    $filename = preg_replace('/^\.?\//', '', $path);

    if (!isset(self::$css_files[$media])) {
      self::$css_files[$media] = array();
    }

    if (!$prepend) {
      self::$css_files[$media][] = $filename;
      return;
    }

    array_unshift(self::$css_files[$media], $filename);
  }

  /**
   * Set the CSS media type order.
   *
   * @param array $order Array of media query strings such as 'screen'.
   * @return void
   */
  public static function setCSSMediaOrder(array $order) {
    self::$css_media_order = $order;
  }

  /**
   * Set the active template.
   *
   * @throws fProgrammerException If any directory is not found or not
   *   readable.
   *
   * @param string $template_name String of template name.
   * @param string $fallback_template The fallback template.
   * @return void
   */
  public static function setActiveTemplate($template_name, $fallback_template = 'default') {
    $path = self::getTemplatesPath();
    self::$template_name = $template_name;
    self::$template_fallback = $fallback_template;

    try {
      new fDirectory($path.'/'.self::$template_name);
      new fDirectory($path.'/'.self::$template_fallback);
    }
    catch (fValidationException $e) {
      throw new fProgrammerException($e->getMessage());
    }
  }

  /**
   * Buffer a file in for content.
   *
   * @param string $filename File name to include without extension.
   * @param array $variables Array of key => value pairs, which will be turned into
   *   local variables before the template file is included.
   *
   * @throws fProgrammerException If the file cannot be found.
   *
   * @return string The captured content.
   */
  public static function buffer($filename, array $variables = array()) {
    $variables = array_merge($variables, self::callCallbacks($filename));
    $path = self::getTemplatesPath();
    $default = $path.'/'.self::$template_fallback.'/'.$filename.'.tpl.php';
    $template = $path.'/'.self::$template_name.'/'.$filename.'.tpl.php';

    extract($variables);
    fBuffer::startCapture();

    if (is_file($template)) {
      require $template;
    }
    else if (is_file($default)) {
      require $default;
    }
    else {
      fBuffer::stopCapture();
      throw new fProgrammerException('Invalid template file "%s" specified.', $filename);
    }

    return fBuffer::stopCapture();
  }

  /**
   * Set the template path used when production mode is enabled. If this is not
   *   set, the default path will be used.
   *
   * @param string $path Path to use.
   * @return void
   */
  public static function setProductionModeTemplatesPath($path) {
    new fDirectory($path);
    self::$production_mode_template_path = $path;
  }

  /**
   * Makes the stylesheets HTML when in production mode.
   *
   * @throws fUnexpectedException If a file is unable to read or found.
   *
   * @return string The HTML link tags.
   */
  protected static function getStylesheetsHTMLProductionMode() {
    fCore::startErrorCapture(E_ALL);

    $html = '';
    $cache = self::getCache();
    $cache_key = __CLASS__.'::last_combined_css_names';
    $names = $cache->get($cache_key, array());
    $cdn = self::getACDN();
    $css = array();

    //$names = array(); // for debugging
    if (!count($names)) {
      foreach (self::$css_files as $media => $files) {
        if (!isset($css[$media])) {
          $css[$media] = '';
        }

        foreach ($files as $file) {
          $ret = file_get_contents($file);

          if ($ret === FALSE) {
            throw new fUnexpectedException('Unable to read file "%s"', $file);
          }

          $css[$media] .= $ret;
        }
      }

      // For CssMin
      $filters = array(
        //           'ConvertLevel3AtKeyframes' => TRUE,
        //           'ConvertLevel3Properties' => TRUE,
      );
      $plugins = array(
        'CompressColorValues' => TRUE,
      );
      $has_css_min = class_exists('CssMin');
      $time = time();

      foreach ($css as $key => $text) {
        if (!$text) {
          continue;
        }

        if ($has_css_min) {
          $text = CssMin::minify($text, $filters, $plugins);
        }
        else {
          // Simple, but CSS has to be near perfect (as it should always be)
          $text = str_replace("\n", '', $text);
        }

        $media = fURL::makeFriendly($key, '-');
        $filename = './'.self::$minifed_css_path.'/css-'.$media.'-'.$time.'.min.css';
        $names[$key] = preg_replace('/^\./', '', $filename);
        $ret = file_put_contents($filename, $text, LOCK_EX);

        if ($ret === FALSE) {
          throw new fUnexpectedException('Unable to write to "%s" (minified CSS).', $filename);
        }
      }

      $cache->set($cache_key, $names, 86400 / 2);
    }

    $used = array();
    foreach (self::$css_media_order as $media) {
      if (isset($names[$media])) {
        $css = $names[$media];

        if (!is_file('.'.$css)) {
          $recache = TRUE;
          break;
        }

        $href = $cdn.$css;
        $used[$href] = TRUE;
        $html .= sHTML::tag('link', array(
          'rel' => 'stylesheet',
          'type' => 'text/css',
          'href' => $href,
          'media' => $media,
        ));
      }
    }

    foreach ($names as $media => $css) {
      $href = $cdn.$css;

      if (isset($used[$href])) {
        continue;
      }

      if (!is_file('.'.$css)) {
        $recache = TRUE;
        break;
      }

      $html .= sHTML::tag('link', array(
        'rel' => 'stylesheet',
        'type' => 'text/css',
        'href' => $href,
        'media' => $media,
      ));
    }

    if ($recache) {
      fCore::stopErrorCapture();
      fCore::debug('A minified CSS file was not found. All files are being re-generated.');
      $cache->delete(__CLASS__.'::last_combined_css_names');
      return self::getStylesheetsHTMLProductionMode();
    }

    fCore::stopErrorCapture();

    return $html;
  }

  /**
   * Get the list of stylesheets in order.
   *
   * @throws fUnexpectedException If the CSS file cannot be read (production mode only).
   *
   * @return string String of link HTML tags.
   * @see sTemplate::setCSSMediaOrder()
   */
  protected static function getStylesheetsHTML() {
    $html = '';

    if (self::$in_production_mode) {
      $cache = self::getCache();
      if ($cache->get(__CLASS__.'::last_mode') != 'production') {
        $cache->delete(__CLASS__.'::last_combined_css_names');
      }

      return self::getStylesheetsHTMLProductionMode();
    }

    $qs = self::$query_strings_enabled ? '?_='.time() : '';
    $added = array();

    foreach (self::$css_media_order as $media) {
      $files = isset(self::$css_files[$media]) ? self::$css_files[$media] : array();

      foreach ($files as $file) {
        $href = '/'.$file.$qs;
        $added[$href] = TRUE;
        $html .= sHTML::tag('link', array(
          'rel' => 'stylesheet',
          'type' => 'text/css',
          'href' => $href,
          'media' => $media,
        ))."\n";
      }
    }

    // Then just add the rest
    foreach (self::$css_files as $media => $files) {
      foreach ($files as $file) {
        $href = '/'.$file.$qs;
        if (!isset($added[$href])) {
          $html .= sHTML::tag('link', array(
            'rel' => 'stylesheet',
            'type' => 'text/css',
            'href' => $href,
            'media' => $media,
          ))."\n";
        }
      }
    }

    return $html;
  }

  /**
   * Get string of HTML scripts for use in the head element.
   *
   * JavaScript here can only be dependent on scripts that are also
   *   in the head element.
   *
   * @param string $where Which scripts to get. One of: 'head', 'body'.
   * @return string
   */
  protected static function getJavaScriptHTML($where) {
    $html = '';
    $qs = !self::$in_production_mode && self::$query_strings_enabled ? '?_='.time() : '';
    $cdn = '';
    $files = self::getJavaScriptFiles($where);

    if (self::$in_production_mode) {
      $cdn = self::getACDN();
    }

    foreach ($files as $path) {
      $url = $cdn.'/'.$path.$qs;
      if (sHTML::linkIsURI($path)) {
        $url = $path;
      }
      $html .= sHTML::tag('script', array(
        'type' => 'text/javascript',
        'src' => $url,
      ))."\n";
    }

    return $html;
  }

  /**
   * Check if a certain template file exists.
   *
   * @param string $template_name Template name to check, without .tpl.php.
   * @return boolean TRUE if the template exists, otherwise FALSE.
   */
  public static function templateExists($template_name) {
    return file_exists(self::getTemplatesPath().'/'.self::$template_name.'/'.$template_name.'.tpl.php');
  }

  /**
   * Add a CDN URL prefix WITHOUT including the final slash.
   *
   * @param string $url The prefix URL to use.
   * @return void
   */
  public static function addCDN($url) {
    self::$cdns[] = $url;
  }

  /**
   * Remove a specified CDN URL.
   *
   * @param string $url The URL to remove.
   * @return void
   */
  public static function removeCDN($url) {
    foreach (self::$cdns as $key => $value) {
      if ($value === $url) {
        unset(self::$cdns[$key]);
        break;
      }
    }
  }

  /**
   * Set the CDNs to use.
   *
   * @param array Array of URL prefixes WITHOUT the ending /.
   * @return void
   */
  public static function setCDNs(array $urls) {
    self::$cdns = $urls;
  }

  /**
   * Get the CDNs in currently in use.
   *
   * @returns array Array of string URLs.
   */
  public static function getCDNs() {
    return self::$cdns;
  }

  /**
   * Remove all CDNs.
   *
   * @return void
   */
  public static function removeCDNs() {
    self::$cdns = array();
  }

  /**
   * Get a CDN to use.
   *
   * @return string Empty string, or CDN URL prefix.
   */
  public static function getACDN() {
    if (!self::$in_production_mode || empty(self::$cdns)) {
      return '';
    }

    $key = fCryptography::random(0, count(self::$cdns) - 1);

    return self::$cdns[$key];
  }

  /**
   * Add a body class. This would normally be output in the class attribute of
   *   the <body> element.
   *
   * @param string $class_name Class name to add.
   * @return void
   */
  public static function addBodyClass($class_name) {
    self::$body_classes[] = $class_name;
  }

  /**
   * Get the body classes.
   *
   * @return array Array of strings.
   */
  public static function getBodyClasses() {
    return self::$body_classes;
  }

  /**
   * Get the correct page template for this URL.
   *
   * @throws fUnexpectedException If a candidate file cannot be found.
   *
   * @return string Template file name (with .tpl.php) to use.
   */
  protected static function getPageTemplate() {
    $route = str_replace('/', '-', substr(fURL::get(), 1));
    $templates_path = self::getTemplatesPath();
    $candidates = array(
      $templates_path.'/'.self::$template_name.'/page-'.$route.'.tpl.php',
      $templates_path.'/'.self::$template_fallback.'/page-'.$route.'.tpl.php',
      $templates_path.'/'.self::$template_name.'/page.tpl.php',
      $templates_path.'/'.self::$template_fallback.'/page.tpl.php',
    );

    foreach ($candidates as $file) {
      if (is_readable($file)) {
        return $file;
      }
    }

    throw new fUnexpectedException('Could not find a valid page template for this URL.');
  }

  /**
   * Perform final rendering. Call this at the end of the router's main action
   *   method.
   *
   * @throws fUnexpectedException If the template cannot be found.
   * @throws fProgrammerException If the keys title or content are missing.
   *
   * @param array $variables Array of key => value pairs, which will be turned into
   *   local variables before the template file is included. Must have the keys content
   *   and title.
   * @return void
   */
  public static function render(array $variables) {
    if (!isset($variables['content'])) {
      throw new fProgrammerException('The content string is missing in the variables array.');
    }
    if (!isset($variables['title'])) {
      throw new fProgrammerException('The title string is missing in the variables array.');
    }

    $path = fURL::get();
    $classes = implode(' ', self::$body_classes);
    $route = str_replace('/', '-', substr($path, 1));
    $logged_in = fAuthorization::checkLoggedIn();
    $file = self::getPageTemplate();

    if ($path != '/') {
      $classes .= ' page-'.str_replace('/', '-', substr($path, 1));
      if ($classes[strlen($classes) - 1] === '-') {
        $classes = substr($classes, 0, -1);
      }
    }
    $classes .= $logged_in ? ' logged-in' : ' not-logged-in';

    $vars = array(
      'lang' => self::$language,
      'dir' => self::$text_direction,
      'is_front' => fURL::get() == '/',
      'css' => self::getStylesheetsHTML(),
      'head_js' => self::getJavaScriptHTML('head'),
//       'conditional_head_js' => self::getConditionalHeadJavaScriptFromJSONFile(),
      'body_id' => '',
      'body_class' => $classes,
      'site_name' => fHTML::encode(self::$site_name),
      'site_slogan' => fHTML::encode(self::$site_slogan),
      'body_js' => self::getJavaScriptHTML('body'),
      'logged_in' => $logged_in,
      'user' => fAuthorization::getUserToken(),
      'production_mode' => self::$in_production_mode,
      'cdn' => self::getACDN(),
    );

    $vars = array_merge($vars, self::callCallbacks('page'));
    $vars = array_merge($vars, self::callCallbacks('page-'.$route));

    // Do not let a template override the title or content
    $vars['title'] = fHTML::encode($variables['title']);
    $vars['content'] = $variables['content'];
    extract($vars);

    // Save the last mode so that the CSS rendering code will know to re-generate
    self::getCache()->set(__CLASS__.'::last_mode', self::$in_production_mode ? 'production' : 'development');

    fHTML::sendHeader();

    if (self::$in_production_mode) {
      fBuffer::startCapture();
      require $file;
      $output = str_replace("\n", '', fBuffer::stopCapture());
      $output = preg_replace('/\s\s+/', '', $output);
      print $output;
    }
    else {
      require $file;
    }
  }

  // @codeCoverageIgnoreStart
  /**
   * Forces use as a static class.
   *
   * @return sTemplate
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
