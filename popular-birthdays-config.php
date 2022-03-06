<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

date_default_timezone_set("Africa/Nairobi");

session_start();

function create_tables(){
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

    $sql = "CREATE TABLE `{$wpdb->base_prefix}birthday_stats` 
( `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY, `user_id` int(11) DEFAULT NULL, `post_id` int(11) DEFAULT NULL, `date_of_birth` text NOT NULL, 
`city` text NOT NULL, `birth_place` text NOT NULL, 
`birth_sign`  text NOT NULL,
`name` text NULL,
`member_of` text NULL,
`url` VARCHAR(255) NOT NULL,
`views` int(11) DEFAULT 0,
`images` text NULL

) $charset_collate;";
    $res = dbDelta($sql);

//echo 'Creating birthday_stats table: ';
//var_dump($res);
//echo '<br>';


    //    lets create categories table if not exists
    $sql = "CREATE TABLE `{$wpdb->base_prefix}categories` 
( `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY, 
`cat_name` text NOT NULL,
`cat_slug` text NOT NULL,
`url` VARCHAR(255) NOT NULL,
`counts_source` INT(11) DEFAULT 0, 
`counts_saved` INT(11) DEFAULT 0,
`created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
`updated_at` TIMESTAMP on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP

) $charset_collate;";
    $res = dbDelta($sql);


    //    lets create categories table if not exists
    $sql = "CREATE TABLE `{$wpdb->base_prefix}categories_names` 
( `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY, 
`cat_name` text NOT NULL,
`cat_slug` text NOT NULL,
`url` VARCHAR(255) NOT NULL,
`counts_source` INT(11) DEFAULT 0, 
`counts_saved` INT(11) DEFAULT 0,
`created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
`updated_at` TIMESTAMP on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP

) $charset_collate;";
    $res = dbDelta($sql);

    //    lets create categories table if not exists
    $sql = "CREATE TABLE `{$wpdb->base_prefix}categories_cities` 
( `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY, 
`cat_name` text NOT NULL,
`cat_slug` text NOT NULL,
`url` VARCHAR(255) NOT NULL,
`counts_source` INT(11) DEFAULT 0, 
`counts_saved` INT(11) DEFAULT 0,
`created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
`updated_at` TIMESTAMP on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP

) $charset_collate;";
    $res = dbDelta($sql);

    //    lets create categories table if not exists
    $sql = "CREATE TABLE `{$wpdb->base_prefix}categories_countries` 
( `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY, 
`cat_name` text NOT NULL,
`cat_slug` text NOT NULL,
`url` VARCHAR(255) NOT NULL,
`counts_source` INT(11) DEFAULT 0, 
`counts_saved` INT(11) DEFAULT 0,
`created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
`updated_at` TIMESTAMP on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP

) $charset_collate;";
    $res = dbDelta($sql);

    //    lets create categories table if not exists
    $sql = "CREATE TABLE `{$wpdb->base_prefix}categories_countries_career` 
( `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY, 
`cat_name` text NOT NULL,
`cat_slug` text NOT NULL,
`url` VARCHAR(255) NOT NULL,
`counts_source` INT(11) DEFAULT 0, 
`counts_saved` INT(11) DEFAULT 0,
`created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
`updated_at` TIMESTAMP on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP

) $charset_collate;";
    $res = dbDelta($sql);


    //    lets create categories table if not exists
    $sql = "CREATE TABLE `{$wpdb->base_prefix}categories_age_astrology` 
( `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY, 
`cat_name` text NOT NULL,
`cat_slug` text NOT NULL,
`url` VARCHAR(255) NOT NULL,
`counts_source` INT(11) DEFAULT 0, 
`counts_saved` INT(11) DEFAULT 0,
`created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
`updated_at` TIMESTAMP on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP

) $charset_collate;";
    $res = dbDelta($sql);

    //    lets create categories table if not exists
    $sql = "CREATE TABLE `{$wpdb->base_prefix}categories_age_profession` 
( `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY, 
`cat_name` text NOT NULL,
`cat_slug` text NOT NULL,
`url` VARCHAR(255) NOT NULL,
`counts_source` INT(11) DEFAULT 0, 
`counts_saved` INT(11) DEFAULT 0,
`created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
`updated_at` TIMESTAMP on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP

) $charset_collate;";
    $res = dbDelta($sql);


    //    lets create categories table if not exists
    $sql = "CREATE TABLE `{$wpdb->base_prefix}categories_astrology_career` 
( `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY, 
`cat_name` text NOT NULL,
`cat_slug` text NOT NULL,
`url` VARCHAR(255) NOT NULL,
`counts_source` INT(11) DEFAULT 0, 
`counts_saved` INT(11) DEFAULT 0,
`created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
`updated_at` TIMESTAMP on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP

) $charset_collate;";
    $res = dbDelta($sql);

    //    lets create categories table if not exists
    $sql = "CREATE TABLE `{$wpdb->base_prefix}categories_awards` 
( `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY, 
`cat_name` text NOT NULL,
`cat_slug` text NOT NULL,
`url` VARCHAR(255) NOT NULL,
`counts_source` INT(11) DEFAULT 0, 
`counts_saved` INT(11) DEFAULT 0,
`created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
`updated_at` TIMESTAMP on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP

) $charset_collate;";
    $res = dbDelta($sql);

    //    lets create categories table if not exists
    $sql = "CREATE TABLE `{$wpdb->base_prefix}categories_bands` 
( `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY, 
`cat_name` text NOT NULL,
`cat_slug` text NOT NULL,
`url` VARCHAR(255) NOT NULL,
`counts_source` INT(11) DEFAULT 0, 
`counts_saved` INT(11) DEFAULT 0,
`created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
`updated_at` TIMESTAMP on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP

) $charset_collate;";
    $res = dbDelta($sql);

    //    lets create categories table if not exists
    $sql = "CREATE TABLE `{$wpdb->base_prefix}categories_broadway` 
( `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY, 
`cat_name` text NOT NULL,
`cat_slug` text NOT NULL,
`url` VARCHAR(255) NOT NULL,
`counts_source` INT(11) DEFAULT 0, 
`counts_saved` INT(11) DEFAULT 0,
`created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
`updated_at` TIMESTAMP on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP

) $charset_collate;";
    $res = dbDelta($sql);

    //    lets create categories table if not exists
    $sql = "CREATE TABLE `{$wpdb->base_prefix}categories_dancecrews` 
( `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY, 
`cat_name` text NOT NULL,
`cat_slug` text NOT NULL,
`url` VARCHAR(255) NOT NULL,
`counts_source` INT(11) DEFAULT 0, 
`counts_saved` INT(11) DEFAULT 0,
`created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
`updated_at` TIMESTAMP on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP

) $charset_collate;";
    $res = dbDelta($sql);

    //    lets create categories table if not exists
    $sql = "CREATE TABLE `{$wpdb->base_prefix}categories_bands_city` 
( `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY, 
`cat_name` text NOT NULL,
`cat_slug` text NOT NULL,
`url` VARCHAR(255) NOT NULL,
`counts_source` INT(11) DEFAULT 0, 
`counts_saved` INT(11) DEFAULT 0,
`created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
`updated_at` TIMESTAMP on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP

) $charset_collate;";
    $res = dbDelta($sql);

    //    lets create categories table if not exists
    $sql = "CREATE TABLE `{$wpdb->base_prefix}categories_bands_country` 
( `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY, 
`cat_name` text NOT NULL,
`cat_slug` text NOT NULL,
`url` VARCHAR(255) NOT NULL,
`counts_source` INT(11) DEFAULT 0, 
`counts_saved` INT(11) DEFAULT 0,
`created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
`updated_at` TIMESTAMP on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP

) $charset_collate;";
    $res = dbDelta($sql);

    //    lets create categories table if not exists
    $sql = "CREATE TABLE `{$wpdb->base_prefix}categories_bands_genre` 
( `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY, 
`cat_name` text NOT NULL,
`cat_slug` text NOT NULL,
`url` VARCHAR(255) NOT NULL,
`counts_source` INT(11) DEFAULT 0, 
`counts_saved` INT(11) DEFAULT 0,
`created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
`updated_at` TIMESTAMP on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP

) $charset_collate;";
    $res = dbDelta($sql);

    //    lets create categories table if not exists
    $sql = "CREATE TABLE `{$wpdb->base_prefix}categories_bands_year` 
( `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY, 
`cat_name` text NOT NULL,
`cat_slug` text NOT NULL,
`url` VARCHAR(255) NOT NULL,
`counts_source` INT(11) DEFAULT 0, 
`counts_saved` INT(11) DEFAULT 0,
`created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
`updated_at` TIMESTAMP on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP

) $charset_collate;";
    $res = dbDelta($sql);


    //    lets create categories table if not exists
    $sql = "CREATE TABLE `{$wpdb->base_prefix}categories_awards_type` 
( `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY, 
`cat_name` text NOT NULL,
`cat_slug` text NOT NULL,
`url` VARCHAR(255) NOT NULL,
`counts_source` INT(11) DEFAULT 0, 
`counts_saved` INT(11) DEFAULT 0,
`created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
`updated_at` TIMESTAMP on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP

) $charset_collate;";
    $res = dbDelta($sql);



//    astrology views
    $horoscope_dates = [
        'Aquarius' => ['01-20','02-18'],
        'Pisces' => ['02-19','03-20'],
        'Aries' => ['03-21','04-19'],
        'Taurus' => ['04-20','05-20'],
        'Gemini' => ['05-21','06-20'],
        'Cancer' => ['06-21','07-22'],
        'Leo' => ['07-23','08-22'],
        'Virgo' => ['08-23','09-22'],
        'Libra' => ['09-23','10-22'],
        'Scorpio' => ['10-23','11-21'],
        'Sagittarius' => ['11-22','12-21'],
        'Capricorn' => ['12-22','01-19']
    ];
    foreach ($horoscope_dates as $key => $horoscope_date){

        $name = strtolower($key).'_';

        $sql = "CREATE TABLE `{$wpdb->base_prefix}{$name}views` 
( `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY, `post_id` int(11) NOT NULL, 
`viewed` int(11) NOT NULL,
`ip` text NULL
) $charset_collate;";
        $res = dbDelta($sql);

//        echo 'Creating '.$wpdb->base_prefix.$name.'views table: ';
//        var_dump($res);
//        echo '<br>';


    }


