<?php

/**
 * @wordpress-plugin
 * Plugin Name:       Companies House Search
 * Plugin URI:        http://leftcurlybracket.com
 * Description:       Basic companieshouse.gov.uk integration for Wordpress
 * Version:           1.0.0
 * Author:            LeftCurlyBracket
 * Author URI:        http://leftcurlybracket.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       companieshouse
 * Domain Path:       /languages
 */
// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

define('PLUGIN_NAME_VERSION', '1.0.0');

class Companieshouse {

    protected $api_key;

    function __construct()
    {
        $this->api_key = get_option('companieshouse_api_key');
        add_shortcode('companieshouse', array($this, 'shortcode_form'));
        add_action('init', array($this, 'result_page'));
    }

    /**
     * Default search by name
     * 
     * @param string $name
     * @return mixed
     */
    public function search($name)
    {
        $opts = array(
            'http' => array(
                'method' => "GET",
                'header' => "Authorization: Basic " . $this->api_key
            )
        );

        $context = stream_context_create($opts);
        $response = file_get_contents('https://api.companieshouse.gov.uk/search/companies?q=' . urlencode($name), false, $context);
        $result = json_decode($response);
        if ($result) {
            $total_result = $result->{'total_results'};
            $items_per_page = $result->{'items_per_page'};
            $pages = ceil($total_result / $items_per_page);
            return $result;
        }
    }

    /**
     * Get results array from search query
     * 
     * @param string $name
     * @return array
     */
    public function get_companies($name)
    {
        $companies = array();
        $result = $this->search($name);
        if ($result && isset($result->items)) {
            foreach ($result->items as $company) {
                $companies[$company->title] = $company;
            }
        }
        return $companies;
    }

    /**
     * Get search form template
     * 
     * @return string
     */
    public function shortcode_form()
    {
        ob_start();
        $this->get_html('form.php');
        return ob_get_clean();
    }

    /**
     * Render search result template
     * 
     * @return void
     */
    public function result_page()
    {
        $url_path = trim(parse_url(add_query_arg(array()), PHP_URL_PATH), '/');
        if ($url_path === 'company/search') {
            $load = $this->get_html('results.php');
            if ($load) {
                exit(); // just exit if template was found and loaded
            }
        }
    }

    /**
     * Render from absolute path to given template, use theme file if exist
     * 
     * @param string $file
     * @return string
     */
    public function get_html($file)
    {

        ob_start();

        $path = plugin_dir_path(__FILE__) . '/templates/' . $file;

        if (file_exists(get_stylesheet_directory() . '/companieshouse/' . $file)) {
            include(get_stylesheet_directory() . '/companieshouse/' . $file);
        } elseif (file_exists($path)) {
            include($path);
        }

        return ob_get_contents();
    }

}

if (is_admin()) {
    require_once( dirname(__FILE__) . '/admin/settings.php' );
} else {
    new Companieshouse;
}
