<?php


$folder = 'category/';

if ( preg_match( "#/movies/#", $_SERVER['REQUEST_URI'] ) ) :
    $page_name = "movies";
// 1. the rewrite_rule
    /**
     * Add rewrite tags and rules
     */

    add_action( 'init', function () {
        global $page_name;
        flush_rewrite_rules();

        return add_rewrite_rule(
            'category/'.$page_name.'/([^/]+)/([^/]+)/?$', // ([^/]+) takes alphanumeric, while ([0-9]+) accepts digits
            'index.php?pagename=dynamic-page&param1=$matches[1]&param2=$matches[2]',
            'top'
        );

    } );

    add_action( 'init', function () {
        global $page_name;
        flush_rewrite_rules();

        return add_rewrite_rule(
            'category/'.$page_name.'/most-popular/?$', // ([^/]+) takes alphanumeric, while ([0-9]+) accepts digits
            'index.php?pagename=dynamic-page&param1=most-popular&param2=$matches[2]',
            'top'
        );

    } );

// 2. setting query vars
    add_filter( 'query_vars', function ( $v ) {
        return array_merge( array( 'param1' ), $v );
    } );
    add_filter( 'query_vars', function ( $v ) {
        return array_merge( array( 'param2' ), $v );
    } );


// lets check if we are inside a pagename then we update document title
    add_action( 'template_redirect', function () {
        global $wp_query;
        global $page_name;

        $param1 = get_query_var( 'param1' );
        $param2 = get_query_var( 'param2' );

            if ( $param1 && $param2 ) {
                add_filter( 'pre_get_document_title', 'custom_title2', 10 );
                function custom_title2() {
                    global $page_name;
                    global $param1;
                    global $param2;
                    $title = ucfirst($page_name).", $param1 - $param2 " . ' | ' . get_bloginfo( 'name' );

                    /* your code to generate the new title and assign the $title var to it... */

                    return $title;
                }

            }

    } );

