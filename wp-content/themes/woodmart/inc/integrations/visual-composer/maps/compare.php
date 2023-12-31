<?php
if ( ! defined( 'WOODMART_THEME_DIR' ) ) {
	exit( 'No direct script access allowed' );
}

/**
* ------------------------------------------------------------------------------------------------
*  Compare element map
* ------------------------------------------------------------------------------------------------
*/
if ( ! function_exists( 'woodmart_get_vc_shortcode_compare' ) ) {
	function woodmart_get_vc_shortcode_compare() {
		return array(
			'name'        => esc_html__( 'Compare', 'woodmart' ),
			'base'        => 'woodmart_compare',
			'category'    => woodmart_get_tab_title_category_for_wpb( esc_html__( 'Theme elements', 'woodmart' ) ),
			'description' => esc_html__( 'Required for the compare table page.', 'woodmart' ),
			'icon'        => WOODMART_ASSETS . '/images/vc-icon/compare.svg',
			'params' => array(
				array(
					'type' => 'wd_notice',
					'param_name' => 'notice',
					'notice_type' => 'info',
					'value' => esc_html__(
						'This element is created for the compare page and you can find all its configuration in Theme Settings.',
						'woodmart'
					),
				)
			),
		);
	}
}


