<?php
function custom_content() {

    global $post;
    global $wpdb;


    $param1 = get_query_var( 'param1' );
    $param2 = get_query_var( 'param2' );

    $key = $param1;
    $value = $param2;

    $items = [];
    $title = 'Shows';
    $table = strtolower($title);

    if ($key == 'most-popular'){
        $regexp = "$value-";
        $items = @$wpdb->get_results("SELECT * from {$wpdb->prefix}{$table} where 1 order by id desc");
        $title = 'Most Popular Shows';
    }elseif ($key == 'year'){
        $regexp = "$value-";
        $items = @$wpdb->get_results("SELECT * from {$wpdb->prefix}{$table} where premiered regexp '$regexp' order by id desc");
        $title = $title.' premiered in the year '.ucfirst($param2);
    }elseif ($key == 'age'){

        $items = @$wpdb->get_results("SELECT * from {$wpdb->prefix}{$table} where 1 order by id desc");
        $title = $title.' aged '.ucfirst($param2).' years';

        (array) $arr = [];
        foreach ($items as $item){

            if (strlen($item->premiered) > 3){
                $year_month = date('Y-m', strtotime($item->premiered));

                $age_temp = (int) date_diff2($year_month, date('Y-m'), 'y');

                if ($value == $age_temp){
                    $arr[] = $item;
                }
            }

        }

        $items = $arr;

    }elseif ($key == 'names'){

        $items = @$wpdb->get_results("SELECT * from {$wpdb->prefix}{$table} where 1 order by id desc");
        $title = 'Shows named '.ucfirst($param2);

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
        $title = 'Shows genre '.ucfirst($param2);
    }elseif ($key == 'rating'){
        $items = @$wpdb->get_results("SELECT * from {$wpdb->prefix}{$table} where rating = '$value' order by id desc");
        $title = 'Shows rating '.ucfirst($param2);
    }elseif ($key == 'artist'){

        $p = preg_replace('#-#', ' ', $value);
        $items = @$wpdb->get_results("SELECT * from {$wpdb->prefix}{$table} where artist like '%$p%'");

        $title = 'Shows artist '.ucwords($p);
    }elseif ($key == 'label'){

        $p = preg_replace('#-#', ' ', $value);
        $items = @$wpdb->get_results("SELECT * from {$wpdb->prefix}{$table} where label like '%$p%'");

        $title = 'Shows label '.ucwords($p);
    }elseif ($key == 'album'){

        $p = preg_replace('#-#', ' ', $value);
        $items = @$wpdb->get_results("SELECT * from {$wpdb->prefix}{$table} where album like '%$p%'");

        $title = 'Shows album '.ucwords($p);
    }elseif ($key == 'network'){

        $p = preg_replace('#-#', ' ', $value);
        $items = @$wpdb->get_results("SELECT * from {$wpdb->prefix}{$table} where network like '%$p%'");

        $title = 'Shows network '.ucwords($p);
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
