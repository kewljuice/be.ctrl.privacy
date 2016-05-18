<?php
/**
 * CiviCRM functions for be.ctrl.privacy
 */

/**
 *
 * Returns an array with CiviCRM location types.
 *
 * @return array
 */
function privacy_civicrm_locationTypes() {
  $types = array();
  // Get all active location types via API.
  $locationtypes = civicrm_api3('LocationType', 'get', array(
    'sequential' => 1,
    'return' => 'id, display_name',
    'is_active' => 1,
  ));
  // Reformat for later use.
  foreach ($locationtypes['values'] as &$value) {
    $types[$value['id']] = $value['display_name'];
  }
  // Return array of location types.
  return $types;
}

/**
 *
 * Creates a CiviCRM field group.
 *
 * @param string $name group name for programmatic handling
 * @param string $title group title for administration
 * @param string $type group type this group extends
 * @return array
 */
function privacy_civicrm_createFieldGroup($name, $title, $type) {
  $group = array();
  $group['status'] = 0;
  // Check if field group 'ctrl_individual_privacy' exists.
  $checkgroup = civicrm_api3('CustomGroup', 'get', array('name' => $name));
  if (!$checkgroup['is_error'] && $checkgroup['count'] > 0) {
    // Group exists, set status to true.
    $group['status'] = 1;
    $group['id'] = $checkgroup['id'];
  }
  else {
    // Create field group $name.
    $creategroup = civicrm_api3('CustomGroup', 'create', array(
      'sequential' => 1,
      'name' => $name,
      'title' => $title,
      'extends' => $type,
    ));
    // On succes, set status to true.
    if (!$checkgroup['is_error']) {
      $group['status'] = 1;
      $group['id'] = $creategroup['id'];
    }
  }
  // Return array of field group.
  return $group;
}

/**
 *
 * Creates a CiviCRM option group.
 *
 * @param string $name group name for programmatic handling
 * @return array
 */
function privacy_civicrm_createOptionGroup($name) {
  $group = array();
  $group['status'] = 0;
  // Check if option group $name exists.
  $checkgroup = civicrm_api3('OptionGroup', 'get', array('name' => $name));
  if (!$checkgroup['is_error'] && $checkgroup['count'] > 0) {
    // Group exists, set status to true.
    $group['status'] = 1;
    $group['id'] = $checkgroup['id'];
  }
  else {
    // Create option group $name.
    $creategroup = civicrm_api3('OptionGroup', 'create', array(
      'sequential' => 1,
      'is_active' => 1,
      'title' => $name,
      'name' => $name,
    ));
    // On succes, set status to true.
    if (!$checkgroup['is_error']) {
      $group['status'] = 1;
      $group['id'] = $creategroup['id'];
    }
  }
  // Return array of option group.
  return $group;
}

/**
 *
 * Adds CiviCRM option group values to option group.
 *
 * @param integer $groupID group ID where values will be added
 * @param array $values values to be added
 * @return array
 * @return array
 */
function privacy_civicrm_createOptionGroupValues($groupID, $values) {
  // TODO: check why this function saves doubles.
  $status = array();
  // Loop array with values.
  foreach ($values as $key => $value) {
    $status[$value]['value'] = $value;
    $status[$value]['status'] = 0;
    // Check if option group value $value exists.
    $checkvalue = civicrm_api3('OptionValue', 'get', array(
      'name' => $key,
      'option_group_id' => $groupID,
    ));
    if (!$checkvalue['is_error'] && $checkvalue['count'] > 0) {
      // Group exists, set status to true.
      $status[$value]['status'] = 1;
      $status[$value]['id'] = $checkvalue['id'];
    }
    else {
      // Create option group value $value.
      $createvalue = civicrm_api3('OptionValue', 'create', array(
        'sequential' => 1,
        'name' => $key,
        'label' => $value,
        'value' => $key,
        'option_group_id' => $groupID,
      ));
      // On succes, set status to true.
      if (!$createvalue['is_error']) {
        $status[$value]['status'] = 1;
        $status[$value]['id'] = $createvalue['id'];
      }
    }
  }
  // Return array of results.
  return $status;
}

/**
 *
 * Adds CiviCRM field group values to field group.
 *
 * @param integer $groupID group ID where custom fields will be added
 * @param integer $optionID option ID values are set
 * @param array $values values to be added
 * @return array
 */
function privacy_civicrm_createFieldGroupValues($groupID, $optionID, $values) {
  $status = array();
  // Loop array with values.
  foreach ($values as &$value) {
    $status[$value]['value'] = $value;
    $status[$value]['status'] = 0;
    // Check if custom field $value exists.
    $checkvalue = civicrm_api3('CustomField', 'get', array(
      'name' => $value,
      'custom_group_id' => $groupID,
    ));
    if (!$checkvalue['is_error'] && $checkvalue['count'] > 0) {
      // Group exists, set status to true.
      $status[$value]['status'] = 1;
      $status[$value]['id'] = $checkvalue['id'];
    }
    else {
      // Create custom field $value.
      $createvalue = civicrm_api3('CustomField', 'create', array(
        'sequential' => 1,
        'name' => $value,
        'label' => $value,
        'custom_group_id' => $groupID,
        'option_group_id' => $optionID,
        'data_type' => "String",
        'html_type' => "Radio",
        'is_searchable' => 1,
      ));
      // On succes, set status to true.
      if (!$createvalue['is_error']) {
        $status[$value]['status'] = 1;
        $status[$value]['id'] = $createvalue['id'];
      }
    }
  }
  // Return array of results.
  return $status;
}

/**
 *
 * Executes CiviCRM Privacy API setAll for shutdown functions.
 *
 * @param integer $contactID contact ID
 * @return array
 */
function privacy_civicrm_shutdown($contactID) {
  // Execute Privacy API.
  $API = civicrm_api3('Privacy', 'set_all', array(
    'sequential' => 1,
    'contact_id' => $contactID
  ));
  return $API;
}