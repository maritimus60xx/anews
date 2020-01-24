<?php

namespace Drupal\feedback\Plugin\Validation\Constraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Validates the FeedbackNameLength constraint.
 */
class FeedbackNameLengthValidator extends ConstraintValidator {

  /**
   * {@inheritdoc}
   */
  public function validate($name, Constraint $constraint) {
    if ($name->value == NULL) {
      $this->context->addViolation($constraint->isEmpty);
    }
    elseif (strlen($name->value) < 5) {
      $this->context->addViolation($constraint->symbolsValue, [
        '%name-feedback' => $name->value,
      ]);
    }
  }

}
