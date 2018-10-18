<?php
/**
 * Created by PhpStorm.
 * User: lubuntu
 * Date: 18/10/18
 * Time: 10:44
 */


namespace Drupal\workshopfactory\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\node\NodeInterface;

/**
 * Defines MailingController class.
 */
class MailingController extends ControllerBase {

  /**
   * Display the markup.
   *
   * @return array
   *   Return markup array.
   */
  public function content(NodeInterface $node) {
    return [
      '#type' => 'markup',
      '#markup' => $this->t('Hello, World!'),
    ];
  }

}