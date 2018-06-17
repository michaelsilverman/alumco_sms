<?php

namespace Drupal\alumco_sms\EventSubscriber;

use Drupal\twilio\Event\ReceiveVoiceEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\Event;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
//use Drupal\srg_twilio\Event\TwilioEvents;
use Drupal\sms\event\SmsEvents;
use Drupal\sms\event\SmsMessageEvent;
use Drupal\srg_twilio\Event\ReceiveTextEvent;
//use Drupal\srg_twilio\Event\ReceiveVoiceEvent;
//use Drupal\srg_twilio\Event\SendTextEvent;
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

        public static function getSubscribedEvents() {
            $events[SmsEvents::MESSAGE_GATEWAY ][] = ['onMessageGatway'];
            $events[SmsEvents::MESSAGE_PRE_PROCESS][] = ['onMessagePreProcess'];
            $events[SmsEvents::MESSAGE_POST_PROCESS][] = ['onMessagePostProcess'];
            $events[SmsEvents::MESSAGE_INCOMING_POST_PROCESS][] = ['onMessageIncomingPostProcess'];
            $events[SmsEvents::MESSAGE_INCOMING_PRE_PROCESS][] = ['onMessageIncomingPreProcess'];
            $events[SmsEvents::MESSAGE_OUTGOING_POST_PROCESS][] = ['onMessageOutgoingPreProcess'];
            $events[SmsEvents::MESSAGE_OUTGOING_PRE_PROCESS][] = ['onMessageOutgoingPostProcess'];
            $events[SmsEvents::MESSAGE_QUEUE_POST_PROCESS][] = ['onMessageQueuePostProcess'];
            $events[SmsEvents::MESSAGE_QUEUE_PRE_PROCESS][] = ['onMessageQueuePreProcess'];

      //      $events[SmsEvents::MESSAGE_INCOMING_POST_PROCESS][] = ['onReceiveText'];
            return $events;
        }


    /**
     * This method is called whenever the twilio.receive_voice_event event is
     * dispatched.
     *
     * @param SmsMessageEvent $event
     */
    public function onMessageGateway(SmsMessageEvent $event) {
        $this->loggerFactory->get('alumco_sms')->notice('Message Gateway');
        // need to create entity to track the state of each message sent
        //each client will have their own event table
        // when message is sent create a state messgae for the "send" phone number
        // This will identify the message that was send
    }

    /**
     * This method is called whenever the twilio.receive_voice_event event is
     * dispatched.
     *
     * @param SmsMessageEvent $event
     */
    public function onMessagePreProcess(SmsMessageEvent $event) {
        $this->loggerFactory->get('alumco_sms')->notice('Message Pre Processxxxx');
        foreach($event->getMessages() as $message) {
            $gateway_id = $message->getGateway()->get('id');
            if ($gateway_id == 'gw_alumco') {
                $this->loggerFactory->get('alumco_sms3')->notice($gateway_id);
            }
            $text = $message->getMessage();
            $from_number = $message->getSenderNumber();
     //       $this->loggerFactory->get('alumco_sms1')->notice($from_number);
     //       $this->loggerFactory->get('alumco_sms2')->notice($text);
            $gateway = $message->getGateway();
      //      $this->loggerFactory->get('alumco_sms3')->notice($gateway_id);
            $plugin = $gateway->getPlugin();
            $plugin_id = $plugin->getPluginId();
     //       $this->loggerFactory->get('alumco_sms4')->notice($plugin_id);
            $plugin_conf = $plugin->getConfiguration();
            foreach ($plugin_conf as $key => $value) {
    //            $this->loggerFactory->get('alumco_sms5')->notice($key.'=>'.$value);
            }


        }
        // need to create entity to track the state of each message sent
        //each client will have their own event table
        // when message is sent create a state messgae for the "send" phone number
        // This will identify the message that was send
    }

    /**
     * This method is called whenever the twilio.receive_voice_event event is
     * dispatched.
     *
     * @param SmsMessageEvent $event
     */
    public function onMessagePostProcess(SmsMessageEvent $event) {
        $this->loggerFactory->get('alumco_sms')->notice('Message Post Process');
        // need to create entity to track the state of each message sent
        //each client will have their own event table
        // when message is sent create a state messgae for the "send" phone number
        // This will identify the message that was send
    }
    /**
     * This method is called whenever the twilio.receive_voice_event event is
     * dispatched.
     *
     * @param SmsMessageEvent $event
     */
    public function onMessageIncomingPreProcess(SmsMessageEvent $event) {
        $this->loggerFactory->get('alumco_sms')->notice('Message incoming Pre Process');
        // need to create entity to track the state of each message sent
        //each client will have their own event table
        // when message is sent create a state messgae for the "send" phone number
        // This will identify the message that was send
    }
    /**
     * This method is called whenever the twilio.receive_voice_event event is
     * dispatched.
     *
     * @param SmsMessageEvent $event
     */
    public function onMessageIncomingPostProcess(SmsMessageEvent $event) {
        $this->loggerFactory->get('alumco_sms')->notice('Message Incoming Post Process');
        // need to create entity to track the state of each message sent
        //each client will have their own event table
        // when message is sent create a state messgae for the "send" phone number
        // This will identify the message that was send
    }
    /**
     * This method is called whenever the twilio.receive_voice_event event is
     * dispatched.
     *
     * @param SmsMessageEvent $event
     */
    public function onMessageOutgoingPre(SmsMessageEvent $event) {
        $this->loggerFactory->get('alumco_sms')->notice('Message Outgoing Pre');
        // need to create entity to track the state of each message sent
        //each client will have their own event table
        // when message is sent create a state messgae for the "send" phone number
        // This will identify the message that was send
    }
    /**
     * This method is called whenever the twilio.receive_voice_event event is
     * dispatched.
     *
     * @param SmsMessageEvent $event
     */
    public function onMessageOutgoingPost(SmsMessageEvent $event) {
        $this->loggerFactory->get('alumco_sms')->notice('Message Outgoing Post');
        // need to create entity to track the state of each message sent
        //each client will have their own event table
        // when message is sent create a state messgae for the "send" phone number
        // This will identify the message that was send
    }
    /**
     * This method is called whenever the twilio.receive_voice_event event is
     * dispatched.
     *
     * @param SmsMessageEvent $event
     */
    public function xxonMessagePreProcess(SmsMessageEvent $event) {
        $this->loggerFactory->get('alumco_sms')->notice('Message Pre Process');
        // need to create entity to track the state of each message sent
        //each client will have their own event table
        // when message is sent create a state messgae for the "send" phone number
        // This will identify the message that was send
    }
    /**
     * This method is called whenever the twilio.receive_voice_event event is
     * dispatched.
     *
     * @param SmsMessageEvent $event
     */
    public function xxonMessagePostProcess(SmsMessageEvent $event) {
        $this->loggerFactory->get('alumco_sms')->notice('Message Pre Process');
        // need to create entity to track the state of each message sent
        //each client will have their own event table
        // when message is sent create a state messgae for the "send" phone number
        // This will identify the message that was send
    }
    /**
     * This method is called whenever the twilio.receive_voice_event event is
     * dispatched.
     *
     * @param SmsMessageEvent $event
     */
    public function onMessageQueuePreProcess(SmsMessageEvent $event) {
        $this->loggerFactory->get('alumco_sms')->notice('Message Queue Pre Process');
        // need to create entity to track the state of each message sent
        //each client will have their own event table
        // when message is sent create a state messgae for the "send" phone number
        // This will identify the message that was send
    }

    /**
     * This method is called whenever the twilio.receive_voice_event event is
     * dispatched.
     *
     * @param SmsMessageEvent $event
     */
    public function onMessageQueuePostProcess(SmsMessageEvent $event) {
        $this->loggerFactory->get('alumco_sms')->notice('Message Queue Post Process');
        // need to create entity to track the state of each message sent
        //each client will have their own event table
        // when message is sent create a state messgae for the "send" phone number
        // This will identify the message that was send
    }
  /**
   * This method is called whenever the twilio.receive_text_event event is
   * dispatched.
   *
   * @param Event $event
   */
  public function onReceiveText(Event $event) {
      // test if the phone number is this clients

      $this->loggerFactory->get('alumco_sms0')->notice('fred');
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
