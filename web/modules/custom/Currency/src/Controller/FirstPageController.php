<?php

/**
 * @file
 * Contains \Drupal\weather\Controller\FirstPageController.
 */

namespace Drupal\currency\Controller;

/**
 * Provides route responses for the currency module.
 */
class FirstPageController {

  /**
   * Returns a simple page.
   *
   * @return array
   *   A simple renderable array.
   */
  public function content() {
    $element = array(
      '#markup' => 'Hello World!',
    );
    return $element;
  }
  /**
   * Returns a private page.
   *
   * @return array
   *   A simple renderable array.
   */
  public function privateContent() {
    $element = array(
      '#markup' => 'Private content',
    );
    return $element;
  }

}
