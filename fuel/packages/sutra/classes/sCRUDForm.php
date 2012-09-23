<?php
/**
 * Creates an HTML form based on an fActiveRecord class.
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

class sCRUDForm {
  const hideFieldNames = 'sCRUDForm::hideFieldNames';

  /**
   * Mappings.
   *
   * @var array
   * @see http://flourishlib.com/docs/FlourishSql#DataTypes
   */
  private static $column_to_form_mappings = array(
    'smallint' => 'number',
    'integer' => 'number',
    'bigint' => 'number',
    'float' => 'number',
    'real' => 'number',
    'decimal' => 'number',
    'char' => 'textfield',
    'varchar' => 'textfield',
    'text' => 'textarea',
    'blob' => 'file',
    'timestamp' => 'datetime',
    'date' => 'date',
    'time' => 'time',
    'boolean' => 'checkbox',
  );

  /**
   * Valid field types. Ones that have a value of TRUE are separate elements
   *   and are not part of the 'type' attribute of the input element.
   *
   * @var array
   */
  private static $valid_field_types = array(
    'text' => FALSE,
    'textfield' => FALSE,
    'textarea' => TRUE,
    'select' => TRUE,
    'tel' => FALSE,
    'url' => FALSE,
    'email' => FALSE,
    'password' => FALSE,
    'datetime' => FALSE,
    'date' => FALSE,
    'month' => FALSE,
    'week' => FALSE,
    'time' => FALSE,
    'datetime-local' => FALSE,
    'number' => FALSE,
    'range' => FALSE,
    'color' => FALSE,
    'checkbox' => FALSE,
    'file' => FALSE,
    'hidden' => FALSE,
  );

  /**
   * Column names to never print fields for.
   *
   * @var array
   */
  private static $always_ignore = array();

  /**
   * Request method.
   *
   * @var string
   */
  private $request_method = 'post';

  /**
   * Action URL.
   *
   * @var string
   */
  private $action_url = NULL;

  /**
   * Form element attributes.
   *
   * @var string
   */
  private $form_attr = array();

  /**
   * The fields to build HTML from.
   *
   * @var array
   */
  private $fields = array();

  /**
   * If this form enables file uploads.
   *
   * @var boolean
   */
  private $file_uploads = FALSE;

  /**
   * The maximum size for a file upload. If not set, the hidden field will not
   *   be printed.
   *
   * @var integer
   */
  private $file_upload_max_size = NULL;

  /**
   * Class name passed in.
   *
   * @var string
   */
  private $class_name = NULL;

  /**
   * Buttons that will display.
   *
   * @var string
   */
  private $buttons = array();

  /**
   * Default action.
   *
   * @var string
   */
  private $action = NULL;

  /**
   * If a CSRF field should be printed.
   *
   * @var boolean
   */
  private $print_csrf = FALSE;

  /**
   * The CSRF field name.
   *
   * @var string
   */
  private $csrf_field_name = 'csrf';

  /**
   * The CSRF field URL.
   *
   * @var string
   */
  private $csrf_field_url = NULL;

  /**
   * The table name.
   *
   * @var string
   */
  private $table_name = NULL;

  /**
   * The columns array of the table.
   *
   * @var array
   */
  private $table_columns = array();

  /**
   * The fActiveRecord instance, if one was passed to the constructor.
   *
   * @var fActiveRecord
   */
  private $active_record = NULL;

  /**
   * The relationships array of the table.
   *
   * @var array
   */
  private $table_relationships = array();

  /**
   * The schema instance.
   *
   * @var fSchema
   */
  private $schema = NULL;

  /**
   * Custom HTML fields.
   *
   * @var array
   */
  private $custom_html = array();

  /**
   * Validate the field type.
   *
   * @throws fProgrammerException If the field type is invalid.
   *
   * @param string $type Field type.
   * @return void
   */
  private static function validateFieldType($type) {
    if (!isset(self::$valid_field_types[$type])) {
      throw new fProgrammerException('The field type specified, "%s", is not valid. Must be one of: %s',
        $type,
        implode(',', self::$valid_field_types)
      );
    }
  }

  /**
   * Configures the class to always ignore certain column names. This may be
   *   useful for fields that are managed by Flourish such as timestamp fields
   *   managed by fORMDate.
   *
   * @param array|string $field_name Field name or array or names to ignore.
   * @return void
   */
  public static function hideFieldNames($field_name) {
    if (!is_array($field_name)) {
      $field_name = func_get_args();
    }

    foreach ($field_name as $name) {
      self::$always_ignore[$name] = TRUE;
    }
  }

  /**
   * Makes an HTML form element wrapped in a div.
   *
   * @param string $type Type of the field.
   * @param string $name Name of the field.
   * @param string $label Label text of the field.
   * @param array $attr Array of fields.
   * @return string HTML of the field.
   */
  private static function makeElement($type, $name, $label, array $attr = array()) {
    if ($type == 'text') {
      $type = 'textfield';
    }

    $attr['label'] = $label;
    $class = 'form-field-container form-'.$type.'-container';
    $container = '<div class="'.$class.'">';
    $container .= sHTML::makeFormElement($type, $name, $attr);
    $container .= '</div>';

    return $container;
  }

  /**
   * Validates the request method.
   *
   * @throws fProgrammerException If the request method is invalid.
   *
   * @param string $method Request method.
   * @return void
   */
  private static function validateRequestMethod($method) {
    $methods = array('get', 'post');
    if (!in_array($method, $methods)) {
      throw new fProgrammerException('Request method "%s" is invalid. Must be one of: %s',
        $method,
        implode(',', $methods)
      );
    }
  }

  /**
   * Sets POST values from the fActiveRecord instance.
   *
   * @param fActiveRecord|string|null $active_record This method does nothing
   *   if this argument is not of type fActiveRecord.
   * @return sCRUDForm The object to allow method chaining.
   * @SuppressWarnings(UnusedLocalVariable)
   */
  private function setPostValues() {
    if ($this->active_record instanceof fActiveRecord) {
      foreach ($this->table_columns as $column_name => $info) {
        $method = 'get'.fGrammar::camelize($column_name, TRUE);
        $value = $this->active_record->$method();
        if ($value) {
          fRequest::set($column_name, $value);
        }
      }
    }
    return $this;
  }

  /**
   * Parses the many-to-one relationships of the table.
   *
   * @return array Array of data about the related columns.
   */
  private function parseRelationships() {
    $related_columns = array();
    foreach ($this->table_relationships['many-to-one'] as $info) {
      $related_columns[$info['column']] = array(
        'column' => $info['related_column'],
        'table' => $info['related_table'],
      );
    }
    return $related_columns;
  }

  /**
   * Gets the correct field type based on the column type and name.
   *
   * If the column name has password within its name, the type password will
   *   be returned.
   *
   * If the column name has email within its name, the type email will be
   *   returned.
   *
   * If $valid_values has any values, type select will be returned.
   *
   * @param string $column_name Column name.
   * @param string $type Column type.
   * @param array $valid_values Valid values array.
   * @return void
   */
  private static function getFieldType($column_name, $type, array $valid_values = array()) {
    if (strpos($column_name, 'password') !== FALSE) {
      return 'password';
    }
    if (strpos($column_name, 'email') !== FALSE) {
      return 'email';
    }
    if (count($valid_values)) {
      return 'select';
    }
    return self::$column_to_form_mappings[$type];
  }

  /**
   * Checks if the field is required.
   *
   * @param string $type Field type.
   * @param array|null $default_values Array of default values or NULL.
   * @return boolean If the field is required.
   */
  private static function isRequiredField($type, $default_values = NULL) {
    if (!isset($default_values) && $type != 'boolean') {
      return TRUE;
    }
    return FALSE;
  }

  /**
   * Adds special attributes to the attributes array depending on field type.
   *
   * @param array $attr Attributes array to modify.
   * @param array $info Information array as from schema.
   * @param string $field_type Field type.
   * @return void
   */
  private static function addSpecialAttributes(array &$attr, array $info, $field_type) {
    switch ($field_type) {
      case 'textarea':
      case 'textfield':
        if (isset($info['max_length']) && $info['type'] !== 'text') {
          $attr['maxlength'] = $info['max_length'];
        }
        $attr['spellcheck'] = TRUE;
        break;

      case 'number':
        $mapping = array(
          'min_value' => 'min',
          'max_value' => 'max',
        );
        foreach ($mapping as $key => $attribute_name) {
          if (isset($info[$key])) {
            if ($info[$key] instanceof fNumber) {
              $attr[$attribute_name] = $info[$key]->__toString();
            }
            else if (is_scalar($info[$key])) {
              $attr[$attribute_name] = $info[$key];
            }
          }
        }
        break;

      case 'select':
        $options = array();
        foreach ($info['valid_values'] as $value) {
          $options[$value] = $value;
        }
        $attr['options'] = $options;
        break;
    }
  }

  /**
   * Makes the attributes array for the field.
   *
   * @param string $column_name Column name (will be used for 'name' attribute).
   * @param array $info Information array as retrieved from schema.
   * @param string $field_type Field type.
   * @return array Attributes array.
   */
  private static function makeAttributesArray($column_name, array $info, $field_type) {
    $attr = array(
      'name' => $column_name,
      'required' => self::isRequiredField($info['type'], $info['default']),
    );

    if (isset($info['default'])) {
      $attr['value'] = $info['default'];
    }

    self::addSpecialAttributes($attr, $info, $field_type);

    return $attr;
  }

  /**
   * Parses the fields of the schema.
   *
   * @return sCRUDForm The object to allow method chaining.
   */
  private function parseSchema() {
    $keys = $this->schema->getKeys($this->table_name, 'primary');
    $keys_count_is_one = count($keys) === 1;
    $typeof_type_is_string = is_string($this->table_columns[$keys[0]]['type']);
    $pk_should_be_printed = FALSE;
    $pk_field_name = $keys_count_is_one ? $keys[0] : NULL;
    $related_columns = $this->parseRelationships();

    if ($keys_count_is_one && !$typeof_type_is_string && $this->table_columns[$keys[0]]['type']['auto_increment'] != TRUE) {
      $pk_should_be_printed = TRUE;
    }

    foreach ($this->table_columns as $column_name => $info) {
      if ($pk_field_name == $column_name) {
        continue;
      }
      if (isset(self::$always_ignore[$column_name])) {
        continue;
      }
      if (isset($related_columns[$column_name])) {
        $this->fields[$column_name] = array(
          'type' => 'select',
          'name' => $column_name,
          'label' => fGrammar::humanize($column_name),
          'attributes' => array(),
          'required' => TRUE,
          'related' => TRUE,
          'related_column' => $related_columns[$column_name]['column'],
          'related_table' => $related_columns[$column_name]['table'],
        );
        continue;
      }

      $field_type = self::getFieldType($column_name, $info['type'], isset($info['valid_values']) ? $info['valid_values'] : array());
      $attr = self::makeAttributesArray($column_name, $info, $field_type);

      $this->fields[$column_name] = array(
        'type' => $field_type,
        'label' => fGrammar::humanize($column_name),
        'attributes' => $attr,
        'related' => FALSE,
        'related_column' => NULL,
        'related_table' => NULL,
      );
    }

    if ($pk_should_be_printed) {
      array_unshift($this->fields, array(
        'type' => self::$column_to_form_mappings[$this->table_columns[$pk_field_name]['type']],
        'label' => fGrammar::humanize($pk_field_name),
        'attributes' => array(
          'required' => TRUE,
        ),
        'related' => FALSE,
        'related_column' => NULL,
        'related_table' => NULL,
      ));
    }

    return $this;
  }

  /**
   * Creates a form based on the schema of a table.
   *
   * @param fActiveRecord|string $class fActiveRecord instance, or class name.
   * @param string $action URL for the action attribute of the form element. If
   *   not specified, will default to the current URL.
   * @param string $method Method type for the form element. One of: 'post',
   *   'get'.
   * @param array $attr Array of HTML attributes for the form elemement.
   * @return sCRUDForm The form object.
   */
  public function __construct($class, $action = NULL, $method = 'post', array $attr = array()) {
    $method = strtolower($method);
    self::validateRequestMethod($method);

    $this->request_method = $method;
    $this->active_record = $class;
    $this->form_attr = $attr;
    $this->class_name = fORM::getClass($class);
    $this->table_name = fORM::tablize($this->class_name);
    $this->schema = fORMSchema::retrieve($this->class_name);
    $this->table_columns = $this->schema->getColumnInfo($this->table_name);
    $this->table_relationships = $this->schema->getRelationships($this->table_name);
    $this->action_url = isset($action) ? (string)$action : fURL::get();

    $this->parseSchema();
    $this->setPostValues();
  }

  /**
   * Changes the form content type to allow file uploads, regardless if there
   *   are file (blob) fields.
   *
   * @param boolean $bool TRUE or FALSE.
   * @return sCRUDForm The object to allow method chaining.
   */
  public function enableFileUpload($bool) {
    $this->file_uploads = $bool ? TRUE : FALSE;
    return $this;
  }

  /**
   * Set the maximum file upload size. This affects all file upload fields.
   *
   * @param integer $size Size to allow.
   * @return sCRUDForm The object to allow method chaining.
   */
  public function setMaxFileUploadSize($size) {
    $this->file_upload_max_size = (int)$size;
    return $this;
  }

  /**
   * Validates if a field name exists in this class.
   *
   * @throws fProgrammerException If the field name is invalid.
   *
   * @param string $name Name of the field.
   * @return sCRUDForm The object to allow method chaining.
   */
  private function validateFieldName($name) {
    if (!isset($this->fields[$name])) {
      throw new fProgrammerException('The field name specified, "%s", does not exist. Must be one of: %s',
        $name,
        implode(', ', array_keys($this->fields))
      );
    }
    return $this;
  }

  /**
   * Fetches the values to display for the related column.
   *
   * @param fDatabase $db fDatabase instance.
   * @param string $value_column Column with the values to use.
   * @param string $title_column Column with values to display to the user.
   * @param string $related_table Table name to fetch values from.
   * @return array Array of options with keys as the values.
   */
  private function fetchRelatedValues(fDatabase $db, $value_column, $title_column, $related_table) {
    $sql = 'SELECT %r,%r FROM %r ORDER BY %r';
    $result = $db->translatedQuery($sql, $value_column, $title_column, $related_table, $title_column);
    $options = array();

    foreach ($result as $result) {
      if (count($result) > 1) {
        $key = current($result);
        $options[$key] = $result[$title_column];
      }
      else {
        $options[$result[$value_column]] = $result[$value_column];
      }
    }

    return $options;
  }

  /**
   * Sets custom HTML for a specific column. Note that this replaces
   *   <em>all</em> HTML (including the container and label), not just the
   *   relevant field tag (such as &lt;input&gt;).
   *
   * If hiding a field is desired, use <code>sCRUDForm->hideFields()</code>.
   *
   * @param string $column_name Column name.
   * @param string $html HTML to use.
   * @return sCRUDForm The object to allow method chaining.
   */
  public function replaceHTML($column_name, $html) {
    $this->custom_html[$column_name] = $html;
    return $this;
  }

  /**
   * Generates the form HTML. Should be called last.
   *
   * @return string The form HTML.
   */
  public function make() {
    $fields = '';
    $db = fORMDatabase::retrieve($this->class_name);
    $no_value_types = array('select');
    $special_value_types = array('date');

    foreach ($this->fields as $column_name => $info) {
      if ($info['type'] == 'file') {
        $this->file_uploads = TRUE;
      }

      if (isset($this->custom_html[$column_name])) {
        $fields .= $this->custom_html[$column_name];
        continue;
      }

      if ($info['related']) {
        $column = $info['related_column'];
        $related_column = isset($info['original_related_column']) ? $info['original_related_column'] : $column;
        $options = $this->fetchRelatedValues($db, $related_column, $column, $info['related_table']);

        $info['attributes'] = array_merge($info['attributes'], array(
          'options' => $options,
          'label' => $info['label'],
          'value' => fRequest::get($info['name'], 'string', NULL, TRUE),
        ));

        $html = '<div class="form-'.$info['type'].'-container">';

        $fields .= $html.sHTML::makeFormElement($info['type'], $column_name, $info['attributes']).'</div>';

        continue;
      }

      $value = fRequest::get($column_name);
      
      if ($value && in_array($info['type'], $special_value_types)) {
        switch ($info['type']) {
          case 'date': // HTML5 'date' field in Chrome only accepts Y-m-d format
            $date = strtotime($value);
            $date = date('Y-m-d', $date);
            $info['attributes']['value'] = $date;
            break;

          default:
            $info['attributes']['value'] = $date;
            break;
        }
      }
      else if ($value && !in_array($info['type'], $no_value_types)) {
        $info['attributes']['value'] = $value;
      }
      else if (!$value && $info['type'] === 'checkbox') {
        $info['attributes']['value'] = 1;
      }

      $fields .= self::makeElement($info['type'], $column_name, $info['label'], $info['attributes']);
    }

    if (isset($this->action)) {
      $fields .= '<input type="hidden" name="action" value="'.$this->action.'">';
    }

    if (count($this->buttons)) {
      $container = '<div class="form-ops-container">';
      foreach ($this->buttons as $button) {
        $action_name = $button[0];
        $label = $button[1];
        $container .= sHTML::makeFormElement('submit', 'action::'.$action_name, array('value' => $label));
      }
      $container .= '</div>';
      $fields .= $container;
    }

    if ($this->print_csrf) {
      $fields .= '<input type="hidden" name="'.$this->csrf_field_name.'" value="'.fRequest::generateCSRFToken($this->csrf_field_url).'">';
    }

    if ($this->file_uploads) {
      $this->form_attr['enctype'] = 'multipart/form-data';
      if ($this->file_upload_max_size) {
        $fields .= '<input name="MAX_FILE_SIZE" value="'.(int)$this->file_upload_max_size.'" type="hidden">';
      }
    }

    $this->form_attr['action'] = $this->action_url;
    $this->form_attr['method'] = $this->request_method;

    return sHTML::tag('form', $this->form_attr, $fields);
  }

  /**
   * Hides fields. Allows for variable arguments.
   *
   * @param string|array $name Name of the field.
   * @return sCRUDForm The object to allow method chaining.
   */
  public function hideFields($name) {
    if (!is_array($name)) {
      $name = func_get_args();
    }

    foreach ($name as $field_name) {
      $this->validateFieldName($field_name);
      unset($this->fields[$field_name]);
    }

    return $this;
  }

  /**
   * Adds a button.
   *
   * @param string $action_name Action name. This is for use with fRequest
   *   during the request.
   * @param string $label Label of the button.
   * @return sCRUDForm The object to allow method chaining.
   */
  public function addAction($action_name, $label) {
    if (!isset($this->action)) {
      $this->action = $action_name;
    }
    $this->buttons[] = array($action_name, $label);
    return $this;
  }

  /**
   * Adds a custom field.
   *
   * @param string $name Name attribute of the field.
   * @param string $label Label text.
   * @param string $type Type of the field.
   * @param array $attr Attributes array.
   * @return sCRUDForm The object to allow method chaining.
   */
  public function addField($name, $label, $type = 'text', array $attr = array()) {
    $attr['label'] = $label;
    $required = isset($attr['required']) ? $attr['required'] : FALSE;

    $this->fields[$name] = array(
      'type' => $type,
      'required' => $required,
      'attributes' => $attr,
      'label' => $label,
      'related' => FALSE,
      'related_column' => NULL,
      'related_table' => NULL,
    );

    return $this;
  }

  /**
   * Enables adding a CSRF field.
   *
   * @param boolean $bool If the CSRF field should be added.
   * @param string $name Name of the field.
   * @param string $url URL for the CSRF.
   * @return sCRUDForm The object to allow method chaining.
   */
  public function enableCSRFField($bool, $name = 'csrf', $url = NULL) {
    $this->print_csrf = $bool ? TRUE : FALSE;
    $this->csrf_field_name = $name;
    $this->csrf_field_url = $url;
    return $this;
  }

  /**
   * Override a field types attributes. This is mainly so that an e-mail or
   *   date field column will render a different field from the default.
   *
   * @param string $name Name of the field.
   * @param string $type Type of the field.
   * @param array $attr Array of other attributes.
   * @return sCRUDForm The object to allow method chaining.
   */
  public function overrideFieldType($name, $type, array $attr = array()) {
    $type = strtolower($type);
    $this->validateFieldName($name);
    self::validateFieldType($type);

    // Ignore these from attributes
    $required = $this->fields[$name]['attributes']['required'];
    unset($attr['type']);
    unset($attr['required']);
    unset($attr['label']);

    if ($type != 'number') {
      unset($this->fields[$name]['attributes']['min']);
      unset($this->fields[$name]['attributes']['max']);
    }

    $this->fields[$name]['type'] = $type;
    $this->fields[$name]['attributes'] = array_merge($this->fields[$name]['attributes'], $attr);
    $this->fields[$name]['attributes']['required'] = $required;

    return $this;
  }

  /**
   * Override the label name for a column.
   *
   * @param string $column_name Column name.
   * @param string $label Label to use.
   * @return sCRUDForm The object to allow method chaining.
   */
  public function overrideLabel($column_name, $label) {
    $this->validateFieldName($column_name);
    $this->fields[$column_name]['label'] = $label;
    return $this;
  }

  /**
   * Override the related column for a related table.
   *
   * @param string $column_name Column name of this table.
   * @param string $related_table_column_name Column name that should be read
   *   from the related table.
   * @return sCRUDForm The object to allow method chaining.
   */
  public function overrideRelatedColumn($column_name, $related_table_column_name) {
    $this->validateFieldName($column_name);
    if (!isset($this->fields[$column_name]['original_related_column'])) {
      $this->fields[$column_name]['original_related_column'] = $this->fields[$column_name]['related_column'];
    }
    $this->fields[$column_name]['related_column'] = $related_table_column_name;
    return $this;
  }

  /**
   * Sets the field order.
   *
   * @param array|string $fields Array of field names.
   * @return sCRUDForm The object to allow method chaining.
   */
  public function setFieldOrder($fields) {
    if (!is_array($fields)) {
      $fields = func_get_args();
    }

    $new = array();
    foreach ($fields as $field) {
      $this->validateFieldName($field);
      $new[$field] = $this->fields[$field];
    }
    $not_set = array_diff_key($this->fields, $new);
    $this->fields = array_merge($new, $not_set);

    return $this;
  }

  /**
   * Set a specific field's attributes.
   *
   * @param string $field Field name.
   * @param array $attr Array of attribute values.
   * @return sCRUDForm The object to allow method chaining.
   */
  public function setFieldAttributes($field, array $attr) {
    $this->validateFieldName($field);
    $this->fields[$field]['attributes'] = array_merge($this->fields[$field]['attributes'], $attr);
    return $this;
  }
}
