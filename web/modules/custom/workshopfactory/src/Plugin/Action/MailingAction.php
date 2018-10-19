<?php
/**
 * Created by PhpStorm.
 * User: lubuntu
 * Date: 18/10/18
 * Time: 13:23
 */


namespace Drupal\workshopfactory\Plugin\Action;

use Drupal\Core\Session\AccountInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\views_bulk_operations\Action\ViewsBulkOperationsActionBase;

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

    drupal_set_message($this->t('Ca marche !!!'));

    //return $this->t('Some result');
  }


  public function executeMultiple(array $entities) {
    foreach ($entities as $delta => $entity) {

      drupal_set_message($this->t('The email is: @title.', [
        '@title' => $entity->getOwner()->getEmail(),
      ]));

      //var_dump($this->view->result[$delta]);
      // Process the entity..



      $sitename = \Drupal::config('system.site')->get('name');
      $langcode = \Drupal::config('system.site')->get('langcode');
      $module = 'my_module';
      $key = 'my_key';
      $to = $entity->getOwner()->getEmail();
      $reply = NULL;
      $send = TRUE;

      $params['message'] = t('Your wonderful message about @sitename', array('@sitename' => $sitename));
      $params['subject'] = t('Message subject');
      $params['options']['username'] = $entity->getOwner()->getUsername();
      $params['options']['title'] = t('Your wonderful title');
      $params['options']['footer'] = t('Your wonderful footer');

      $mailManager = \Drupal::service('plugin.manager.mail');
      $mailManager->mail($module, $key, $to, $langcode, $params, $reply, $send);





    }
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