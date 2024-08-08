<?php
namespace AIOSEO\Plugin\Common\Main;

use AIOSEO\Plugin\Common\Models\CrawlCleanupLog;
use AIOSEO\Plugin\Common\Models\CrawlCleanupBlockedArg;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Query arguments class.
 *
 * @since   4.2.1
 * @version 4.5.8
 */
class QueryArgs {
	/**
	 * Construct method.
	 *
	 * @since 4.2.1
	 */
	public function __construct() {
		if (
			is_admin() ||
			aioseo()->helpers->isWpLoginPage() ||
			aioseo()->helpers->isAjaxCronRestRequest() ||
			aioseo()->helpers->isDoingWpCli()
		) {
			return;
		}

		add_action( 'template_redirect', [ $this, 'maybeRemoveQueryArgs' ], 1 );
	}

	/**
	 * Check if we can remove query args.
	 *
	 * @since 4.5.8
	 *
	 * @return boolean True if the query args can be removed.
	 */
	private function canRemoveQueryArgs() {
		if (
			! aioseo()->options->searchAppearance->advanced->blockArgs->enable ||
			is_user_logged_in() ||
			is_admin() ||
			is_robots() ||
			get_query_var( 'aiosp_sitemap_path' ) ||
			empty( $_GET ) // phpcs:ignore HM.Security.NonceVerification.Recommended
		) {
			return false;
		}

		if ( is_singular() ) {
			global $post;
			$thePost = aioseo()->helpers->getPost( $post->ID );

			// Leave the preview query arguments intact.
			if (
				isset( $_GET['preview'] ) && // phpcs:ignore HM.Security.NonceVerification.Recommended
				isset( $_GET['preview_nonce'] ) && // phpcs:ignore HM.Security.NonceVerification.Recommended
				current_user_can( 'edit_post', $thePost->ID )
			) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Maybe remove query args.
	 *
	 * @since 4.5.8
	 *
	 * @return void
	 */
	public function maybeRemoveQueryArgs() {
		if ( ! $this->canRemoveQueryArgs() ) {
			return;
		}

		$currentRequest = aioseo()->helpers->getRequestUrl();

		// Remove the home path from the url for subfolder installs.
		$currentRequest       = aioseo()->helpers->excludeHomePath( $currentRequest );
		$currentRequestParsed = wp_parse_url( $currentRequest );

		// No query args? Never mind!
		if ( empty( $currentRequestParsed['query'] ) ) {
			return;
		}

		parse_str( $currentRequestParsed['query'], $currentRequestQueryArgs );
		$notAllowed          = [];
		$recognizedQueryLogs = [];

		foreach ( $currentRequestQueryArgs as $key => $value ) {
			if ( ! is_string( $value ) ) {
				continue;
			}
			$this->addQueryLog( $currentRequestParsed['path'], $key, $value );

			$blocked = CrawlCleanupBlockedArg::getByKeyValue( $key, null );
			if ( ! $blocked->exists() ) {
				$blocked = CrawlCleanupBlockedArg::getByKeyValue( $key, $value );
			}

			if ( ! $blocked->exists() ) {
				$blocked = CrawlCleanupBlockedArg::matchRegex( $key, $value );
			}

			if ( $blocked->exists() ) {
				$queryArg = $key . ( $value ? '=' . $value : null );
				$notAllowed[] = $queryArg;
				$blocked->addHit();
				continue;
			}

			$recognizedQueryLogs[ $key ] = empty( $value ) ? true : $value;
		}

		if ( ! empty( $notAllowed ) ) {
			$newUrl = home_url( $currentRequestParsed['path'] );

			header( 'Content-Type: redirect', true );
			header_remove( 'Content-Type' );
			header_remove( 'Last-Modified' );
			header_remove( 'X-Pingback' );

			wp_safe_redirect( add_query_arg( $recognizedQueryLogs, $newUrl ), 301, AIOSEO_PLUGIN_SHORT_NAME . ' Crawl Cleanup' );
			exit;
		}
	}


	/**
	 * Add query args log.
	 *
	 * @since 4.5.8
	 *
	 * @param string $path  A String of the path to create a slug.
	 * @param string $key   A String of key from query arg.
	 * @param string $value A String of value from query arg.
	 * @return void
	 */
	private function addQueryLog( $path, $key, $value = null ) {
		$slug = $path . '?' . $key . ( 0 < strlen( $value ) ? '=' . $value : '' );
		$log  = CrawlCleanupLog::getBySlug( $slug );

		$data = [
			'slug'  => $slug,
			'key'   => $key,
			'value' => $value
		];

		$log->set( $data );
		$log->create();
	}
}