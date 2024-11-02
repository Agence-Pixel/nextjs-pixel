<?php

if (!function_exists('utopian_set_plugins_array_to_install')) {
    function utopian_set_plugins_array_to_install()
    {
        global $default_plugins_array_to_install;

        $default_plugins_array_to_install = array('faustwp', 'wp-graphql', 'wp-graphql-content-blocks', 'contact-form-7', 'wordpress-seo', 'wp-graphql-yoast-seo', 'advanced-custom-fields-pro', 'wp-graphql-acf', 'wp-graphql-offset-pagination', 'wp-graphql-wpml', 'otgs-installer');
    }

    add_action('utopian_action_before_options_map', 'utopian_set_plugins_array_to_install');
}

if (!function_exists('utopian_plugins_list')) {
    function utopian_plugins_list($filter_array = array())
    {
        $plugins = array(
            array(
                'name'                  => esc_html__('Faust.js', 'utopian'),
                'slug'                  => 'faustwp',
                'source'                   => '',
                'required'                 => true,
                'version'                 => '',
                'force_activation'         => false,
                'force_deactivation'     => false,
                'external_url'             => ''
            ),
            array(
                'name'                  => esc_html__('WPGraphQL', 'utopian'),
                'slug'                  => 'wp-graphql',
                'source'                   => '',
                'required'                 => true,
                'version'                 => '',
                'force_activation'         => false,
                'force_deactivation'     => false,
                'external_url'             => ''
            ),
            array(
                'name'                  => esc_html__('WPGraphQL Content Blocks', 'utopian'),
                'slug'                  => 'wp-graphql-content-blocks',
                'source'                => get_template_directory() . '/plugins/wp-graphql-content-blocks.zip',
                'version'               => '',
                'required'                => true,
                'force_activation'        => false,
                'force_deactivation'    => false,
                'external_url'            => '',
            ),
            array(
                'name'                     => esc_html__('Contact Form 7', 'utopian'),
                'slug'                     => 'contact-form-7',
                'source'                   => '',
                'required'                 => true,
                'version'                 => '',
                'force_activation'         => false,
                'force_deactivation'     => false,
                'external_url'             => ''
            ),
            array(
                'name'                     => esc_html__('Yoast SEO', 'utopian'),
                'slug'                     => 'wordpress-seo',
                'source'                   => '',
                'required'                 => true,
                'version'                 => '',
                'force_activation'         => false,
                'force_deactivation'     => false,
                'external_url'             => ''
            ),
            array(
                'name'                     => esc_html__('WPGraphQL Yoast SEO', 'utopian'),
                'slug'                     => 'wp-graphql-yoast-seo',
                'source'                => get_template_directory() . '/plugins/wp-graphql-yoast-seo-master.zip',
                'required'                 => true,
                'version'                 => '',
                'force_activation'         => false,
                'force_deactivation'     => false,
                'external_url'             => ''
            ),
            array(
                'name'                     => esc_html__('Advanced Custom Fields Pro', 'utopian'),
                'slug'                     => 'advanced-custom-fields-pro',
                'source'                => get_template_directory() . '/plugins/advanced-custom-fields-pro.zip',
                'required'                 => false,
                'version'                 => '',
                'force_activation'         => false,
                'force_deactivation'     => false,
                'external_url'             => ''
            ),
            array(
                'name'                     => esc_html__('WPGraphQL ACF', 'utopian'),
                'slug'                     => 'wp-graphql-acf',
                'source'                => get_template_directory() . '/plugins/wpgraphql-acf.zip',
                'required'                 => false,
                'version'                 => '',
                'force_activation'         => false,
                'force_deactivation'     => false,
                'external_url'             => ''
            ),
            array(
                'name'                     => esc_html__('WPGraphQL Offset Pagination', 'utopian'),
                'slug'                     => 'wp-graphql-offset-pagination',
                'source'                => get_template_directory() . '/plugins/wp-graphql-offset-pagination-master.zip',
                'required'                 => false,
                'version'                 => '',
                'force_activation'         => false,
                'force_deactivation'     => false,
                'external_url'             => ''
            ),
            array(
                'name'                     => esc_html__('WPGraphQL WPML', 'utopian'),
                'slug'                     => 'wp-graphql-wpml',
                'source'                => get_template_directory() . '/plugins/wp-graphql-wpml-master.zip',
                'required'                 => false,
                'version'                 => '',
                'force_activation'         => false,
                'force_deactivation'     => false,
                'external_url'             => ''
            ),
            array(
                'name'                     => esc_html__('WPML', 'utopian'),
                'slug'                     => 'otgs-installer',
                'source'                => get_template_directory() . '/plugins/otgs-installer-plugin.3.1.0.zip',
                'required'                 => false,
                'version'                 => '',
                'force_activation'         => false,
                'force_deactivation'     => false,
                'external_url'             => ''
            )
        );

        if (!empty($filter_array)) {
            $filtered_plugins = array();
            foreach ($filter_array as $k1 => $val1) {
                foreach ($plugins as $k2 => $val2) {
                    if ($plugins[$k2]['slug'] == $val1) {
                        $filtered_plugins[$plugins[$k2]['slug']] = $plugins[$k2]['name'];
                    }
                }
            }
            return $filtered_plugins;
        } else {
            return $plugins;
        }
    }
}

