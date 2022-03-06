<?php
function popularity($i) {

    foreach($i as $item => $value) {
        $$item = $value;
    }

    $items = @$wpdb->get_results("SELECT * from {$wpdb->prefix}birthday_stats where post_id = $post->ID")[0];

    $popularity = '?';

    //    DOB
    $month_of_birth = date('F', strtotime($items->date_of_birth));
    $day_of_birth = (int) date('d', strtotime($items->date_of_birth));
    $dob = $month_of_birth.' '.$day_of_birth;
    $dob_rank = '?';

    $year_month = date('Y-m-d', strtotime($items->date_of_birth));
    $age = (int) date_diff2($year_month, date('Y-m-d'), 'y');
    $age_rank = '?';

     $birth_place = $items->birth_place;
     $birth_place_rank = '?';

     $first_name = explode(' ', get_the_title($items->post_id))[0];
     $first_name_rank = '?';

     $birth_city = $items->city;
     $birth_city_rank = '';

     $cats = @get_the_category($post->ID);
     $cat = cat($cats);
     $cat_name = $cat->name;
     $cat_link = get_category_link($cat->term_id);

    $profession_rank = '?';

    //    update_option( 'siteurl', 'http://localhost/wordpress/popular-birthdays' );
//    update_option( 'home', 'http://localhost/wordpress/popular-birthdays' );





//    $arr = [];
//    foreach ($items_all as $item){
//
//        $month_of_birth_t = date('F', strtotime($item->date_of_birth));
//        $day_of_birth_t = (int) date('d', strtotime($item->date_of_birth));
//        $dob_t = $month_of_birth_t.' '.$day_of_birth_t;
//
//
//        if ($dob_t == $dob){
//            $arr[] = $item;
//        }
//
//    }
//
//    $counter = 0;
//    foreach ($arr as $item){
//        $counter ++;
//
//        $exists = @$wpdb->get_results("SELECT * from {$wpdb->prefix}popularity where post_id = $item->post_id")[0];
//        $arr_t = ['post_id' => $item->post_id, 'dob_rank' => $counter];
//        //        lets insert or update the popularity
//        if (!$exists){
//            $wpdb->insert("{$wpdb->prefix}popularity",
//                $arr_t);
//        }else{
//            $wpdb->update("{$wpdb->prefix}popularity",
//                $arr_t,
//                array( "post_id" => $item->post_id )
//            );
//        }
//
//    }







//    //doing cat_rank
//    $profession = '?';
//    $profession_rank = '?';
//    $cats = @get_the_category($post->ID);
//    $cat = cat($cats);
//    $cat_name = $cat->name;
//    $cat_link = get_category_link($cat->term_id);
//
//
//    $arr = [];
//    foreach ($items_all as $item){
//
//        $cats = @get_the_category($item->post_id);
//        $cat = cat($cats);
//        $cat_name_t = $cat->name;
//
//
//        if ($cat_name_t == $cat_name){
//            $arr[] = $item;
//        }
//
//    }
//
//    $counter = 0;
//    foreach ($arr as $item){
//        $counter ++;
//
//        $exists = @$wpdb->get_results("SELECT * from {$wpdb->prefix}popularity where post_id = $item->post_id")[0];
//        $arr_t = ['post_id' => $item->post_id, 'cat_rank' => $counter];
//        //        lets insert or update the popularity
//        if (!$exists){
//            $wpdb->insert("{$wpdb->prefix}popularity",
//                $arr_t);
//        }else{
//            $wpdb->update("{$wpdb->prefix}popularity",
//                $arr_t,
//                array( "post_id" => $item->post_id )
//            );
//        }
//
//    }


//    get from DB

    $items = @$wpdb->get_results("SELECT * from {$wpdb->prefix}popularity where post_id = $post->ID")[0];

    if ($items){
        $popularity = $items->popularity ?: '?';
        $dob_rank = $items->dob_rank ?: '?';
        $age_rank = $items->age_rank ?: '?';
        $birth_place_rank = $items->birth_place_rank ?: '?';
        $first_name_rank = $items->first_name_rank ?: '?';
        $birth_city_rank = $items->birth_city_rank ?: '?';
        $cat_rank = $items->cat_rank ?: '?';
    }

    return get_defined_vars();

}
