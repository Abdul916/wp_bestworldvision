<?php


namespace Nextend\SmartSlider3Pro\Renderable\Item\ImageBox;


use Nextend\Framework\Icon\Icon;
use Nextend\Framework\Parser\Color;
use Nextend\Framework\Sanitize;
use Nextend\Framework\View\Html;
use Nextend\SmartSlider3\Renderable\AbstractRenderableOwner;
use Nextend\SmartSlider3\Renderable\Component\AbstractComponent;
use Nextend\SmartSlider3\Renderable\Item\AbstractItemFrontend;

class ItemImageBoxFrontend extends AbstractItemFrontend {

    public function isAuto() {
        return !$this->data->get('fullwidth', 1);
    }

    public function render() {
        return $this->getHtml();
    }

    public function renderAdminTemplate() {
        return $this->getHtml();
    }

    private function getHtml() {
        $owner = $this->layer->getOwner();

        $this->loadResources($owner);

        $style = $owner->addStyle($this->data->get('style'), 'heading');

        $layout = $this->data->get('layout');

        $attr = array(
            'class'       => 'n2-ss-item-imagebox-container n2-ss-item-content n2-ow-all ' . $style,
            'data-layout' => $layout,
            'style'       => AbstractComponent::innerAlignToStyle($this->data->get('inneralign'))
        );

        if ($layout == 'left' || $layout == 'right') {
            $attr['style'] .= 'align-items:' . $this->data->get('verticalalign') . ';';
        }

        $html = Html::openTag('div', $attr);

        // START IMAGE SECTION
        $imageHTML           = '';
        $imageContainerStyle = '';
        $imageInnerStyle     = '';
        $icon                = $this->data->get('icon');
        $image               = $this->data->get('image');
        $imageType           = $this->data->get('imagetype', 'icon');
        $linkAttributes      = array();
        if (!empty($icon) && $imageType == 'icon') {
            $iconData  = Icon::render($icon);
            $imageHTML .= Html::tag('i', array(
                'class' => 'n2i ' . $iconData['class'],
                'style' => 'color: ' . Color::colorToRGBA($this->data->get('iconcolor')) . ';font-size:' . ($this->data->get('iconsize') / 16 * 100) . '%'
            ), $iconData['ligature']);

            $ariaLabel = $this->data->get('href-aria-label', '');
            if (!empty($ariaLabel)) {
                $linkAttributes['aria-label'] = $ariaLabel;
            }
        } else if (!empty($image)) {

            if ($layout == 'top' || $layout == 'bottom') {
                $imageInnerStyle .= 'max-width:' . $this->data->get('imagewidth') . '%;';
            } else {
                $imageContainerStyle .= 'max-width:' . $this->data->get('imagewidth') . '%;';
            }

            $image = $owner->fill($this->data->get('image'));

            $imageAttributes = array(
                'alt'   => $owner->fill($this->data->get('alt')),
                'style' => $imageInnerStyle,
                'class' => ''
            );

            $imageHTML = $owner->renderImage($this, $image, $imageAttributes);
        }

        if (!empty($imageHTML)) {
            $html .= Html::tag('div', array(
                'class' => 'n2-ss-item-imagebox-image',
                'style' => $imageContainerStyle
            ), $this->getLink($imageHTML, $linkAttributes));
        }
        // END IMAGE SECTION


        // START CONTENT SECTION
        $html .= Html::openTag('div', array(
            'class' => 'n2-ss-item-imagebox-content',
            'style' => 'padding:' . implode('px ', explode('|*|', $this->data->get('padding'))) . 'px'
        ));

        $heading = Sanitize::filter_allowed_html($this->data->get('heading'));
        if (!empty($heading)) {
            $font = $owner->addFont($this->data->get('fonttitle'), 'hover');

            $priority = $this->data->get('headingpriority');
            $html     .= $this->getLink(Html::tag($priority > 0 ? 'h' . $priority : $priority, array('class' => $font), $owner->fill($heading)));
        }

        $description = Sanitize::filter_allowed_html($this->data->get('description'));
        if (!empty($description)) {
            $font = $owner->addFont($this->data->get('fontdescription'), 'paragraph');

            $html .= Html::tag('div', array('class' => $font), $owner->fill($description));
        }

        $html .= '</div>';
        // END CONTENT SECTION


        $html .= '</div>';

        return $html;
    }

    /**
     * @param AbstractRenderableOwner $owner
     */
    public function loadResources($owner) {
        $owner->addLess(self::getAssetsPath() . "/imagebox.n2less", array(
            "sliderid" => $owner->getElementID()
        ));
    }
}