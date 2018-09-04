<?php

require_once 'CRM/Core/Form.php';

/**
 * Form controller class
 *
 * @see http://wiki.civicrm.org/confluence/display/CRMDOC43/QuickForm+Reference
 */
class CRM_HDI_Form_Settings extends CRM_Core_Form {

  function setDefaultValues() {
    $defaults = [];
    $defaults['hide_disabled'] = Civi::settings()->get('hide_disabled_items');
    return $defaults;
  }

  function buildQuickForm() {
    $this->addCheckBox('hide_disabled',
      ts('Disabled items to hide'),
      $this->getOptions(), NULL, NULL, TRUE
    );
    $this->addButtons(array(
      array(
        'type' => 'submit',
        'name' => ts('Submit'),
        'isDefault' => TRUE,
      ),
    ));

    // export form elements
    $this->assign('elementNames', $this->getRenderableElementNames());
    parent::buildQuickForm();
  }

  function postProcess() {
    $values = $this->exportValues();
    if (CRM_Utils_Array::value('hide_disabled', $values)) {
      Civi::settings()->set('hide_disabled_items', $values['hide_disabled']);
    }
    CRM_Core_Session::setStatus(ts('Your preferences have been saved.'));
    parent::postProcess();
  }

  function getOptions() {
    $options = array(
      ts('Hide disabled Scheduled Reminders') => 'reminders',
      ts('Hide disabled Message Templates') => 'templates',
    );
    return $options;
  }

  /**
   * Get the fields/elements defined in this form.
   *
   * @return array (string)
   */
  function getRenderableElementNames() {
    // The _elements list includes some items which should not be
    // auto-rendered in the loop -- such as "qfKey" and "buttons".  These
    // items don't have labels.  We'll identify renderable by filtering on
    // the 'label'.
    $elementNames = array();
    foreach ($this->_elements as $element) {
      /** @var HTML_QuickForm_Element $element */
      $label = $element->getLabel();
      if (!empty($label)) {
        $elementNames[] = $element->getName();
      }
    }
    return $elementNames;
  }
}
