<?php

namespace Nextend\SmartSlider3Pro\Generator\Common\Instagram;

use Joomla\CMS\Uri\Uri;
use Nextend\Framework\Browse\BulletProof\Exception;
use Nextend\Framework\Data\Data;
use Nextend\Framework\Form\Container\ContainerTable;
use Nextend\Framework\Form\Element\Message\Notice;
use Nextend\Framework\Form\Element\Message\Warning;
use Nextend\Framework\Form\Element\Text;
use Nextend\Framework\Form\Element\Token;
use Nextend\Framework\Form\Form;
use Nextend\Framework\Model\StorageSectionManager;
use Nextend\Framework\Notification\Notification;
use Nextend\Framework\Platform\Platform;
use Nextend\Framework\Request\Request;
use Nextend\SmartSlider3\Generator\AbstractGeneratorGroupConfiguration;
use Nextend\SmartSlider3Pro\Generator\Common\Instagram\api\InstagramBasicDisplay;
use Nextend\SmartSlider3Pro\Generator\Common\Instagram\Elements\InstagramRefreshToken;
use Nextend\SmartSlider3Pro\Generator\Common\Instagram\Elements\InstagramToken;

class ConfigurationInstagram extends AbstractGeneratorGroupConfiguration {

    private $data;

    /**
     * N2SliderGeneratorinstagramConfiguration constructor.
     *
     * @param GeneratorGroupInstagram $group
     */
    public function __construct($group) {
        parent::__construct($group);
        $this->data = new Data(array(
            'appId'       => '',
            'secret'      => '',
            'accessToken' => '',
            'expiresAt'   => ''
        ));

        $this->data->loadJSON(StorageSectionManager::getStorage('smartslider')
                                                   ->get('instagram'));
    }

    public function wellConfigured() {
        if (!$this->data->get('appId') || !$this->data->get('secret') || !$this->data->get('accessToken')) {
            return false;
        }

        $api = $this->getApi();
        try {
            $api->getUserProfile();

            return true;
        } catch (Exception $e) {
            Notification::error($e->getMessage());

            return false;
        }
    }

    public function getApi() {
        $appId       = $this->data->get('appId');
        $appSecret   = $this->data->get('secret');
        $accessToken = $this->data->get('accessToken');

        if (!empty($appId) && !empty($appSecret)) {
            $api = new InstagramBasicDisplay(array(
                'appId'       => $appId,
                'appSecret'   => $appSecret,
                'redirectUri' => $this->getCallbackUrl()
            ));

            if (!empty($accessToken)) {
                $api->setAccessToken($accessToken);
            }

            return $api;
        } else if (!empty($appId) && empty($this->data->get('secret'))) {
            throw new Exception(n2_('The secret is empty. Please insert that value too!'));
        } else if (empty($appId) && !empty($this->data->get('secret'))) {
            throw new Exception(n2_('The App ID is empty. Please insert that value too!'));
        } else {
            throw new Exception(n2_('The App ID and the Secret is empty!'));
        }

    }

    public function getData() {
        return $this->data->toArray();
    }

    public function addData($data, $store = true) {
        $this->data->loadArray($data);
        if ($store) {
            StorageSectionManager::getStorage('smartslider')
                                 ->set('instagram', null, json_encode($this->data->toArray()));
        }
    }

