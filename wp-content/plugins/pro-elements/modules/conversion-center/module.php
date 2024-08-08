<?php

namespace ElementorPro\Modules\ConversionCenter;

use Elementor\Core\Base\Module as BaseModule;
use Elementor\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Module extends BaseModule {

	const EXPERIMENT_NAME = 'conversion-center';

	public static function is_active(): bool {
		return Plugin::$instance->experiments->is_feature_active( static::EXPERIMENT_NAME );
	}

	public function get_name(): string {
		return static::EXPERIMENT_NAME;
	}

	public function get_widgets(): array {
		return [
			'Link_In_Bio_Var_3',
			'Link_In_Bio_Var_4',
			'Link_In_Bio_Var_5',
			'Link_In_Bio_Var_7',
		];
	}

}
