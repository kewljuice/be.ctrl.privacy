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

    // Set variables.
    $status = "";
    $valid = true;
    $preferred = null;
    $primary = null;
    $address = array();

    // Get all addresses for contact_id.
    $addressAPI = civicrm_api3('Address', 'get', array(
      'sequential' => 1,
      'return' => "location_type_id, email, is_primary",
      'contact_id' => $params['contact_id'],
    ));

    // 1. Check if contact has addresses set else skip.
    if($valid)  {
      if ($addressAPI['count'] > 0) {
        $status = "Contact has addresses";
      } else {
        $status = "Contact doesn't have addresses";
        $valid = false;
      }
    }

    // 2. Check if contact has preferred location_type_id else skip.
    if ($valid) {
      // Get custom_id for 'Email' from be.ctrl.privacy variable custom fields.
      $customfield = CRM_Core_BAO_Setting::getItem('be.ctrl.privacy', 'customfields');
      $customfield = 'custom_' . $customfield['Address'];
      // Get preferred location type id from custom field value by contact id.
      $customAPI = civicrm_api3('Contact', 'get', array(
        'sequential' => 1,
        'return' => $customfield,
        'id' => $params['contact_id'],
      ));
      $preferred = $customAPI['values'][0][$customfield];
      // Check preferred value is set.
      if(isset($preferred[0])) {
        $preferred = $preferred[0];
        $status = "Contact has preferred location type set";
      } else {
        $preferred = null;
        $status = "Contact doesn't have preferred location type set";
        $valid = false;
      }
    }

    // 3. Check if preferred location_type_id is present in addresses list.
    if ($valid) {
      // Check in email addresses list.
      $check = false;
      foreach($addressAPI['values'] as $value) {
        if($value['location_type_id'] == $preferred) {
          $address = $value;
          $check = true;
        }
      }
      // Check if statement is valid.
      if ($check) {
        $status = "Contact has preferred location type set in email list";
      } else {
        $status = "Contact doesn't have preferred location type: $preferred set in email list";
        $valid = false;
      }
    }

    // 4. Check if preferred location_type_id is primary.
    if ($valid) {
      // Get current location type id that is primary
      $primaryAPI = civicrm_api3('Address', 'get', array(
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
        $status = "Contact doesn't have preferred location type: $preferred as default";
      }
    }

    // 5. Execute set new primary.
    if ($valid) {
      // Call API.
      $executeAPI = civicrm_api3('Address', 'create', array(
        'sequential' => 1,
        'id' => $address['id'],
        'contact_id' => $params['contact_id'],
        'is_primary' => 1,
      ));
      if (!$executeAPI['is_error']) {
        $status = "Primary email address id: $address[id] has been changed";
      } else {
        $status = "Primary email address id:$address[id] hasn't been changed";
      }
    }

    // Get default value for phone from custom fields.
    $object = array("preferred" => $preferred, "primary" => $primary, "execute" => $valid, "status" => $status, "list" => $addressAPI['values']);
    // Create return array.
    $values = array($object);
    // Return values via API function succes.
    return civicrm_api3_create_success($values, $params, 'set_address', 'create');

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