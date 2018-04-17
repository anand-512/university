<?php
/**
 * Created by PhpStorm.
 * User: anand
 * Date: 16/04/18
 * Time: 4:48 PM
 */

add_action('rest_api_init', 'universityLikeRoutes');

function universityLikeRoutes() {
    register_rest_route('university/v1', 'manageLike', [
        'methods' => 'POST',
        'callback' => 'createLike'
    ]);

    register_rest_route('university/v1', 'manageLike', [
        'methods' => 'DELETE',
        'callback' => 'deleteLike'
    ]);
}

function createLike($data) {
    if(is_user_logged_in()) {
        $professorID = sanitize_text_field($data['professorID']);

        $existQuery = new WP_Query([
            'author' => get_current_user_id(),
            'post_type' => 'like',
            'meta_query' => [
                [
                    'key' => 'liked_professor_id',
                    'compare' => '=',
                    'value' => $professorID
                ]
            ]
        ]);

        if($existQuery->found_posts == 0 && get_post_type($professorID) == 'professor') {
            return wp_insert_post([
                'post_type' => 'like',
                'post_status' => 'publish',
                'post_title' => '4th PHP test',
                'meta_input' => [
                    'liked_professor_id' => $professorID
                ]
            ]);
        } else {
            die("Invalid Professor ID");
        }

    } else {
        die("Only logged in users can create a like");
    }
}

function deleteLike($data) {
    $likeID = sanitize_text_field($data['like']);
    if(get_current_user_id() == get_post_field('post_author', $likeID) AND get_post_type($likeID) == 'like') {
        wp_delete_post($likeID, true);
        return "Congrats, Like deleted.";
    } else {
        die("You do not have permission to delete that.");
    }
}