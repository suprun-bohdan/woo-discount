(function( $ ) {
	'use strict';

	$(function() {
		const addDiscountBtn = $('#add_discount_level');
		const discountFieldsContainer = $('#dynamic_discount_fields');


		addDiscountBtn.on('click', function(e) {
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

			discountFieldsContainer.append(newField);
		});

		discountFieldsContainer.on('click', '.remove_discount', function(e) {
			e.preventDefault();
			$(this).closest('.discount_block').remove();
		});
	});

})( jQuery );
