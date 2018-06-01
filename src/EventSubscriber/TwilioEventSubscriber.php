<?php

namespace Drupal\alumco_sms\EventSubscriber;

use Drupal\twilio\Event\ReceiveVoiceEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
//use Symfony\Component\EventDispatcher\Event;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\srg_twilio\Event\TwilioEvents;
use Drupal\srg_twilio\Event\ReceiveTextEvent;
//use Drupal\srg_twilio\Event\ReceiveVoiceEvent;
use Drupal\srg_twilio\Event\SendTextEvent;
//use Drupal\srg_twilio\Event\SendVoiceEvent;

/**
 * Class ReceiveTextSubscriber.
 */
class TwilioEventSubscriber implements EventSubscriberInterface {

  /**
   * Drupal\Core\Logger\LoggerChannelFactoryInterface definition.
   *
   * @var \Drupal\Core\Logger\LoggerChannelFactoryInterface
   */
  protected $loggerFactory;

  /**
   * Constructs a new ReceiveTextSubscriber object.
   */
  public function __construct(LoggerChannelFactoryInterface $logger_factory) {
    $this->loggerFactory = $logger_factory;
  }

  /**
   * {@inheritdoc}
   */



    static function getSubscribedEvents() {
        $events[TwilioEvents::SEND_TEXT_EVENT][] = array('onSendText');
        $events[TwilioEvents::RECEIVE_TEXT_EVENT][] = array('onReceiveText');
   //     $events[TwilioEvents::SEND_VOICE_EVENT][] = array('onSendVoice');
   //     $events[TwilioEvents::RECEIVE_VOICE_EVENT][] = array('onReceiveVoice');
        return $events;
    }

    /**
     * This method is called whenever the twilio.receive_voice_event event is
     * dispatched.
     *
     * @param SendTextEvent $event
     */
    public function onSendText(SendTextEvent $event) {
        $this->loggerFactory->get('alumco_sms')->notice('Send Text');
        // need to create entity to track the state of each message sent
        //each client will have their own event table
        // when message is sent create a state messgae for the "send" phone number
        // This will identify the message that was send
    }

  /**
   * This method is called whenever the twilio.receive_text_event event is
   * dispatched.
   *
   * @param ReceiveTextEvent $event
   */
  public function onReceiveText(ReceiveTextEvent $event) {
      // test if the phone number is this clients


      $client_name = 'Aluminum Company';
      $query = \Drupal::entityQuery('node')
          ->condition('status', 1)
          ->condition('type', 'sms_company')
          ->condition('title', $client_name, '=');
      $nids = $query->execute();
      if (empty($nids)) {
          $this->loggerFactory->get('twilio')->debug
          ('The client record was not found or has missing or incorrect information @client_name.', [
                  '@client_name' => $client_name,
          ]);
          return;
      }

      $node = node_load(reset($nids));
      $client_nbr = preg_replace("/[^0-9,.]/", "", $node->field_sms_phone_number->value);
      $to_number = str_replace('+1', '',$event->getPackage()->getTo() );
      $this->loggerFactory->get('alumco_sms0')->notice($client_nbr.'=>'.$event->getPackage()->getTo());
  //    $this->loggerFactory->get('alumco_sms2')->notice($event->getPackage()->getFrom().$event->getPackage()->getTo().'->'.$event->getPackage()->getMessage());

      if ($to_number !== $client_nbr) {
          return;
      }
// look up from number
      $this->loggerFactory->get('alumco_sms3')->notice($event->getPackage()->getTo().$node->field_sms_phone_number->value);
    //   Message received
    // if sent record exists - update record based on respons
    // if sent record does not exist - read message and
      // yes - read message and update

      /*
       * messages
       */

  }
  /**
   * This method is called whenever the twilio.receive_voice_event event is
   * dispatched.
   *
   * @param ReceiveVoiceEvent $event
   */
  public function onReceiveVoice(ReceiveVoiceEvent $event) {
      $this->loggerFactory->get('alumco_sms')->notice('Receive Voice');
  }

    /**
     * This method is called whenever the twilio.receive_voice_event event is
     * dispatched.
     *
     * @param SendVoiceEvent $event
     */
    public function onSendVoice(SendVoiceEvent $event) {
        $this->loggerFactory->get('alumco_sms')->notice('Send Voice');
    }

}
