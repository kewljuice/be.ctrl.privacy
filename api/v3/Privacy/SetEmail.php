<?php
/**
 *
 * File for the CiviCRM API v3 Privacy Set Email function
 *
 * http://booki.flossmanuals.net/civicrm-developer-guide/api/
 */
function civicrm_api3_privacy_set_email($params)
{
  // Set mandatory fields.
  civicrm_api3_verify_mandatory($params, NULL, array('contact_id',));

  // Execute function.
  try {

    // Get all email addresses for contact_id.
    $emailAPI = civicrm_api3('Email', 'get', array(
      'sequential' => 1,
      'return' => "location_type_id, email, is_primary",
      'contact_id' => $params['contact_id'],
    ));

    // Get custom_id for 'Email' from be.ctrl.privacy variable custom fields.
    $customfields = CRM_Core_BAO_Setting::getItem('be.ctrl.privacy', 'customfields');
    $customfield = 'custom_' . $customfields['Email'];

    // Get preferred location type id from custom field value by contact id.
    $customAPI = civicrm_api3('Contact', 'get', array(
      'sequential' => 1,
      'return' => $customfield,
      'id' => $params['contact_id'],
    ));
    $preferred = $customAPI['values'][0][$customfield];
    $preferred = $preferred[0];

    // Set status
    $status = "";
    $valid = true;

    // 1. Check if contact has preferred location_type_id else skip.
    if ($valid) {
      // Check in email addresses list.
      $check = array_search($preferred, array_column($emailAPI['values'], 'location_type_id'));
      if ($check) {
        $status = "Contact has preferred location type set";
      } else {
        $status = "Contact doesn't have preferred location type set";
        $valid = false;
      }
    }

    // 2. Check if preferred location_type_id is primary.
    if ($valid) {
      // Get current location type id that is primary
      $primaryAPI = civicrm_api3('Email', 'get', array(
        'sequential' => 1,
        'return' => "location_type_id",
        'is_primary' => 1,
        'contact_id' => $params['contact_id'],
      ));
      $primary = $primaryAPI['values'][0]['location_type_id'];
      if ($primary == $preferred) {
        $status = "Contact has preferred location type as default";
        $valid = false;
      } else {
        $status = "Contact doesn't have preferred location type as default";
      }
    }

    // Get default value for phone from custom fields.
    $object = array("preferred" => $preferred, "primary" => $primary,  "execute" => $valid, "status" => $status, "list" => $emailAPI['values']);

    // Create return array.
    $values = array($object);

    // Execute.
    return civicrm_api3_create_success($values, $params, 'set_email', 'create');

  } catch (Exception $e) {

    // Exception.
    return civicrm_api3_create_error('Caught exception: ', $e->getMessage(), '\n');

  }
}

/**
 * Adjust Metadata for set_email action.
 *
 * @param array $params
 *   Array of parameters determined by getfields.
 */
function _civicrm_api3_privacy_set_email_spec(&$params)
{
  // We declare all these pseudoFields as there are other undocumented fields accessible via the api.
  $params['contact_id'] = array(
    'title' => 'Contact ID',
    'description' => 'Unique Contact ID',
    'api.required' => 1,
    'type' => CRM_Utils_Type::T_INT,
  );
}