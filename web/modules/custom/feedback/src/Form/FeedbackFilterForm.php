<?php
/**
 * @file
 * Contains \Drupal\feedback\Form\FeedbackFilterForm
 */
namespace Drupal\feedback\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides an feedback filter form.
 */
class FeedbackFilterForm extends FormBase {

  /**
   * @var \Symfony\Component\HttpFoundation\RequestStack
   */
  protected $requestStack;

  /**
   * Constructor.
   *
   * @param \Symfony\Component\HttpFoundation\RequestStack $request_stack
   */
  public function __construct(RequestStack $request_stack) {
    $this->requestStack = $request_stack;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('request_stack')
    );
  }

  /**
   * (@inheritdoc)
   */
  public function getFormId()
  {
    return 'feedback_filter_form';
  }
  /**
   * (@inheritdoc)
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $getFilterValue = $this->requestStack->getCurrentRequest()->query->get('filter');
    $form['filter'] = array(
      '#title' => $this->t('Enter text'),
      '#type' => 'textfield',
      '#size' => 20,
      '#required' => TRUE,
      '#default_value' => (isset($getFilterValue) ? $getFilterValue:''),
    );
    $form['actions']['submit'] = array(
      '#type' => 'submit',
      '#value' =>$this->t('Filter'),
    );
    return $form;

  }
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $filter = Url::fromUserInput('/admin/reports/feedback/?filter='.$form_state->getValue('filter'));
    $form_state->setRedirectUrl($filter);
  }
}
