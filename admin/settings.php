<?php

namespace Companieshouse;

class Settings {

    private $options = array();

    public function __construct()
    {
        add_action('admin_init', array($this, 'init'));
        add_action('admin_menu', array($this, 'add_settings'));
        add_filter("plugin_action_links_companieshouse/plugin.php", array($this, 'plugin'));
    }

    public function init()
    {

        add_settings_section(
                'default', '', // Title
                array($this, 'print_section_info'), 'companieshouse'
        );

        add_settings_field(
                'api_key', 'ID Number', array($this, 'api_key_callback'), 'companieshouse', 'default'
        );
    }

    /**
     * Add settings link to plugin page
     * 
     * @param array $links
     * @return array
     */
    public function plugin($links)
    {
        $settings_link = '<a href="options-general.php?page=companieshouse">' . __('Settings') . '</a>';
        array_push($links, $settings_link);
        return $links;
    }

    /**
     * Add options page
     */
    public function add_settings()
    {
        add_options_page(
                'Settings Admin', 'Companies House', 'manage_options', 'companieshouse', array($this, 'create_admin_page')
        );
    }

    /**
     * Options page callback
     */
    public function create_admin_page()
    {

        /**
         * Save and get form options
         */
        if (isset($_POST['companieshouse'])) {
            update_option('companieshouse_api_key', $_POST['companieshouse']['api_key']);
        }

        $options = array('companieshouse_api_key');
        foreach ($options as $option) {
            $this->options[$option] = get_option($option);
        }
        ?>
        <div class="wrap">
            <h1><?php _e('Companies House API'); ?></h1>
            <form method="post" action="options-general.php?page=companieshouse">
                <?php
                // This prints out all hidden setting fields
                do_settings_sections('companieshouse');
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize($input)
    {
        $new_input = array();
        if (isset($input['api_key']))
            $new_input['api_key'] = sanitize_text_field($input['api_key']);

        return $new_input;
    }

    /**
     * Print the Section text
     */
    public function print_section_info()
    {
        
    }

    /**
     * Get the settings option array and print one of its values
     */
    public function api_key_callback()
    {
        printf(
                '<input type="text" id="api_key" name="companieshouse[api_key]" value="%s" />', isset($this->options['companieshouse_api_key']) ? esc_attr($this->options['companieshouse_api_key']) : ''
        );
    }

}

function register_setting($option_group, $option_name, $args = array())
{
    global $new_whitelist_options, $wp_registered_settings;

    $defaults = array(
        'type' => 'string',
        'group' => $option_group,
        'description' => '',
        'sanitize_callback' => null,
        'show_in_rest' => false,
    );
}

new \Companieshouse\Settings();
