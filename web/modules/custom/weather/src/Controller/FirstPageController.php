<?php

/**
 * @file
 * Contains \Drupal\weather\Controller\FirstPageController.
 */

namespace Drupal\weather\Controller;

/**
 * Provides route responses for the weather module.
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
      '#markup' => 'hello world',
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
?>

