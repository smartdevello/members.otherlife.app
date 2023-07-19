<?php

// Exit if accessed directly

if (! defined('ABSPATH'))
    exit;

class MentorCPT
{
    private static $instance = null;
    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new MentorCPT();
        }
        return self::$instance;
    }
    public function __construct()
    {
        add_action('rest_api_init', array($this, "api_inits"));
        //shortcode order
        add_shortcode('getMentorSection', array($this, 'getMentorSection'));
    }
    public function api_inits()
    {
        register_rest_route("members/v1", "select_mentor", array(
            "methods" => "POST",
            "callback" => array($this, "select_mentor"),
            "permission_callback" => "__return_true"
        ));

        register_rest_route("members/v1", "all_mentors", array(
            "methods" => "GET",
            "callback" => array($this, "all_mentors"),
            "permission_callback" => "__return_true"
        ));
        register_rest_route("members/v1", "getMentorSection", array(
            "methods" => "GET",
            "callback" => array($this, "api_getMentorSection"),
            "permission_callback" => "__return_true"
        ));
    }
    public function api_getMentorSection()
    {
        $mentor_id = get_user_meta(wp_get_current_user()->ID, 'mentor_id', true);
        if (empty($mentor_id))
            $mentor_id = '9968';

        // $field = $atts['field'];

        $data = array(
            'ID' => $mentor_id,
            'name' => get_post_field("name", $mentor_id),
            'email' => get_post_field("email", $mentor_id),
            'message' => get_post_field("message", $mentor_id),
            'picture_url' => get_post_field("picture_url", $mentor_id),
            'facebook_url' => get_post_field("facebook_url", $mentor_id),
            'call_url' => get_post_field('call_url', $mentor_id),
            'infinite_link' => get_post_field('infinite_link', $mentor_id),
            'test' => '3',
        );
        return $data;
    }
    public function getMentorSection($atts)
    {
        $mentor_id = get_user_meta(wp_get_current_user()->ID, 'mentor_id', true);
        if (empty($mentor_id))
            $mentor_id = '9968';

        $field = $atts['field'];

        $data = array(
            'ID' => $mentor_id,
            'name' => get_post_field("name", $mentor_id),
            'email' => get_post_field("email", $mentor_id),
            'message' => get_post_field("message", $mentor_id),
            'picture_url' => get_post_field("picture_url", $mentor_id),
            'facebook_url' => get_post_field("facebook_url", $mentor_id),
            'call_url' => get_post_field('call_url', $mentor_id),
            'infinite_link' => get_post_field('infinite_link', $mentor_id),
        );
        return $data[$field];
    }
    public function all_mentors()
    {
        $posts = get_posts(array(
            'posts_per_page' => -1,
            'post_type' => 'mentor',
            "post_status" => 'publish'
        ));
        $data = [];
        foreach ($posts as $post) {
            $data[] = array(
                'ID' => $post->ID,
                'name' => get_post_field("name", $post->ID),
                'email' => get_post_field("email", $post->ID),
                'message' => get_post_field("message", $post->ID),
                'picture_url' => get_post_field("picture_url", $post->ID),
                'facebook_url' => get_post_field("facebook_url", $post->ID),
                'call_url' => get_post_field('call_url', $post->ID),
                'infinite_link' => get_post_field('infinite_link', $post->ID),
            );
        }
        return $data;
    }
    public function select_mentor($request)
    {
        $payload = $request->get_json_params();
        // error_log('=====select mentor=========');
        // error_log(print_r($payload, true));
        // error_log('===========================');
        $mentor_id = $payload['customData']['mentor_id'];
        $user = get_user_by('email', $payload['email']);
        update_user_meta($user->ID, 'mentor_id', $mentor_id);

        return $mentor_id;
    }
}
MentorCPT::getInstance();