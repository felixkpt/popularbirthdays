<?php

if (@$_GET['action'] == 'do-popularity'){
    global $wpdb;

    $start = now();

    //do most popular stats
    $items_all = @$wpdb->get_results("SELECT * from {$wpdb->prefix}birthday_stats where views > 0 order by views desc");

    $popularity = '?';
    $counter = 0;
    foreach ($items_all as $item){
        $counter ++;

        $exists = @$wpdb->get_results("SELECT * from {$wpdb->prefix}popularity where post_id = $item->post_id")[0];
        $arr_t = ['post_id' => $item->post_id, 'popularity' => $counter];
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

	$title = "Most popular people";

	$items = @$wpdb->get_results("SELECT * from {$wpdb->prefix}popularity inner join {$wpdb->prefix}birthday_stats on {$wpdb->prefix}popularity.post_id = {$wpdb->prefix}birthday_stats.post_id where popularity > 0 order by {$wpdb->prefix}popularity.popularity asc limit 50");

	$arr = $items;

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
