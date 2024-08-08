<?php


namespace Nextend\SmartSlider3Pro\Application\Admin\Slider;

use Nextend\Framework\Request\Request;
use Nextend\SmartSlider3\Application\Admin\Slider\ControllerSlider;

class PluggedControllerSlider {

    /** @var ControllerSlider */
    protected $controller;

    public function __construct($controller) {
        $this->controller = $controller;

        $this->controller->addExternalAction('editGroup', array(
            $this,
            'actionEditGroup'
        ));

        $this->controller->addExternalAction('shapedivider', array(
            $this,
            'actionShapeDivider'
        ));

        $this->controller->addExternalAction('shapedividerpreview', array(
            $this,
            'actionShapeDividerPreview'
        ));

        $this->controller->addExternalAction('particle', array(
            $this,
            'actionParticle'
        ));
    }

    /**
     * @param array $slider
     */
    public function actionEditGroup($slider = array()) {
        if (empty($slider)) {
            $this->controller->redirectToSliders();
        }

        $this->controller->loadSliderManager();

        $view = new ViewSliderEditGroup($this->controller);
        $view->setSlider($slider);
        $view->display();
    }

    public function actionShapeDivider() {
        if ($this->controller->validateToken() && $this->controller->validatePermission('smartslider_edit')) {

            $view = new ViewSliderShapeDivider($this->controller);
            $view->setSliderID($this->controller->getSliderID());
            $view->display();

        }
    }

    public function actionShapeDividerPreview() {

        if ($this->controller->validateToken() && $this->controller->validatePermission('smartslider_edit')) {
            $view = new ViewSliderShapeDividerPreview($this->controller);
            $view->setSliderData(json_decode(Request::$POST->getVar('sliderData', '[]'), true));
            $view->setSliderID($this->controller->getSliderID());

            $view->display();
        }
    }

    public function actionParticle() {
        if ($this->controller->validateToken() && $this->controller->validatePermission('smartslider_edit')) {

            $view = new ViewSliderParticle($this->controller);
            $view->setSliderID($this->controller->getSliderID());
            $view->display();

        }
    }
}