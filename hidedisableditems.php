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
 * Implementation of hook_civicrm_install
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function hidedisableditems_civicrm_install() {
  _hidedisableditems_civix_civicrm_install();
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
 * Implementation of hook_civicrm_navigationMenu
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_navigationMenu
 */
function hidedisableditems_civicrm_navigationMenu(&$menu) {
  _hidedisableditems_civix_insert_navigation_menu($menu, 'Administer/Customize Data and Screens', [
    'label' => ts('Hide Disabled Items Settings', ['domain' => 'biz.jmaconsulting.hidedisableditems']),
    'name' => 'Hide Disabled Items Settings',
    'url' => CRM_Utils_System::url('civicrm/hidedisabled/settings', 'reset=1&action=browse', TRUE),
    'active' => 1,
    'has_separator' => 1,
    'permission_operator' => 'AND',
    'permission' => 'administer CiviCRM',
  ]);
  _hidedisableditems_civix_navigationMenu($menu);
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
