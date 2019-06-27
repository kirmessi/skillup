<?php

namespace App\Config\Acf\Fields;

use App\Orm\Model\CustomTableModel;

/**
 * Class Cpt
 * @package App\Config\Acf\Fields
 */
class AcfFields
{
    /**
     * @var array
     */
    private $keys = [
        'title',
        'description',
    ];

    /**
     * Cpt constructor.
     */
    public function __construct()
    {
        add_action('acf/init', [$this, 'registerCptFields']);
        add_filter('acf/load_value', [$this, 'myAcfLoadValue'], 11, 3);
        add_filter('acf/update_value', [$this, 'myAcfUpdateValue'], 10, 2);
    }

    /**
     * Register cpt fields
     */
    public function registerCptFields()
    {
        acf_add_local_field_group(array(
            'key' => 'group_5d0b440557dfgd98',
            'title' => 'post CPT',
            'fields' => array(
                array(
                    'key' => 'title',
                    'label' => 'title',
                    'name' => 'title',
                    'type' => 'text',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'placeholder' => '',
                    'prepend' => '',
                    'append' => '',
                    'maxlength' => '',
                ),
                array(
                    'key' => 'description',
                    'name' => 'description',
                    'type' => 'textarea',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'placeholder' => '',
                    'maxlength' => '',
                    'rows' => '',
                    'new_lines' => '',
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'post',
                    ),
                ),
            ),
            'menu_order' => 0,
            'position' => 'normal',
            'style' => 'default',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
            'hide_on_screen' => '',
            'active' => true,
            'description' => '',
        ));
    }

    /**
     * @param $value
     * @param $post_id
     * @param $field
     *
     * @return mixed
     */
    public function myAcfLoadValue($value, $post_id, $field)
    {
        $data = CustomTableModel::where('post_id', $post_id)->get()->toArray();

        if (in_array($field['key'], $this->keys)) {
            return $data[0]{$field['key']};
        }
    }

    /**
     * @param $value
     * @param $post_id
     */
    public function myAcfUpdateValue($value, $post_id)
    {
        if (empty($_POST['acf'])) {
            return;
        }

        $fields = $_POST['acf'];

        $post_id_exists = CustomTableModel::where('post_id', $post_id)->exists();

        if ($post_id_exists) {
            CustomTableModel::where('post_id', $post_id)->update([
                'title' => $fields['title'],
                'description' => $fields['description'],
                'updated_at' => current_time('mysql', false),
            ]);
        } else {
            CustomTableModel::create(
                [
                    'title' => $fields['title'],
                    'description' => $fields['description'],
                    'post_id' => $post_id,
                ]
            );
        }
    }
}
