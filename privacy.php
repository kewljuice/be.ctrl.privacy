<?php

require_once 'privacy.civix.php';
require_once 'php/privacy_functions.php';

/**
 * Implements hook_civicrm_config().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function privacy_civicrm_config(&$config)
{
  _privacy_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_xmlMenu().
 *
 * @param array $files
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_xmlMenu
 */
function privacy_civicrm_xmlMenu(&$files)
{
  _privacy_civix_civicrm_xmlMenu($files);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function privacy_civicrm_install()
{
  _privacy_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_uninstall
 */
function privacy_civicrm_uninstall()
{
  _privacy_civix_civicrm_uninstall();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function privacy_civicrm_enable()
{
  // Check/create field group.
  $fieldgroup = privacy_civicrm_createFieldGroup('ctrl_individual_privacy', 'Privacy', 'Individual');

  // Check/create option group.
  $optiongroup = privacy_civicrm_createOptionGroup('ctrl_individual_privacy_group');

  // Get all active location types from website.
  $optiongrouplist = privacy_civicrm_locationTypes();

  // Check/add option group fields from list.
  if ($optiongroup['status']) {
    $optiongroupvalues = privacy_civicrm_createOptionGroupValues($optiongroup['id'], $optiongrouplist);
  }

  // Check/create field group fields 'Address, Email, Phone'.
  // TODO: re-implement phone as privacy entity.
  $fieldgrouplist = array('Address', 'Email');
  if ($fieldgroup['status']) {
    $fieldgroupvalues = privacy_civicrm_createFieldGroupValues($fieldgroup['id'], $optiongroup['id'], $fieldgrouplist);
  }

  // Save values as be.ctrl.privacy extension variable.
  $customfields = array();
  foreach ($fieldgroupvalues as $fieldgroupvalue) {
    $customfields[$fieldgroupvalue['value']] = $fieldgroupvalue['id'];
  }
  CRM_Core_BAO_Setting::setItem($customfields, 'be.ctrl.privacy', 'customfields');
  CRM_Core_BAO_Setting::setItem($optiongrouplist, 'be.ctrl.privacy', 'optiongrouplist');

  // Continue.
  _privacy_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_disable
 */
function privacy_civicrm_disable()
{
  // Clear extension variables.
  CRM_Core_BAO_Setting::setItem('', 'be.ctrl.privacy', 'customfields');
  CRM_Core_BAO_Setting::setItem('', 'be.ctrl.privacy', 'optiongrouplist');

  // Continue.
  _privacy_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @param $op string, the type of operation being performed; 'check' or 'enqueue'
 * @param $queue CRM_Queue_Queue, (for 'enqueue') the modifiable list of pending up upgrade tasks
 *
 * @return mixed
 *   Based on op. for 'check', returns array(boolean) (TRUE if upgrades are pending)
 *                for 'enqueue', returns void
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_upgrade
 */
function privacy_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL)
{
  return _privacy_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_managed
 */
function privacy_civicrm_managed(&$entities)
{
  _privacy_civix_civicrm_managed($entities);
}

/**
 * Implements hook_civicrm_caseTypes().
 *
 * Generate a list of case-types.
 *
 * @param array $caseTypes
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function privacy_civicrm_caseTypes(&$caseTypes)
{
  _privacy_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implements hook_civicrm_angularModules().
 *
 * Generate a list of Angular modules.
 *
 * Note: This hook only runs in CiviCRM 4.5+. It may
 * use features only available in v4.6+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function privacy_civicrm_angularModules(&$angularModules)
{
  _privacy_civix_civicrm_angularModules($angularModules);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterSettingsFolders
 */
function privacy_civicrm_alterSettingsFolders(&$metaDataFolders = NULL)
{
  _privacy_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

/**
 * Implements hook_civicrm_preProcess().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_preProcess
 */
function privacy_civicrm_preProcess($formName, &$form)
{
  /* */
}

/**
 * Implements hook_civicrm_navigationMenu().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_navigationMenu
 *
 * function privacy_civicrm_navigationMenu(&$menu) {
 * _privacy_civix_insert_navigation_menu($menu, NULL, array(
 * 'label' => ts('The Page', array('domain' => 'be.ctrl.privacy')),
 * 'name' => 'the_page',
 * 'url' => 'civicrm/the-page',
 * 'permission' => 'access CiviReport,access CiviContribute',
 * 'operator' => 'OR',
 * 'separator' => 0,
 * ));
 * _privacy_civix_navigationMenu($menu);
 * } // */
