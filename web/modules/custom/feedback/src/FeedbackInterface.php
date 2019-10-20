<?php

namespace Drupal\feedback;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\user\EntityOwnerInterface;
use Drupal\Core\Entity\EntityChangedInterface;

/**
 * Provides an interface defining a Contact entity.
 *
 * We have this interface so we can join the other interfaces it extends.
 *
 * @ingroup feedback
 */
interface FeedbackInterface extends ContentEntityInterface, EntityOwnerInterface, EntityChangedInterface {

}
