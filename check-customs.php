<?php
if (@$_GET['action'] == 'do-popularity'){

    add_action('init',
            function (){
global $wp_query;


                $url = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
                $cat_slug = @explode("/category/", $url)[1];
                $cat_slug = explode("/", $cat_slug)[0];

                $cat = get_category_by_slug($cat_slug);
                if ($cat){
                    $term_id = $cat->term_id;

                global $wpdb;
                $start = now();

                $categories = get_categories( array(
                    'orderby' => 'name',
                    'order'   => 'ASC'
                ) );

                foreach( $categories as $category ) {

                    $link = get_category_link($category->term_id);

                    $exists = @$wpdb->get_results("SELECT * from {$wpdb->prefix}popularity_category where term_id = $category->term_id")[0];
                    $arr_t = ['term_id' => $category->term_id];
                    //        lets insert or update the popularity category
                    if (!$exists){
                        $wpdb->insert("{$wpdb->prefix}popularity_category",
                            $arr_t);
                    }

                }

//                Doing the popularity thing
                    $categories = @$wpdb->get_results("SELECT * from {$wpdb->prefix}popularity_category where term_id = $term_id order by updated_at asc");

                $counter = 0;
                foreach( $categories as $category ) {

                    $counter ++;
                    $link = get_category_link($category->term_id);

                    if (1==2){
                        ?>
                        <div class="col-md-4">
                            <?= $counter ?>.  <a href="<?= $link ?>"><?= $link ?> --updated at <?= $category->updated_at ?></a>
                        </div>

                        <?php
                    }

                    $args=array(
                        'cat'  =>  $category->term_id,
                        'order'  =>  'DESC',
                        'orderby'  =>  'rand',
                        'posts_per_page'  =>  9999,
                        'caller_get_posts'  =>  1
                    );

                    $my_query = null;

                    $my_query = new WP_Query($args);

                    if( $my_query->have_posts() ) {

                        $ids = '';
                        while ($my_query->have_posts()) : $my_query->the_post();

                            $ids .= "^".get_the_ID()."$|";

                        endwhile;

                    }
                    wp_reset_query();
                    $ids = rtrim($ids, "|");

                    $regexp = $ids;

                    //do stats
                    $items_all = @$wpdb->get_results("SELECT * from {$wpdb->prefix}birthday_stats where post_id regexp '$regexp' and views > 0 order by views desc");

                    $popularity = '?';
                    $counter = 0;
                    foreach ($items_all as $item){
                        $counter ++;

                        $exists = @$wpdb->get_results("SELECT * from {$wpdb->prefix}popularity where post_id = $item->post_id")[0];

                        //            is DOB test
                        $cat_name = get_the_category_by_ID($category->term_id);
                        $test = date('Y-m-d', strtotime($cat_name));
                        if ($test !== '1970-01-01'){
                            $arr_t = ['post_id' => $item->post_id, 'dob_rank' => $counter];
                        }else{
                            $arr_t = ['post_id' => $item->post_id, 'cat_rank' => $counter];
                        }

                        //        lets insert or update the popularity
                        if (!$exists){
                            $wpdb->insert("{$wpdb->prefix}popularity",
                                $arr_t);
                        }else{
                            $wpdb->update("{$wpdb->prefix}popularity",
                                $arr_t,
                                array( "post_id" => $item->post_id )
                            );
                        }


                    }

//        updated_at
                    $wpdb->update("{$wpdb->prefix}popularity_category",
                        ['updated_at' => now()],
                        array( "id" => $category->id )
                    );

                }

                $duration = date_diff2($start , now(), 'mins');
                die("Completed popularity action in {$duration} minutes.");



            }

    });

}

$folder = 'stats/';

if ( preg_match( "#/category/([a-z0-9-]+)/([a-z].*)#i", $_SERVER['REQUEST_URI'] ) && !preg_match( "#/page/#", $_SERVER['REQUEST_URI'] ) ) :

    include 'check-custom-category.php';

elseif ( preg_match( "#/most-popular-people#", $_SERVER['REQUEST_URI'] ) ) :
    $page_name = "most-popular-people";
