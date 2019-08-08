<?php
/**
 * @file
 * Contains \Drupal\feedback\Controller\ReportController.
 */
namespace Drupal\feedback\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Database;
use Drupal\Core\Database\Query\ExtendableInterface;

/**
 * Controller for Feedback List Report
 */

class ReportController extends ControllerBase {
//  /**
//   * Gets all Feedback for all nodes.
//   *
//   * @return array
//   */
//  protected function load() {
//    $select = Database::getConnection() -> select('feedback', 'f');
////    Join the users table, so we can get the entry creator's username.
//    $select -> join('users_field_data', 'u', 'f.uid = u.uid');
////    Select these specific fields for the output.
//    $select -> addField('u', 'name', 'username');
//    $select -> addField('f', 'name');
//    $select -> addField('f', 'email');
//    $select -> addField('f', 'message');
//    $select -> addField('f', 'created');
//    $pager = $select-> extend ('Drupal\Core\Database\Query\PagerSelectExtender') ->limit(5);
//    $entries = $pager -> execute() -> fetchAll(\PDO::FETCH_ASSOC);
////    $entries = $select -> execute() -> fetchAll(\PDO::FETCH_ASSOC);
//    return $entries;
//  }
//  /**
//   * Creates the report page.
//   *
//   * @return array
//   * Render array for report output.
//   */
//  public function report() {
//    $content = array();
//    $content['message'] = array(
//      '#markup' => $this -> t('List of Feedback'),
//    );
//    $headers = array(
//      t('User'),
//      t('Name'),
//      t('Email'),
//      t('Message'),
//      t('Created'),
//    );
//    $rows = array();
//    foreach ($entries = $this -> load() as $entry) {
//      $rows[] = array_map('Drupal\Component\Utility\SafeMarkup::checkPlain', $entry);
//    }
//    $content['table'] = array(
//      '#type' => 'table',
//      '#header' => $headers,
//      '#rows' => $rows,
//      '#empty' => t('No entries available.'),
//    );
////    Don't cache this page.
//    $content['#cache']['max-age'] = 0;
//    $content ['pager'] = array (
//      '#type' => 'pager'
//    );
//    return $content;
//  }

  /**
   * Gets all Feedback for all nodes.
   *
   * @return array
   */
  protected function load() {
    $query = Database::getConnection() -> select('feedback', 'f');
//    Join the users table, so we can get the entry creator's username.
    $query -> join('users_field_data', 'u', 'f.uid = u.uid');
//    Select these specific fields for the output.
    $query -> addField('u', 'name', 'username');
    $query -> addField('f', 'name');
    $query -> addField('f', 'email');
    $query -> addField('f', 'message');
    $query -> addField('f', 'created');
    $pager = $query-> extend ('Drupal\Core\Database\Query\PagerSelectExtender') ->limit(50);
    $entries = $pager -> execute() -> fetchAll(\PDO::FETCH_ASSOC);
    return $entries;
  }
  /**
   * Creates the report page.
   *
   * @return array
   * Render array for report output.
   */
  public function report() {
    $content = array();
    $content['message'] = array(
      '#markup' => $this -> t('List of Feedback'),
    );
    $headers = array(
      t('User'),
      t('Name'),
      t('Email'),
      t('Message'),
      t('Created'),
    );
    $rows = array();
    foreach ($entries = $this -> load() as $entry) {
      $rows[] = array_map('Drupal\Component\Utility\SafeMarkup::checkPlain', $entry);
    }
    $content['table'] = array(
      '#type' => 'table',
      '#header' => $headers,
      '#rows' => $rows,
      '#empty' => t('No entries available.'),
    );
//    Don't cache this page.
    $content['#cache']['max-age'] = 0;
    $content ['pager'] = array (
      '#type' => 'pager'
    );
    return $content;
  }

}