//    create member_of (group_stats) tables


    $sql = "CREATE TABLE `{$wpdb->base_prefix}movies` 
( `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY, `post_id` int(11) NOT NULL, 
`name` text NOT NULL,
 `stats_ids` text NOT NULL,
 `genre` text NULL,
 `released` text NULL,
 `rating` text NULL,
 `views` int(11) DEFAULT 0,
 `images` text NULL
 
 
) $charset_collate;";
    $res = dbDelta($sql);

//    echo 'Creating '.$wpdb->base_prefix.'movies table: ';
//    var_dump($res);
//    echo '<br>';

    $sql = "CREATE TABLE `{$wpdb->base_prefix}movies_views` 
( `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY, `post_id` int(11) NOT NULL, 
`viewed` int(11) NOT NULL,
`ip` text NULL
) $charset_collate;";
    $res = dbDelta($sql);

//    echo 'Creating '.$wpdb->base_prefix.'movies_views table: ';
//    var_dump($res);
//    echo '<br>';

    $sql = "CREATE TABLE `{$wpdb->base_prefix}schools` 
( `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY, `post_id` int(11) NOT NULL, 
`name` text NOT NULL,
 `established` text NULL,
 `location` text NULL,
 `nickname` text NULL,
 `views` int(11) DEFAULT 0,
 `images` text NULL
 
) $charset_collate;";
    $res = dbDelta($sql);

//    echo 'Creating '.$wpdb->base_prefix.'schools table: ';
//    var_dump($res);
//    echo '<br>';

    $sql = "CREATE TABLE `{$wpdb->base_prefix}schools_views` 
( `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY, `post_id` int(11) NOT NULL, 
`viewed` int(11) NOT NULL,
`ip` text NULL
) $charset_collate;";
    $res = dbDelta($sql);

