<?php

namespace Nextend\SmartSlider3Pro\Application\Admin\Slider;

use Nextend\Framework\Asset\Js\Js;
use Nextend\Framework\Filesystem\Filesystem;
use Nextend\Framework\Request\Request;
use Nextend\Framework\ResourceTranslator\ResourceTranslator;
use Nextend\SmartSlider3\Settings;
use Nextend\SmartSlider3\Slider\Slider;

/**
 * @var $this ViewSliderParticle
 */

JS::addGlobalInline('document.documentElement.classList.add("n2_html--application-only");');

$postedSliderData                    = (array)Request::$POST->getVar('slider', false);
$postedSliderData['desktop']         = 1; // Shape divider does not work if slider is not visible.
$postedSliderData['playWhenVisible'] = 0;


$frontendSlider = new Slider($this, $this->getSliderID(), array(
    'disableResponsive' => true,
    'sliderData'        => $postedSliderData
), true);

$frontendSlider->initAll();
$sliderHTML = $frontendSlider->render();

$externals = esc_attr(Settings::get('external-css-files'));
if (!empty($externals)) {
    $externals = explode("\n", $externals);
    foreach ($externals as $external) {
        echo "<link rel='stylesheet' href='" . esc_url($external) . "' type='text/css' media='all'>";
    }
}

Js::addStaticGroup(ResourceTranslator::toPath('$ss3-pro-frontend$/dist/particle.min.js'), 'particles');

$folder    = ResourceTranslator::toPath('$ss3-pro-frontend$/js/particle/presets/');
$files     = Filesystem::files($folder);
$extension = 'json';

$types = array();
for ($i = 0; $i < count($files); $i++) {
    $pathInfo = pathinfo($files[$i]);
    if (isset($pathInfo['extension']) && $pathInfo['extension'] == $extension) {
        $types[$pathInfo['filename']] = file_get_contents($folder . $files[$i]);
    }
}

Js::addFirstCode("    
    new _N2.ParticleAdminManager(" . $this->getSliderID() . ", " . json_encode($types) . ");
");

$this->renderForm();
?>

<div class="n2_slider_preview_area">
    <div class="n2_slider_preview_area__inner" style="width:100%;max-width:<?php echo esc_attr($frontendSlider->features->responsive->sizes['desktopPortrait']['width']); ?>px;">
        <?php

        // PHPCS - Content already escaped
        echo $sliderHTML; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        ?>
    </div>
</div>