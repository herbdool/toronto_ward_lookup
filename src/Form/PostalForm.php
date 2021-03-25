<?php
namespace Drupal\toronto_ward_lookup\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

/**
* Implements a Toronto Ward Lookup by Postal Code form.
*/
class PostalForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['toronto_postal'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Type your postal code'),
      '#description' => $this->t('E.g., M5W 1E6'),
      '#size' => '28',
    );
    $form['actions'] = [
      '#type' => 'actions',
    ];
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Find my ward'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'toronto_ward_lookup_postal_form';
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $messenger = \Drupal::messenger();
    $url = Url::fromUri('internal:/' . TORONTO_WARD_FAIL_REDIRECT);
    $form_state->setRedirectUrl($url);

    $postal_code = represent_format_postal_code($form_state->getValue('toronto_postal'));
    $boundaries = represent_boundaries_by_postal_code($postal_code, 'toronto-wards-2018');

    // If not empty check if it matches our hash table mapping Toronto wards to Ontario ridings.
    if (!isset($boundaries['toronto-wards-2018'])) {
      $messenger->addMessage(t('Could not find a ward for postal code <em>@postal</em>.', ['@postal' => $form_state->getValue('toronto_postal')]));

      return;
    }

    $wards = [];
    foreach ($boundaries['toronto-wards-2018'] as $key => $boundary) {
      $ward_id = (int) $boundaries['toronto-wards-2018'][$key]->external_id;
      if (!empty($ward_id)) {
        $wards[] = $ward_id;
      }
    }

    $count = count($wards);
    if ($count > 1) {
      $ward_urls = [];
      foreach ($wards as $ward) {
        $ward_urls[] = l(t('Ward') . '  ' . $ward, TORONTO_WARD_URL_PREFIX . $ward);
      }
      $messenger->addMessage(t('Postal code @postal matches more than one ward. Found @count matches:<br/>!matches', [
            '@postal' => $form_values['toronto_postal'],
            '@count' => $count,
            '@matches' => implode('<br/>', $ward_urls)
        ]
      ));
      return;
    }

    if (!empty($wards[0])) {
      $url = Url::fromUri('internal:/' . TORONTO_WARD_URL_PREFIX . $wards[0]);
      $form_state->setRedirectUrl($url);
      return;
    }
  }

}