//    echo 'Creating '.$wpdb->base_prefix.'schools_views table: ';
//    var_dump($res);
//    echo '<br>';

    $sql = "CREATE TABLE `{$wpdb->base_prefix}shows` 
( `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY, `post_id` int(11) NOT NULL, 
`name` text NOT NULL,
 `stats_ids` text NOT NULL,
 `genre` text NULL,
 `premiered` text NULL,
 `network` text NULL,
 `views` int(11) DEFAULT 0,
 `images` text NULL
 
) $charset_collate;";
    $res = dbDelta($sql);


//    echo 'Creating '.$wpdb->base_prefix.'shows table: ';
//    var_dump($res);
//    echo '<br>';

    $sql = "CREATE TABLE `{$wpdb->base_prefix}shows_views` 
( `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY, `post_id` int(11) NOT NULL, 
`viewed` int(11) NOT NULL,
`ip` text NULL
) $charset_collate;";
    $res = dbDelta($sql);

//    echo 'Creating '.$wpdb->base_prefix.'shows_views table: ';
//    var_dump($res);
//    echo '<br>';

    $sql = "CREATE TABLE `{$wpdb->base_prefix}songs` 
( `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY, `post_id` int(11) NOT NULL, 
`name` text NOT NULL,
 `stats_ids` text NOT NULL,
 `released` text NULL,
 `artist` text NULL,
 `album` text NULL,
 `runtime` text NULL,
 `label` text NULL,
 `genre` text NULL,
 `views` int(11) DEFAULT 0,
 `images` text NULL
 
) $charset_collate;";
    $res = dbDelta($sql);

//    echo 'Creating '.$wpdb->base_prefix.'songs table: ';
//    var_dump($res);
//    echo '<br>';

    $sql = "CREATE TABLE `{$wpdb->base_prefix}songs_views` 
( `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY, `post_id` int(11) NOT NULL, 
`viewed` int(11) NOT NULL,
`ip` text NULL
) $charset_collate;";
    $res = dbDelta($sql);

