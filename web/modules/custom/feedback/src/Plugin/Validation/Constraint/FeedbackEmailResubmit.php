<?php

namespace Drupal\feedback\Plugin\Validation\Constraint;

use Symfony\Component\Validator\Constraint;

/**
 * Requires a field to have min 10 symbols when the entity is published.
 *
 * @Constraint(
 *   id = "FeedbackEmailResubmit",
 *   label = @Translation("Feedback email resubmit", context = "Validation"),
 *   type = "string"
 * )
 */
class FeedbackEmailResubmit extends Constraint {

  public $symbolsValue = 'The email "%email-feedback" can not be use for form resubmit, because it was already used for sending.';
  public $isEmpty = 'The email field is empty.';
}
