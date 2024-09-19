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
class Woo_Discount_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/woo-discount-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/woo-discount-admin.js', array( 'jquery' ), $this->version, false );

	}

    public function add_dynamic_discount_fields(): void
    {
        global $post;

        echo '<div class="options_group pricing show_if_simple show_if_external hidden" style="display: block;">';

        $discounts = get_post_meta($post->ID, '_dynamic_discounts', true);
        $discounts = is_array($discounts) ? $discounts : [];

        foreach ($discounts as $level => $percentage) {
            echo <<<HTML
        <style>
            #add_discount_level {
                margin: 10px!important;
            }
            
            .remove_discount {
                margin-left: 5px!important;
            }
        </style>
        <div class="discount_block">
            <p class="form-field _quantity_field ">
                <label for="dynamic_discount_quantity[]">Кількість одиниць</label>
                <input type="number" class="short wc_input_price" name="dynamic_discount_quantity[]" id="dynamic_discount_quantity[]" value="{$level}" placeholder="Кількість одиниць" min="1">
            </p>
            <p class="form-field _percentage_field ">
                <label for="dynamic_discount_percentage[]">Відсоток знижки</label>
                <input type="number" class="short wc_input_price" name="dynamic_discount_percentage[]" id="dynamic_discount_percentage[]" value="{$percentage}" placeholder="Відсоток знижки" min="0" max="100" step="1">
                <a href="#" class="remove_discount">Видалити</a>
            </p>
        </div>
        HTML;
        }

        echo '</div>';

        echo '<button type="button" id="add_discount_level" class="button">Додати рівень знижки</button>';

        echo <<<JS
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function () {
            const addDiscountBtn = document.getElementById('add_discount_level');
            const discountFieldsContainer = document.querySelector('.options_group.pricing');

            addDiscountBtn.addEventListener('click', (e) => {
                e.preventDefault();

                const newField = `
                <div class="discount_block">
                    <p class="form-field _quantity_field ">
                        <label for="dynamic_discount_quantity[]">Кількість одиниць</label>
                        <input type="number" class="short wc_input_price" name="dynamic_discount_quantity[]" placeholder="Кількість одиниць" min="1">
                    </p>
                    <p class="form-field _percentage_field ">
                        <label for="dynamic_discount_percentage[]">Відсоток знижки</label>
                        <input type="number" class="short wc_input_price" name="dynamic_discount_percentage[]" placeholder="Відсоток знижки" min="0" max="100" step="1">
                        <a href="#" class="remove_discount">Видалити</a>
                    </p>
                </div>`;
                
                discountFieldsContainer.insertAdjacentHTML('beforeend', newField);
            });

            discountFieldsContainer.addEventListener('click', (e) => {
                if (e.target.classList.contains('remove_discount')) {
                    e.preventDefault();
                    e.target.closest('.discount_block').remove();
                }
            });
        });
    </script>
    JS;
    }




    public function save_dynamic_discount_fields( $post_id ): void
    {
        if ( isset( $_POST['dynamic_discount_quantity'] ) && isset( $_POST['dynamic_discount_percentage'] ) ) {
            $quantities = $_POST['dynamic_discount_quantity'];
            $percentages = $_POST['dynamic_discount_percentage'];

            $discounts = array();
            for ( $i = 0; $i < count( $quantities ); $i++ ) {
                $quantity = intval( $quantities[$i] );
                $percentage = floatval( $percentages[$i] );
                if ( $quantity > 0 && $percentage >= 0 ) {
                    $discounts[$quantity] = $percentage;
                }
            }

            update_post_meta( $post_id, '_dynamic_discounts', $discounts );
        }
    }
}
