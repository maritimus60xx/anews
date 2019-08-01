<?php
/**
 * @file
 * contains \Drupal\feedback\Plugin\Block\FeedbackBlock
 */
namespace Drupal\feedback\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;
use Drupal\User\Entity\User;

/**
 * Provides an 'Feedback' Block
 * @Block(
 *   id = "feedback_block",
 *   admin_label = @Translation("Feedback Block"),
 * )
 */

class FeedbackBlock extends BlockBase {
  /**
   * {@inheritdoc}
   */

  public function build()
  {
    return \Drupal::formBuilder() -> getForm('Drupal\feedback\Form\FeedbackForm');
  }
  public function blockAccess(AccountInterface $account)
  {
    /**
     * @var \Drupal\user\Entity\User $user
     */

    $user = \Drupal\user\Entity\User::load(\Drupal::currentUser()->id());
    if(($user->id())>=0) {
      return AccessResult::allowedIfHasPermission($account, 'view feedback');
    }
    return AccessResult::forbidden();
  }
}
