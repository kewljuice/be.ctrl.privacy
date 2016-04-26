<?php
/**
 *
 * File for the CiviCRM API v3 Privacy Set Phone function
 *
 * http://booki.flossmanuals.net/civicrm-developer-guide/api/
 */
function civicrm_api3_privacy_set_phone($params) {
  // Set mandatory fields.
  civicrm_api3_verify_mandatory($params, NULL, array('contact_id',));

  // Execute function.
  try {

    // Get all phone numbers for contact_id.
    $list = array("private" => "phone1", "home" => "phone2");

    // Get default value for phone from custom fields.
    $object = array("default" => "currentvalue", "list" => $list);

    // Create return array.
    $values = array($object);

    // Execute.
    return civicrm_api3_create_success($values, $params, 'set_phone', 'create');
  } catch (Exception $e) {
    // Exception.
    return civicrm_api3_create_error('Caught exception: ', $e->getMessage(), '\n');
  }
}

/**
 * Adjust Metadata for set_phone action.
 *
 * @param array $params
 *   Array of parameters determined by getfields.
 */
function _civicrm_api3_privacy_set_phone_spec(&$params) {
  // We declare all these pseudoFields as there are other undocumented fields accessible via the api.
  $params['contact_id'] = array(
    'title' => 'Contact ID',
    'description' => 'Unique Contact ID',
    'api.required' => 1,
    'type' => CRM_Utils_Type::T_INT,
  );
}