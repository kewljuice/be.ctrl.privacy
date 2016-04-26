<?php
/**
 *
 * File for the CiviCRM API v3 Privacy Set Address function
 *
 * http://booki.flossmanuals.net/civicrm-developer-guide/api/
 */
function civicrm_api3_privacy_set_address($params) {
  // Set mandatory fields.
  civicrm_api3_verify_mandatory($params, NULL, array('contact_id',));

  // Execute function.
  try {
    // Create return array.
    $values = array();
    // Execute.
    return civicrm_api3_create_success($values, $params);
  } catch (Exception $e) {
    // Exception.
    return civicrm_api3_create_error('Caught exception: ', $e->getMessage(), '\n');
  }
}

/**
 * Adjust Metadata for set_address action.
 *
 * @param array $params
 *   Array of parameters determined by getfields.
 */
function _civicrm_api3_privacy_set_address_spec(&$params) {
  // We declare all these pseudoFields as there are other undocumented fields accessible via the api.
  $params['contact_id'] = array(
    'title' => 'Contact ID',
    'description' => 'Unique Contact ID',
    'api.required' => 1,
    'type' => CRM_Utils_Type::T_INT,
  );
}