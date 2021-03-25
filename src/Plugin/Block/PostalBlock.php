<?php

namespace Drupal\toronto_ward_lookup\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Block\BlockPluginInterface;

/**
 * Provides a 'Toronto Ward Lookup Postal' Block.
 *
 * @Block(
 *   id = "toronto_ward_lookup_postal_block",
 *   admin_label = @Translation("Toronto Ward Lookup Postal"),
 *   category = @Translation("Toronto Ward Lookup"),
 * )
 */
class PostalBlock extends BlockBase implements BlockPluginInterface {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $form = \Drupal::formBuilder()->getForm('Drupal\toronto_ward_lookup\Form\PostalForm');
    return $form;
  }

}
