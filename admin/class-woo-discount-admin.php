<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://github.com/suprun-bohdan/woo-discount
 * @since      1.0.0
 *
 * @package    Woo_Discount
 * @subpackage Woo_Discount/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Woo_Discount
 * @subpackage Woo_Discount/admin
 * @author     Bohdan Suprun <bohdan-suprun@outlook.com>
 */
class Woo_Discount_Admin
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @param string $plugin_name The name of this plugin.
     * @param string $version The version of this plugin.
     * @since    1.0.0
     */
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;

        add_filter('woocommerce_product_data_tabs', array($this, 'add_dynamic_discount_tab'));
        add_action('woocommerce_product_data_panels', array($this, 'add_dynamic_discount_fields_panel'));
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Woo_Discount_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Woo_Discount_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/woo-discount-admin.css', array(), $this->version, 'all');

    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Woo_Discount_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Woo_Discount_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/woo-discount-admin.js', array('jquery'), $this->version, false);

    }

    public function add_dynamic_discount_tab($tabs) {
        $tabs['dynamic_discounts'] = array(
            'label'    => __('Dynamic Discounts', 'woo-discount'),
            'target'   => 'dynamic_discounts_data',
            'class'    => array('show_if_simple', 'show_if_variable'),
            'priority' => 21,
        );
        return $tabs;
    }

    public function add_dynamic_discount_fields_panel(): void
    {
        echo '<div id="dynamic_discounts_data" class="panel woocommerce_options_panel hidden">';
        include plugin_dir_path(__FILE__) . 'partials/woo-discount-admin-section.php';
        echo '</div>';
    }

    public function save_dynamic_discount_fields($post_id): void
    {
        delete_post_meta($post_id, '_dynamic_discounts');

        if (isset($_POST['dynamic_discount_quantity']) && isset($_POST['dynamic_discount_percentage'])) {
            $quantities = $_POST['dynamic_discount_quantity'];
            $percentages = $_POST['dynamic_discount_percentage'];

            $discounts = array();

            for ($i = 0; $i < count($quantities); $i++) {
                $quantity = isset($quantities[$i]) ? intval($quantities[$i]) : 0;
                $percentage = isset($percentages[$i]) ? floatval($percentages[$i]) : 0;

                if ($quantity > 0 && $percentage >= 0 && $percentage <= 100) {
                    $discounts[$quantity] = $percentage;
                }
            }

            if (!empty($discounts)) {
                update_post_meta($post_id, '_dynamic_discounts', $discounts);
            }
        }
    }
}
