<?php

namespace Drupal\feedback\Plugin\Validation\Constraint;

use Symfony\Component\Validator\Constraint;

/**
 * Requires a field to have min 10 symbols when the entity is published.
 *
 * @Constraint(
 *   id = "FeedbackMessageLength",
 *   label = @Translation("Feedback message length", context = "Validation"),
 *   type = "string"
 * )
 */
class FeedbackMessageLength extends Constraint {

  public $symbolsValue = 'The message "%message-feedback" is too short. The number of symbols in this field should be more than 10.';
  public $isEmpty = 'The message field is empty.';

}
