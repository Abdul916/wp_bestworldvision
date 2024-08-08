<?php


namespace Nextend\SmartSlider3Pro\Renderable\Item\Transition;


use Nextend\Framework\View\Html;
use Nextend\SmartSlider3\Renderable\AbstractRenderableOwner;
use Nextend\SmartSlider3\Renderable\Item\AbstractItemFrontend;

class ItemTransitionFrontend extends AbstractItemFrontend {

    public function render() {
        return $this->getHtml();
    }

    public function renderAdminTemplate() {
        return $this->getHtml();
    }

    private function getHtml() {

        $image  = $this->data->get('image', '');
        $image2 = $this->data->get('image2', '');
        if (empty($image) && empty($image2)) {
            return '';
        }

        $owner = $this->layer->getOwner();

        $image  = $owner->fill($image);
        $image2 = $owner->fill($image2);

        if (empty($image) && empty($image2)) {
            return '';
        }

        $this->loadResources($owner);
        $owner->addScript('new _N2.FrontendItemTransition(this, "' . $this->id . '", "' . $this->data->get('animation', 'Fade') . '");');

        $html = Html::openTag("div", array(
            "class" => "n2-ss-item-transition-inner"
        ));

        $imageAttributes = array(
            'alt'   => htmlspecialchars($owner->fill($this->data->get('alt', ''))),
            'class' => 'n2-ss-item-transition-image1'
        );

        $html .= $owner->renderImage($this, $image, $imageAttributes);

        $imageAttributes2 = array(
            'alt'   => htmlspecialchars($owner->fill($this->data->get('alt2', ''))),
            'class' => 'n2-ss-item-transition-image2'
        );

        $html .= $owner->renderImage($this, $image2, $imageAttributes2);


        $html .= Html::closeTag('div');

        $linkAttributes = array();
        if ($this->isEditor) {
            $linkAttributes['onclick'] = 'return false;';
        }

        return Html::tag("div", array(
            "id"    => $this->id,
            "class" => "n2-ss-item-transition n2-ss-item-content n2-ow-all"
        ), $this->getLink($html, $linkAttributes));
    }

    /**
     * @param $owner AbstractRenderableOwner
     */
    public function loadResources($owner) {

        $owner->addLess(self::getAssetsPath() . "/transition.n2less", array(
            "sliderid" => $owner->getElementID()
        ));
    }
}