<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor upgrades class.
 *
 * Elementor upgrades handler class is responsible for updating different
 * Elementor versions.
 *
 * @since 1.0.0
 */
class Upgrades {

	/**
	 * Add actions.
	 *
	 * Hook into WordPress actions and launce Elementor upgrades.
	 *
	 * @static
	 * @since 1.0.0
	 * @access public
	 */
	public static function add_actions() {
		add_action( 'init', [ __CLASS__, 'init' ], 20 );
	}

	/**
	 * Init.
	 *
	 * Initialize Elementor upgrades.
	 *
	 * Fired by `init` action.
	 *
	 * @static
	 * @since 1.0.0
	 * @access public
	 */
	public static function init() {
		$elementor_version = get_option( 'elementor_version' );

		// Normal init.
		if ( ELEMENTOR_VERSION === $elementor_version ) {
			return;
		}

		self::check_upgrades( $elementor_version );

		Plugin::$instance->posts_css_manager->clear_cache();

		update_option( 'elementor_version', ELEMENTOR_VERSION );
	}

	/**
	 * Check upgrades.
	 *
	 * Checks whether the Elementor version need the be upgraded.
	 *
	 * If an upgrade required for a specific Elementor version, it will update
	 * the `elementor_upgrades` option in the database.
	 *
	 * @static
	 * @since 1.0.10
	 * @access private
	 */
	private static function check_upgrades( $elementor_version ) {
		// It's a new install.
		if ( ! $elementor_version ) {
			return;
		}

		$elementor_upgrades = get_option( 'elementor_upgrades', [] );

		$upgrades = [
			'0.3.2'  => '_upgrade_v032',
			'0.9.2'  => '_upgrade_v092',
			'0.11.0' => '_upgrade_v0110',
		];

		foreach ( $upgrades as $version => $function ) {
			if ( version_compare( $elementor_version, $version, '<' ) && ! isset( $elementor_upgrades[ $version ] ) ) {
				self::$function();
				$elementor_upgrades[ $version ] = true;
				update_option( 'elementor_upgrades', $elementor_upgrades );
			}
		}
	}

	/**
	 * Upgrade Elementor 0.3.2
	 *
	 * Change the image widget link URL, setting is to `custom` link.
	 *
	 * @static
	 * @since 1.0.0
	 * @access private
	 */
	private static function _upgrade_v032() {
		global $wpdb;

		$post_ids = $wpdb->get_col(
			'SELECT `post_id` FROM `' . $wpdb->postmeta . '`
					WHERE `meta_key` = \'_elementor_version\'
						AND `meta_value` = \'0.1\';'
		);

		if ( empty( $post_ids ) ) {
			return;
		}

		foreach ( $post_ids as $post_id ) {
			$data = Plugin::$instance->db->get_plain_editor( $post_id );
			if ( empty( $data ) ) {
				continue;
			}

			$data = Plugin::$instance->db->iterate_data( $data, function( $element ) {
				if ( empty( $element['widgetType'] ) || 'image' !== $element['widgetType'] ) {
					return $element;
				}

				if ( ! empty( $element['settings']['link']['url'] ) ) {
					$element['settings']['link_to'] = 'custom';
				}

				return $element;
			} );

			Plugin::$instance->db->save_editor( $post_id, $data );
		}
	}

	/**
	 * Upgrade Elementor 0.9.2
	 *
	 * Change the icon widget, icon-box widget and the social-icons widget,
	 * setting their icon padding size to an empty string.
	 *
	 * Change the image widget, setting the image size to full image size.
	 *
	 * @static
	 * @since 1.0.0
	 * @access private
	 */
	private static function _upgrade_v092() {
		global $wpdb;

		// Fix Icon/Icon Box Widgets padding.
		$post_ids = $wpdb->get_col(
			'SELECT `post_id` FROM `' . $wpdb->postmeta . '`
					WHERE `meta_key` = \'_elementor_version\'
						AND `meta_value` = \'0.2\';'
		);

		if ( empty( $post_ids ) ) {
			return;
		}

		foreach ( $post_ids as $post_id ) {
			$data = Plugin::$instance->db->get_plain_editor( $post_id );
			if ( empty( $data ) ) {
				continue;
			}

			$data = Plugin::$instance->db->iterate_data( $data, function( $element ) {
				if ( empty( $element['widgetType'] ) ) {
					return $element;
				}

				if ( in_array( $element['widgetType'], [ 'icon', 'icon-box', 'social-icons' ] ) ) {
					if ( ! empty( $element['settings']['icon_padding']['size'] ) ) {
						$element['settings']['icon_padding']['size'] = '';
					}
				}

				if ( 'image' === $element['widgetType'] ) {
					if ( empty( $element['settings']['image_size'] ) ) {
						$element['settings']['image_size'] = 'full';
					}
				}

				return $element;
			} );

			Plugin::$instance->db->save_editor( $post_id, $data );
		}
	}

	/**
	 * Upgrade Elementor 0.11.0
	 *
	 * Change the button widget sizes, setting up new button sizes.
	 *
	 * @static
	 * @since 1.0.0
	 * @access private
	 */
	private static function _upgrade_v0110() {
		global $wpdb;

		// Fix Button widget to new sizes options.
		$post_ids = $wpdb->get_col(
			'SELECT `post_id` FROM `' . $wpdb->postmeta . '`
					WHERE `meta_key` = \'_elementor_version\'
						AND `meta_value` = \'0.3\';'
		);

		if ( empty( $post_ids ) ) {
			return;
		}

		foreach ( $post_ids as $post_id ) {
			$data = Plugin::$instance->db->get_plain_editor( $post_id );
			if ( empty( $data ) ) {
				continue;
			}

			$data = Plugin::$instance->db->iterate_data( $data, function( $element ) {
				if ( empty( $element['widgetType'] ) ) {
					return $element;
				}

				if ( 'button' === $element['widgetType'] ) {
					$size_to_replace = [
						'small' => 'xs',
						'medium' => 'sm',
						'large' => 'md',
						'xl' => 'lg',
						'xxl' => 'xl',
					];

					if ( ! empty( $element['settings']['size'] ) ) {
						$old_size = $element['settings']['size'];

						if ( isset( $size_to_replace[ $old_size ] ) ) {
							$element['settings']['size'] = $size_to_replace[ $old_size ];
						}
					}
				}

				return $element;
			} );

			Plugin::$instance->db->save_editor( $post_id, $data );
		}
	}
}

Upgrades::add_actions();
