<?php

namespace Drupal\feedback\Plugin\Validation\Constraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Validates the FeedbackMessageLength constraint.
 */
class FeedbackMessageLengthValidator extends ConstraintValidator {

  /**
   * {@inheritdoc}
   */
  public function validate($message, Constraint $constraint) {
    if ($message->value == NULL) {
      $this->context->addViolation($constraint->isEmpty);
    }
    elseif (strlen($message->value) < 10) {
      $this->context->addViolation($constraint->symbolsValue, [
        '%message-feedback' => $message->value,
      ]);
    }
  }

}
