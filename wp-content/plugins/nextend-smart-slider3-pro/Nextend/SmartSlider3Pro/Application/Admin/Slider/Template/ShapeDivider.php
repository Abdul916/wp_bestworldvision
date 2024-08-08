<?php

namespace Nextend\SmartSlider3Pro\Application\Admin\Slider;

use Nextend\Framework\Asset\Js\Js;
use Nextend\Framework\Filesystem\Filesystem;
use Nextend\Framework\Request\Request;
use Nextend\Framework\ResourceTranslator\ResourceTranslator;

/**
 * @var $this ViewSliderShapeDivider
 */

JS::addGlobalInline('document.documentElement.classList.add("n2_html--application-only");');

$postedSliderData                    = (array)Request::$POST->getVar('slider', false);
$postedSliderData['playWhenVisible'] = 0;
/**
 * Shape divider admin editor does not work if slider is not visible.
 */
$postedSliderData['desktopportrait']  = 1;
$postedSliderData['desktoplandscape'] = 1;
$postedSliderData['tabletportrait']   = 1;
$postedSliderData['tabletlandscape']  = 1;
$postedSliderData['mobileportrait']   = 1;
$postedSliderData['mobilelandscape']  = 1;

$folder = ResourceTranslator::toPath('$ss3-pro-frontend$/shapedivider/');

$files     = Filesystem::files($folder);
$extension = 'svg';
$types     = array();
for ($i = 0; $i < count($files); $i++) {
    $pathInfo = pathinfo($files[$i]);
    if (isset($pathInfo['extension']) && $pathInfo['extension'] == $extension) {
        $types['simple-' . $pathInfo['filename']] = file_get_contents($folder . $files[$i]);
    }
}

$folder .= 'bicolor/';
$files  = Filesystem::files($folder);
for ($i = 0; $i < count($files); $i++) {
    $pathInfo = pathinfo($files[$i]);
    if (isset($pathInfo['extension']) && $pathInfo['extension'] == $extension) {
        $types['bi-' . $pathInfo['filename']] = file_get_contents($folder . $files[$i]);
    }
}

Js::addFirstCode("    
    new _N2.ShapeDividerAdminManager(" . $this->getSliderID() . ", " . json_encode($types) . ");
");

$this->renderForm();
?>

<div class="n2_slider_preview_area" style="min-height:0;">
    <div class="n2_slider_preview_area__inner">
        <form id="n2_shape_divider__frame_form" target="n2_shape_divider__frame" action="<?php echo esc_url($this->MVCHelper->createUrl(array(
            'slider/shapedividerpreview',
            array(
                'sliderid' => $this->getSliderID()
            )
        ), true)); ?>" method="post" class="n2_form_element--hidden">
            <input type="hidden" name="sliderData" value="<?php echo esc_attr(json_encode($postedSliderData)); ?>">
        </form>
        <iframe name="n2_shape_divider__frame" id="n2_shape_divider__frame" style="width:100%;height: calc(100vh - 100px);"></iframe>
    </div>
</div>