// 1. the rewrite_rule
    /**
     * Add rewrite tags and rules
     */

    add_action( 'init', function () {
        global $page_name;
        flush_rewrite_rules();

        return add_rewrite_rule(
            $page_name.'/([^/]+)/?$', // ([^/]+) takes alphanumeric, while ([0-9]+) accepts digits
            'index.php?pagename=dynamic-page&param1=$matches[1]',
            'top'
        );

    } );

// 2. setting query vars
    add_filter( 'query_vars', function ( $v ) {
        return array_merge( array( 'param1' ), $v );
    } );


// lets check if we are inside a pagename then we update document title
    add_action( 'template_redirect', function () {
        global $wp_query;
        global $page_name;

        $param1 = get_query_var( 'param1' );

                add_filter( 'pre_get_document_title', 'custom_title2', 10 );
                function custom_title2() {
                    global $page_name;
                    $title = "Most popular people " . ' | ' . get_bloginfo( 'name' );

                    /* your code to generate the new title and assign the $title var to it... */

                    return $title;
                }


    } );

// 3. loading pagename template
    add_action( 'template_redirect', function () {
        global $wp_query;
        global $page_name;

        $post  = $wp_query->get_queried_object();

        $param1 = get_query_var( 'param1' );

        $proceed = true;

        if ( $param1 > 0 ) {
            $proceed = false;
            error_404();

        } else {
            $query = $param1;
        }

        if ( $proceed ) {
            include $GLOBALS['folder'].$page_name.'.php';
            die;
        }

    } );

elseif ( preg_match( "#/age#", $_SERVER['REQUEST_URI'] ) ) :
    $page_name = 'age';
    $param1_default = 0;
// 1. the rewrite_rule
    /**
     * Add rewrite tags and rules
     */

    add_action( 'init', function () {
        global $page_name;
        flush_rewrite_rules();

        return add_rewrite_rule(
            $page_name.'/([0-9]+)/?$', // ([^/]+) takes alphanumeric, while ([0-9]+) accepts digits
            'index.php?pagename=dynamic-page&param1=$matches[1]',
            'top'
        );

    } );

// 2. setting query vars
    add_filter( 'query_vars', function ( $v ) {
        return array_merge( array( 'param1' ), $v );
    } );


// lets check if we are inside a pagename then we update document title
    add_action( 'template_redirect', function () {
        global $wp_query;
        global $page_name;

        $param1 = get_query_var( 'param1' ) ?: $GLOBALS['param1_default'];

        if ( $param1 ) {

            if ( $param1 ) {
                add_filter( 'pre_get_document_title', 'custom_title2', 10 );
                function custom_title2() {
                    global $param1;
                    $title = 'People with '.$param1.' years | ' . get_bloginfo( 'name' );

                    /* your code to generate the new title and assign the $title var to it... */

                    return $title;
                }

            }

        }

    } );

// 3. loading pagename template
    add_action( 'template_redirect', function () {
        global $wp_query;
        global $page_name;

        $post  = $wp_query->get_queried_object();

        $param1 = get_query_var( 'param1' ) ?: $GLOBALS['param1_default'];

        $proceed = true;

        if ( $param1 < 1 || $param1 > 200 ) {
            $proceed = false;
            error_404();
        }

        if ( $proceed ) {
            include $GLOBALS['folder'].$page_name.'.php';
            die;
        }

    } );


elseif ( preg_match( "#/birth-place#", $_SERVER['REQUEST_URI'] ) ) :
    $page_name = "birth-place";
// 1. the rewrite_rule
    /**
     * Add rewrite tags and rules
     */

    add_action( 'init', function () {
        global $page_name;
        flush_rewrite_rules();

        return add_rewrite_rule(
            $page_name.'/([^/]+)/?$', // ([^/]+) takes alphanumeric, while ([0-9]+) accepts digits
            'index.php?pagename=dynamic-page&param1=$matches[1]',
            'top'
        );

    } );

// 2. setting query vars
    add_filter( 'query_vars', function ( $v ) {
        return array_merge( array( 'param1' ), $v );
    } );


// lets check if we are inside a pagename then we update document title
    add_action( 'template_redirect', function () {
        global $wp_query;
        global $page_name;

        $param1 = get_query_var( 'param1' );

        if ( $param1 ) {

            if ( $param1 ) {
                add_filter( 'pre_get_document_title', 'custom_title2', 10 );
                function custom_title2() {
                    global $wpdb;
                    global $param1;
                    $p = preg_replace('#-#', ' ', $param1);
                    $items = @$wpdb->get_results("SELECT * from {$wpdb->prefix}birthday_stats where birth_place like '%$p%'")[0];
                    if ($items) {
                        $param1 = $items->birth_place;
                    }
                    $title = 'People born in '.ucfirst($param1). ' | ' . get_bloginfo( 'name' );

                    /* your code to generate the new title and assign the $title var to it... */

                    return $title;
                }

            }

        }

    } );

