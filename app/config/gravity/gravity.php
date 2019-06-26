<?php

namespace App\Config\Gravity;

Class Gravity
{
    /**
     * Gravity constructor.
     */
    public function __construct()
    {
        add_filter('gform_pre_render_1', [$this, 'populate_posts']);
        add_filter('gform_pre_validation_1', [$this, 'populate_posts']);
        add_filter('gform_pre_submission_filter_1', [$this, 'populate_posts']);
        add_filter('gform_admin_pre_render_1', [$this, 'populate_posts']);
        add_action('gform_pre_render_1', [$this, 'set_chosen_options']);
        add_action('gform_pre_render_1', [$this, 'do_price_stuff']);
    }

    /**
     * @param $form
     *
     * @return mixed
     */
    public function populate_posts($form)
    {

        foreach ($form['fields'] as &$field) {

            if ($field->type != 'select') {
                continue;
            }

            // you can add additional parameters here to alter the posts that are retrieved
            // more info: http://codex.wordpress.org/Template_Tags/get_posts
            $posts = get_posts('numberposts=-1&post_status=publish');

            $choices = array();

            foreach ($posts as $post) {
                $choices[] = array('text' => $post->post_title, 'value' => $post->post_title);
            }

            // update 'Select a Post' to whatever you'd like the instructive option to be
            $field->placeholder = 'Select a Post';
            $field->choices     = $choices;

        }

        return $form;
    }

    /**
     * @param $form
     *
     * @return mixed
     */
    public function set_chosen_options($form)
    {
        ?>
        <script type="text/javascript">
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

        </script>

        <?php
        //return the form object from the php hook
        return $form;
    }

    public function do_price_stuff( $form ) {
        ?>
        <script type="text/javascript">
            jQuery(document).on('gform_price_change', function(event, productIds, htmlInput){
                console.log(productIds);
                if (productIds['productFieldId'] == 13){
                    alert('Price updated');
                }
            });
        </script>
        <?php
        return $form;
    }


}