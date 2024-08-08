<?php


namespace Nextend\SmartSlider3Pro\Renderable\Item\BeforeAfter;


use Nextend\Framework\Parser\Color;
use Nextend\Framework\Sanitize;
use Nextend\Framework\View\Html;
use Nextend\SmartSlider3\Renderable\AbstractRenderableOwner;
use Nextend\SmartSlider3\Renderable\Item\AbstractItemFrontend;

class ItemBeforeAfterFrontend extends AbstractItemFrontend {

    public function render() {
        return $this->getHtml();
    }

    public function renderAdminTemplate() {
        return $this->getHtml();
    }


    private function getHtml() {
        $image  = $this->data->get('imagebefore', '');
        $image2 = $this->data->get('imageafter', '');

        if (empty($image) && empty($image2)) {
            return '';
        }

        $owner = $this->layer->getOwner();
        $this->loadResources($owner);

        $image  = $owner->fill($image);
        $image2 = $owner->fill($image2);

        if (empty($image) && empty($image2)) {
            return '';
        }


        $attributes = [
            'direction'         => $this->data->get('direction', 'horizontal'),
            'startposition'     => $this->data->get('startposition', '50'),
            'interaction'       => $this->data->get('interaction', 'drag'),
            'dividerstyle'      => $this->data->get('dividerstyle', 'line'),
            'dividercolor'      => $this->data->get('dividercolor', '00000080'),
            'dividerwidth'      => $this->data->get('dividerwidth', '4'),
            'labelpositon'      => $this->data->get('labelposition', 'end'),
            'labelfront'        => $this->data->get('labelfront', '0'),
            'labeltype'         => $this->data->get('labeltype', 'normal'),
            'showlabel'         => $this->data->get('showlabel', 1),
            'labelbackground'   => $this->data->get('labelbackground', 'FFFFFF80'),
            'fontlabel'         => $owner->addFont($this->data->get('fontlabel'), 'simple'),
            'fontcaption'       => $owner->addFont($this->data->get('fontcaption'), 'simple'),
            'showcaption'       => $this->data->get('showcaption', ""),
            'captiontype'       => $this->data->get('captiontype', ""),
            'captionbackground' => $this->data->get('captionbackground', "00000080"),
            'captionpos'        => $this->data->get('captionposition', "2-3"),
            'captionrow'        => '2',
            'captioncol'        => '3',
        ];


        //need here because style
        $gridValues               = explode('-', $attributes['captionpos']);
        $attributes['captionrow'] = $gridValues[0];
        $attributes['captioncol'] = $gridValues[1];

        //Container
        $styles = "
       --dividerColor : " . Color::colorToRGBA($attributes['dividercolor'], '00000080') . " ;
       --dividerPos : " . $attributes['startposition'] . "%;
       --imagePos : " . $attributes['startposition'] . "%;
       --labelBackground : " . Color::colorToRGBA($attributes['labelbackground'], '00000080') . ";
       --captionBackground : " . Color::colorToRGBA($attributes['captionbackground'], '00000080') . ";
       --dividerWidth : " . $attributes['dividerwidth'] . "px;";

        $containerInEditor = $this->isEditor ? "n2-ss-item-ba-container--ineditor" : "";

        $html = Html::openTag("div", array(
            'style' => $styles,
            "class" => "n2-ss-item-ba-container n2-ss-item-ba-container--{$attributes['direction']} n2_container_scrollable $containerInEditor n2-ss-item-ba-container--interaction-{$attributes['interaction']}"
        ));


        $labelBefore = $textBefore = "";
        $labelAfter  = $textAfter = "";
        if ($attributes['showlabel']) {
            $textBefore = Sanitize::filter_allowed_html($owner->fill($this->data->get('textbefore', "")));
            $textAfter  = Sanitize::filter_allowed_html($owner->fill($this->data->get('textafter', "")));
            $show       = $attributes['interaction'] === 'hover' && $attributes['labeltype'] === 'hover' ? 'normal' : $attributes['labeltype'];

            $labelClass = $this->isEditor ? "" : "n2-ss-item-ba-label--show-$show n2-ss-text n2-ow ";
            $labelClass .= $attributes['fontlabel'];

            if ($textBefore) $labelBefore = "<div class='n2-ss-item-ba-label n2-ss-item-ba-label--before $labelClass'> $textBefore</div>";
            if ($textAfter) $labelAfter = "<div class='n2-ss-item-ba-label n2-ss-item-ba-label--after $labelClass'> $textAfter</div>";

        }

        //Caption
        if ($attributes['showcaption']) {
            $captionText = Sanitize::filter_allowed_html($owner->fill($this->data->get('captiontext', "")));
            if ($captionText != "") {
                $show           = $attributes['interaction'] === 'hover' && $attributes['captiontype'] === 'hover' ? 'normal' : $attributes['captiontype'];
                $labelClass     = $this->isEditor ? "" : "n2-ss-item-ba-caption--show-$show n2-ss-text n2-ow";
                $caption        = "<div class='n2-ss-item-ba-caption $labelClass {$attributes['fontcaption']}'> $captionText</div>";
                $labelContainer = Html::tag('div', array(
                    "data-position" => $attributes['captionpos'],
                    "class"         => "n2-ss-item-ba-caption-container"
                ), $caption);
                $html           .= $labelContainer;
            }
        }

        //Images

        $imageAttributes = array(
            'alt'       => htmlspecialchars($owner->fill($this->data->getIfEmpty('altbefore', $textBefore))),
            'class'     => 'n2-ow-all n2-ss-item-ba-image n2-ss-item-ba-image--before',
            'draggable' => 'false'
        );

        $imageBefore = Html::tag("div", array(
            "class" => "n2-ss-item-ba-image-container n2-ss-item-ba-image-container--bottom n2-ss-item-ba-label-container--{$attributes['labelpositon']}"
        ), $labelBefore . $owner->renderImage($this, $image, $imageAttributes));


        $imageAttributes2 = array(
            'alt'       => htmlspecialchars($owner->fill($this->data->getIfEmpty('altafter', $textAfter))),
            'class'     => 'n2-ow-all n2-ss-item-ba-image n2-ss-item-ba-image--after',
            'draggable' => 'false'
        );

        $imageAfter = Html::tag("div", array(
            "class" => "n2-ss-item-ba-image-container n2-ss-item-ba-image-container--top n2-ss-item-ba-label-container--{$attributes['labelpositon']}"
        ), $labelAfter . $owner->renderImage($this, $image2, $imageAttributes2));


        $html .= $imageBefore;
        $html .= $imageAfter;

        //Divider
        $dividerClass = ($attributes['dividerstyle'] === 'line' || $attributes['dividerstyle'] === 'arrow') ? '' : 'n2-ss-item-ba-divider--gap';
        $divider      = Html::openTag("div", array(
            "class" => "n2-ss-item-ba-divider $dividerClass"
        ));
        //Divider-part-top
        $divider .= "<div class='n2-ss-item-ba-divider-part n2-ss-item-ba-divider-part--top'></div>";

        //Svg arrows
        $svg = $this->item->getArrowSvg($attributes['dividerstyle']);
        //arrowContainer
        if ($svg) {
            $arrowContainer = Html::tag("div", array(
                "class" => "n2-ss-item-ba-arrow-container n2-ss-item-ba-arrow-container--{$attributes['dividerstyle']}"
            ), $svg . $svg);

            $divider .= $arrowContainer;
        }

        //Divider-part-bottom
        $divider .= "<div class='n2-ss-item-ba-divider-part n2-ss-item-ba-divider-part--bottom'></div>";

        //Divider Close
        $divider .= Html::closeTag('div');


        //Dividercontainer
        $dividerContainer = Html::tag("div", array(
            "class" => "n2-ss-item-ba-divider-container"
        ), $divider);

        $html .= $dividerContainer;

        //Close Container
        $html .= Html::closeTag('div');


        $jsData = array(
            'startPos'    => $attributes['startposition'],
            'interaction' => $attributes['interaction'],
            'direction'   => $attributes['direction'],
            'labeltype'   => $attributes['labeltype'],
            'captiontype' => $attributes['captiontype'],

        );

        if (!$this->isEditor && !$owner->underEdit) {
            $owner->addScript('new _N2.FrontendItemBeforeAfter(this, "' . $this->id . '", ' . json_encode($jsData) . ');');
        }


        return Html::tag("div", array(
            "id"    => $this->id,
            "class" => "n2-ss-item-ba-wrapper n2-ss-item-content n2-ow-all"
        ), $html);
    }


    /**
     * @param $owner AbstractRenderableOwner
     */
    public function loadResources($owner) {


        $owner->addLess(self::getAssetsPath() . "/beforeAfter.n2less", array(
            "sliderid" => $owner->getElementID()
        ));


    }


}