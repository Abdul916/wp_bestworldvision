<?php

namespace Nextend\SmartSlider3Pro\Generator\Common\Instagram;

use Nextend\SmartSlider3\Application\ApplicationSmartSlider3;
use WP_REST_Server;
use Exception;


class GeneratorGroupRESTInstagram {

    public function __construct() {
        add_action('rest_api_init', array(
            $this,
            'registerInstagramRedirectRESTRoute'
        ));
    
    }

    public function registerInstagramRedirectRESTRoute() {
        register_rest_route('smart-slider-3/v1', '/instagram/authorize', array(
            'methods'             => WP_REST_Server::READABLE,
            'callback'            => array(
                $this,
                'redirectToInstagramEndpointWithStateAndCode'
            ),
            'args'                => array(
                'state' => array(
                    'required' => true,
                ),
                'code'  => array(
                    'required' => true,
                )
            ),
            'permission_callback' => '__return_true',
        ));
    
    }


    public function redirectToInstagramEndpointWithStateAndCode($request) {
        $params = $request->get_params();

        if (!empty($params['state']) && !empty($params['code'])) {

            $realCallbackUrl = ApplicationSmartSlider3::getInstance()
                                                      ->getApplicationTypeAdmin()
                                                      ->createUrl(array(
                                                          "generator/finishAuth",
                                                          array(
                                                              'group' => 'instagram',
                                                              'state' => $params['state'],
                                                              'code'  => $params['code']
                                                          )
                                                      ));

            wp_safe_redirect($realCallbackUrl);
            exit;
        } else {

            return new Exception(n2_('State or code parameter is empty!'));
        }
    
    }
}