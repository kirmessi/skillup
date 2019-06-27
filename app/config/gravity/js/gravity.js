jQuery(document).ready(function () {
    jQuery(document).on('gform_price_change', function (event, productIds, htmlInput) {
        console.log(productIds);
        if (productIds['productFieldId'] == 13) {
            alert('Price updated');
        }
    });
    gform.addFilter('gform_chosen_options', 'set_chosen_options_js');

//limit how many options may be chosen in a multi-select to 2
    function set_chosen_options_js(options, element) {
        if (element.attr('id') == 'input_1_12') {
            options.max_selected_options = 2;
        }
        return options;
    }

    gform.addFilter('gform_calculation_formula', function (formula, formulaField, formId, calcObj) {
        if (formId == '1' && formulaField.field_id == '9') {
            formula += '+5';
        }
        return formula;
    });
});