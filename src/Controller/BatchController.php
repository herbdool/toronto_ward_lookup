<?php

namespace Drupal\toronto_ward_lookup\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Defines BatchController class.
 */
class BatchController extends ControllerBase {

  /**
   * Display the form.
   *
   * @return array
   *   Return form array.
   */
  public function content() {
    $form = \Drupal::formBuilder()->getForm('Drupal\toronto_ward_lookup\Form\BatchForm');
    return $form;
  }

}
