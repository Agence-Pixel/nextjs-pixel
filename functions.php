<?php

include_once get_template_directory() . '/theme-includes.php';

if (!function_exists('theme_setup')) {
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which runs
	 * before the init hook.
	 */
	function theme_setup()
	{
		// Add support for block styles.
		add_theme_support('wp-block-styles');

		// Enqueue editor styles.
		add_editor_style(['assets/css/editor-style.css']);
	}
}

add_action('after_setup_theme', 'theme_setup');

function be_gutenberg_scripts()
{
	wp_enqueue_script(
		'utopian-editor',
		get_stylesheet_directory_uri() . '/build/editor.js',
		'1',
		array('wp-blocks', 'wp-dom'),
		true
	);
}

add_action('enqueue_block_editor_assets', 'be_gutenberg_scripts');

function be_reusable_blocks_admin_menu()
{
	add_menu_page('Reusable Blocks', 'Reusable Blocks', 'edit_posts', 'edit.php?post_type=wp_block', '', 'dashicons-editor-table', 22);
}
add_action('admin_menu', 'be_reusable_blocks_admin_menu');

class JSXBlock
{
	function __construct($name, $renderCallback = null, $data = null, $dataIsFunction = false, $dependencies = ['wp-element'])
	{
		$this->name = $name;
		$this->location = "/build/blocks/{$name}/";
		$this->data = $data;
		$this->dataIsFunction = $dataIsFunction;
		$this->renderCallback = $renderCallback;
		$this->dependencies = $dependencies;
		add_action('init', [$this, 'onInit']);
		add_action('wp_enqueue_scripts', [$this, 'enqueueBlockFiles']);
	}

	function ourRenderCallback($attributes, $content)
	{
		ob_start();

		require get_theme_file_path("/assets/blocks/{$this->name}/index.php");

		return ob_get_clean();
	}

	function onInit()
	{

		if ($this->data) {
			wp_localize_script($this->name, $this->name, $this->data);
		}

		$ourArgs = [];

		if ($this->renderCallback) {
			$ourArgs['render_callback'] = [$this, 'ourRenderCallback'];
		}

		register_block_type(__DIR__ . $this->location, $ourArgs);
	}

	function enqueueBlockFiles()
	{
		if ($this->renderCallback) {
			if (has_block("utopian/{$this->name}")) {

				$asset_file = include(__DIR__ . "{$this->location}frontend/index.asset.php");

				if ($this->data) {
					wp_register_script(
						$this->name,
						get_stylesheet_directory_uri() . "{$this->location}frontend/index.js",
						$this->dependencies,
						$asset_file['version'],
						true
					);

					wp_localize_script($this->name, $this->name, $this->dataIsFunction ? call_user_func($this->data) : $this->data);

					wp_enqueue_script(
						$this->name
					);
				} else {
					wp_enqueue_script(
						$this->name,
						get_stylesheet_directory_uri() . "{$this->location}frontend/index.js",
						$this->dependencies,
						$asset_file['version'],
						true
					);
				}

				wp_enqueue_style(
					$this->name,
					get_stylesheet_directory_uri() . "{$this->location}frontend/index.css",
					[],
					$asset_file['version']
				);
			}
		}
	}
}

new JSXBlock('svg', true);
new JSXBlock('faq', true);

if (function_exists('register_block_pattern_category')) {
	register_block_pattern_category(
		'utopian',
		array('label' => __('Utopian', 'utopian'))
	);
}

add_filter('wpcf7_autop_or_not', '__return_false');

add_action('acf/init', 'my_acf_blocks_init');
function my_acf_blocks_init()
{

	// check function exists
	if (!function_exists('acf_add_options_page')) {
		return;
	}

	// register options page
	$my_options_page = acf_add_options_page(
		array(
			'page_title'      => __('Options'),
			'menu_title'      => __('Options'),
			'menu_slug'       => 'options',
			'capability'      => 'edit_posts',
			'show_in_graphql' => true,
		)
	);

	// 	Add Google API Key
	// 	acf_update_setting('google_api_key', 'xxx');

	// 	Check function exists.
	// if (function_exists('acf_register_block_type')) {

	// acf_register_block_type(array(
	// 	'title'			=> __('Hero', 'utopian'),
	// 	'name'			=> 'hero',
	// 'render_template'	=> 'assets/blocks/hero/hero.php',
	// 	'mode'			=> 'preview',
	// 	'supports'		=> [
	// 		'align'			=> false,
	// 		'anchor'		=> true,
	// 		'customClassName'	=> true,
	// 		'jsx' 			=> true,
	// 	]
	// ));

	// acf_register_block_type(array(
	// 	'title'			=> __('Language Switcher', 'utopian'),
	// 	'name'			=> 'language-switcher',
	// 	'render_template'	=> 'assets/blocks/language-switcher/language-switcher.php',
	// 	'mode'			=> 'preview'
	// ));
	// }
}