//    echo 'Creating '.$wpdb->base_prefix.'songs_views table: ';
//    var_dump($res);
//    echo '<br>';


//    Popularity table
    $sql = "CREATE TABLE `{$wpdb->base_prefix}popularity` 
( `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY, `post_id` int(11) NOT NULL, 
`popularity` int(11) DEFAULT 0,
 `dob_rank` int(11) DEFAULT 0,
 `age_rank` int(11) DEFAULT 0,
 `birth_place_rank` int(11) DEFAULT 0,
 `first_name_rank` int(11) DEFAULT 0,
 `birth_city_rank` int(11) DEFAULT 0,
 `cat_rank` int(11) DEFAULT 0
) $charset_collate;";
    $res = dbDelta($sql);

    //    Popularity cat table
    $sql = "CREATE TABLE `{$wpdb->base_prefix}popularity_category` 
( `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY, `term_id` int(11) NOT NULL, 
 `updated_at` TIMESTAMP on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) $charset_collate;";
    $res = dbDelta($sql);

}

/**
 * Enqueue scripts and styles
 */
function popular_birthdays_scripts() {
    wp_enqueue_style( 'bootstrap-min-css','/wp-content/plugins/popular-birthdays/css/bootstrap-min.css' );


    $style_version = filemtime(get_stylesheet_directory() . '/style.css').'sT9oUdzdW';

    wp_enqueue_style( 'child-style',
        '/wp-content/plugins/popular-birthdays/css/style.css',
        [],
        $style_version);


    $v = now();

    wp_enqueue_style( 'font-awesome','/wp-content/plugins/popular-birthdays/css/font-awesome/css/fontawesome-all.css?wd=s' );
    wp_enqueue_script( 'bootstrap-script', '/wp-content/plugins/popular-birthdays/js/js.js', array(), $v, true );
}

add_action( 'wp_enqueue_scripts', 'popular_birthdays_scripts' );

// plugin activation
register_activation_hook( $file, 'popular_activate' );
function popular_activate() {
    add_option( 'my_plugin_option', 'some-value' );


    create_tables();

}

// plugin deactivation
register_deactivation_hook( $file, 'popular_deactivate' );
function popular_deactivate() {
    // some code for deactivation...
    // exit('deactivation!!!');

}

