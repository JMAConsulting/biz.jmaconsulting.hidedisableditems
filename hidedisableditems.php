<?php

require_once 'hidedisableditems.civix.php';

/**
 * Implementation of hook_civicrm_config
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function hidedisableditems_civicrm_config(&$config) {
  _hidedisableditems_civix_civicrm_config($config);
}

/**
 * Implementation of hook_civicrm_xmlMenu
 *
 * @param $files array(string)
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_xmlMenu
 */
function hidedisableditems_civicrm_xmlMenu(&$files) {
  _hidedisableditems_civix_civicrm_xmlMenu($files);
}

/**
 * Implementation of hook_civicrm_install
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function hidedisableditems_civicrm_install() {
  _hidedisableditems_civix_civicrm_install();
}

/**
 * Implementation of hook_civicrm_uninstall
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_uninstall
 */
function hidedisableditems_civicrm_uninstall() {
  _hidedisableditems_civix_civicrm_uninstall();
}

/**
 * Implementation of hook_civicrm_enable
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function hidedisableditems_civicrm_enable() {
  _hidedisableditems_civix_civicrm_enable();
}

/**
 * Implementation of hook_civicrm_disable
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_disable
 */
function hidedisableditems_civicrm_disable() {
  _hidedisableditems_civix_civicrm_disable();
}

/**
 * Implementation of hook_civicrm_upgrade
 *
 * @param $op string, the type of operation being performed; 'check' or 'enqueue'
 * @param $queue CRM_Queue_Queue, (for 'enqueue') the modifiable list of pending up upgrade tasks
 *
 * @return mixed  based on op. for 'check', returns array(boolean) (TRUE if upgrades are pending)
 *                for 'enqueue', returns void
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_upgrade
 */
function hidedisableditems_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _hidedisableditems_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implementation of hook_civicrm_managed
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_managed
 */
function hidedisableditems_civicrm_managed(&$entities) {
  _hidedisableditems_civix_civicrm_managed($entities);
}

/**
 * Implementation of hook_civicrm_caseTypes
 *
 * Generate a list of case-types
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function hidedisableditems_civicrm_caseTypes(&$caseTypes) {
  _hidedisableditems_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implementation of hook_civicrm_alterSettingsFolders
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterSettingsFolders
 */
function hidedisableditems_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _hidedisableditems_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

/**
 * Implementation of hook_civicrm_navigationMenu
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_navigationMenu
 */
function hidedisableditems_civicrm_navigationMenu(&$params) {
  $navId = CRM_Core_DAO::singleValueQuery("SELECT max(id) FROM civicrm_navigation");
  if (is_integer($navId)) {
    $navId++;
  }
  $parentId = CRM_Core_DAO::getFieldValue('CRM_Core_DAO_Navigation', 'Customize Data and Screens', 'id', 'name');
  $params[$parentId]['child'][$navId] = array(
    'attributes' => array (
      'label' => ts('Hide Disabled Items Settings',array('domain' => 'biz.jmaconsulting.hidedisableditems')),
      'name' => 'Hide Disabled Items Settings',
      'url' => 'civicrm/hidedisabled/settings',
      'permission' => 'access CiviCRM',
      'operator' => 'AND',
      'separator' => 1,
      'parentID' => $parentId,
      'navID' => $navId,
      'active' => 1
    ),
  );
}

/**
 * Implementation of hook_civicrm_pageRun
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_pageRun
 */
function hidedisableditems_civicrm_pageRun(&$page) {
  if ($page->getVar('_name') == "CRM_Admin_Page_ScheduleReminders") {
    $items = Civi::settings()->get('hide_disabled_items');
    if (!empty($items['reminders'])) {
      $reminders = CRM_Core_Smarty::singleton()->get_template_vars('rows');
      foreach ($reminders as $key => $reminder) {
        if (!$reminder['is_active']) {
          unset($reminders[$key]);
        }
      }
      $page->assign('rows', $reminders);
    }
  }
  if ($page->getVar('_name') == "CRM_Admin_Page_MessageTemplates") {
    $items = Civi::settings()->get('hide_disabled_items');
    if (!empty($items['templates'])) {
      $templates = CRM_Core_Smarty::singleton()->get_template_vars('rows');
      foreach ($templates as $key => &$section) {
        foreach ($section as $index => $template) {
          if (!$template['is_active']) {
            unset($section[$index]);
          }
        }
      }
      $page->assign('rows', $templates);
    }
  }
}