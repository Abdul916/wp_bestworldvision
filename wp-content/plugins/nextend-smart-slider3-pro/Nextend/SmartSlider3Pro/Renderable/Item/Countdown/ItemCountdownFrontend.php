<?php

namespace Nextend\SmartSlider3Pro\Renderable\Item\Countdown;

use Nextend\Framework\Platform\Platform;
use Nextend\Framework\View\Html;
use Nextend\SmartSlider3\Renderable\AbstractRenderableOwner;
use Nextend\SmartSlider3\Renderable\Item\AbstractItemFrontend;

class ItemCountdownFrontend extends AbstractItemFrontend {

    private $font, $style, $fontLabel;

    public function isAuto() {
        return true;
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

        if ($this->data->get('slide-schedule')) {
            $date = $this->layer->getOwner()->publish_down;
        } else {
            $date = $this->data->get('date');
        }

        if (empty($date)) {
            $date = date('Y-m-d H:i:s', time() + 86400);
        }

        $currentTimestampUtc = gmdate('U');

        $timezoneOffset = Platform::getTimestamp() - $currentTimestampUtc;
        /**
         * Adjust date to GMT
         */
        $timestampUTC = strtotime($date) - $timezoneOffset;


        $diff = $timestampUTC - $currentTimestampUtc;

        $days    = 0;
        $hours   = 0;
        $minutes = 0;
        $seconds = 0;

        if ($diff > 0) {
            $days    = floor($diff / 86400);
            $diff    -= $days * 86400;
            $hours   = floor($diff / 3600);
            $diff    -= $hours * 3600;
            $minutes = floor($diff / 60);
            $diff    -= $minutes * 60;
            $seconds = $diff;
        }

        $this->font      = $owner->addFont($this->data->get('font'), 'simple');
        $this->fontLabel = $owner->addFont($this->data->get('fontlabel'), 'simple');
        $this->style     = $owner->addStyle($this->data->get('style'), 'heading');


        $columns = $this->data->get('columns', 4);
        $gap     = explode('|*|', $this->data->get('gap', '10|*|10'));

        $cssVariables = array(
            '--ss-counter-columns:' . $columns,
            '--ss-counter-gap-v:' . $gap[0] . 'px',
            '--ss-counter-gap-h:' . $gap[1] . 'px'
        );

        if ($this->data->get('tablet-style')) {
            $tabletColumns = $this->data->get('tablet-columns', 4);
            if ($columns !== $tabletColumns) {
                $cssVariables[] = '--ss-counter-tablet-columns:' . $tabletColumns;
            }
            $tabletGap = explode('|*|', $this->data->get('tablet-gap', '10|*|10'));
            if ($gap[0] !== $tabletGap[0]) {
                $cssVariables[] = '--ss-counter-tablet-gap-v:' . $tabletGap[0] . 'px';
            }
            if ($gap[1] !== $tabletGap[1]) {
                $cssVariables[] = '--ss-counter-tablet-gap-h:' . $tabletGap[1] . 'px';
            }
        }

        if ($this->data->get('mobile-style')) {
            $mobileColumns = $this->data->get('mobile-columns', 4);
            if ($columns !== $mobileColumns) {
                $cssVariables[] = '--ss-counter-mobile-columns:' . $mobileColumns;
            }
            $mobileGap = explode('|*|', $this->data->get('mobile-gap', '10|*|10'));
            if ($gap[0] !== $mobileGap[0]) {
                $cssVariables[] = '--ss-counter-mobile-gap-v:' . $mobileGap[0] . 'px';
            }
            if ($gap[1] !== $mobileGap[1]) {
                $cssVariables[] = '--ss-counter-mobile-gap-h:' . $mobileGap[1] . 'px';
            }
        }

        return Html::tag('div', array(
            'class'             => 'n2-ss-item-countdown_container n2-ss-item-content n2-ow-all',
            'style'             => implode(';', $cssVariables),
            'data-timestamp'    => $timestampUTC,
            'data-action'       => $this->data->get('action'),
            'data-redirect-url' => $this->data->get('redirect-url')
        ), implode(array(
            $this->createCard('day', $days, n2_('Days')),
            $this->createCard('hour', $hours, n2_('Hours')),
            $this->createCard('minute', $minutes, n2_('Minutes')),
            $this->createCard('second', $seconds, n2_('Seconds'))
        )));
    }

    private function createCard($element, $value, $label) {

        $labelHTML = '';
        if ($this->data->get('label', 1)) {
            $labelHTML = Html::tag('div', array(
                'class' => 'n2-ss-item-countdown_label ' . $this->fontLabel
            ), $label);
        }

        return Html::tag('div', array(
            'class' => 'n2-ss-item-countdown_element n2-ss-item-countdown_' . $element . ' ' . $this->style
        ), Html::tag('div', array(
                'class' => 'n2-ss-item-countdown_number ' . $this->font
            ), $this->formatNumber($value)) . $labelHTML);
    }

    private function formatNumber($number) {
        if ($number < 10) {
            return '0' . $number;
        } else {
            return $number;
        }
    }

    /**
     * @param AbstractRenderableOwner $owner
     */
    public function loadResources($owner) {
        $owner->addLess(self::getAssetsPath() . "/countdown.n2less", array(
            "sliderid" => $owner->getElementID()
        ));

        if (!$this->layer->getOwner()
                         ->isAdmin() && !$owner->isScriptAdded('countdown')) {
            $owner->addScript('this.sliderElement.querySelectorAll(\'.n2-ss-item-countdown_container\').forEach((function(el){new _N2.FrontendItemCountdown(el, this)}).bind(this));', 'countdown');
        }
    }
}