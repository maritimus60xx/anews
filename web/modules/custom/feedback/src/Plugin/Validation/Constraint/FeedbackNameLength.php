<?php

namespace Drupal\feedback\Plugin\Validation\Constraint;

use Symfony\Component\Validator\Constraint;

/**
 * Requires a field to have min 5 symbols when the entity is published.
 *
 * @Constraint(
 *   id = "FeedbackNameLength",
 *   label = @Translation("Feedback name length", context = "Validation"),
 *   type = "string"
 * )
 */
class FeedbackNameLength extends Constraint {

  public $symbolsValue = 'The name "%name-feedback" is too short. The number of symbols in this field should be more than 5.';
  public $isEmpty = 'The name field is empty.';

}
