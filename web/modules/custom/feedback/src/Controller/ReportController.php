<?php

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
