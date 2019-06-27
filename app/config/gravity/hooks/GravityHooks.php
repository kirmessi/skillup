<?php

namespace App\Config\Gravity\Hooks;

class GravityHooks
{
    /**
     * Gravity constructor.
     */
    public function __construct()
    {
        add_filter('gform_pre_render_1', [$this, 'populatePosts']);
        add_filter('gform_pre_validation_1', [$this, 'populatePosts']);
        add_filter('gform_pre_submission_filter_1', [$this, 'populatePosts']);
        add_filter('gform_admin_pre_render_1', [$this, 'populatePosts']);
    }

    /**
     * @param $form
     *
     * @return mixed
     */
    public function populatePosts($form)
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


}
