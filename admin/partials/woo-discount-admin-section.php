<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://github.com/suprun-bohdan/woo-discount
 * @since      1.0.0
 *
 * @package    Woo_Discount
 * @subpackage Woo_Discount/admin/partials
 */

global $post;

$discounts = get_post_meta($post->ID, '_dynamic_discounts', true);
$discounts = is_array($discounts) ? $discounts : [];
?>
<div id="dynamic_discount_fields" class="options_group pricing show_if_simple show_if_external hidden" style="display: block;">

    <?php foreach ($discounts as $level => $percentage) : ?>
        <div class="discount_block">
            <p class="form-field _quantity_field ">
                <label for="dynamic_discount_quantity[]">Кількість одиниць</label>
                <input type="number" class="short wc_input_price" name="dynamic_discount_quantity[]" id="dynamic_discount_quantity[]" value="<?php echo esc_attr($level); ?>" placeholder="Кількість одиниць" min="1">
            </p>
            <p class="form-field _percentage_field ">
                <label for="dynamic_discount_percentage[]">Відсоток знижки</label>
                <input type="number" class="short wc_input_price" name="dynamic_discount_percentage[]" id="dynamic_discount_percentage[]" value="<?php echo esc_attr($percentage); ?>" placeholder="Відсоток знижки" min="0" max="100" step="1">
                <a href="#" class="remove_discount">Видалити</a>
            </p>
        </div>
    <?php endforeach; ?>

</div>

<button type="button" id="add_discount_level" class="button">Додати рівень знижки</button>