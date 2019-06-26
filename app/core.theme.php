<?php

namespace App;

/**
 * Class Core
 * @package App
 */
class Core
{
    /**
     * @var string
     */
    private $namespace;

    /**
     * Core constructor.
     */
    public function __construct()
    {
        add_action('rest_api_init', [$this, 'rest_api_route_get_cpt']);
        $this->namespace = 'nix/v1';
    }

    /**
     * Register rest api route for crud cpt data and login
     */
    public function rest_api_route_get_cpt()
    {
        register_rest_route($this->namespace, '(?P<id>\d+)', array(
            'methods' => 'GET',
            'callback' => [$this, 'get_cpt_data'],
            'args' => array(
                'id' => array(
                    'validate_callback' => function ($param) {
                        return is_numeric($param);
                    }
                ),
            ),
            'permission_callback' => null,
        ));

        register_rest_route($this->namespace, 'list', array(
            'methods' => 'GET',
            'callback' => [$this, 'get_cpt_data_list'],
            'permission_callback' => null,
        ));

        register_rest_route($this->namespace, 'create', array(
                'methods' => 'POST',
                'callback' => [$this, 'create_cpt_data'],
                'args' => array(
                    'name' => array(
                        'type' => 'string',
                        'required' => true,
                    ),
                    'description' => array(
                        'type' => 'string',
                        'required' => true,
                    ),
                ),

               'permission_callback' => array($this, 'get_items_permissions_check'),
            )
        );

        register_rest_route($this->namespace, 'login', array(
                'methods' => 'POST',
                'callback' => [$this, 'get_cpt_login'],
                'args' => array(
                    'username' => array(
                        'type' => 'string',
                        'required' => true,
                    ),
                    'password' => array(
                        'type' => 'string',
                        'required' => true,
                    ),
                ),
            )
        );

        register_rest_route($this->namespace, 'update/(?P<id>\d+)', array(
                'methods' => 'PUT',
                'callback' => [$this, 'update_cpt_data'],
                'args' => array(
                    'id' => array(
                        'validate_callback' => function ($param) {
                            return is_numeric($param);
                        },
                    ),
                    'name' => array(
                        'type' => 'string',
                        'required' => true,
                    ),
                    'description' => array(
                        'type' => 'string',
                        'required' => true,
                    ),
                ),

                'permission_callback' => array($this, 'get_items_permissions_check'),
            )
        );

        register_rest_route($this->namespace, 'delete/(?P<id>\d+)', array(
                'methods' => 'DELETE',
                'callback' => [$this, 'delete_cpt_data'],
                'args' => array(
                    'id' => array(
                        'validate_callback' => function ($param) {
                            return is_numeric($param);
                        },
                    ),
                    'permission_callback' => array($this, 'get_items_permissions_check'),
                )
            )
        );

    }

    /**
     * @param $request
     * @return mixed|\WP_REST_Response
     */
    public function get_cpt_data($request)
    {
        $cpt_id = $request['id'];
        $cptData = CustomTableModel::find($cpt_id);
        return rest_ensure_response($cptData);
    }

    /**
     * @param $request
     *
     * @return string
     */
    public function get_cpt_login($request)
    {
        $creds = array();
        $creds['user_login'] = $request['username'];
        $creds['user_password'] = $request['password'];
        $creds['remember'] = true;

        $user = wp_signon( $creds, false );

        if ( is_wp_error($user) ) {
            return $user->get_error_message();
        }
        return 'successfully login';
    }

    /**
     * @return mixed|\WP_REST_Response
     */
    public function get_cpt_data_list()
    {
        $cptData = CustomTableModel::all()->toArray();
        return rest_ensure_response($cptData);
    }

    /**
     * @param $request
     * @return string
     */
    public function create_cpt_data($request)
    {
        CustomTableModel::create(
            [
                'title' => $request->get_param('name'),
                'description' => $request->get_param('description'),
            ]
        );

        return 'cpt is successfully created';
    }

    /**
     * @param $request
     * @return string
     */
    public function update_cpt_data($request)
    {
        $cpt_id = $request['id'];
        CustomTableModel::where('id', $cpt_id)->update([
            'title' => $request->get_param('name'),
            'description' => $request->get_param('description'),
            'updated_at' => current_time('mysql', false),
        ]);
        return 'cpt is successfully updated';
    }

    /**
     * @param $request
     * @return string
     */
    public function delete_cpt_data($request)
    {
        $cpt_id = $request['id'];
        CustomTableModel::where('id', $cpt_id)->delete();
        return 'cpt is successfully deleted';
    }

    /**
     * Check if a given request has access to get items
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|bool
     */
    public function get_items_permissions_check($request)
    {
        $creds = array();
        $creds['user_login'] = $_SERVER['PHP_AUTH_USER'];
        $creds['user_password'] = $_SERVER['PHP_AUTH_PW'];
        $creds['remember'] = true;
        $user = wp_signon( $creds, false );

        if ( is_wp_error($user) ) {
            return false;
        }
        return true;
    }

}
