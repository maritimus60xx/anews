<?php
//**
// * @file
// * Contains \Drupal\feedback\Controller\ReportController.
// */
//namespace Drupal\feedback\Controller;
//
//use Drupal\Core\Controller\ControllerBase;
//use Drupal\Core\Database\Database;
//use Drupal\Core\Database\Connection;
//use Symfony\Component\DependencyInjection\ContainerInterface;
//use Drupal\Core\Database\Query\ExtendableInterface;
//
///**
// * Controller for Feedback List Report
// */
//
//class ReportController extends ControllerBase {
////  /**
////   * Gets all Feedback for all nodes.
////   *
////   * @return array
////   */
////  protected function load() {
////    $select = Database::getConnection() -> select('feedback', 'f');
//////    Join the users table, so we can get the entry creator's username.
////    $select -> join('users_field_data', 'u', 'f.uid = u.uid');
//////    Select these specific fields for the output.
////    $select -> addField('u', 'name', 'username');
////    $select -> addField('f', 'name');
////    $select -> addField('f', 'email');
////    $select -> addField('f', 'message');
////    $select -> addField('f', 'created');
////    $pager = $select-> extend ('Drupal\Core\Database\Query\PagerSelectExtender') ->limit(5);
////    $entries = $pager -> execute() -> fetchAll(\PDO::FETCH_ASSOC);
//////    $entries = $select -> execute() -> fetchAll(\PDO::FETCH_ASSOC);
////    return $entries;
////  }
////  /**
////   * Creates the report page.
////   *
////   * @return array
////   * Render array for report output.
////   */
////  public function report() {
////    $content = array();
////    $content['message'] = array(
////      '#markup' => $this -> t('List of Feedback'),
////    );
////    $headers = array(
////      t('User'),
////      t('Name'),
////      t('Email'),
////      t('Message'),
////      t('Created'),
////    );
////    $rows = array();
////    foreach ($entries = $this -> load() as $entry) {
////      $rows[] = array_map('Drupal\Component\Utility\SafeMarkup::checkPlain', $entry);
////    }
////    $content['table'] = array(
////      '#type' => 'table',
////      '#header' => $headers,
////      '#rows' => $rows,
////      '#empty' => t('No entries available.'),
////    );
//////    Don't cache this page.
////    $content['#cache']['max-age'] = 0;
////    $content ['pager'] = array (
////      '#type' => 'pager'
////    );
////    return $content;
////  }
//
//  /**
//   * Gets all Feedback for all nodes.
//   *
//   * @return array
//   */
//  protected function load() {
//    $query = Database::getConnection() -> select('feedback', 'f');
////    Join the users table, so we can get the entry creator's username.
//    $query -> join('users_field_data', 'u', 'f.uid = u.uid');
////    Select these specific fields for the output.
//    $query -> addField('f', 'feedback_id');
//    $query -> addField('u', 'name', 'username');
//    $query -> addField('f', 'name');
//    $query -> addField('f', 'email');
//    $query -> addField('f', 'message');
//    $query -> addField('f', 'created');
//    $pager = $query-> extend ('Drupal\Core\Database\Query\PagerSelectExtender') ->limit(50);
//    $entries = $pager -> execute() -> fetchAll(\PDO::FETCH_ASSOC);
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
//      t('Feedback ID'),
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
//
//}
/**
 * @file
 * Contains \Drupal\feedback\Controller\ReportController.
 */

namespace Drupal\feedback\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Connection;
use Drupal\Core\Link;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Url;

/**
 * Controller routines for report routes.
 */
class ReportController extends ControllerBase
{

  /**
   * The Database Connection.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $database;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $controller = new static(
      $container->get('database')
    );
    $controller->setStringTranslation($container->get('string_translation'));
    return $controller;
  }

  /**
   * ReportController constructor.
   *
   * @param \Drupal\Core\Database\Connection $database
   *   The database connection.
   */
  public function __construct(Connection $database) {
    $this->database = $database;
  }

  /**
   * A simple controller method to explain what the report is about.
   */
  public function description() {
    $header = [
      ['data' => $this->t('Feedback_id'), 'field' => 'f.feedback_id'],
      ['data' => $this->t('User_ID'), 'field' => 'f.uid'],
      ['data' => $this->t('Name'), 'field' => 'f.name'],
      ['data' => $this->t('Email'), 'field' => 'f.email'],
      ['data' => $this->t('Message')],
      ['data' => $this->t('Created'), 'field' => 'f.created'],
      ['data' => $this->t('Operation')],
      ['data' => $this->t('Operation')],
    ];


    $query = $this->database->select('feedback', 'f')
      ->extend('Drupal\Core\Database\Query\TableSortExtender');
    $query->fields('f');

    $result = $query
      ->orderByHeader($header)
      ->execute();

    $rows = [];
    foreach ($result as $data) {
      $delete = Url::fromUserInput('/admin/reports/feedback/delete/'.$data->feedback_id);
      $edit   = Url::fromUserInput('/admin/reports/feedback-edit?num='.$data->feedback_id);

      $rows[] = array(
        'feedback_id' =>$data->feedback_id,
        'uid' => $data->uid,
        'name' => $data->name,
        'email' => $data->email,
        'message' => $data->message,
        'created' => $data->created,
        Link::fromTextAndUrl('Delete', $delete),
        Link::fromTextAndUrl('Edit', $edit),
      );
    }

    // Build the table.

    $build['feedback_table'] = [
      '#theme' => 'table',
      '#header' => $header,
      '#rows' => $rows,
    ];

    return $build;
  }

}
