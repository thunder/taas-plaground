<?php

namespace Drupal\custom2\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Returns responses for custom2 routes.
 */
class Custom2Controller extends ControllerBase {

  /**
   * Builds the response.
   */
  public function build() {

    $build['content'] = [
      '#type' => 'item',
      '#markup' => $this->t('Custom feature 2 works!'),
    ];

    return $build;
  }

}
