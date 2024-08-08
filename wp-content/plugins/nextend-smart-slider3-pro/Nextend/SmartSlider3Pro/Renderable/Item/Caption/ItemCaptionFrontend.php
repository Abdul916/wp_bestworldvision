<?php


namespace Nextend\SmartSlider3Pro\Renderable\Item\Caption;


use Nextend\Framework\Parser\Color;
use Nextend\Framework\Parser\Common;
use Nextend\Framework\Sanitize;
use Nextend\Framework\View\Html;
use Nextend\SmartSlider3\Renderable\AbstractRenderableOwner;
use Nextend\SmartSlider3\Renderable\Item\AbstractItemFrontend;

class ItemCaptionFrontend extends AbstractItemFrontend {

    public function render() {
        return $this->getHtml();
    }

    public function renderAdminTemplate() {
        return $this->getHtml();
    }

    private function getHtml() {
        $owner = $this->layer->getOwner();

        $this->loadResources($owner);

        list($mode, $direction, $scale) = Common::parse($this->data->get('animation', 'Simple|*|left|*|0'));
        switch ($direction) {
            case 'top':
                $axis  = 'yP';
                $ratio = -1;
                break;
            case 'right':
                $axis  = 'xP';
                $ratio = 1;
                break;
            case 'bottom':
                $axis  = 'yP';
                $ratio = 1;
                break;
            case 'left':
                $axis  = 'xP';
                $ratio = -1;
                break;
        }
        $owner->addScript('new _N2.FrontendItemCaption(this, "' . $this->id . '", "' . $mode . '",' . json_encode($axis) . ',' . json_encode($ratio) . ', ' . intval($scale) . ');');

        $image = $owner->fill($this->data->get('image', ''));

        $imageAttributes = array(
            'alt' => htmlspecialchars($owner->fill($this->data->get('alt', '')))
        );

        $html = $owner->renderImage($this, $image, $imageAttributes);

        $rgba = Color::colorToRGBA($this->data->get('color', '00000080'));
        $html .= Html::openTag("div", array(
            "class" => "n2-ss-item-caption-content",
            "style" => "background: {$rgba};" . 'justify-content:' . $this->data->get('verticalalign', 'center') . ';'
        ));

        $title = Sanitize::filter_allowed_html($owner->fill($this->data->get('content', '')));
        if ($title != '') {
            $fontTitle = $owner->addFont($this->data->get('fonttitle'), 'paragraph');
            $html      .= Html::tag("div", array("class" => 'n2-div-h4 ' . $fontTitle), $title);
        }

        $description = Sanitize::filter_allowed_html($owner->fill($this->data->get('description', '')));
        if ($description != '') {
            $font = $owner->addFont($this->data->get('font'), 'paragraph');
            $html .= Html::tag("p", array("class" => $font), $description);
        }

        $html .= Html::closeTag("div");

        $linkAttributes = array();
        if ($this->isEditor) {
            $linkAttributes['onclick'] = 'return false;';
        }

        return Html::tag("div", array(
            "id"             => $this->id,
            "class"          => "n2-ss-item-caption n2-ss-item-content n2-ow-all n2-ss-item-caption-" . $mode,
            "data-direction" => $direction
        ), $this->getLink($html, $linkAttributes));
    }

    /**
     * @param AbstractRenderableOwner $owner
     */
    public function loadResources($owner) {

        $owner->addLess(self::getAssetsPath() . "/caption.n2less", array(
            "sliderid" => $owner->getElementID()
        ));
    }
}