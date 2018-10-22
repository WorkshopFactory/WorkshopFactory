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
use Drupal\Core\Plugin\PluginFormInterface;
use Drupal\Core\Form\FormStateInterface;


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
class MailingAction extends ViewsBulkOperationsActionBase implements PluginFormInterface {


  use StringTranslationTrait;


  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $form['example_config_setting'] = [
      '#title' => t('Example setting pre-execute'),
      '#type' => 'textfield',
      '#default_value' => $form_state->getValue('example_config_setting'),
    ];
    return $form;
  }



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


      $mailManager = \Drupal::service('plugin.manager.mail');
      $module = "workshopfactory";
 $key = 'send_mail';
 $to = $entity->getOwner()->getEmail();
 //$entity->get('body')->value;
      $message['body'] = $params['message'];
      $params['message'] = $this->configuration["example_config_setting"];

 //$params['node_title'] = $entity->label();
 $langcode = $entity->getOwner()->getPreferredLangcode();
 $send = true;
 $result = $mailManager->mail($module, $key, $to, $langcode, $params, NULL, $send);
 if ($result['result'] !== true) {
   drupal_set_message(t('There was a problem sending your message and it was not sent.'), 'error');
 }
 else {
   drupal_set_message(t('Your message has been sent.'));
 }



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