add_action('acf/include_fields', function () {
	if (!function_exists('acf_add_local_field_group')) {
		return;
	}

	acf_add_local_field_group(array(
		'key' => 'group_64ae0447f3498',
		'title' => 'Header/Footer Options',
		'fields' => array(
			array(
				'key' => 'field_64ae0448d3c8b',
				'label' => 'Logo',
				'name' => 'logo',
				'aria-label' => '',
				'type' => 'textarea',
				'instructions' => '',
				'required' => 1,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'show_in_graphql' => 1,
				'default_value' => '',
				'maxlength' => '',
				'rows' => '',
				'placeholder' => '',
				'new_lines' => '',
			),
			array(
				'key' => 'field_64ae85c1760b8',
				'label' => 'Favicon',
				'name' => 'favicon',
				'aria-label' => '',
				'type' => 'image',
				'instructions' => '',
				'required' => 1,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'show_in_graphql' => 1,
				'return_format' => 'array',
				'library' => 'all',
				'min_width' => '',
				'min_height' => '',
				'min_size' => '',
				'max_width' => '',
				'max_height' => '',
				'max_size' => '',
				'mime_types' => '',
				'preview_size' => 'medium',
			),
			array(
				'key' => 'field_64b3b97868589',
				'label' => 'Copyright',
				'name' => 'copyright',
				'aria-label' => '',
				'type' => 'textarea',
				'instructions' => '',
				'required' => 1,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'show_in_graphql' => 1,
				'default_value' => '',
				'maxlength' => '',
				'rows' => '',
				'placeholder' => '',
				'new_lines' => 'wpautop',
			),
		),
		'location' => array(
			array(
				array(
					'param' => 'options_page',
					'operator' => '==',
					'value' => 'options',
				),
			),
		),
		'menu_order' => 0,
		'position' => 'normal',
		'style' => 'default',
		'label_placement' => 'top',
		'instruction_placement' => 'label',
		'hide_on_screen' => '',
		'active' => true,
		'description' => '',
		'show_in_rest' => 1,
		'show_in_graphql' => 1,
		'graphql_field_name' => 'options',
		'map_graphql_types_from_location_rules' => 0,
		'graphql_types' => '',
	));
});

// Extra functions you might need
/*
// Our custom post type function
function create_posttype()
{


	register_taxonomy(
		'categorie-equipe',
		'equipe',
		array(
			'hierarchical' => true,
			'label' => 'Catégorie équipe',
			'query_var' => true,
			'show_in_rest' => true,
			'rewrite' => array(
				'slug' => 'categorie-equipe',
				'with_front' => false
			)
		)
	);

	register_post_type(
		'equipe',
		// CPT Options
		array(
			'labels' => array(
				'name' => __('Équipe'),
				'singular_name' => __("Membre de l'équipe")
			),
			'public' => false,
			'show_ui' => true,
			'supports' => array('title', 'custom-fields', 'thumbnail'),
			'taxonomies' => array(''),
			'has_archive' => true,
			'rewrite' => array('slug' => 'equipe'),
			'show_in_rest' => true,
			'menu_icon' => 'dashicons-businessman'
		)
	);

	add_filter('woocommerce_show_page_title', '__return_true', 1);
	add_filter('woocommerce_single_product_summary', 'woocommerce_template_single_title', 6);
}
// Hooking up our function to theme setup
add_action('init', 'create_posttype');*/

// add_action('rest_api_init', function () {
// 	register_rest_route('utopian/v1', '/reports', array(
// 		'methods' => 'GET',
// 		'callback' => 'getAllReports',
// 		'permission_callback' => '__return_true',
// 	));
// });

// function getAllReports(){
	
// 	$years = [];
	
// 	$args = array(
// 		'post_type' => 'report',
// 		'posts_per_page' => -1,
// 		'orderby' => 'date'
// 	);

// 	// The Query
// 	$the_query = new WP_Query( $args );

// 	// The Loop
// 	if ( $the_query->have_posts() ) {
// 		$arr = [];
// 		while ( $the_query->have_posts() ) {
// 			$the_query->the_post();
// 			if(!isset($year)) {
// 				$year = get_the_date('Y');
// 			}
// 			if($year != get_the_date('Y')) {
// 				$years[] = [
// 					'year' => $year,
// 					'items' => $arr
// 				];
// 				$arr = [];
// 				$year = get_the_date('Y');
// 			}
			
// 			$arr[] = [
// 				'title' => get_the_title(),
// 				'content' => wpautop(get_the_content()),
// 				'links' => get_field('icons')
// 			];
			
// 		}
// 		wp_reset_postdata();
// 	}
	
// 	return $years;
// }