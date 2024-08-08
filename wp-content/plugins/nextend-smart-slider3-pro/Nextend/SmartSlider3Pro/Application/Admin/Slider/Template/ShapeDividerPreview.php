<?php

namespace Nextend\SmartSlider3Pro\Application\Admin\Slider;


use Nextend\Framework\Asset\Js\Js;
use Nextend\SmartSlider3\Settings;

/**
 * @var $this ViewSliderShapeDividerPreview
 */


JS::addGlobalInline('document.documentElement.classList.add("n2_html--application-only");');
JS::addGlobalInline('document.documentElement.classList.add("n2_html--slider-preview");');

$slider = $this->renderSlider();

$externals = esc_attr(Settings::get('external-css-files'));
if (!empty($externals)) {
    $externals = explode("\n", $externals);
    foreach ($externals as $external) {
        echo "<link rel='stylesheet' href='" . esc_attr($external) . "' type='text/css' media='all'>";
    }
}
// PHPCS - Content already escaped
echo $slider; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
?>

<script>
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            parent.postMessage(JSON.stringify({action: 'cancel'}), "*");
        }
    });
</script>