// 3. loading pagename template
    add_action( 'template_redirect', function () {
        global $wp_query;
        global $page_name;

        $post  = $wp_query->get_queried_object();

        $param1 = get_query_var( 'param1' );

        $proceed = true;

        if ( strlen($param1) < 1 ) {
            $proceed = false;
            error_404();

        } else {
            $query = $param1;
        }

        
        if ( $proceed ) {
            include $GLOBALS['folder'].$page_name.'.php';
            die;
        }

    } );

elseif ( preg_match( "#/names/#", $_SERVER['REQUEST_URI'] ) ) :
    $page_name = "names";
// 1. the rewrite_rule
    /**
     * Add rewrite tags and rules
     */

    add_action( 'init', function () {
        global $page_name;
        flush_rewrite_rules();

        return add_rewrite_rule(
            $page_name.'/([^/]+)/?$', // ([^/]+) takes alphanumeric, while ([0-9]+) accepts digits
            'index.php?pagename=dynamic-page&param1=$matches[1]',
            'top'
        );

    } );

// 2. setting query vars
    add_filter( 'query_vars', function ( $v ) {
        return array_merge( array( 'param1' ), $v );
    } );


// lets check if we are inside a pagename then we update document title
    add_action( 'template_redirect', function () {
        global $wp_query;
        global $page_name;

        $param1 = get_query_var( 'param1' );

        if ( $param1 ) {

            if ( $param1 ) {
                add_filter( 'pre_get_document_title', 'custom_title2', 10 );
                function custom_title2() {
                    global $param1;
                    $title = 'People with name '.ucfirst($param1). ' | ' . get_bloginfo( 'name' );

                    /* your code to generate the new title and assign the $title var to it... */

                    return $title;
                }

            }

        }

    } );

// 3. loading pagename template
    add_action( 'template_redirect', function () {
        global $wp_query;
        global $page_name;

        $post  = $wp_query->get_queried_object();

        $param1 = get_query_var( 'param1' );

        $proceed = true;

        if ( strlen($param1) < 1 ) {
            $proceed = false;
            error_404();

        } else {
            $query = $param1;
        }

        if ( $proceed ) {
            include $GLOBALS['folder'].$page_name.'.php';
            die;
        }

    } );

elseif ( preg_match( "#/city/#", $_SERVER['REQUEST_URI'] ) ) :
    $page_name = "city";
    $param1_default = 1;
// 1. the rewrite_rule
    /**
     * Add rewrite tags and rules
     */

    add_action( 'init', function () {
        global $page_name;
        flush_rewrite_rules();

        return add_rewrite_rule(
            $page_name.'/([^/]+)/?$', // ([^/]+) takes alphanumeric, while ([0-9]+) accepts digits
            'index.php?pagename=dynamic-page&param1=$matches[1]',
            'top'
        );

    } );

// 2. setting query vars
    add_filter( 'query_vars', function ( $v ) {
        return array_merge( array( 'param1' ), $v );
    } );


// lets check if we are inside a pagename then we update document title
    add_action( 'template_redirect', function () {
        global $wp_query;
        global $page_name;

        $param1 = get_query_var( 'param1' );

        if ( $param1 ) {

            if ( $param1 ) {
                add_filter( 'pre_get_document_title', 'custom_title2', 10 );
                function custom_title2() {
                    global $wpdb;
                    global $page_name;
                    global $param1;
                    $p = preg_replace('#-#', ' ', $param1);
                    $items = @$wpdb->get_results("SELECT * from {$wpdb->prefix}birthday_stats where $page_name like '%$p%'")[0];
                    if ($items) {
                        $param1 = $items->city;
                    }
                    $title = 'People born in '.ucfirst($param1). ' | ' . get_bloginfo( 'name' );

                    /* your code to generate the new title and assign the $title var to it... */

                    return $title;
                }

            }

        }

    } );