if (!function_exists('utopian_register_theme_included_plugins')) {

    /**
     * Registers theme required and optional plugins. Hooks to tgmpa_register hook
     */

    function utopian_register_theme_included_plugins()
    {
        global $default_plugins_array_to_install;
        $plugins = utopian_plugins_list();
        $plugins_to_load = array();

        //if this option is already set (ie someone is updating theme) than update current option with new array entries
        if (!add_option("utopian_required_plugins", $default_plugins_array_to_install)) {
            $former_options = get_option("utopian_required_plugins");
            if (is_array($default_plugins_array_to_install) && count($default_plugins_array_to_install) > 0) {
                foreach ($default_plugins_array_to_install as $default_plugin) {
                    if (!in_array($default_plugin, $former_options)) {
                        array_push($former_options, $default_plugin);
                    }
                }
            }
            update_option("utopian_required_plugins", $former_options);
        }

        $utopian_required_plugins = get_option("utopian_required_plugins");
        if (empty($utopian_required_plugins)) {
            $utopian_required_plugins = array();
        }

        $filtered_plugins = apply_filters('utopian_filter_required_plugins', $utopian_required_plugins);

        foreach ($filtered_plugins as $k1 => $val1) {
            foreach ($plugins as $k2 => $val2) {
                if ($plugins[$k2]['slug'] == $val1) {
                    array_push($plugins_to_load, $plugins[$k2]);
                }
            }
        }

        $config = array(
            'domain'            => 'utopian',
            'default_path'        => '',
            'parent_slug'        => 'themes.php',
            'capability'        => 'edit_theme_options',
            'menu'                => 'install-required-plugins',
            'has_notices'        => true,
            'is_automatic'        => false,
            'message'            => '',
            'strings'            => array(
                'page_title'                        => esc_html__('Install Required Plugins', 'utopian'),
                'menu_title'                        => esc_html__('Install Plugins', 'utopian'),
                'installing'                        => esc_html__('Installing Plugin: %s', 'utopian'),
                'oops'                                => esc_html__('Something went wrong with the plugin API.', 'utopian'),
                'notice_can_install_required'        => _n_noop('This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.', 'utopian'),
                'notice_can_install_recommended'    => _n_noop('This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.', 'utopian'),
                'notice_cannot_install'                => _n_noop('Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.', 'utopian'),
                'notice_can_activate_required'        => _n_noop('The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.', 'utopian'),
                'notice_can_activate_recommended'    => _n_noop('The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.', 'utopian'),
                'notice_cannot_activate'            => _n_noop('Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.', 'utopian'),
                'notice_ask_to_update'                => _n_noop('The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.', 'utopian'),
                'notice_cannot_update'                => _n_noop('Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.', 'utopian'),
                'install_link'                        => _n_noop('Begin installing plugin', 'Begin installing plugins', 'utopian'),
                'activate_link'                        => _n_noop('Activate installed plugin', 'Activate installed plugins', 'utopian'),
                'return'                            => esc_html__('Return to Required Plugins Installer', 'utopian'),
                'plugin_activated'                    => esc_html__('Plugin activated successfully.', 'utopian'),
                'complete'                            => esc_html__('All plugins installed and activated successfully. %s', 'utopian'),
                'nag_type'                            => 'updated'
            )
        );

        tgmpa($plugins_to_load, $config);
    }

    add_action('tgmpa_register', 'utopian_register_theme_included_plugins');
}
