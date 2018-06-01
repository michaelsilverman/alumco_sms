<?php

namespace Drupal\alumco_sms\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Component\Serialization\Json;
//use Drupal\Component\Utility\SafeMarkup;
//use Drupal\twilio\Entity\TwilioSMS;
//use Twilio\Rest\Client;
//use Drupal\twilio\Event\ReceiveTextEvent;
//use Drupal\twilio\Event\ReceiveVoiceEvent;
//use Drupal\twilio\Event\TwilioEvents;
//use Twilio\Twiml;
use Drupal\srg_twilio\Services\ConvertMessage;
use GuzzleHttp\Exception\ConnectException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\srg_twilio\Services\Command;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
//use Symfony\Component\HttpFoundation\Response;

/**
 * Default controller for the alumco  module.
 */
class smsAlumcoController extends ControllerBase {
    /*
     * @param LoggerChannelFactoryInterface $loggerFactory
     */
    protected  $loggerFactory;
    protected $convertMessage;

    public function __construct(Command $command, LoggerChannelFactoryInterface $loggerFactory, ConvertMessage $convertMessage) {
        $this->command = $command;
        $this->convertMessage = $convertMessage;
        $this->loggerFactory = $loggerFactory;
    }
    public static function create(ContainerInterface $container) {
        $command = $container->get('srg_twilio.command');
        $convertMessage = $container->get('srg_twilio.message');
        $loggerFactory = $container->get('logger.factory');
        return new static($command, $loggerFactory, $convertMessage);
    }

    public function getServiceCalls($date) {
        $config = \Drupal::config('alumco_sms.settings');
        $url = $this->config('alumco_sms.settings')->get('api_uri');
        //'http://52.41.235.57:8882/api/ServiceCalls?date=2018-05-18';
        try {
            $response = \Drupal::httpClient()->get($url, array('headers' => array('Accept' => 'application/json')));
            $data = (string) $response->getBody();
            if (empty($data)) {
                $this->loggerFactory->get('alumco_sms')->debug
                ('The REST target was not found:  @url.', [
                    '@url' => $url,
                ]);
                $markup = [
                    '#markup' => 'Error: the REST target was no found',
                ];
                return $markup;
            }
        }
        catch (ConnectException $e) {
            $this->loggerFactory->get('alumco_sms')->emergency
            ('An error was encountered trying to access "@url"', [
                '@url' => $url,
            ]);
        }
        $service_calls = Json::decode($data);
        foreach ($service_calls as $service_call) {
            $service_id = $service_call['ServiceCallId'];
            $job_number = $service_call['JobNumber'];
            $cust_name = $service_call['CustomerName'];
            $address = $service_call['Address'];
            $appt_time = $service_call['AppointmentTime'];
            $appt_date = $service_call['AppointmentDate'];
            foreach ($service_call['PhoneNumbers'] as $phone_number) {
                if (strpos($phone_number['Type'], 'Mobile' ) > 1) {
                    $mobile_number = str_replace('-','', $phone_number['Number']);
                    break;
                } else
                {
                    $mobile_number = 'not found';
                }
            }
            if ($mobile_number !== 'not found') {
                $index = 0;
                foreach ($service_call['ServiceTechs'] as $service_tech) {
                    $techs[$index]['name'] = $service_tech['Name'];
                    $position = strpos($service_tech['Email'], '@aluminumcompany.com');
                    $techs[$index]['userid'] = substr($service_tech['Email'], 0,$position);
                    $techs[$index]['phone'] = str_replace('-','',$service_tech['MobilePhone']);
                    $index++;
                }
                $message_nid = 2;

                $replacement_array = array(
                    'service id' => $service_id,
                    'job number' => $job_number,
                    'customer name' => $cust_name,
                    'appointment time' => $appt_time,
                    'appointment date' => $appt_date,
                    'service rep' => $techs['0']['name'],
                    'address' => $address,
                );
                $message_text = ConvertMessage::getMessage($message_nid, $replacement_array);
                $client_name = 'Aluminum Company';
                $client_info = new Command($client_name);
                $image_file = strtolower(str_replace(' ', '-',$service_tech['Name']));
                $uri = $this->config('alumco_sms.settings')->get('image_default_address').$image_file.'.jpg';

                dpm($message_text, $mobile_number);
                if ($this::checkForImage($uri)) {
                    $client_info->messageSend($mobile_number, $message_text['value'], $uri);
                } else {
                    $client_info->messageSend($mobile_number, $message_text['value']);
                }

            }
        }

        $markup = [
            '#markup' => 'SMS Message received',
        ];
        return $markup;

    }

//    public function sendMessages($dist_listID=1, $messageID=2) {
    public function sendMessages($service_call)
    {
        $client_name = 'Aluminum Company';
        $client_info = new Command($client_name);
    //    $node = node_load($messageID);
    //    $company_id = $node->get('field_sms_company')->getValue();
    //    $message = $node->get('field_sms_message')->getValue();
    //    $message_text = $message[0]['value'];
    //    $image = $node->get('field_sms_image_link')->getValue();
   //     $image_url = $image[0]['uri'];
        // sami http://aluminumcompany.com/sites/all/themes/aluminum_company/images/meet_jeff_monsein.jpg
        //'http://aluminumcompany.com/sites/all/themes/aluminum_company/images/meet_sami_hanna.jpg'];
        $distribution_list[] = ['number' => '6308999711', 'name' => 'Michael'];
        //dpm($distribution_list, 'dist');
        foreach ($distribution_list as $distribution_item) {
     //       $client_info->messageSend($distribution_item['number'], $message_text, $image_url);
        }

        $markup = [
            '#markup' => 'Text send to: ' . $distribution_list['number'],
        ];
        return $markup;
    }