// 3. loading pagename template
    add_action( 'template_redirect', function () {
        global $wp_query;
        global $page_name;

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


elseif ( preg_match( "#/songs/#", $_SERVER['REQUEST_URI'] ) ) :
    $page_name = "songs";
// 1. the rewrite_rule
    /**
     * Add rewrite tags and rules
     */

    add_action( 'init', function () {
        global $page_name;
        flush_rewrite_rules();

        return add_rewrite_rule(
            'category/'.$page_name.'/([^/]+)/([^/]+)/?$', // ([^/]+) takes alphanumeric, while ([0-9]+) accepts digits
            'index.php?pagename=dynamic-page&param1=$matches[1]&param2=$matches[2]',
            'top'
        );

    } );

    add_action( 'init', function () {
        global $page_name;
        flush_rewrite_rules();

        return add_rewrite_rule(
            'category/'.$page_name.'/most-popular/?$', // ([^/]+) takes alphanumeric, while ([0-9]+) accepts digits
            'index.php?pagename=dynamic-page&param1=most-popular&param2=$matches[2]',
            'top'
        );

    } );

// 2. setting query vars
    add_filter( 'query_vars', function ( $v ) {
        return array_merge( array( 'param1' ), $v );
    } );
    add_filter( 'query_vars', function ( $v ) {
        return array_merge( array( 'param2' ), $v );
    } );


// lets check if we are inside a pagename then we update document title
    add_action( 'template_redirect', function () {
        global $wp_query;
        global $page_name;

        $param1 = get_query_var( 'param1' );
        $param2 = get_query_var( 'param2' );

        if ( $param1 && $param2 ) {
            add_filter( 'pre_get_document_title', 'custom_title2', 10 );
            function custom_title2() {
                global $page_name;
                global $param1;
                global $param2;
                $title = ucfirst($page_name).", $param1 - $param2 " . ' | ' . get_bloginfo( 'name' );

                /* your code to generate the new title and assign the $title var to it... */

                return $title;
            }

        }

    } );

// 3. loading pagename template
    add_action( 'template_redirect', function () {
        global $wp_query;
        global $page_name;

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

elseif ( preg_match( "#/shows/#", $_SERVER['REQUEST_URI'] ) ) :
    $page_name = "shows";
// 1. the rewrite_rule
    /**
     * Add rewrite tags and rules
     */

    add_action( 'init', function () {
        global $page_name;
        flush_rewrite_rules();

        return add_rewrite_rule(
            'category/'.$page_name.'/([^/]+)/([^/]+)/?$', // ([^/]+) takes alphanumeric, while ([0-9]+) accepts digits
            'index.php?pagename=dynamic-page&param1=$matches[1]&param2=$matches[2]',
            'top'
        );

    } );

    add_action( 'init', function () {
        global $page_name;
        flush_rewrite_rules();

        return add_rewrite_rule(
            'category/'.$page_name.'/most-popular/?$', // ([^/]+) takes alphanumeric, while ([0-9]+) accepts digits
            'index.php?pagename=dynamic-page&param1=most-popular&param2=$matches[2]',
            'top'
        );

    } );

// 2. setting query vars
    add_filter( 'query_vars', function ( $v ) {
        return array_merge( array( 'param1' ), $v );
    } );
    add_filter( 'query_vars', function ( $v ) {
        return array_merge( array( 'param2' ), $v );
    } );


// lets check if we are inside a pagename then we update document title
    add_action( 'template_redirect', function () {
        global $wp_query;
        global $page_name;

        $param1 = get_query_var( 'param1' );
        $param2 = get_query_var( 'param2' );

        if ( $param1 && $param2 ) {
            add_filter( 'pre_get_document_title', 'custom_title2', 10 );
            function custom_title2() {
                global $page_name;
                global $param1;
                global $param2;
                $title = ucfirst($page_name).", $param1 - $param2 " . ' | ' . get_bloginfo( 'name' );

                /* your code to generate the new title and assign the $title var to it... */

                return $title;
            }

        }

    } );

// 3. loading pagename template
    add_action( 'template_redirect', function () {
        global $wp_query;
        global $page_name;

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

elseif ( preg_match( "#/songs/#", $_SERVER['REQUEST_URI'] ) ) :
    $page_name = "songs";
// 1. the rewrite_rule
    /**
     * Add rewrite tags and rules
     */

    add_action( 'init', function () {
        global $page_name;
        flush_rewrite_rules();

        return add_rewrite_rule(
            'category/'.$page_name.'/([^/]+)/([^/]+)/?$', // ([^/]+) takes alphanumeric, while ([0-9]+) accepts digits
            'index.php?pagename=dynamic-page&param1=$matches[1]&param2=$matches[2]',
            'top'
        );

    } );

    add_action( 'init', function () {
        global $page_name;
        flush_rewrite_rules();

        return add_rewrite_rule(
            'category/'.$page_name.'/most-popular/?$', // ([^/]+) takes alphanumeric, while ([0-9]+) accepts digits
            'index.php?pagename=dynamic-page&param1=most-popular&param2=$matches[2]',
            'top'
        );

    } );

// 2. setting query vars
    add_filter( 'query_vars', function ( $v ) {
        return array_merge( array( 'param1' ), $v );
    } );
    add_filter( 'query_vars', function ( $v ) {
        return array_merge( array( 'param2' ), $v );
    } );


// lets check if we are inside a pagename then we update document title
    add_action( 'template_redirect', function () {
        global $wp_query;
        global $page_name;

        $param1 = get_query_var( 'param1' );
        $param2 = get_query_var( 'param2' );

        if ( $param1 && $param2 ) {
            add_filter( 'pre_get_document_title', 'custom_title2', 10 );
            function custom_title2() {
                global $page_name;
                global $param1;
                global $param2;
                $title = ucfirst($page_name).", $param1 - $param2 " . ' | ' . get_bloginfo( 'name' );

                /* your code to generate the new title and assign the $title var to it... */

                return $title;
            }

        }

    } );

// 3. loading pagename template
    add_action( 'template_redirect', function () {
        global $wp_query;
        global $page_name;

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

    elseif ( preg_match( "#/schools/#", $_SERVER['REQUEST_URI'] ) ) :
    $page_name = "schools";
// 1. the rewrite_rule
    /**
     * Add rewrite tags and rules
     */

    add_action( 'init', function () {
        global $page_name;
        flush_rewrite_rules();

        return add_rewrite_rule(
            'category/'.$page_name.'/([^/]+)/([^/]+)/?$', // ([^/]+) takes alphanumeric, while ([0-9]+) accepts digits
            'index.php?pagename=dynamic-page&param1=$matches[1]&param2=$matches[2]',
            'top'
        );

    } );

    add_action( 'init', function () {
        global $page_name;
        flush_rewrite_rules();

        return add_rewrite_rule(
            'category/'.$page_name.'/most-popular/?$', // ([^/]+) takes alphanumeric, while ([0-9]+) accepts digits
            'index.php?pagename=dynamic-page&param1=most-popular&param2=$matches[2]',
            'top'
        );

    } );

// 2. setting query vars
    add_filter( 'query_vars', function ( $v ) {
        return array_merge( array( 'param1' ), $v );
    } );
    add_filter( 'query_vars', function ( $v ) {
        return array_merge( array( 'param2' ), $v );
    } );


// lets check if we are inside a pagename then we update document title
    add_action( 'template_redirect', function () {
        global $wp_query;
        global $page_name;

        $param1 = get_query_var( 'param1' );
        $param2 = get_query_var( 'param2' );

        if ( $param1 && $param2 ) {
            add_filter( 'pre_get_document_title', 'custom_title2', 10 );
            function custom_title2() {
                global $page_name;
                global $param1;
                global $param2;
                $title = ucfirst($page_name).", $param1 - $param2 " . ' | ' . get_bloginfo( 'name' );

                /* your code to generate the new title and assign the $title var to it... */

                return $title;
            }

        }

    } );

// 3. loading pagename template
    add_action( 'template_redirect', function () {
        global $wp_query;
        global $page_name;

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

endif;



?>