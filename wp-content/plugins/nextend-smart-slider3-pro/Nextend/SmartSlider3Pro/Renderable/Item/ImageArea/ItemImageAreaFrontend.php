<?php


namespace Nextend\SmartSlider3Pro\Renderable\Item\ImageArea;


use Nextend\Framework\ResourceTranslator\ResourceTranslator;
use Nextend\SmartSlider3\Renderable\Item\AbstractItemFrontend;

class ItemImageAreaFrontend extends AbstractItemFrontend {

    public function render() {

        if ($this->hasLink()) {
            return $this->getLink($this->getHtml(false), array(
                'style' => 'display: block; width:100%;height:100%;',
                'class' => 'n2-ss-item-content n2-ow'
            ));
        }

        return $this->getHtml();
    }

    public function renderAdminTemplate() {
        return $this->getHtml();
    }

    private function getHtml($isContent = true) {
        $owner = $this->layer->getOwner();

        $image = $this->data->get('image', '');
        if (empty($image)) {
            return '';
        }

        $image = $owner->fill($image);

        $imageUrl = ResourceTranslator::toUrl($image);

        $owner->addImage($imageUrl);

        $imageAttributes = array(
            "alt"   => htmlspecialchars($owner->fill($this->data->get('alt', ''))),
            'class' => ($isContent ? 'n2-ss-item-content ' : '') . ' n2-ss-item-image-area',
            'style' => 'object-fit:' . $this->data->get('fillmode', 'cover') . ';object-position:' . $this->data->get('positionx', 50) . '% ' . $this->data->get('positiony', 50) . '%;'
        );

        return $owner->renderImage($this, $image, $imageAttributes, array(
            'class' => 'n2-ow-all'
        ));
    }

    public function needHeight() {
        return true;
    }
}