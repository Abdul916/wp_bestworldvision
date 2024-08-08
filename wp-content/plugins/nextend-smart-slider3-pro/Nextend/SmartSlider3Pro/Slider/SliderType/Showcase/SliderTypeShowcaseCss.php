<?php


namespace Nextend\SmartSlider3Pro\Slider\SliderType\Showcase;


use Nextend\Framework\Parser\Color;
use Nextend\SmartSlider3\Slider\SliderType\AbstractSliderTypeCss;

class SliderTypeShowcaseCss extends AbstractSliderTypeCss {

    public function __construct($slider) {
        parent::__construct($slider);
        $params = $this->slider->params;

        switch ($params->get('animation-direction')) {
            case 'vertical':
                $this->context['distanceh'] = 0;
                $this->context['distancev'] = intval($params->get('slide-distance')) . 'px';
                break;
            default:
                $this->context['distancev'] = 0;
                $this->context['distanceh'] = intval($params->get('slide-distance')) . 'px';
        }


        $this->context['perspective'] = intval($params->get('perspective')) . 'px';


        $width  = intval($this->context['width']);
        $height = intval($this->context['height']);

        $this->context['sliderwidth'] = $width . 'px';

        $this->context['backgroundSize']       = $params->getIfEmpty('background-size', 'inherit');
        $this->context['backgroundAttachment'] = $params->get('background-fixed') ? 'fixed' : 'scroll';

        $borderWidth                   = $params->getIfEmpty('border-width', 0);
        $borderColor                   = $params->get('border-color');
        $this->context['borderRadius'] = $params->get('border-radius') . 'px';


        $this->context['border'] = $borderWidth . 'px';

        $rgba                        = Color::hex2rgba($borderColor);
        $this->context['borderrgba'] = 'RGBA(' . $rgba[0] . ',' . $rgba[1] . ',' . $rgba[2] . ',' . round($rgba[3] / 127, 2) . ')';

        $width  = $width - $borderWidth * 2;
        $height = $height - $borderWidth * 2;

        $this->context['slideBorderRadius'] = $params->get('slide-border-radius') . 'px';

        $slideBorderWidth                  = max(0, $params->get('slide-border-width', 0));
        $this->context['slideborderwidth'] = $slideBorderWidth . 'px';

        $rgba                              = Color::hex2rgba($params->get('slide-border-color'));
        $this->context['slidebordercolor'] = 'RGBA(' . $rgba[0] . ',' . $rgba[1] . ',' . $rgba[2] . ',' . round($rgba[3] / 127, 2) . ')';


        $slideWidth  = min($width, max(50, intval($params->get('slide-width'))));
        $slideHeight = min($height, max(50, intval($params->get('slide-height'))));

        $this->context['slideouterwidth']  = $slideWidth . 'px';
        $this->context['slideouterheight'] = $slideHeight . 'px';

        $this->context['verticalmargin']   = round(($height - $slideHeight) / 2) . 'px';
        $this->context['horizontalmargin'] = round(($width - $slideWidth) / 2) . 'px';

        $this->context['canvaswidth']  = $slideWidth - 2 * $slideBorderWidth . 'px';
        $this->context['canvasheight'] = $slideHeight - 2 * $slideBorderWidth . 'px';


        $this->initSizes();

        $this->slider->addLess(SliderTypeShowcase::getAssetsPath() . '/style.n2less', $this->context);


        $this->base = array(
            'sliderWidth'  => $width,
            'sliderHeight' => $height,
            'slideWidth'   => $slideWidth,
            'slideHeight'  => $slideHeight
        );
    }
}