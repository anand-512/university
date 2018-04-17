<?php
/**
 * Created by PhpStorm.
 * User: anand
 * Date: 24/03/18
 * Time: 5:59 PM
 */

require get_theme_file_path('/inc/like-route.php');
require get_theme_file_path('/inc/search-route.php');

function university_custom_rest() {
    register_rest_field('post', 'authorName', [
            'get_callback' => function() {
                return get_the_author();
            }
    ]);
    register_rest_field('note', 'userNoteCount', [
        'get_callback' => function() {
            return count_user_posts(get_current_user_id(), 'note');
        }
    ]);
}
add_action('rest_api_init', 'university_custom_rest');

function pageBanner($args = NULL) {
    if(!$args['title']) {
        $args['title'] = get_the_title();
    }

    if(!$args['subtitle']) {
        $args['subtitle'] = get_field('page_banner_subtitle');
    }

    if(!$args['photo']) {
        if(get_field('page_banner_background_image')) {
            $pageBannerImage = get_field('page_banner_background_image');
            $args['photo'] = $pageBannerImage['sizes']['pageBanner'];
        }
        else {
            $args['photo'] = get_theme_file_uri('/images/ocean.jpg');
        }
    }

    ?>
    <div class="page-banner">
        <div class="page-banner__bg-image" style="background-image: url(<?= $args['photo'] ?>);"></div>
        <div class="page-banner__content container container--narrow">
            <h1 class="page-banner__title"><?= $args['title'] ?></h1>
            <div class="page-banner__intro">
                <p><?= $args['subtitle'] ?></p>
            </div>
        </div>
    </div>
<?php
}

function university_files() {

    wp_enqueue_script('googleMap', '//maps.googleapis.com/maps/api/js?key=AIzaSyCIvku8_CodRMEcW-CEXOg4fr487TGx1Ig', NULL, '1.0', true);
    wp_enqueue_script('main-university-js', get_theme_file_uri('/js/scripts-bundled.js'), NULL, '1.0', true);
    wp_enqueue_style('custom-google-fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
    wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
    wp_enqueue_style('university-main-style', get_stylesheet_uri());
    wp_localize_script('main-university-js', 'universityData', [
       'root_url' => get_site_url(),
        'nonce' => wp_create_nonce('wp_rest'),
    ]);

}
add_action('wp_enqueue_scripts', 'university_files');

function university_features() {
//    register_nav_menu('headerMenuLocation', 'Header Menu Location');
//    register_nav_menu('footerLocationOne', 'Footer Location One');
//    register_nav_menu('footerLocationTwo', 'Footer Location Two');

    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_image_size('professorLandscape', 400, 260, true);
    add_image_size('professorPortrait', 480, 650, true);
    add_image_size('pageBanner', 1500, 350, true);
}
add_action('after_setup_theme', 'university_features');

function university_adjust_queries($query) {
    if(!is_admin() && is_post_type_archive('event') && $query->is_main_query()) {
        $today = date('Ymd');
        $query->set('meta_key', 'event_date');
        $query->set('orderby', 'meta_value_num');
        $query->set('order', 'ASC');
        $query->set('meta_query', [
            [
                'key' => 'event_date',
                'compare' => '>=',
                'value' => $today,
                'type' => 'numeric',
            ],
        ]);
    }

    if(!is_admin() && is_post_type_archive('program') && $query->is_main_query()) {
        $query->set('orderby', 'title');
        $query->set('order', 'ASC');
        $query->set('posts_per_page', -1);
    }

    if(!is_admin() && is_post_type_archive('campus') && $query->is_main_query()) {
        $query->set('posts_per_page', -1);
    }
}
add_action('pre_get_posts', 'university_adjust_queries');

function universityMapKey($api) {
    $api['key'] = 'AIzaSyCIvku8_CodRMEcW-CEXOg4fr487TGx1Ig';
    return $api;
}
add_filter('acf/fields/google_map/api', 'universityMapKey');

//Redirect subscriber accounts out of admin and onto homepage
add_action('admin_init', 'redirectSubsToFrontend');
function redirectSubsToFrontend() {
    $ourCurrentUser = wp_get_current_user();
    if(count($ourCurrentUser->roles) == 1 AND $ourCurrentUser->roles[0] == 'subscriber') {
        wp_redirect(site_url('/'));
        exit;
    }

}

add_action('wp_loaded', 'noSubsAdminBar');
function noSubsAdminBar() {
    $ourCurrentUser = wp_get_current_user();
    if(count($ourCurrentUser->roles) == 1 AND $ourCurrentUser->roles[0] == 'subscriber') {
        show_admin_bar(false);
    }
}

//Customize Login Screen

add_filter('login_headerurl', 'ourHeaderUrl');
function ourHeaderUrl() {
    return esc_url(site_url('/'));
}

add_action('login_enqueue_scripts', 'ourLoginCSS');

function ourLoginCSS() {
    wp_enqueue_style('university-main-style', get_stylesheet_uri());
    wp_enqueue_style('custom-google-fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
}

add_filter('login_headertitle', 'ourLoginTitle');

function ourLoginTitle() {
    return get_bloginfo('name');
}

//Force note posts to be private
add_filter('wp_insert_post_data', 'makeNotePrivate', 10, 2);

function makeNotePrivate($data, $postArr) {
    if($data['post_type'] == 'note') {
        if(count_user_posts(get_current_user_id(), 'note') > 4 AND !$postArr['ID']) {
            die("You've reached your post limit");
        }
        $data['post_title'] = sanitize_text_field($data['post_title']);
        $data['post_content'] = sanitize_textarea_field($data['post_content']);
    }
    if($data['post_type'] == 'note' AND $data['post_status'] != 'trash') {
        $data['post_status'] = "private";
    }
    return $data;
}