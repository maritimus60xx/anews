<?php
/**
 * @file
 * contains \Drupal\feedback\Plugin\Block\FeedbackBlock
 */
namespace Drupal\feedback\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormBuilderInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides an 'Feedback' Block
 * @Block(
 *   id = "feedback_block",
 *   admin_label = @Translation("Feedback Block"),
 * )
 */

class FeedbackBlock extends BlockBase implements ContainerFactoryPluginInterface {
  /**
   * {@inheritdoc}
   */
  protected $formBuilder;

  /**
   * {@inheritDoc}
   */

  public function __construct(array $configuration, $plugin_id, $plugin_definition, FormBuilderInterface $formBuilder) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->formBuilder = $formBuilder;
  }

  /**
   * {@inheritdoc}
   *
   */

  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('form_builder')
    );
  }

  /**
   * {@inheritDoc}
   */

  public function build()
  {
    $form = $this->formBuilder->getForm('Drupal\feedback\Form\FeedbackForm');
    return $form;
  }
}
