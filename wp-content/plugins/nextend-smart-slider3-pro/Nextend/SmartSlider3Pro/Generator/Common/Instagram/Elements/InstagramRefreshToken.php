<?php

namespace Nextend\SmartSlider3Pro\Generator\Common\Instagram\Elements;

use Nextend\Framework\Asset\Js\Js;
use Nextend\Framework\Form\Element\Button;
use Nextend\Framework\Request\Request;


class InstagramRefreshToken extends Button {

    protected function fetchElement() {

        $authUrl = $this->getForm()
                        ->createAjaxUrl(array(
                            "generator/getRefresh",
                            array(
                                'group' => Request::$REQUEST->getVar('group'),
                                'type'  => Request::$REQUEST->getVar('type')
                            )
                        ));

        Js::addInline('new _N2.FormElementInstagramRefreshToken("' . $this->fieldID . '", "' . $authUrl . '");');

        return parent::fetchElement();
    }
}


