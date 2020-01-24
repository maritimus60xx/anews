<?php

namespace Drupal\feedback\Plugin\Validation\Constraint;

use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Validates the FeedbackEmailResubmit constraint.
 */
class FeedbackEmailResubmitValidator extends ConstraintValidator implements ContainerInjectionInterface {

  /**
   * Retrieves the configuration factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * Retrieves the entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Constructs the object.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   Retrieves the configuration factory.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   *   Retrieves the entity type manager.
   */
  public function __construct(ConfigFactoryInterface $config_factory, EntityTypeManagerInterface $entityTypeManager) {
    $this->configFactory = $config_factory;
    $this->entityTypeManager = $entityTypeManager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory'),
      $container->get('entity_type.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function validate($email, Constraint $constraint) {

    // Get user's email.
    $email_now = $email->value;

    if ($email_now == NULL) {
      $this->context->addViolation($constraint->isEmpty);
    }
    else {
      // Get configuration form value.
      $config = $this->configFactory->getEditable('feedback.settings');
      $configuration = $config->get('prohibit_resubmit_value');

      // Check email in the entityTypeManager.
      $query_manager = $this->entityTypeManager->getStorage('feedback');
      $query = $query_manager->getQuery();
      $query->condition('email', $email_now);
      $email_query = $query->execute();

      // One email to one feedback.
      // There is a configuration form where you can cancel it.
      if (!empty($email_query) && $configuration == 1) {
        $this->context->addViolation($constraint->symbolsValue, [
          '%email-feedback' => $email->value,
        ]);
      }
    }
  }

}
