<?php
/**
 * Created by PhpStorm.
 * User: lubuntu
 * Date: 18/10/18
 * Time: 15:00
 */



namespace Drupal\workshopfactory\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Drupal\views_bulk_operations\ViewsBulkOperationsEvent;


class MailingEventSubscriber implements EventSubscriberInterface {


  public static function getSubscribedEvents() {
    $events = [];
    // The next line prevents hard dependency on VBO module.
    if (class_exists(ViewsBulkOperationsEvent::class)) {
      $events['views_bulk_operations.view_data'][] = ['provideViewData', 0];
    }
    return $events;
  }

  /**
   * Provide entity type data and entity getter to VBO.
   *
   * @param \Drupal\views_bulk_operations\ViewsBulkOperationsEvent $event
   *   The event object.
   */
  public function provideViewData(ViewsBulkOperationsEvent $event) {
    if ($event->getProvider() === 'some_module') {
      $event->setEntityTypeIds(['node']);
      $event->setEntityGetter([
        'file' => drupal_get_path('module', 'some_module') . '/src/someClass.php',
        'callable' => '\Drupal\some_module\someClass::getEntityFromRow',
      ]);
    }
  }

}