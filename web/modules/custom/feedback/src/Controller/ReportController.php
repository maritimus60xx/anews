<?php

/**
 * @file
 * Contains \Drupal\feedback\Controller\ReportController.
 */

namespace Drupal\feedback\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Connection;
use Drupal\Core\Form\FormBuilderInterface;
use Drupal\Core\Link;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;


/**
 * Controller routines for report routes.
 */
class ReportController extends ControllerBase {

  /**
   * The Database Connection.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $database;

  /**
   * @var \Symfony\Component\HttpFoundation\RequestStack
   */
  protected $requestStack;

  /**
   * @var \Drupal\Core\Form\FormBuilderInterface
   */
  protected $formBuilder;

  /**
   * ReportController constructor.
   *
   * @param \Drupal\Core\Database\Connection $database
   *
   * @param \Symfony\Component\HttpFoundation\RequestStack $request_stack
   *
   * @param \Drupal\Core\Form\FormBuilderInterface $formBuilder
   */
  public function __construct(Connection $database, RequestStack $request_stack, FormBuilderInterface $formBuilder) {
    $this->database = $database;
    $this->requestStack = $request_stack;
    $this->formBuilder = $formBuilder;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('database'),
      $container->get('request_stack'),
      $container->get('form_builder')
    );
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

    $getFilterValue = $this->requestStack->getCurrentRequest()->query->get('filter');

    if (isset($getFilterValue)) {
      $get_value = $this->database->escapeLike($getFilterValue);
      $query = $this->database->select('feedback', 'f');
      $query->fields('f');
      $query->condition('f.name', $get_value  . '%', 'LIKE');

    } else {
      $query = $this->database->select('feedback', 'f');
      $query->fields('f');
    }

    $table_sort = $query->extend('Drupal\Core\Database\Query\TableSortExtender')
      ->orderByHeader($header);
    $pager = $table_sort->extend('Drupal\Core\Database\Query\PagerSelectExtender')
      ->limit(50);

    $result = $pager
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

    $build['form'] = [
      $form = $this->formBuilder->getForm('Drupal\feedback\Form\FeedbackFilterForm'),
    ];

    $build['feedback_table'] = [
      '#theme' => 'table',
      '#header' => $header,
      '#rows' => $rows,
    ];

    //  Add the pager.
    $build['pager'] = array(
      '#type' => 'pager'
    );

    return $build;
  }

}
