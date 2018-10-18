<?php
/**
 * Created by PhpStorm.
 * User: lubuntu
 * Date: 18/10/18
 * Time: 13:23
 */


namespace Drupal\workshopfactory\Plugin\Action;

use Drupal\views_bulk_operations\Action\ViewsBulkOperationsActionBase;

use Drupal\Core\Session\AccountInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * Mail a message to selected users
 *
 * @Action(
 *   id = "mailing_action",
 *   label = @Translation("Mail a message to selected users"),
 *   type = "",
 *   confirm = TRUE
 * )
 */
class MailingAction extends ViewsBulkOperationsActionBase {


  use StringTranslationTrait;

  public function execute($entity = NULL) {
    // Do some processing..

    // Don't return anything for a default completion message, otherwise return translatable markup.
    return $this->t('Some result');
  }

  public function access($object, AccountInterface $account = NULL, $return_as_object = FALSE) {
    if ($object->getEntityType() === 'node') {
      $access = $object->access('update', $account, TRUE)
        ->andIf($object->status->access('edit', $account, TRUE));
      return $return_as_object ? $access : $access->isAllowed();
    }

    // Other entity types may have different
    // access methods and properties.
    return TRUE;
  }


}