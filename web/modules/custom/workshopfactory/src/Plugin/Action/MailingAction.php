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
use Drupal\node\Entity\Node;



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

    $options = [];
    $mail_templates = \Drupal::entityQuery('node')->condition('type','mail_template')->execute();
    foreach ($mail_templates as $mail_template) {
      $node = Node::load($mail_template);
      $options[$mail_template] = $node->getTitle();
    }



    $form['template_choice'] = array(
      '#type' => 'radios',
      '#title' => t('Choose your mail template'),
      '#options' => $options,
     // '#default_value' => $form_state->getValue('template_choice'),
    );


    return $form;
  }


  /**
   * {@inheritdoc}
   */
  public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {

    kint($form_state->getValue('template_choice'));

    $nid = $form_state->getValue('template_choice');

    $node_storage = \Drupal::entityTypeManager()->getStorage('node');

    $template_chosen = $node_storage->load($nid);


    $this->configuration['template_choice_subject'] = $template_chosen->get('title')->value;


    $this->configuration['template_choice_body'] = $template_chosen->get('body')->value;

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
      $params['subject'] = $this->configuration["template_choice_subject"];
      $params['message'] = $this->configuration["template_choice_body"];

//      $params['message'] = $this->configuration["example_config_setting"];

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