// plugin uninstallation
register_uninstall_hook( $file, 'popular_uninstall' );
function popular_uninstall() {

    include 'popular-birthdays-uninstall.php';

}

$check_page_exist = get_page_by_title('Dynamic Page', 'OBJECT', 'page');
// Check if the page already exists
if(empty($check_page_exist)) {

    $page_id = wp_insert_post(
        array(
            'comment_status' => 'close',
            'ping_status'    => 'close',
            'post_author'    => 1,
            'post_title'     => ucwords('Dynamic Page'),
            'post_name'      => 'dynamic-page',
            'post_status'    => 'publish',
            'post_content'   => "The Dynamic Page.",
            'post_type'      => 'page',
            'post_parent'    => 0
        )
    );

}



if (@$_GET['delete'] == '12345qSwq_233Dw') {
    global $wpdb;


    $wpdb->query( "DROP TABLE IF EXISTS ".$wpdb->prefix."birthday_stats" );
    $wpdb->query( "DROP TABLE IF EXISTS ".$wpdb->prefix."views" );
    $wpdb->query( "DROP TABLE IF EXISTS ".$wpdb->prefix."songs" );
    $wpdb->query( "DROP TABLE IF EXISTS ".$wpdb->prefix."shows" );
    $wpdb->query( "DROP TABLE IF EXISTS ".$wpdb->prefix."schools" );
    $wpdb->query( "DROP TABLE IF EXISTS ".$wpdb->prefix."movies" );


    //    astrology views
    $horoscope_dates = [
        'Aquarius' => ['01-20','02-18'],
        'Pisces' => ['02-19','03-20'],
        'Aries' => ['03-21','04-19'],
        'Taurus' => ['04-20','05-20'],
        'Gemini' => ['05-21','06-20'],
        'Cancer' => ['06-21','07-22'],
        'Leo' => ['07-23','08-22'],
        'Virgo' => ['08-23','09-22'],
        'Libra' => ['09-23','10-22'],
        'Scorpio' => ['10-23','11-21'],
        'Sagittarius' => ['11-22','12-21'],
        'Capricorn' => ['12-22','01-19']
    ];
    foreach ($horoscope_dates as $key => $horoscope_date){

        $name = strtolower($key).'_';

        $wpdb->query( "DROP TABLE IF EXISTS ".$wpdb->prefix.$name."views" );

    }


    create_tables();

    die ('OKay');

}

add_action('after_setup_theme', 'popular_remove_admin_bar');

function popular_remove_admin_bar() {
    if (!current_user_can('administrator') && !is_admin()) {
        show_admin_bar(false);
    }
}


//before_delete_post
add_action( 'before_delete_post', function( $post_id ) {

    global $wpdb;

    $attachments = get_attached_media( '', $post_id );
    foreach ($attachments as $attachment) {
        wp_delete_attachment( $attachment->ID, 'true' );
    }

//    delete entities in related tables too
    $tables = ['birthday_stats', 'views', 'songs','songs_views',
        'shows','shows_views','movies','movies_views',
        'schools','schools_views'];
    ;
    //    astrology views
    $horoscope_dates = [
        'Aquarius' => ['01-20','02-18'],
        'Pisces' => ['02-19','03-20'],
        'Aries' => ['03-21','04-19'],
        'Taurus' => ['04-20','05-20'],
        'Gemini' => ['05-21','06-20'],
        'Cancer' => ['06-21','07-22'],
        'Leo' => ['07-23','08-22'],
        'Virgo' => ['08-23','09-22'],
        'Libra' => ['09-23','10-22'],
        'Scorpio' => ['10-23','11-21'],
        'Sagittarius' => ['11-22','12-21'],
        'Capricorn' => ['12-22','01-19']
    ];
    $arr = [];
    foreach ($horoscope_dates as $key => $horoscope_date){

        $name = strtolower($key).'_views';

        $arr[] = $name;

    }

    $tables = array_merge($tables, $arr);

    foreach ($tables as $table) {
        @$wpdb->delete($wpdb->prefix.$table, ['post_id' => $post_id]);

    }

} );

create_tables()

?>