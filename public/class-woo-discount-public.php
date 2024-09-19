<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://github.com/suprun-bohdan/woo-discount
 * @since      1.0.0
 *
 * @package    Woo_Discount
 * @subpackage Woo_Discount/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Woo_Discount
 * @subpackage Woo_Discount/public
 * @author     Bohdan Suprun <bohdan-suprun@outlook.com>
 */
class Woo_Discount_Public
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
     * @param string $plugin_name The name of the plugin.
     * @param string $version The version of this plugin.
     * @since    1.0.0
     */
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;

    }

    /**
     * Register the stylesheets for the public-facing side of the site.
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

        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/woo-discount-public.css', array(), $this->version, 'all');

    }

    /**
     * Register the JavaScript for the public-facing side of the site.
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

        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/woo-discount-public.js', array('jquery'), $this->version, false);

    }

    public function apply_dynamic_discounts($cart): void
    {
        foreach ($cart->get_cart() as $cart_item) {
            $product_id = $cart_item['product_id'];
            $quantity = $cart_item['quantity'];
            $price = $cart_item['data']->get_price();

            $discounts = get_post_meta($product_id, '_dynamic_discounts', true);
            if (!empty($discounts) && is_array($discounts)) {
                $applicable_discount = 0;

                foreach ($discounts as $required_quantity => $percentage) {
                    if ($quantity >= $required_quantity) {
                        $applicable_discount = max($applicable_discount, $percentage);
                    }
                }

                if ($applicable_discount > 0) {
                    $new_price = $price - ($price * ($applicable_discount / 100));
                    $cart_item['data']->set_price($new_price);
                }
            }
        }
    }
}
