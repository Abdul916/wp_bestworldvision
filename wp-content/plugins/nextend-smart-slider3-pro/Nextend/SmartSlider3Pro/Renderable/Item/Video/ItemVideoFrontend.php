<?php


namespace Nextend\SmartSlider3Pro\Renderable\Item\Video;


use Nextend\Framework\Data\Data;
use Nextend\Framework\FastImageSize\FastImageSize;
use Nextend\Framework\Image\Image;
use Nextend\Framework\ResourceTranslator\ResourceTranslator;
use Nextend\Framework\View\Html;
use Nextend\SmartSlider3\Renderable\AbstractRenderableOwner;
use Nextend\SmartSlider3\Renderable\Item\AbstractItemFrontend;

class ItemVideoFrontend extends AbstractItemFrontend {

    public function render() {
        $owner = $this->layer->getOwner();

        $aspectRatio = $this->data->get('aspect-ratio', '16:9');

        $hasImage = 0;
        $poster   = $owner->fill($this->data->get('poster'));

        $coverImage = '';
        if (!empty($poster)) {

            $coverImage = $owner->renderImage($this, $poster, array(
                'class' => 'n2_ss_video_cover',
                'alt'   => n2_('Play')
            ), array(
                'class' => 'n2-ow-all'
            ));

            $hasImage  = 1;
            $playImage = '';

            if ($this->data->get('playbutton', 1) == 1) {

                $playWidth  = intval($this->data->get('playbuttonwidth', '48'));
                $playHeight = intval($this->data->get('playbuttonheight', '48'));
                if ($playWidth > 0 && $playHeight > 0) {

                    $attributes = Html::addExcludeLazyLoadAttributes(array(
                        'style' => '',
                        'class' => 'n2_ss_video_play_btn'
                    ));

                    if ($playWidth != 48) {
                        $attributes['style'] .= 'width:' . $playWidth . 'px;';
                    }
                    if ($playHeight != 48) {
                        $attributes['style'] .= 'height:' . $playHeight . 'px;';
                    }

                    $playButtonImage = $this->data->get('playbuttonimage', '');
                    if (!empty($playButtonImage)) {
                        $image = $this->data->get('playbuttonimage', '');
                        FastImageSize::initAttributes($image, $attributes);
                        $src = ResourceTranslator::toUrl($image);
                    } else {
                        $image = '$ss3-frontend$/images/play.svg';
                        FastImageSize::initAttributes($image, $attributes);
                        $src = Image::SVGToBase64($image);
                    }

                    $playImage = Html::image($src, 'Play', $attributes);
                }
            }

            $coverImage = Html::tag('div', array(
                'class'              => 'n2_ss_video_player__cover',
                'data-force-pointer' => ''
            ), $coverImage . $playImage);
        }

        $owner->addScript('new _N2.FrontendItemVideo(this, "' . $this->id . '", ' . $this->data->toJSON() . ', ' . $hasImage . ');');

        $style = '';
        if ($aspectRatio == 'custom') {
            $style = 'style="padding-top:' . ($this->data->get('aspect-ratio-height', '9') / $this->data->get('aspect-ratio-width', '16') * 100) . '%"';
        }

        return Html::tag("div", array(
            'class'             => 'n2_ss_video_player n2-ss-item-content n2-ss-item-video-container n2-ow-all',
            'data-aspect-ratio' => $aspectRatio
        ), '<div class="n2_ss_video_player__placeholder" ' . $style . '></div>' . Html::tag("video", $this->setOptions($this->data, $this->id), $this->setContent($owner, $this->data)) . $coverImage);
    }

    public function renderAdminTemplate() {
        $aspectRatio = $this->data->get('aspect-ratio', '16:9');

        $style = '';
        if ($aspectRatio == 'custom') {
            $style = 'style="padding-top:' . ($this->data->get('aspect-ratio-height', '9') / $this->data->get('aspect-ratio-width', '16') * 100) . '%"';
        }

        $playButtonImage = $this->data->get('playbuttonimage', '');
        if (!empty($playButtonImage)) {
            $playButtonImage = ResourceTranslator::toUrl($playButtonImage);
        } else {
            $playButtonImage = Image::SVGToBase64('$ss3-frontend$/images/play.svg');
        }

        $playButtonStyle  = '';
        $playButtonWidth  = intval($this->data->get('playbuttonwidth', '48'));
        $playButtonHeight = intval($this->data->get('playbuttonheight', '48'));

        if ($playButtonWidth > 0) {
            $playButtonStyle .= 'width:' . $playButtonWidth . 'px;';
        }
        if ($playButtonHeight > 0) {
            $playButtonStyle .= 'height:' . $playButtonHeight . 'px;';
        }

        $playButton = Html::image($playButtonImage, n2_('Play'), Html::addExcludeLazyLoadAttributes(array(
            'class' => 'n2_ss_video_play_btn',
            'style' => $playButtonStyle
        )));

        return Html::tag('div', array(
            'class'             => 'n2_ss_video_player n2-ss-item-content n2-ss-item-video-container n2-ow-all',
            'data-aspect-ratio' => $aspectRatio,
            "style"             => 'background: URL(' . ResourceTranslator::toUrl($this->data->getIfEmpty('poster', '$ss3-frontend$/images/placeholder/video.png')) . ') no-repeat 50% 50%; background-size: cover;'
        ), '<div class="n2_ss_video_player__placeholder" ' . $style . '></div>' . ($this->data->get('playbutton', 1) ? '<div class="n2_ss_video_player__cover">' . $playButton . '</div>' : ''));
    }

    /**
     * @param $data Data
     * @param $id
     *
     * @return array
     */
    private function setOptions($data, $id) {
        $videoOptions = array(
            'style'        => '',
            'class'        => 'n2-ow intrinsic-ignore data-tf-not-load n2-' . $data->get("fill-mode", 'cover'),
            'encode'       => false,
            'controlsList' => 'nodownload'
        );

        $videoOptions["data-volume"] = $data->get("volume", 1);
        if ($videoOptions["data-volume"] == 0) {
            $videoOptions['muted'] = 'muted';
        }

        $videoOptions['playsinline']        = 1;
        $videoOptions['webkit-playsinline'] = 1;

        if ($data->get('loop')) {
            $videoOptions['loop'] = 'loop';
        }


        $videoOptions["id"] = $id;

        if ($data->get("showcontrols")) {
            $videoOptions["controls"] = "yes";
        } else {
            $videoOptions["style"] .= "pointer-events:none;";
        }

        $videoOptions["preload"] = $data->get("preload", "auto");

        return $videoOptions;
    }

    /**
     * @param $owner AbstractRenderableOwner
     * @param $data  Data
     *
     * @return string
     */
    private function setContent($owner, $data) {
        $videoContent = "";

        if ($data->get("video_mp4", false)) {
            $videoContent .= Html::tag("source", array(
                "src"  => ResourceTranslator::toUrl($owner->fill($data->get("video_mp4"))),
                "type" => "video/mp4"
            ), '', false);
        }

        return $videoContent;
    }
}