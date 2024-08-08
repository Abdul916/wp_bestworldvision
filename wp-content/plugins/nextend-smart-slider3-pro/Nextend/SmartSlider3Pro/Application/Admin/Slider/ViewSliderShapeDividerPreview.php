<?php

namespace Nextend\SmartSlider3Pro\Application\Admin\Slider;


use Nextend\Framework\View\AbstractView;
use Nextend\SmartSlider3\Application\Admin\Layout\LayoutEmpty;
use Nextend\SmartSlider3\SliderManager\SliderManager;

class ViewSliderShapeDividerPreview extends AbstractView {

    /** @var integer */
    protected $sliderID;

    /** @var array */
    protected $sliderData;

    public function display() {
        $this->layout = new LayoutEmpty($this);

        $this->layout->addContent($this->render('ShapeDividerPreview'));

        $this->layout->render();

    }

    /**
     * @return int
     */
    public function getSliderID() {
        return $this->sliderID;
    }

    /**
     * @param int $sliderID
     */
    public function setSliderID($sliderID) {
        $this->sliderID = $sliderID;
    }

    /**
     * @return array
     */
    public function getSliderData() {
        return $this->sliderData;
    }

    /**
     * @param array $sliderData
     */
    public function setSliderData($sliderData) {
        $this->sliderData = $sliderData;
    }

    /**
     * @return string contains escaped html data
     */
    public function renderSlider() {

        $locale = setlocale(LC_NUMERIC, 0);
        setlocale(LC_NUMERIC, "C");

        $sliderManager = new SliderManager($this, $this->sliderID, true, array(
            'sliderData' => $this->getSliderData()
        ));
        $sliderManager->allowDisplayWhenEmpty();

        $sliderHTML = $sliderManager->render();

        setlocale(LC_NUMERIC, $locale);

        return $sliderHTML;
    }
}