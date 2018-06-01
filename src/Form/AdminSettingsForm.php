<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * @file
 * Contains \Drupal\alumco_sms\Form\RSSSettingsForm
 */

namespace Drupal\alumco_sms\Form;

use Drupal\Component\Utility\UrlHelper;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\HttpFoundation\Request;
//Drupal\Core\Form\FormStateInterface $form_state

/**
 * Defines a form to configure ABT RSS module settings
 */

class AdminSettingsForm  extends ConfigFormBase
{
    /**
     * {@inheritdoc}
     */
    public function getFormId()
    {
        return 'alumco_sms_admin_settings';
    }
    /**
     * {@inheritdoc}
     */
    public function getEditableConfigNames()
    {
        return [
            'alumco_sms.settings'
            ];
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state, Request $request = NULL) {
  //      $config = \Drupal::config('alumco_sms.settings');

        $url_location = $this->config('alumco_sms.settings')->get('image_default_address');
        $target_server = $this->config('alumco_sms.settings')->get('api_uri');

 //       $report_email = $this->config('alumco_sms.settings')->get('default_email');
 //       $rep_array = $this->config('alumco_sms.settings')->get('rep_names');
 //       $outcomes = $this->config('alumco_sms.settings')->get('outcomes');


        $form['image_location'] = array(
            '#title' => 'Image URL location prefix',
            '#description' => '',
            '#type' => 'textfield',
            '#default_value' => $url_location,
            '#size' => '80',
            '#attributes' => array(
                'class' => array(''),
            ),
        );
       $form['target_server'] = array(
            '#title' => 'Target server',
            '#type' => 'textfield',
            '#default_value' => $target_server,
            '#size' => '80',
            '#attributes' => array(
                'class' => array(''),
            ),
        );
 /*       $form['rep_names'] = [
            '#title' => 'Representative List',
            '#type' => 'textarea',
            '#rows' => '10',
            '#cols' => '50',
            '#resizable' => 'both',
            '#default_value' => $rep_array,
            '#attributes' => array(
                'class' => array(''),
            ),
        ];
        $form['outcomes'] = [
            '#title' => 'Outcome List',
            '#type' => 'textarea',
            '#rows' => '10',
            '#cols' => '50',
            '#resizable' => 'both',
            '#default_value' => $outcomes,
            '#attributes' => array(
                'class' => array(''),
            ),
        ]; */
        return parent::buildForm($form, $form_state);
    }
    public function validateForm(array &$form, FormStateInterface $form_state) {
        $url = $form_state->getValue('image_location');


     //   if(!EmailHUrlHelper::isValid($url)) {
     //       drupal_set_message('The RSS feed is not a valid URL', 'error');
     //   }
    }

    /**
     * {@inheritdoc}.
     */
    public function submitForm(array &$form, FormStateInterface $form_state) {
        \Drupal::configFactory()->getEditable('alumco_sms.settings')
            ->set('image_default_address', $form_state->getValue('image_location'))
            ->save();

        \Drupal::configFactory()->getEditable('alumco_sms.settings')
            ->set('api_uri', $form_state->getValue('target_server'))
            ->save();

        parent::submitForm($form, $form_state);
    }
}