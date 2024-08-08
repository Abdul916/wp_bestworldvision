<?php

use Nextend\SmartSlider3\Platform\SmartSlider3Platform;

add_action('plugins_loaded', 'smart_slider_3_pro_plugins_loaded', 20);

function smart_slider_3_pro_plugins_loaded() {

    //Do not load the free version when pro is available
    remove_action('plugins_loaded', 'smart_slider_3_plugins_loaded', 30);

    define('NEXTEND_SMARTSLIDER_3', dirname(__FILE__) . DIRECTORY_SEPARATOR);
    define('NEXTEND_SMARTSLIDER_3_BASENAME', NEXTEND_SMARTSLIDER_3_PRO_BASENAME);
    define('NEXTEND_SMARTSLIDER_3_SLUG', NEXTEND_SMARTSLIDER_3_PRO_SLUG);

    require_once dirname(__FILE__) . '/Defines.php';
    require_once(SMARTSLIDER3_LIBRARY_PATH . '/Autoloader.php');

    add_action("after_plugin_row_smart-slider-3/smart-slider-3.php", function ($plugin_file, $plugin_data) {

        echo '<tr class="plugin-update-tr' . (is_plugin_active($plugin_file) ? ' active' : '') . '" data-slug="' . esc_attr($plugin_data['slug']) . '" data-plugin="' . esc_attr($plugin_file) . '">
            <td colspan="4" class="plugin-update colspanchange">
                <div class="notice inline notice-warning notice-alt">
                    <p><i class="dashicons dashicons-info-outline" style="color: #d63638;margin-right:6px;"></i>' . esc_html(n2_('Smart Slider 3 Pro is activated on your site. We recommend removing the Free version as you no longer need it.')) . ' | <a target="_blank" href="https://smartslider.helpscoutdocs.com/article/1918-upgrading-from-free-to-pro">' . esc_html(n2_('How to switch from Free to Pro?')) . '</a></p>
                </div>
                <script>
                    (function(){
                        const row = document.querySelector(\'tr[data-slug="' . esc_js($plugin_data['slug']) . '"]\');
                        if(row){
                            row.classList.add("update");
                        }
                    })();
                </script>
            </td>
         </tr>';
    }, 10, 2);

    SmartSlider3Platform::getInstance();
}

