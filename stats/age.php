<?php
if (@$_GET['action'] == 'do-popularity') {
    global $wpdb;

    $start = now();

    $param1 = get_query_var( 'param1' );
    $age = $param1;

    $items_all = @$wpdb->get_results("SELECT * from {$wpdb->prefix}birthday_stats where views > 0 order by views desc");

    (array) $arr = [];
    foreach ($items_all as $item){

        if (strlen($item->date_of_birth) > 6){
            $year_month = date('Y-m-d', strtotime($item->date_of_birth));

            $age_temp = (int) date_diff2($year_month, date('Y-m-d'), 'y');

            if ($age == $age_temp){
                $arr[] = $item;
            }
        }

    }

//doing age rank

    $arr = [];
    foreach ($items_all as $item) {

        $age_temp = (int)(date_diff2($item->date_of_birth, date('Y-m-d'), 'y'));

        if ($age_temp == $age) {
            $arr[] = $item;
        }

    }

    $counter = 0;
    foreach ($arr as $item) {
        $counter++;

        $exists = @$wpdb->get_results("SELECT * from {$wpdb->prefix}popularity where post_id = $item->post_id")[0];
        $arr_t = ['post_id' => $item->post_id, 'age_rank' => $counter];
        //        lets insert or update the popularity
        if (!$exists) {
            $wpdb->insert("{$wpdb->prefix}popularity",
                $arr_t);
        } else {
            $wpdb->update("{$wpdb->prefix}popularity",
                $arr_t,
                array("post_id" => $item->post_id)
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
    $age = $param1;

    $title = 'People with '.ucfirst($param1).' years';

    $items = @$wpdb->get_results("SELECT * from {$wpdb->prefix}popularity inner join {$wpdb->prefix}birthday_stats on {$wpdb->prefix}popularity.post_id = {$wpdb->prefix}birthday_stats.post_id where age_rank > 0 order by {$wpdb->prefix}popularity.age_rank asc limit 50");

    (array) $arr = [];
    foreach ($items as $item){

        if (strlen($item->date_of_birth) > 6){
            $year_month = date('Y-m-d', strtotime($item->date_of_birth));

            $age_temp = (int) date_diff2($year_month, date('Y-m-d'), 'y');

            if ($age == $age_temp){
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
