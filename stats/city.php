<?php
if (@$_GET['action'] == 'do-popularity') {
    global $wpdb;

    $start = now();

    global $param1;
    $p = preg_replace('#-#', ' ', $param1);
    $items = @$wpdb->get_results("SELECT * from {$wpdb->prefix}birthday_stats where city like '%$p%'")[0];
    if ($items) {
        $param1 = $items->city;
    }
    $city = $param1;

    $items = @$wpdb->get_results("SELECT * from {$wpdb->prefix}birthday_stats where 1 order by id desc");

    (array) $arr = [];
    foreach ($items as $item){

        if (strlen($item->city) > 1){

            $city_temp = preg_replace('# #', '-', strtolower($item->city));

            if (preg_replace("# #", "-", strtolower($city)) == $city_temp){
                $arr[] = $item;
            }
        }

    }

    $counter = 0;
    foreach ($arr as $item){
        $counter ++;

        $exists = @$wpdb->get_results("SELECT * from {$wpdb->prefix}popularity where post_id = $item->post_id")[0];
        $arr_t = ['post_id' => $item->post_id, 'birth_city_rank' => $counter];
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


    global $wpdb;
    global $pagename;
    global $param1;


    $p = preg_replace('#-#', ' ', $param1);
    $items = @$wpdb->get_results("SELECT * from {$wpdb->prefix}birthday_stats where city like '%$p%'")[0];
    if ($items) {
        $param1 = $items->city;
    }
    $title = 'People born in '.ucfirst($param1);

    $city = $param1;

    $items = @$wpdb->get_results("SELECT * from {$wpdb->prefix}popularity inner join {$wpdb->prefix}birthday_stats on {$wpdb->prefix}popularity.post_id = {$wpdb->prefix}birthday_stats.post_id where birth_city_rank > 0 order by {$wpdb->prefix}popularity.birth_city_rank asc limit 50");

    (array) $arr = [];
    foreach ($items as $item){

        if (strlen($item->city) > 1){

            $city_temp = preg_replace('# #', '-', strtolower($item->city));

            if (preg_replace("# #", "-", strtolower($city)) == $city_temp){
                $arr[] = $item;
            }
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
