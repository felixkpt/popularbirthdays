<?php

$columns = ['released', 'premiered', 'established', 'location', 'nickname', 'genre', 'network', 'artist', 'album', 'runtime', 'label', 'rating'];

$columns2 = ['location', 'nickname', 'genre', 'network', 'artist', 'album', 'runtime', 'label', 'rating'];

function group_stats($options) {

global $columns;
    global $columns2;

    $o = shortcode_parameters($options);

    global $post;
    global $wpdb;
    $post_id = $post->ID;


    foreach ($o as $k => $v)
        $$k = $v;


    if ($field == 'slot1') {
        ob_start();
        ?>
        <div class="bg-light m-1">
            Slot 1
        </div>
        <?php
        return ob_get_clean();
    }

    if ($field == 'image') {
        ob_start();

        if ( $post->post_type == 'post' && $post->post_status == 'publish' ) {

            $cat = get_category( $post->ID );
            $cats = @get_the_category($post->ID);
            $cat = cat($cats);
            $cat_name = $cat->name;

            $name = sanitize_title($cat_name);

            $items = @$wpdb->get_results("SELECT * from {$wpdb->prefix}{$name} where post_id = $post->ID")[0];

            $attachments = explode(',', $items->images);

            $uploaddir = wp_upload_dir()['baseurl'].'/images/';
            $img_arr = [];
            foreach ($attachments as $attachment) {

                $img_arr[] = $uploaddir.$attachment;
            }

            $attachment = $img_arr[0];

            if ( $attachment ) {

                            $class = "post-attachment mime-";
                            $img_url =  $attachment;

                            ?>
                                <img class="mb-2 rounded-lg m-auto" src="<?php echo $img_url; ?>">
                            <?php
            }
        }

        return ob_get_clean();

    }

    if ($field == 'stats') {
        ob_start();

        $cat = get_category( $post->ID );
        $cats = @get_the_category($post->ID);
        $cat = cat($cats);
        $cat_name = $cat->name;

        $name = sanitize_title($cat_name);

        $items = @$wpdb->get_results("SELECT * from {$wpdb->prefix}{$name} where post_id = $post->ID")[0];
//var_dump($items);


        ?>
        <!-- main stats-->
        <div class="col-12">

            <?php
            foreach ($items as $key => $item):

                if ($item && in_array($key, $columns)):
                ?>

                <div class="col-12 grp-stat-col">
                        <div class="row flex-row">
                            <i class="fa fa-star mr-1 text-primary-custom"></i>
                            <h6><?= ucfirst($key) ?></h6>
                        </div>

                        <?php

                        preg_match_all('/\b(\d{4})\b/', $item, $matches);
                        $year = @$matches[0][0];

                        if (preg_match('#\b(\d{4})\b#', $item)): ?>
                        <a href="<?= site_url() ?>/category/<?= $name.'/year/'.$year; ?>"><?= $item ?></a>
                        <?php else: ?>
                            <a href="<?= site_url() ?>/category/<?= $name.'/'.$key.'/'.sanitize_title($item) ?>">
                                <?= $item ?>
                            </a>
                        <?php

                        endif;
                        ?>

                </div>

            <?php
                endif;

            endforeach;
            ?>
        </div>
        <?php
        return ob_get_clean();

    }

    if ($field == 'boost'){
        ob_start();
        ?>
        <div class="col-12 d-none">
            <div class="row group-boost btn-boost-wrap">
                    <div class="col-5">
                        <span class="rank-info">
                        <div class="rank-no">#1609</div>
                        <div class="rank-title">TV show</div>
                        </span>
                    </div>
                    <div class="col-7">
                        <a id="btn-group-boost" href="#" class="btn btn-primary btn-boost btn-group-boost" data-group_id="5184">
                            <div class="boost-label">
                                <i class="icn icn-star"></i> Boost
                            </div>
                        </a>
                    </div>
            </div>
        </div>
            <?php
        return ob_get_clean();

    }

    if ($field == 'stats_mobile') {
       ob_start();
       ?>
        <div class="col-4 text-center">
            <div class="item-label text-muted">Released</div>
            <div class="item-value">
                <a href="<?= site_url() ?>/songs/date/july10.html" title="Songs Released July 10">Jul 10</a>,
                <a href="<?= site_url() ?>/songs/year/2018.html">2018</a>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    if ($field == 'cast') {
        ob_start();


        $o = group_popularity(get_defined_vars());
        foreach ($o as $i => $k){
            $$i = $k;
        }

        ?>

        <div id="footer-section" class="mx-3 mb-2">
            <div class="col-12">

                <div class="row bg-white shadow rounded">
                    <div class="col-12 bg-secondary-custom rounded">

                        <h5 class="mt-2 w-100 shadow-lg rounded-lg text-muted px-3"><span class="text-limit"><?php echo $post->post_title; ?></span> Popularity</h5>
                        <div class="row">
                            <div class="col-sm-3 my-0 p-2">
                                <div class="card h-100"> <!-- full height -->
                                    <div class="card-header p-0 px-2">Popularity</div>
                                    <div class="card-body p-1">
                                        <a class="d-flex flex-column justify-content-center w-100 h-100 btn btn-outline-dark" href="<?= site_url(); ?>/category/<?= $name_init ?>/most-popular">
                                            <div class="text-wrap">Most Popular</div>
                                            <div class="font-weight-bold text-primary-custom mt-auto">Rank #<?= $popularity; ?></div>
                                        </a>
                                    </div>
                                    <div class="card-footer p-1"></div>
                                </div>
                            </div>
                            <div class="col-sm-3 my-0 p-2">
                                <div class="card h-100"> <!-- full height -->
                                    <div class="card-header p-0 px-2">Created Date</div>
                                    <div class="card-body p-1">
                                        <a class="d-flex flex-column justify-content-center w-100 h-100 btn btn-outline-dark" href="<?= site_url(); ?>/category/<?= $name_init ?>/year/<?= sanitize_title($dob); ?>">
                                            <div class="text-wrap" title="Born on <?= $dob; ?>"><?= $dob; ?></div>
                                            <div class="font-weight-bold text-primary-custom mt-auto">Rank #<?= $dob_rank; ?></div>
                                        </a>
                                    </div>
                                    <div class="card-footer p-1"></div>
                                </div>
                            </div>
                            <div class="col-sm-3 my-0 p-2">
                                <div class="card h-100"> <!-- full height -->
                                    <div class="card-header p-0 px-2">Same Age</div>
                                    <div class="card-body p-1">
                                        <a class="d-flex flex-column justify-content-center w-100 h-100 btn btn-outline-dark" href="<?= site_url(); ?>/category/<?= $name_init ?>/age/<?= $age; ?>">
                                            <div class="text-wrap"><?= $age; ?> Years Old</div>
                                            <div class="font-weight-bold text-primary-custom mt-auto">Rank #<?= $age_rank; ?></div>
                                        </a>
                                    </div>
                                    <div class="card-footer p-1"></div>
                                </div>
                            </div>
                            <div class="col-sm-3 my-0 p-2">
                                <div class="card h-100"> <!-- full height -->
                                    <div class="card-header p-0 px-2">First Name</div>
                                    <div class="card-body p-1">
                                        <a class="d-flex flex-column justify-content-center w-100 h-100 btn btn-outline-dark" href="<?= site_url(); ?>/category/<?= $name_init ?>/names/<?= sanitize_title($first_name); ?>">
                                            <div class="text-wrap"><?= $first_name; ?></div>
                                            <div class="font-weight-bold text-primary-custom mt-auto">Rank #<?= $first_name_rank; ?></div>
                                        </a>
                                    </div>
                                    <div class="card-footer p-1"></div>
                                </div>
                            </div>
                            <?php foreach ($columns2 as $column){

                                if (!empty($$column)){
                                ?>
                            <div class="col-sm-3 my-0 p-2">
                                <div class="card h-100"> <!-- full height -->
                                    <div class="card-header p-0 px-2"><?= ucfirst($column) ?></div>
                                    <div class="card-body p-1">
                                        <a class="d-flex flex-column justify-content-center w-100 h-100 btn btn-outline-dark" href="<?php echo site_url(); ?>/category/<?= $name_init ?>/<?= $column ?>/<?= sanitize_title($$column); ?>">
                                            <div class="text-wrap"><?= $$column; ?></div>
                                            <div class="font-weight-bold text-primary-custom mt-auto">Rank #<?= ${$column.'_rank'}; ?></div>
                                        </a>
                                    </div>
                                    <div class="card-footer p-1"></div>
                                </div>
                            </div>
                            <?php }
                            } ?>
                            <div class="col-sm-3 my-0 p-2">
                                <div class="card h-100"> <!-- full height -->
                                    <div class="card-header p-0 px-2">Category</div>
                                    <div class="card-body p-1">
                                        <a class="d-flex flex-column justify-content-center w-100 h-100 btn btn-outline-dark" href="<?php echo $cat_link; ?>">
                                            <div class="text-wrap"><?= $cat_name ?></div>
                                            <div class="font-weight-bold text-primary-custom mt-auto">Rank #<?= $profession_rank ?></div>
                                        </a>
                                    </div>
                                    <div class="card-footer p-1"></div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>


                    <?php


                    $ip = ip();

                    $cat = get_category( $post->ID );
                    $cats = @get_the_category($post->ID);
                    $cat = cat($cats);
                    $cat_name = $cat->name;

                    $more_msg = $cat_name;
                    $more_link = $cat_link = get_category_link($cat->term_id);

                    $name_init = $name = sanitize_title($cat_name);

                    $items = @$wpdb->get_results("SELECT * from {$wpdb->prefix}{$name} where post_id = $post->ID")[0];
//                    var_dump($items);


                    $previous_view = $_SESSION['previous_view_'.$name_init] ?? 0;
                    if ($previous_view == $post->ID){
                        $previous_view = 0;
                    }


                    $group_views = @$wpdb->get_results("SELECT * FROM `{$wpdb->base_prefix}{$name}_views` where post_id = $post->ID and ip = '$ip'");

                    if (!$group_views) {

                        @$wpdb->insert("{$wpdb->base_prefix}{$name}_views",
                            ['post_id' => $post_id, 'viewed' => $previous_view, 'ip' => $ip]
                        )[0];

//                insert popular rank and general post views
                        $items = @$wpdb->get_results("SELECT * from {$wpdb->prefix}{$name} where post_id = $post->ID")[0];

                        @$wpdb->update("{$wpdb->prefix}{$name}",
                            ['views' => $items->views + 1],
                            ['id' => $items->id]
                        )[0];


                    }

                    //       Getting and Printing saved items
                    $items = @$wpdb->get_results("SELECT * from {$wpdb->base_prefix}{$name}_views where post_id = $post->ID and viewed > 0");

                    $arr = [];

                    foreach ($items as $item){

                        $the_viewed = $item->viewed;

                        if (!in_array($the_viewed, array_column($arr, 'viewed'))){

                            $counts = 1;
                            foreach ($items as $item2) {

                                if ($item2->viewed == $the_viewed && $item2->id !== $item->id){
                                    $counts ++;
                                }
                            }

                            $arr[] = ['viewed' => $the_viewed, 'counts' => $counts];

                        }
//            end if not in array


                    }
                    //        end foreach

                    $arr = limit(order_by_key($arr, 'viewed'), 12);
                    $post_ids2 = array_column($arr, 'viewed');

                    ?>
        <div class="ad row shadow rounded">
            <div class="col-12 rounded p-1 mb-1">
                <div class="bg-light m-1">
                    Slot 2
                </div>
            </div>
        </div>
                <?php if ($post_ids2): ?>
        <div class="row bg-white shadow rounded">
            <div class="col-12 rounded p-1 mb-1">
                <div class="bg-light m-1">

                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex h-100 justify-content-center">
                                <div class="col-12 shadow-sm m-auto bg-dark">

                                    <h3 class="mx-3"><span class="text-muted"><?= $post->post_title ?> Fans Also Viewed</span></h3>
                                    <div class="col-lg-12 p-0 mb-1 bg-light overflow-hidden">
                                        <div class="d-flex flex-row">
                                            <div class="d-flex flex-row carosel rounded-lg">
                                                <a class="nav-btn text-decoration-none justify-content-center p-3 rounded-circle d-flex flex-row position-absolute border carosel-control carosel-control-left" href="#">
                                                        <span class="col-2 justify-content-center d-flex align-items-center">
                                                            <span class="fa fa-chevron-left text-white-50">
                                                            </span>
                                                        </span>
                                                </a>

                                                <?php

                                                foreach ($post_ids2 as $post_id) {

                                                    $attachments = get_posts( array(
                                                        'post_type' => 'attachment',
                                                        'posts_per_page' => -1,
                                                        'post_parent' => $post_id,
                                                        'exclude'     => get_post_thumbnail_id()
                                                    ) );

                                                    if ( $attachments ) {

                                                        foreach ( array_slice($attachments, 0, 1) as $key => $attachment ) {

                                                            $active = '';
                                                            if ($key == 0)
                                                                $active = 'active';

                                                            $class = "post-attachment mime-" . sanitize_title( $attachment->post_mime_type );
                                                            $img_url =  wp_get_attachment_url($attachment->ID);
                                                            $title = get_the_title($post_id);
                                                            $cats = @get_the_category( $post_id );
                                                            $cat = cat($cats);
                                                            $cat_name = $cat->name;
                                                            ?>
                                                            <div title="<?php echo $cat_name; ?>" class="bg-dark carosel-item carosel-item-stack" style="background: url(<?php echo $img_url; ?>) no-repeat center center; background-size:cover;">
                                                                <a class="btn d-flex flex-column justify-content-end w-100 h-100 p-0 pl-1" href="<?php echo get_permalink($post_id); ?>">
                                                                    <div class="item-title text-white text-left"><?php echo $title; ?></div>
                                                                </a>
                                                            </div>
                                                            <?php

                                                        }

                                                    }

                                                }

                                                if (!$post_ids2) {
                                                    ?>
                                                    <div class="bg-dark carosel-item carosel-item-stack" style="background: url() no-repeat center center; background-size:cover;">
                                                        <a class="btn d-flex flex-column justify-content-end w-100 h-100 p-0 pl-1" href="#">
                                                            <div class="item-title text-white text-left"></div>
                                                        </a>
                                                    </div>
                                                    <?php
                                                }
                                                ?>

                                                <a class="nav-btn text-decoration-none justify-content-center p-3 rounded-circle d-flex flex-row position-absolute border carosel-control carosel-control-right" href="#">
                                                        <span class="col-2 justify-content-center d-flex align-items-center">
                                                            <span class="fa fa-chevron-right text-white-50">
                                                            </span>
                                                        </span>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="d-flex flex-row">
                                            <div class="d-flex flex-row w-100 m-1">
                                                <a href="<?= $more_link ?>" class="btn w-100 bg-primary-custom">More <?= $more_msg ?></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>

                </div>
            </div>
                </div>
            <?php endif ?>

                    <?php
                    $_SESSION['previous_view_'.$name_init] = $post->ID;

                    ?>


                    </div>
                </div>
<!--        #footer-section-->


        <?php
        return ob_get_clean();
    }


}
//    end group_stats

function group_popularity($i) {

    foreach($i as $item => $value) {
        $$item = $value;
    }

    $ip = ip();

    $cat = get_category( $post->ID );
    $cats = @get_the_category($post->ID);
    $cat = cat($cats);
    $cat_name = $cat->name;

    $more_msg = $cat_name;
    $more_link = $cat_link = get_category_link($cat->term_id);

    $name_init = $name = sanitize_title($cat_name);

    $items_all = @$wpdb->get_results("SELECT * from {$wpdb->base_prefix}{$name} order by views desc");

    $popularity = '?';
    $counter = 0;
    foreach ($items_all as $item){
        $counter ++;
        if ($item->post_id == $post->ID){
            $popularity = $counter;
            break;
        }
    }

    $items = @$wpdb->get_results("SELECT * from {$wpdb->prefix}{$name} where post_id = $post->ID")[0];

    $created_title = '?';
    $year = '?';
foreach ($items as $key => $item){

    if (preg_match('#released#', $key)){

        $created_title = 'released';
        $year = date('Y', strtotime($item));

}elseif (preg_match('#premiered#', $key)){

        $created_title = 'premiered';
        $year = date('Y', strtotime($item));

    }elseif (preg_match('#established#', $key)){

        $created_title = 'established';
        preg_match_all('/\b(\d{4})\b/', $item, $matches);
        $year = @$matches[0][0];

    }

}

    //    doing DOB rank
    $dob = $year;
    $dob_rank = '?';


    $arr = [];
    foreach ($items_all as $item){

        $year_t = date('Y', strtotime($item->$created_title));

        if ($year_t == $year){
            $arr[] = $item;
        }

    }

    $counter = 0;
    foreach ($arr as $item){
        $counter ++;
        if ($item->post_id == $post->ID){
            $dob_rank = $counter;
            break;
        }
    }


    //doing age rank
    $age_rank = '?';
    $age = '?';
    if (strlen($items->$created_title) > 0) {



        $since = '';
        if (preg_match("/\d{4}-\d{2}-\d{2}/", $items->$created_title)){
            $since = $items->$created_title;
        }else{
            preg_match_all('/(\d{4})/', $items->$created_title, $matches);
            $year = $matches[0][0];

            $since = $year.'-01-01';
        }

        $age = (int) date_diff2($since, date('Y-m-d'), 'y');


    }

    $arr = [];
    foreach ($items_all as $item){

        $age_temp = number_format_strict(date_diff2($item->$created_title, date('Y-m-d'), 'y'));

        if ($age_temp == $age){
            $arr[] = $item;
        }

    }

    $counter = 0;
    foreach ($arr as $item){
        $counter ++;
        if ($item->post_id == $post->ID){
            $age_rank = $counter;
            break;
        }
    }

    //doing first_name_rank
    $name = $post->post_title;
    $first_name = explode(' ', $name)[0];
    $first_name_rank = '?';

    $arr = [];
    foreach ($items_all as $item){

        $name = $item->name;

        $first_name_t = explode(' ', $name)[0];

        if ($first_name_t == $first_name){
            $arr[] = $item;
        }

    }

    $counter = 0;
    foreach ($arr as $item){
        $counter ++;
        if ($item->post_id == $post->ID){
            $first_name_rank = $counter;
            break;
        }
    }

    //doing cat_rank
    $profession = '?';
    $profession_rank = '?';
    $cats = @get_the_category($post->ID);
    $cat = cat($cats);
    $cat_name = $cat->name;
    $cat_link = get_category_link($cat->term_id);


    $arr = [];
    foreach ($items_all as $item){

        $cats = @get_the_category($item->post_id);
        $cat = cat($cats);
        $cat_name_t = $cat->name;


        if ($cat_name_t == $cat_name){
            $arr[] = $item;
        }

    }

    $counter = 0;
    foreach ($arr as $item){
        $counter ++;
        if ($item->post_id == $post->ID){
            $profession_rank = $counter;
            break;
        }
    }

//    multiple columns
    $arr = [];

    foreach($columns2 as $column){

        if (!empty($items->$column)){

                $$column = '?';
               ${$column.'_rank'} = '?';
    if (strlen($items->$column) > 0) {
        $$column = $items->$column;
    }

    $arr = [];
    foreach ($items_all as $item){

        $itm_t = $item->$column;

        if ($itm_t == $$column){
            $arr[] = $item;
        }

    }

    $counter = 0;
    foreach ($arr as $item){
        $counter ++;
        if ($item->post_id == $post->ID){
            ${$column.'_rank'} = $counter;
            break;
        }
    }


        }

    }


    return get_defined_vars();
}

    add_shortcode('group_stats', 'group_stats');