<?php
/**
* @file
* Contains \Drupal\workshopfactory\Plugin\Mail\WorkshopfactoryMail.
*/
namespace Drupal\workshopfactory\Plugin\Mail;


use Drupal\Core\Mail\MailInterface;
use Drupal\Core\Mail\Plugin\Mail\PhpMail;
use Drupal\Core\Render\Markup;
use Drupal\Core\Site\Settings;
use Drupal\Component\Render\MarkupInterface;
use Drupal\Component\Utility\Html;
use Drupal\Core\Render\Renderer;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;

/**
* Defines the plugin Mail.
*
* @Mail(
*   id = "workshopfactory_mail",
*   label = @Translation("Workshopfactory HTML mailer"),
*   description = @Translation("Sends an HTML email")
* )
*/
class WorkshopfactoryMail extends PHPMailer implements MailInterface, ContainerFactoryPluginInterface {

/**
* @var \Drupal\Core\Render\Renderer;
*/
protected $renderer;

/**
* MyModuleMail constructor.
*
* @param \Drupal\Core\Render\Renderer $renderer
*   The service renderer.
*/
function __construct(Renderer $renderer) {
$this->renderer = $renderer;
}

/**
* {@inheritdoc}
*/
public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
  return new static(
    $container->get('renderer')
  );
}

/**
* {@inheritdoc}
*/
public function format(array $message) {

// $message = $this->cleanBody($message);
$message['options']['texte'] = $message['body'];

$render = [
//'#theme' => 'workshopfactory_courriel',
'#message' => $message,
];
$message['body'] = $this->renderer->renderRoot($render);
return $message;
}

/**
* {@inheritdoc}
*/
public function mail(array $message) {

//    return parent::Send($message);

  return parent::mail($message);
}

}