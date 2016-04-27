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

    // Set variables.
    $status = "";
    $valid = true;
    $preferred = null;
    $primary = null;

    // TODO: Create proces for privacy options.

    // Get default value for phone from custom fields.
    $object = array("preferred" => $preferred, "primary" => $primary, "execute" => $valid, "status" => $status);
    // Create return array.
    $values = array($object);
    // Return values via API function succes.
    return civicrm_api3_create_success($values, $params, 'set_email', 'create');

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