<?php
function custom_content() {

    global $post;
    global $wpdb;


    $param1 = get_query_var( 'param1' );
    $param2 = get_query_var( 'param2' );

    $key = $param1;
    $value = $param2;

    $items = [];
    $title = 'Movies';
    $table = strtolower($title);

    if ($key == 'most-popular'){
        $regexp = "$value-";
        $items = @$wpdb->get_results("SELECT * from {$wpdb->prefix}{$table} where 1 order by id desc");
        $title = 'Most Popular Movies';
    }elseif ($key == 'year'){
        $regexp = "$value-";
        $items = @$wpdb->get_results("SELECT * from {$wpdb->prefix}{$table} where released regexp '$regexp' order by id desc");
        $title = $title.' released in the year '.ucfirst($param2);
    }elseif ($key == 'age'){

        $items = @$wpdb->get_results("SELECT * from {$wpdb->prefix}{$table} where 1 order by id desc");
        $title = $title.' aged '.ucfirst($param2).' years';

        (array) $arr = [];
        foreach ($items as $item){

            if (strlen($item->released) > 3){
                $year_month = date('Y-m', strtotime($item->released));

                $age_temp = (int) date_diff2($year_month, date('Y-m'), 'y');

                if ($value == $age_temp){
                    $arr[] = $item;
                }
            }

        }

        $items = $arr;

    }elseif ($key == 'names'){

        $items = @$wpdb->get_results("SELECT * from {$wpdb->prefix}{$table} where 1 order by id desc");
        $title = $title.' named '.ucfirst($param2);

        (array) $arr = [];
        foreach ($items as $item){

            if (strlen($item->name) > 1){

                $name_temp = strtolower($item->name);

                $pattern = "#$value|$value | $value#";

                if (preg_match($pattern, $name_temp)){
                    $arr[] = $item;
                }
            }

        }
    }elseif ($key == 'genre'){
        $items = @$wpdb->get_results("SELECT * from {$wpdb->prefix}{$table} where genre = '$value' order by id desc");
        $title = $title.' genre '.ucfirst($param2);
    }elseif ($key == 'rating'){
        $items = @$wpdb->get_results("SELECT * from {$wpdb->prefix}{$table} where rating = '$value' order by id desc");
        $title = $title.' rating '.ucfirst($param2);
    }

    $arr = $arr ?? $items;

    $limit = 50;
    $arr = json_decode(json_encode($arr), true);
            $arr = filter_trash($arr);
            $items = limit(order_by_key($arr, 'views'), $limit * 2);
    $items = add_attachments($items, $limit, 1);

    article(get_defined_vars());
}

include_once get_theme_file_path().'/single.php';
