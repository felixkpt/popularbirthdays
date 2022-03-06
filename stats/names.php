<?php
if (@$_GET['action'] == 'do-popularity') {
    global $wpdb;

    $start = now();

    global $param1;
    $first_name = $param1;

    $items_all = @$wpdb->get_results("SELECT * from {$wpdb->prefix}birthday_stats where views > 0 order by views desc");

    //doing first_name_rank
    (array) $arr = [];
    foreach ($items_all as $item){

        $first_name_t = strtolower(get_the_title($item->post_id));

        $pattern = "#($first_name)|($first_name )|( $first_name)#";

        if (preg_match($pattern, $first_name_t)){
            $arr[] = $item;
        }

    }

    $counter = 0;
    foreach ($arr as $item){
        $counter ++;

        $exists = @$wpdb->get_results("SELECT * from {$wpdb->prefix}popularity where post_id = $item->post_id")[0];
        $arr_t = ['post_id' => $item->post_id, 'first_name_rank' => $counter];
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

    $duration = date_diff2($start , now(), 'mins');
    die("Completed popularity action in {$duration} minutes.");

}

function custom_content() {

    global $post;
    global $wpdb;

    $param1 = get_query_var( 'param1' );
    $first_name = $param1;

    $title = 'People with name '.ucfirst($param1);

    $items = @$wpdb->get_results("SELECT * from {$wpdb->prefix}popularity inner join {$wpdb->prefix}birthday_stats on {$wpdb->prefix}popularity.post_id = {$wpdb->prefix}birthday_stats.post_id where first_name_rank > 0 order by {$wpdb->prefix}popularity.first_name_rank asc limit 50");

    (array) $arr = [];
    foreach ($items as $item){

        $first_name_t = strtolower(get_the_title($item->post_id));

        $pattern = "#($first_name)|($first_name )|( $first_name)#";

        if (preg_match($pattern, $first_name_t)){
            $arr[] = $item;
        }

    }

    $limit = 50;
    $arr = json_decode(json_encode($arr), true);
            $arr = filter_trash($arr);
            $items = limit(order_by_key($arr, 'views'), $limit * 2);
    $items = add_attachments($items, $limit, 1);

    $size = "";
    $show_rank = true;$run_popularity = true;
    article(get_defined_vars(), null, null);

}

include_once get_theme_file_path().'/single.php';