    public function render($MVCHelper) {

        $form = new Form($MVCHelper, 'generator');
        $form->loadArray($this->getData());
        $table       = new ContainerTable($form->getContainer(), 'instagram-api', 'Instagram api');
        $callBackUrl = $this->getCallbackUrl();

        if (substr($callBackUrl, 0, 8) !== 'https://') {
            $url = "https://www.wpbeginner.com/wp-tutorials/how-to-add-ssl-and-https-in-wordpress/";
            $warning     = $table->createRow('instagram-warning');
            $warningText = sprintf(n2_('%1$s allows HTTPS Redirect URIs only! You must move your site to HTTPS in order to use this generator!'), 'Instagram') . ' - <a href="' . $url . '" target="_blank" rel="nofollow noopener noreferrer">' . n2_('How to get SSL for my website?') . '</a>';
            new Warning($warning, 'warning', $warningText);
        } else {
            $instruction     = $table->createRow('instagram-instruction');
            $instructionText = sprintf(n2_('%2$s Check the documentation %3$s to learn how to configure your %1$s app.'), 'Instagram', '<a href="https://smartslider.helpscoutdocs.com/article/2052-instagram-generator" target="_blank">', '</a>');
            new Notice($instruction, 'instruction', n2_('Instruction'), $instructionText);
        }

        $expDate = $this->data->get('expiresAt');
        if ($this->data->get('accessToken') && $expDate) {
            $exp     = $table->createRow('instagram-exp-warning');
            $expDate = $this->data->get('expiresAt');
            $this->checkExpire($table, $exp);
            new Notice($exp, 'expires', n2_('The token will expire at:'), Platform::localizeDate($expDate));
        }
        $settings = $table->createRow('instagram');
        new Text($settings, 'appId', 'App ID', '', array(
            'style' => 'width:120px;'
        ));
        new Text($settings, 'secret', 'Secret', '', array(
            'style' => 'width:250px;'
        ));

        new InstagramToken($settings, 'accessToken', n2_('Token'));
        new Notice($settings, 'callback', n2_('Callback url'), $callBackUrl);

        new Token($settings);

        $form->render();


    }

    public function refreshToken($MVCHelper) {
        $api   = $this->getApi();
        $token = $api->refreshToken($this->data->get('accessToken'));

        $api->setAccessToken($token->access_token);
        try {
            $user = $api->getUserProfile();
            if ($user) {
                $data                = $this->getData();
                $data['accessToken'] = $token->access_token;
                $data['expiresAt']   = time() + $token->expires_in;
                $this->addData($data);

                return true;
            }

            return false;
        } catch (Exception $e) {
            return $e;
        }

    }

    public function startAuth($MVCHelper) {

        if (session_id() == "") {
            session_start();
        }
        $this->addData(Request::$REQUEST->getVar('generator'), false);

        $_SESSION['data'] = $this->getData();

        $api = $this->getApi();

        $_SESSION['instagramstate'] = $this->generateRandomState();

        return $api->getLoginUrl(array(
            'user_profile',
            'user_media'
        ), $_SESSION['instagramstate']);
    }

    public function finishAuth($MVCHelper) {
        if (session_id() == "") {
            session_start();
        }

        if (Request::$REQUEST->getVar('state') !== null && isset($_SESSION['instagramstate']) && Request::$REQUEST->getVar('state') == $_SESSION['instagramstate']) {
            $this->addData($_SESSION['data'], false);
            unset($_SESSION['data']);

            $code  = Request::$GET->getVar('code');
            $api   = $this->getApi();
            $token = $api->getOAuthToken($code, true);
            if ($token) {
                $token = $api->getLongLivedToken($token, false);
                $api->setAccessToken($token->access_token);
                try {
                    $user = $api->getUserProfile();
                    if ($user) {
                        $data                = $this->getData();
                        $data['accessToken'] = $token->access_token;
                        $data['expiresAt']   = time() + $token->expires_in;
                        $this->addData($data);

                        return true;
                    }

                    return false;
                } catch (Exception $e) {
                    return $e;
                }
            }

            return new Exception(n2_('Access token was not returned.Please check the credentials!'));

        } else {
            return new Exception(n2_('State does not match!'));
        }
    }


    public function checkExpire($container, $group = null) {
        $expDate = $this->data->get('expiresAt');
        $now     = Platform::getTimestamp();
        if ($expDate && $expDate - (2 * 24 * 60 * 60) < $now) {
            if (!$group) {
                $errorGroup = new ContainerTable($container, 'instagram-api', 'Token Expiration');
                $group      = $errorGroup->createRow('error');
            }
            if ($expDate <= $now) {
                Notification::error(n2_('The token expired. Please request new token! '));
                new Warning($group, 'expires', n2_('The token expired. Please request new token!'));

                return false;
            } else {
                new Warning($group, 'expires', n2_('The token will expire in two days! Please refresh the token!'));
                new InstagramRefreshToken($group, 'refreshToken', n2_('Refresh Token'), n2_('Refresh'));
            }
        }

        return true;

    }

    private function getCallbackUrl() {
        return rest_url('smart-slider-3/v1/instagram/authorize');
    }
}
