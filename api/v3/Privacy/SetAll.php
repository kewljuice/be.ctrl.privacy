<?php
/**
 *
 * File for the CiviCRM API v3 Privacy Set All function
 *
 * http://booki.flossmanuals.net/civicrm-developer-guide/api/
 */
function civicrm_api3_privacy_set_all($params)
{
  // Set mandatory fields.
  civicrm_api3_verify_mandatory($params, NULL, array('contact_id',));

  // Execute function.
  try {
    // Create return array.
    $contact = $params['contact_id'];

    // Execute SetAddress.
    $addressAPI = civicrm_api3('Privacy', 'set_address', array('sequential' => 1, 'contact_id' => $contact));

    // Execute SetEmail.
    $emailAPI = civicrm_api3('Privacy', 'set_email', array('sequential' => 1, 'contact_id' => $contact));

    // Get default value for phone from custom fields.
    $object = array("Address" => $addressAPI['values'], "Email" => $emailAPI['values']);

    // Create return array.
    $values = array($object);

    // Execute.
    return civicrm_api3_create_success($values, $params, 'set_all', 'create');

  } catch (Exception $e) {
    // Exception.
    return civicrm_api3_create_error('Caught exception: ', $e->getMessage(), '\n');
  }
}

/**
 * Adjust Metadata for set_all action.
 *
 * @param array $params
 *   Array of parameters determined by getfields.
 */
function _civicrm_api3_privacy_set_all_spec(&$params)
{
  // We declare all these pseudoFields as there are other undocumented fields accessible via the api.
  $params['contact_id'] = array(
    'title' => 'Contact ID',
    'description' => 'Unique Contact ID',
    'api.required' => 1,
    'type' => CRM_Utils_Type::T_INT,
  );
}