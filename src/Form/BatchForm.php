<?php
namespace Drupal\toronto_ward_lookup\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
* Implements a Toronto Ward Lookup Batch form.
*/
class BatchForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['description'] = [
      '#type' => 'markup',
      '#markup' => '<p>' . t('Find and update all contacts so that they have ward numbers that match their addresses.') . '</p>',
    ];
    $form['actions'] = [
      '#type' => 'actions',
    ];
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => t('Update ward numbers'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'toronto_ward_lookup_batch_form';
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    $batch = [];
    $batch = $this->generateBatch();
    batch_set($batch);
  }

  /**
   * Generate Batch.
   *
   * Batch will process 100 items at a time.
   *
   */
  public function generateBatch() {
    $batch = [
      'operations' => [],
      'finished' => 'toronto_ward_lookup_batch_finished', // runs after batch is finished
      'title' => $this->t('Processing Update'),
      'init_message' => $this->t('Update is starting.'),
      'progress_message' => $this->t('Processed @current out of @total.'),
      'error_message' => $this->t('Update has encountered an error.'),
    ];
    $progress = 0; // where to start
    $limit = 100; // how many to process for each run
    $max = _toronto_ward_lookup_count_addresses(); // how many records to process until stop.
    while ($progress <= $max) {
      $batch['operations'][] = ['toronto_ward_lookup_address_process', [$progress, $limit]];
      $progress = $progress + $limit;
    }

    return $batch;
  }
}