  /**
   * Handle incoming SMS message requests.
   *
   * @todo Needs Work.
   */

  public function checkForImage($url) {
      $header_response = get_headers($url, 1);
      if ( strpos( $header_response[0], "404" ) === FALSE )
      {
          // FILE EXISTS!!
      }
      else
      {

          $url = FALSE;
      }


      return $url;
  }

  /**
   * Handle incoming voice requests.
   *
   * @todo Needs Work.
   */
  public function receiveVoice() {
      $twilio_message = new TwilioSMS($_REQUEST);
      $event = new ReceiveVoiceEvent($twilio_message);
      $dispatcher = \Drupal::service('event_dispatcher');
      $dispatcher->dispatch(TwilioEvents::RECEIVE_VOICE_EVENT, $event);

      $response = new Response();
      $twiml = new Twiml();
      $twiml->say('Hello World');
      $gather = $twiml->gather(['input' => 'speech dtmf', 'timeout' => 3,
          'numDigits' => 1, 'action' => '/twilio/test1']);
      $gather->say('Please press 1 or say sales for sales.');

      $response->setContent($twiml);
      return $response;

}
    /**
   * Invokes twilio_message_incoming hook.
   *
   * @param string $number
   *   The sender's mobile number.
   * @param string $number_twilio
   *   The twilio recipient number.
   * @param string $message
   *   The content of the text message.
   * @param array $media
   *   The absolute media url for a media file attatched to the message.
   * @param array $options
   *   Options array.
   */
  public function xxmessageIncoming($number, $number_twilio, $message, array $media = array(), array $options = array()) {
    // Build our SMS array to be used by our hook and rules event.
    $sms = array(
      'number' => $number,
      'number_twilio' => $number_twilio,
      'message' => $message,
      'media' => $media,
    );
    // Invoke a hook for the incoming message so other modules can do things.
    $this->moduleHandler()->invokeAll('twilio_message_incoming', [$sms, $options]);
    if ($this->moduleHandler()->moduleExists('rules')) {
      rules_invoke_event('twilio_message_incoming', $sms);
    }
  }

  /**
   * Invokes twilio_voice_incoming hook.
   *
   * @param string $company_name
   *   The sender's company name.

   */
  public function getCompany() {
      $client_name = 'Aluminum Company';
      $fred = new Command($client_name);
      $number = $fred->getNumber();
      $token = $fred->getToken();
      $sid = $fred->getSid();
      $markup = [
          '#markup' => $number.' '.$token.' '.$sid,
      ];
      return $markup;

  }



}