// 3. loading pagename template
    add_action( 'template_redirect', function () {
        global $wp_query;
        global $page_name;

        $post  = $wp_query->get_queried_object();

        $param1 = get_query_var( 'param1' );

        $proceed = true;

        if ( strlen($param1) < 1 ) {
            $proceed = false;
            error_404();

        } else {
            $query = $param1;
        }

        if ( $proceed ) {
            include $GLOBALS['folder'].$page_name.'.php';
            die;
        }

    } );


elseif ( preg_match( "#/profession/#", $_SERVER['REQUEST_URI'] ) ) :
    $page_name = "profession";
// 1. the rewrite_rule
    /**
     * Add rewrite tags and rules
     */

    add_action( 'init', function () {
        global $page_name;
        flush_rewrite_rules();

        return add_rewrite_rule(
            $page_name.'/([^/]+)/?$', // ([^/]+) takes alphanumeric, while ([0-9]+) accepts digits
            'index.php?pagename=dynamic-page&param1=$matches[1]'.'&param2=$matches[2]',
            'top'
        );

    } );

// 2. setting query vars
    add_filter( 'query_vars', function ( $v ) {
        return array_merge( array( 'param1' ), $v );
    } );


// lets check if we are inside a pagename then we update document title
    add_action( 'template_redirect', function () {
        global $wp_query;
        global $page_name;

        $param1 = get_query_var( 'param1' );

        if ( $param1 ) {

            if ( $param1 ) {
                add_filter( 'pre_get_document_title', 'custom_title2', 10 );
                function custom_title2() {
                    global $page_name;
                    $title = $page_name . ' | ' . get_bloginfo( 'name' );

                    /* your code to generate the new title and assign the $title var to it... */

                    return $title;
                }

            }

        }

    } );

// 3. loading pagename template
    add_action( 'template_redirect', function () {
        global $wp_query;
        global $page_name;

        $post  = $wp_query->get_queried_object();

        $param1 = get_query_var( 'param1' );

        $proceed = true;

        if ( $param1 < 1 ) {
            $proceed = false;
            error_404();

        } else {
            $query = $param1;
        }

        if ( $proceed ) {
            include $GLOBALS['folder'].$page_name.'.php';
            die;
        }

    } );

elseif ( preg_match( "#/astrology/#", $_SERVER['REQUEST_URI'] ) ) :
    $page_name = "astrology";
    $param1_default = 1;
    $folder = $page_name.'/';
// 1. the rewrite_rule
    /**
     * Add rewrite tags and rules
     */

    add_action( 'init', function () {
        global $page_name;
        flush_rewrite_rules();

        return add_rewrite_rule(
            $page_name.'/([^/]+)/?$', // ([^/]+) takes alphanumeric, while ([0-9]+) accepts digits
            'index.php?pagename=dynamic-page&param1=$matches[1]',
            'top'
        );

    } );

// 2. setting query vars
    add_filter( 'query_vars', function ( $v ) {
        return array_merge( array( 'param1' ), $v );
    } );


// lets check if we are inside a pagename then we update document title
    add_action( 'template_redirect', function () {
        global $wp_query;
        global $page_name;

        $param1 = get_query_var( 'param1' );

        if ( $param1 ) {

            if ( $param1 ) {
                add_filter( 'pre_get_document_title', 'custom_title2', 10 );
                function custom_title2() {
                    global $param1;
                    $title = ucfirst($param1) . ' birthdays | ' . get_bloginfo( 'name' );

                    /* your code to generate the new title and assign the $title var to it... */

                    return $title;
                }

            }

        }

    } );

// 3. loading pagename template
    add_action( 'template_redirect', function () {
        global $wp_query;
        global $page_name;

        $post  = $wp_query->get_queried_object();

        $param1 = get_query_var( 'param1' );

        $proceed = true;

        if ( strlen($param1) < 1 ) {
            $proceed = false;
            error_404();

        } else {
            $query = $param1;

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

            $param1 = get_query_var( 'param1' );
            $horoscope = ucfirst(strtolower($param1));

            if (!key_exists($horoscope, $horoscope_dates)){
                $proceed = false;
                error_404();
            }else{
                $horoscope_date = $horoscope_dates[$horoscope];
            }


        }

        if ( $proceed ) {
            include $GLOBALS['folder'].$page_name.'.php';
            die;
        }

    } );

endif;
