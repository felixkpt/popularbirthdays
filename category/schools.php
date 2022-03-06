<?php
function custom_content() {

    global $post;
    global $wpdb;


    $param1 = get_query_var( 'param1' );
    $param2 = get_query_var( 'param2' );

    $key = $param1;
    $value = $param2;

    $items = [];
    $title = 'Schools';
    $table = strtolower($title);

    if ($key == 'most-popular'){
        $regexp = "$value-";
        $items = @$wpdb->get_results("SELECT * from {$wpdb->prefix}{$table} where 1 order by id desc");
        $title = 'Most Popular Schools';
    }elseif ($key == 'year'){
        $items = @$wpdb->get_results("SELECT * from {$wpdb->prefix}{$table} where 1 order by id desc");
        $title = $title.' established in the year '.ucfirst($param2);
        (array) $arr = [];
        foreach ($items as $item) {

            preg_match_all('/\b(\d{4})\b/', $item->established, $matches);
            $year_temp = @$matches[0][0];

            if ($year_temp == $value){
                $arr[] = $item;
            }
        }

        }elseif ($key == 'age'){

        $items = @$wpdb->get_results("SELECT * from {$wpdb->prefix}{$table} where 1 order by id desc");
        $title = $title.' aged '.ucfirst($param2).' years';

        (array) $arr = [];
        foreach ($items as $item){

            $since = '';
            if (preg_match("/\d{4}-\d{2}-\d{2}/", $item->established)){
                $since = date('Y-m-d', strtotime($item->established));
                ;
            }else{
                preg_match_all('/(\d{4})/', $item->established, $matches);
                $year = $matches[0][0];

                $since = $year.'-01-01';
            }

                $age_temp = (int) date_diff2($since, date('Y-m-d'), 'y');

                if ($value == $age_temp){
                    $arr[] = $item;
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
    }elseif ($key == 'artist'){

        $p = preg_replace('#-#', ' ', $value);
        $items = @$wpdb->get_results("SELECT * from {$wpdb->prefix}{$table} where artist like '%$p%'");

        $title = $title.' artist '.ucwords($p);
    }elseif ($key == 'label'){

        $p = preg_replace('#-#', ' ', $value);
        $items = @$wpdb->get_results("SELECT * from {$wpdb->prefix}{$table} where label like '%$p%'");

        $title = $title.' label '.ucwords($p);
    }elseif ($key == 'album'){

        $p = preg_replace('#-#', ' ', $value);
        $items = @$wpdb->get_results("SELECT * from {$wpdb->prefix}{$table} where album like '%$p%'");

        $title = $title.' album '.ucwords($p);
    }elseif ($key == 'network'){

        $p = preg_replace('#-#', ' ', $value);
        $items = @$wpdb->get_results("SELECT * from {$wpdb->prefix}{$table} where network like '%$p%'");

        $title = $title.' network '.ucwords($p);
    }elseif ($key == 'nickname'){

        $p = preg_replace('#-#', ' ', $value);
        $items = @$wpdb->get_results("SELECT * from {$wpdb->prefix}{$table} where nickname like '%$p%'");

        $title = $title.' nicknamed '.ucwords($p);
    }elseif ($key == 'location'){

        $items = @$wpdb->get_results("SELECT * from {$wpdb->prefix}{$table} where 1");

        (array) $arr = [];
        foreach ($items as $item){

                $name_temp = sanitize_title($item->location);

                $pattern = "#$value#";

                if (preg_match($pattern, $name_temp)){
                    $arr[] = $item;
                }

        }

      $title = $title.' nicknamed '.ucwords(preg_replace("/-/", " ", $value));
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
