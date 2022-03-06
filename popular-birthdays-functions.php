<?php

function stats($options) {

    $o = shortcode_parameters($options);

    global $post;
    global $wpdb;
    $post_id = $post->ID;


    foreach ($o as $k => $v)
        $$k = $v;



    if (@$field == 'image-slider') {

        //    delete invalid posts

        $items = @$wpdb->get_results("SELECT * from {$wpdb->prefix}birthday_stats where post_id = $post->ID")[0];
        if (!$items){
            wp_delete_post($post->ID);
            redirect(site_url());
        }

        ob_start();

        if ( $post->post_type == 'post' && $post->post_status == 'publish' ) {

            $attachments = explode(',', $items->images);

            $uploaddir = wp_upload_dir()['baseurl'].'/images/';
            $img_arr = [];
            foreach ($attachments as $attachment) {

                $img_arr[] = $uploaddir.$attachment;
            }

            $attachments = $img_arr;

            if ( $attachments ) {
                ?>

                <div id="carouselIndicator" class="carousel slide mb-3" data-ride="carousel">
                    <ol class="carousel-indicators <?php if (count($attachments) < 2): echo 'd-none'; endif; ?>">
                        <?php
                        foreach ( $attachments as $key => $attachment ) {

                            $active = '';
                            if ($key == 0)
                                $active = 'active';

                            ?>

                            <li data-target="#carouselExampleIndicators" data-slide-to="<?php echo $key; ?>" class="<?php echo $active; ?>"></li>

                            <?php
                        }

                        ?>
                    </ol>
                    <div class="carousel-inner border shadow-sm rounded-lg">

                        <?php
                        foreach ( $attachments as $key => $attachment ) {

                            $active = '';
                            if ($key == 0)
                                $active = 'active';

                            $img_url =  $attachment;

                            ?>


                            <div class="carousel-item img bg-dark rounded-lg <?php echo $active; ?>">
                                <img class="d-block w-100 h-auto rounded-lg" src="<?php echo $img_url; ?>">
                            </div>

                            <?php

                        }

                        ?>
                    </div>
                    <?php if (count($attachments) > 1): ?>
                    <a class="carousel-control-prev" href="#carouselIndicator" role="button" data-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                    </a>
                    <a class="carousel-control-next" href="#carouselIndicator" role="button" data-slide="next">
                        <span class="carousel-control-next-icon"></span>
                    </a>
                <?php endif; ?>
                </div>
                <?php

            }
        }

        return ob_get_clean();

    }

    if (@$field == 'header'){

        ob_start();

        $name = $post->post_title;

        $cats = @get_the_category( $post->ID );
        $cat = cat($cats);
        $cat_name = $cat->name;
        $cat_link = get_category_link($cat->term_id);
        $description = '';

        ?>
        <h2 class="text-white p-2 bg-primary-custom rounded-lg"><?php echo $name; ?></h2>
        <div class="text-left">
            <a class="text-xl" href="<?php echo $cat_link; ?>"><?php echo $cat_name; ?></a>
            <?php echo $description; ?>
        </div>


        <?php
        return ob_get_clean();
    }

    if (@$field == 'stats'){

        ob_start();

        $items = @$wpdb->get_results("SELECT * from {$wpdb->prefix}birthday_stats where post_id = $post->ID")[0];

        ?>
        <div class="col-12 mb-2">
            <div class="rounded-lg p-2 border-primary-custom">
                <h5>Date of Birth</h5>
                <span class="rounded-lg font-weight-bold px-2 text-primary-custom text-primary-custom">
                    <?php
                    if (isset($items->date_of_birth)) {
                        echo $items->date_of_birth;
                    }else{
                        echo '?';
                    }
                    ?></span>
            </div>
        </div>
        <div class="col-12 mb-2">
            <div class="rounded-lg p-2 border-primary-custom">
                <h5>Birth Place</h5>
                <span class="rounded-lg font-weight-bold px-2 text-primary-custom">
                    <?php
                    if (isset($items->city)) {
                        echo $items->city;

                        if ($items->birth_place)
                            echo ", $items->birth_place";
                    }else{
                        echo '?';
                    }
                    ?>
                </span>
            </div>
        </div>
        <div class="col-12 mb-2">
            <div class="rounded-lg p-2 border-primary-custom">
                <h5>Age</h5>
                <span class="rounded-lg font-weight-bold px-2 text-primary-custom">
                    <?php
                    if (isset($items->date_of_birth)) {
                        echo number_format_strict(date_diff2($items->date_of_birth, date('Y-m-d'), 'y'));
                    }else {
                        echo '?';
                    }
                    ?> Yrs old
                </span>
            </div>
        </div>
        <div class="col-12 mb-2">
            <div class="rounded-lg p-2 border-primary-custom">
                <h5>Birth Sign</h5>
                <span class="rounded-lg font-weight-bold px-2 text-primary-custom">
                    <?php
                    if (isset($items->birth_sign)) {
                        echo $items->birth_sign;
                    }else{
                        echo '?';
                    }?>
                </span>
            </div>
        </div>
        <?php

        return ob_get_clean();

    }

    if (@$field == 'stats-mobile'){

        ob_start();

        $items = @$wpdb->get_results("SELECT * from {$wpdb->prefix}birthday_stats where post_id = $post->ID")[0];

        ?>
        <div class="col-12 mb-2">
            <div class="rounded-lg p-2 border-primary-custom">
                <h5>Date of Birth</h5>
                <span class="rounded-lg font-weight-bold px-2 text-primary-custom text-primary-custom"><?php echo $items->date_of_birth; ?></span>
            </div>
        </div>
        <div class="col-12 mb-2">
            <div class="rounded-lg p-2 border-primary-custom">
                <h5>Birth Place</h5>
                <span class="rounded-lg font-weight-bold px-2 text-primary-custom">
                    <?php echo $items->city;

                    if ($items->birth_place)
                        echo ", $items->birth_place";
                    ?>
                </span>
            </div>
        </div>
        <div class="col-12 mb-2">
            <div class="rounded-lg p-2 border-primary-custom">
                <h5>Age</h5>
                <span class="rounded-lg font-weight-bold px-2 text-primary-custom">
                    <?php echo number_format_strict(date_diff2($items->date_of_birth, date('Y-m-d'), 'y')); ?> Yrs old
                </span>
            </div>
        </div>
        <div class="col-12 mb-2">
            <div class="rounded-lg p-2 border-primary-custom">
                <h5>Birth Sign</h5>
                <span class="rounded-lg font-weight-bold px-2 text-primary-custom">
                    <?php echo $items->birth_sign; ?>
                </span>
            </div>
        </div>
        <?php

        return ob_get_clean();

    }

    if (@$field == 'popularity'){

        ob_start();

        $o = popularity(get_defined_vars());
        foreach ($o as $i => $k){
            $$i = $k;
        }

        ?>
        <div class="col-12 bg-secondary-custom rounded">
            <h5 class="mt-2 w-100 shadow-lg rounded-lg text-muted px-3"><span class="text-limit"><?php echo $first_name; ?></span> Popularity</h5>
            <div class="row">
                <div class="col-sm-6 my-0 p-2">
                    <div class="card h-100"> <!-- full height -->
                        <div class="card-header">Popularity</div>
                        <div class="card-body p-1">
                            <a class="d-flex flex-column justify-content-center w-100 h-100 btn btn-outline-dark" href="<?= site_url(); ?>/most-popular-people">
                                <div class="text-wrap">Most Popular</div>
                                <div class="font-weight-bold text-primary-custom mt-auto">Rank #<?= $popularity; ?></div>
                            </a>
                        </div>
                        <div class="card-footer p-1"></div>
                    </div>
                </div>
                <div class="col-sm-6 my-0 p-2">
                    <div class="card h-100"> <!-- full height -->
                        <div class="card-header">Same DOB</div>
                        <div class="card-body p-1">
                            <a class="d-flex flex-column justify-content-center w-100 h-100 btn btn-outline-dark" href="<?= site_url(); ?>/category/<?= sanitize_title($dob); ?>">
                                <div class="text-wrap" title="Born on <?= $dob; ?>"><?= $dob; ?></div>
                                <div class="font-weight-bold text-primary-custom mt-auto">Rank #<?= $dob_rank; ?></div>
                            </a>
                        </div>
                        <div class="card-footer p-1"></div>
                    </div>
                </div>
                <div class="col-sm-6 my-0 p-2">
                    <div class="card h-100"> <!-- full height -->
                        <div class="card-header">Same Age</div>
                        <div class="card-body p-1">
                            <a class="d-flex flex-column justify-content-center w-100 h-100 btn btn-outline-dark" href="<?= site_url(); ?>/age/<?= $age; ?>">
                                <div class="text-wrap"><?= $age; ?> Years Old</div>
                                <div class="font-weight-bold text-primary-custom mt-auto">Rank #<?= $age_rank; ?></div>
                            </a>
                        </div>
                        <div class="card-footer p-1"></div>
                    </div>
                </div>
                <?php

                if ($birth_place !== ''): ?>
                    <div class="col-sm-6 my-0 p-2">
                        <div class="card h-100"> <!-- full height -->
                            <div class="card-header">Birth Place</div>
                            <div class="card-body p-1">
                                <a class="d-flex flex-column justify-content-center w-100 h-100 btn btn-outline-dark" href="<?= site_url(); ?>/birth-place/<?= sanitize_title($birth_place); ?>">
                                    <div class="text-wrap"><?= $birth_place; ?></div>
                                    <div class="font-weight-bold text-primary-custom mt-auto">Rank #<?= $birth_place_rank; ?></div>
                                </a>
                            </div>
                            <div class="card-footer p-1"></div>
                        </div>
                    </div>
                <?php endif; ?>
                <div class="col-sm-6 my-0 p-2">
                    <div class="card h-100"> <!-- full height -->
                        <div class="card-header">First Name</div>
                        <div class="card-body p-1">
                            <a class="d-flex flex-column justify-content-center w-100 h-100 btn btn-outline-dark" href="<?= site_url(); ?>/names/<?= sanitize_title($first_name); ?>">
                                <div class="text-wrap"><?= $first_name; ?></div>
                                <div class="font-weight-bold text-primary-custom mt-auto">Rank #<?= $first_name_rank; ?></div>
                            </a>
                        </div>
                        <div class="card-footer p-1"></div>
                    </div>
                </div>
                <div class="col-sm-6 my-0 p-2">
                    <div class="card h-100"> <!-- full height -->
                        <div class="card-header">Birth City</div>
                        <div class="card-body p-1">
                            <a class="d-flex flex-column justify-content-center w-100 h-100 btn btn-outline-dark" href="<?php echo site_url(); ?>/city/<?= sanitize_title($birth_city); ?>">
                                <div class="text-wrap"><?= $birth_city; ?></div>
                                <div class="font-weight-bold text-primary-custom mt-auto">Rank #<?= $birth_city_rank; ?></div>
                            </a>
                        </div>
                        <div class="card-footer p-1"></div>
                    </div>
                </div>
                <div class="col-sm-6 my-0 p-2">
                    <div class="card h-100"> <!-- full height -->
                        <div class="card-header">Profession</div>
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
        <?php

        return ob_get_clean();

    }


    if (@$field == 'sidenav'){

        ob_start();
        ?>
        <div class="col-12 sidebar-container shadow-sm bg-secondary-custom rounded">
            <h5 class="mt-2 w-100 shadow-lg rounded-lg text-muted px-3">Trending</h5>
            <?php

            global $post;
            global $wpdb;

            $items = @$wpdb->get_results("SELECT * from {$wpdb->prefix}popularity inner join {$wpdb->prefix}birthday_stats on {$wpdb->prefix}popularity.post_id = {$wpdb->prefix}birthday_stats.post_id where popularity > 0 order by {$wpdb->prefix}popularity.popularity asc limit 20");

            if ($items){

    $arr = $items;

    $limit = 9;
    $arr = json_decode(json_encode($arr), true);
    $arr = filter_trash($arr);
    $items = limit(order_by_key($arr, 'views'), $limit * 2);
    $items = add_attachments($items, $limit, 1);

    $show_rank = false;
    $size = 'sm';
    article(get_defined_vars(), false, true);
}

            ?>
        </div>
        <?php
        return ob_get_clean();

    }

    if ($field == 'popularity-mobile') {
        ob_start();
        $o = popularity(get_defined_vars());
        foreach ($o as $i => $k){
            $$i = $k;
        }
        ?>
        <div class="col-12 bg-light shadow-sm">
            <div class="d-flex h-100">
                <div class="col-12 bg-secondary-custom rounded">
                    <h5 class="mt-2 w-100 shadow-lg rounded-lg text-muted px-3"><span class="text-limit"><?php echo $first_name; ?></span> Popularity</h5>
                    <div class="row">
                        <div class="col-sm-3 my-0 p-2">
                            <div class="card h-100"> <!-- full height -->
                                <div class="card-header p-0 px-2">Popularity</div>
                                <div class="card-body p-1">
                                    <a class="d-flex flex-column justify-content-center w-100 h-100 btn btn-outline-dark" href="<?= site_url(); ?>/most-popular-people">
                                        <div class="text-wrap">Most Popular</div>
                                        <div class="font-weight-bold text-primary-custom mt-auto">Rank #<?= $popularity; ?></div>
                                    </a>
                                </div>
                                <div class="card-footer p-1"></div>
                            </div>
                        </div>
                        <div class="col-sm-3 my-0 p-2">
                            <div class="card h-100"> <!-- full height -->
                                <div class="card-header p-0 px-2">Same DOB</div>
                                <div class="card-body p-1">
                                    <a class="d-flex flex-column justify-content-center w-100 h-100 btn btn-outline-dark" href="<?= site_url(); ?>/category/<?= sanitize_title($dob); ?>">
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
                                    <a class="d-flex flex-column justify-content-center w-100 h-100 btn btn-outline-dark" href="<?= site_url(); ?>/age/<?= $age; ?>">
                                        <div class="text-wrap"><?= $age; ?> Years Old</div>
                                        <div class="font-weight-bold text-primary-custom mt-auto">Rank #<?= $age_rank; ?></div>
                                    </a>
                                </div>
                                <div class="card-footer p-1"></div>
                            </div>
                        </div>
                        <?php if ($birth_place !== ''): ?>
                            <div class="col-sm-3 my-0 p-2">
                                <div class="card h-100"> <!-- full height -->
                                    <div class="card-header p-0 px-2">Birth Place</div>
                                    <div class="card-body p-1">
                                        <a class="d-flex flex-column justify-content-center w-100 h-100 btn btn-outline-dark" href="<?= site_url(); ?>/birth-place/<?= sanitize_title($birth_place); ?>">
                                            <div class="text-wrap"><?= $birth_place; ?></div>
                                            <div class="font-weight-bold text-primary-custom mt-auto">Rank #<?= $birth_place_rank; ?></div>
                                        </a>
                                    </div>
                                    <div class="card-footer p-1"></div>
                                </div>
                            </div>
                        <?php endif; ?>
                        <div class="col-sm-3 my-0 p-2">
                            <div class="card h-100"> <!-- full height -->
                                <div class="card-header p-0 px-2">First Name</div>
                                <div class="card-body p-1">
                                    <a class="d-flex flex-column justify-content-center w-100 h-100 btn btn-outline-dark" href="<?= site_url(); ?>/names/<?= sanitize_title($first_name); ?>">
                                        <div class="text-wrap"><?= $first_name; ?></div>
                                        <div class="font-weight-bold text-primary-custom mt-auto">Rank #<?= $first_name_rank; ?></div>
                                    </a>
                                </div>
                                <div class="card-footer p-1"></div>
                            </div>
                        </div>
                        <div class="col-sm-3 my-0 p-2">
                            <div class="card h-100"> <!-- full height -->
                                <div class="card-header p-0 px-2">Birth City</div>
                                <div class="card-body p-1">
                                    <a class="d-flex flex-column justify-content-center w-100 h-100 btn btn-outline-dark" href="<?php echo site_url(); ?>/city/<?= sanitize_title($birth_city); ?>">
                                        <div class="text-wrap"><?= $birth_city; ?></div>
                                        <div class="font-weight-bold text-primary-custom mt-auto">Rank #<?= $birth_city_rank; ?></div>
                                    </a>
                                </div>
                                <div class="card-footer p-1"></div>
                            </div>
                        </div>
                        <div class="col-sm-3 my-0 p-2">
                            <div class="card h-100"> <!-- full height -->
                                <div class="card-header p-0 px-2">Profession</div>
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
        </div>
        <?php

        return ob_get_clean();

    }

    if (@$field == 'items-and-also-viewed'){

        ob_start();

        $ip = ip();

        $name = $post->post_title;
        $first_name = explode(' ', $name)[0];

        $post_ids = [];

        $has_items = false;
        $has_items_class = 'col-lg-12';
        if ($post_ids) {
            $has_items = true;
            $has_items_class = 'col-lg-6';
        }


        $items = @$wpdb->get_results("SELECT * from {$wpdb->prefix}birthday_stats where post_id = $post->ID")[0];

        $birth_sign = $items->birth_sign;

        $previous_view = $_SESSION['previous_view'] ?? 0;
        if ($previous_view == $post->ID){
            $previous_view = 0;
        }


        $name = strtolower($birth_sign).'_';

        $zodiac_views = @$wpdb->get_results("SELECT * FROM `{$wpdb->base_prefix}{$name}views` where post_id = $post->ID and ip = '$ip'");

        if (!$zodiac_views) {

            @$wpdb->insert("{$wpdb->base_prefix}{$name}views",
                ['post_id' => $post_id, 'viewed' => $previous_view, 'ip' => $ip]
            )[0];

//                insert popular rank and general post views
            $items = @$wpdb->get_results("SELECT * from {$wpdb->prefix}birthday_stats where post_id = $post->ID")[0];

            @$wpdb->update("{$wpdb->prefix}birthday_stats",
                ['views' => $items->views + 1],
                ['id' => $items->id]
            )[0];


        }

//       Getting and Printing saved items
        $items = @$wpdb->get_results("SELECT * from {$wpdb->base_prefix}{$name}views where post_id = $post->ID and viewed > 0 order by id desc limit 2000");

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

        $limit = 12;
        $arr = json_decode(json_encode($arr), true);
        $arr = filter_trash($arr);
        $items = limit(order_by_key($arr, 'viewed'), $limit * 2);

        $items2 = add_attachments($items, $limit, 1);

        ?>
        <div class="col-12">
            <div class="row">
                <?php if ($has_items): ?>
                    <div class="col-lg-6 col-md-12 p-1 mb-1 bg-light overflow-hidden">
                        <div class="d-flex flex-row">
                            <div class="col-12 shadow-sm">
                                <h3><?php echo $first_name; ?> Songs</h3>
                            </div>
                        </div>
                        <div class="d-flex flex-row bg-dark">
                            <div class="d-flex flex-row carosel rounded-lg">
                                <a class="nav-btn text-decoration-none justify-content-center p-3 rounded-circle d-flex flex-row position-absolute border carosel-control carosel-control-left" href="#">
                                                        <span class="col-2 justify-content-center d-flex align-items-center">
                                                            <span class="fa fa-chevron-left text-white-50">
                                                            </span>
                                                        </span>
                                </a>

                                <?php

                                foreach ($post_ids as $post_id) {

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
                                            <div class="bg-dark carosel-item carosel-item-stack" style="background: url(<?php echo $img_url; ?>) no-repeat center center; background-size:cover;">
                                                <a class="btn d-flex flex-column justify-content-end w-100 h-100 p-0 pl-1" href="<?php echo get_permalink($post_id); ?>">
                                                    <div class="item-title text-white text-left"><?php echo $title; ?></div>
                                                </a>
                                            </div>
                                            <?php

                                        }

                                    }

                                }

                                if (!$post_ids) {
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

                    </div>

                <?php  endif; ?>

                <div class="ad col-12 p-1 d-md-none d-lg-none mb-1">
                    <div class="d-flex h-100 justify-content-center">
                        <div class="col-12 bg-white shadow-sm p-1 mb-1">
                            <div class="w-100">
                                <div class="bg-light m-1">
                                    Slot 4
                                </div>
                            </div>

                        </div>
                    </div>

                </div>

                <?php

                $items = $items2;
                if ($items){

                    ?>
                    <div class="<?php echo $has_items_class; ?> col-md-12 p-1 pl-lg-1 mb-1 bg-light overflow-hidden">
                        <div class="d-flex flex-row">
                            <div class="col-12 shadow-sm">
                                <h3><?php echo $first_name; ?> Fans Also Viewed</h3>
                            </div>
                        </div>
                        <div class="d-flex flex-row bg-dark">
                            <div class="d-flex flex-row carosel rounded-lg">
                                <a class="nav-btn text-decoration-none justify-content-center p-3 rounded-circle d-flex flex-row position-absolute border carosel-control carosel-control-left ml-lg-1" href="#">
                                                        <span class="col-2 justify-content-center d-flex align-items-center">
                                                            <span class="fa fa-chevron-left text-white-50">
                                                            </span>
                                                        </span>
                                </a>

                                <?php
                                $size = '';
                                stack_thumbnails(get_defined_vars());
                                ?>
                                <a class="nav-btn text-decoration-none justify-content-center p-3 rounded-circle d-flex flex-row position-absolute border carosel-control carosel-control-right" href="#">
                                                        <span class="col-2 justify-content-center d-flex align-items-center">
                                                            <span class="fa fa-chevron-right text-white-50">
                                                            </span>
                                                        </span>
                                </a>
                            </div>
                        </div>

                    </div>
                <?php } ?>

            </div>

        </div>
        <?php
        $_SESSION['previous_view'] = $post->ID;

        return ob_get_clean();

    }

    if (@$field == 'similarities'){

        ob_start();
        $separator = '-';

        $items = @$wpdb->get_results("SELECT * from {$wpdb->prefix}birthday_stats where post_id = $post->ID")[0];

        if ($items) :

            $birth_sign = $items->birth_sign;
            $month_of_birth = date('F', strtotime($items->date_of_birth));
            $day_of_birth = (int) date('d', strtotime($items->date_of_birth));
            $regexp = '-'.date('m', strtotime($items->date_of_birth)).'-';

            $items = @$wpdb->get_results("SELECT * from {$wpdb->prefix}birthday_stats where post_id != $post->ID and birth_sign = '$birth_sign' order by views desc");

            $arr = $arr ?? $items;

            $limit = 12;
            $arr = json_decode(json_encode($arr), true);
            $arr = filter_trash($arr);
            $items = limit(order_by_key($arr, 'post_id'), $limit * 2);

            $items = add_attachments($items, $limit, 1);


            $items2 = @$wpdb->get_results("SELECT * from {$wpdb->prefix}birthday_stats where post_id != $post->ID and date_of_birth regexp '$regexp' order by views desc");

            $arr = $arr ?? $items2;

            $limit = 12;
            $arr = json_decode(json_encode($arr), true);
            $arr = filter_trash($arr);
            $items2 = limit(order_by_key($arr, 'post_id', 'asc'), $limit * 2);

            $items2 = add_attachments($items2, $limit, 1);

            ?>

            <div class="col-12">
                <div class="row">

                    <div class="col-sm-7 p-1 mb-1">
                        <div class="d-flex h-100 justify-content-center">
                            <div class="col-12 shadow-sm m-auto bg-dark">
                                <h3 class="mx-3"><a class="text-muted" href="<?php echo site_url(); ?>/astrology/<?php echo strtolower($birth_sign); ?>"><?php echo $birth_sign; ?> Birthdays</a></h3>
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
                                            $size = 'lg';
                                            stack_thumbnails(get_defined_vars());
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
                                            <a href="<?php echo site_url(); ?>/astrology/<?php echo strtolower($birth_sign); ?>" class="btn w-100 bg-primary-custom">More <?php echo $birth_sign; ?></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-5 p-1 mb-1 overflow-hidden">
                        <div class="d-flex h-100 justify-content-center">
                            <div class="col-12 shadow-sm m-auto bg-dark">
                                <h3 class="mx-3"><a class="text-muted" href="<?php echo site_url(); ?>/category/<?php echo strtolower($month_of_birth).$separator. $day_of_birth; ?>"><?php echo $month_of_birth.' '. $day_of_birth; ?> Birthdays</a></h3>
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
                                            $items = $items2;
                                            $size = 'lg';
                                            stack_thumbnails(get_defined_vars());
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
                                            <a href="<?php echo site_url(); ?>/category/<?php echo strtolower($month_of_birth).$separator. $day_of_birth; ?>" class="btn w-100 border-primary-custom bg-primary-custom-hover">More <?php echo $month_of_birth.' '.$day_of_birth; ?> Birthdays</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        <?php

        endif;

        return ob_get_clean();

    }

    if ($field == 'slot1') {
        ob_start();
        ?>
        <div class="bg-light m-1">
            Slot 1
        </div>
        <?php
        return ob_get_clean();
    }
    if ($field == 'slot2') {
        ob_start();
        ?>
        <div class="bg-light m-1">
            Slot 2
        </div>
        <?php
        return ob_get_clean();
    }
    if ($field == 'slot3') {
        ob_start();
        ?>
        <div class="bg-light">
            Slot 3
        </div>
        <?php
        return ob_get_clean();
    }
    if ($field == 'slot5') {
        ob_start();
        ?>
        <div class="bg-light">
            Slot 5
        </div>
        <?php
        return ob_get_clean();
    }
}

add_shortcode('stats', 'stats');


function cat($cats) {

    //	    Filter Unwanted cats
    $pattern = "#(January )|(February )|(March )|(April )|(May )|(June )|(July )|(August )|(September )|(October )|(November )|(December )#";
    $arr = [];
    foreach ($cats as $cat){
        if (!preg_match($pattern, $cat->name)){
            $arr[] = $cat;
        }
    }

    $cat = @$arr[0];
    return $cat;
}


function article($i, $append = null, $content_only = null){

    foreach ($i as $k => $v){
        $$k = $v;
    }

    if (@$run_popularity == true)
    {
        ?>
    <script>
        jQuery(document).ready(function ($){
        // Do popularity in background
        var url = window.location.href;
            var response = httpGet(url + '?action=do-popularity');
            console.log(response);

            // reload page is empty content
            if ($('#inner-content #empty').text() == 1 && url.match('reloaded') == null){
                window.location.href = url + '?reloaded=true';
            }
        })
    </script>
    <?php
}

if (!$content_only): ?>
        <div class="row mt-3r">
            <div class="col-12 p-4">
                <div class="row mx-3">

                    <?php if ($title == "Today's Birthdays"): ?>


                                <div class="col-12 d-lg-none">
                                    <header class="entry-header">
                                        <div class="row justify-content-center"><h1 class="entry-title"><?= $title; ?></h1></div>
                                    </header><!-- .entry-header -->
                                </div>
                                <div class="col-6 col-lg-3">
                                    <div class="row justify-content-start">
                                        <h5><a href="<?= $yesterday_link ?>" class="btn border-secondary-custom bg-secondary-custom btn-sm"><span class="fa fa-chevron-left" style="font-size: 80%"></span>&nbsp;Yesterday</a></h5>
                                    </div>
                                </div>
                                <div class="d-none d-lg-block col-md-6">
                                    <header class="entry-header">
                                        <div class="row justify-content-center">
                                            <h1 class="page-title text-nowrap overflow-hidden" style="text-overflow: ellipsis" title="Evan Almighty"><?= $title; ?></h1>
                                        </div>
                                    </header><!-- .entry-header -->

                                </div>
                                <div class="col-6 col-lg-3">
                                    <div class="row justify-content-end"><h5><a href="<?= $tomorrow_link ?>" class="btn border-secondary-custom bg-secondary-custom btn-sm">Tomorrow&nbsp;<span class="fa fa-chevron-right" style="font-size: 80%"></span></a></h5>
                                    </div>
                                </div>

                    <?php else: ?>

                        <header class="entry-header">
                            <h1 class="entry-title"><?= $title; ?></h1>
                        </header><!-- .entry-header -->

                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>


    <article id="post-<?= $post->ID; ?>" class="card post-<?= $post->ID; ?> page type-<?= $post->post_type; ?> status-<?= $post->post_status; ?> hentry">
        <div class="card-body p-4">
            <div class="">
                <div class="row">
                    <div class="col-lg-12 col-md-12 mb-1 bg-light overflow-hidden">
                        <div id="inner-content" class="row bg-dark rounded-lg justify-content-center">

                            <?php
                            stack_thumbnails(get_defined_vars());
                            ?>
                        </div>
                    </div>
                </div>

                <?php if ($append){?>
                    <div class="row append"><?= $append ?></div>
                <?php }?>

            </div><!-- .entry-content -->
        </div>
        <!-- /.card-body -->

    </article>
    <?php


}

function add_attachments($items, $limit, $attachment_limit = 1){
    $arr = [];


    foreach ($items as $item) {

        if (count($arr) >= $limit) {
            break;
        }

        $post_id = $item['post_id'] ?? $item['viewed'];

        if (!empty($item['viewed'])){
            global $wpdb;
            $item = @$wpdb->get_results("SELECT * from {$wpdb->prefix}birthday_stats where post_id = $post_id")[0];
            $item = json_decode(json_encode($item), true);

        }

        if ($post_id) {

            $attachments = explode(',', $item['images']);

            $uploaddir = wp_upload_dir()['baseurl'].'/images/';
            $img_arr = [];
            foreach ($attachments as $attachment) {

                $img_arr[] = $uploaddir.$attachment;
            }

            $attachments = $img_arr;

            if ($attachments) {

                if ($attachment_limit > 1){

                    $images = [];
                    foreach ($attachments as $attachment){

                        $images[] = $attachment;
                    }

                }else{
                    $images = $attachments[0];
                }

                $arr[] = array_merge($item, ['image_url' => $images]);
            }
        }

    }

    return $arr;
}

include_once 'group-stats.php';


function stack_thumbnails($i){

    foreach ($i as $k => $val){

        $$k = $val;

    }


    if (@$size){
        $size = '-'.$size;
    }else{
        $size = '';
    }

    $show_rank = $show_rank ?? false;

    $counter = 0;
    foreach ($items as $the_val) {
        $counter ++;

        $post_id = $the_val['post_id'] ?? $the_val['viewed'];

        $img_url = $the_val['image_url'];

        $items = @$wpdb->get_results("SELECT * from {$wpdb->prefix}birthday_stats where post_id = $post_id")[0];

        if (isset($items->date_of_birth)) {
            $age = number_format_strict(date_diff2($items->date_of_birth, date('Y-m-d'), 'y'));
        }else {
            $age = '?';
        }

        $title = get_the_title($post_id);
        $cats = @get_the_category( $post_id );
        $cat = cat($cats);
        $cat_name = @$cat->name;

        ?>

        <div class="bg-dark carosel-item<?= $size ?> carosel-item-stack img-parent">

            <div class="img-child w-100 h-100" style="background: url(<?php echo $img_url; ?>) no-repeat center center; background-size:cover;">

                <a style="text-align: left!important;" class="btn d-flex flex-column justify-content-end w-100 h-100 p-0 pl-1" href="<?php echo get_permalink($post_id); ?>">
                    <?php if ($show_rank): ?>
                        <i class="mt-1 icn icn-star justify-content-start w-100 h-100">
                            <span class="text-white px-1 border-primary-custom rank"><?= $counter ?><span style="font-size: 50%"><?= @$the_val['views'] ?></span></span>
                        </i>
                    <?php endif; ?>

                    <div class="item-title text-white text-left overflow-hidden" style="text-overflow: ellipsis"><?php echo $title.', '.$age; ?>
                        <div class="text-white-50"><?php echo $cat_name; ?></div>
                    </div>
                </a>
            </div>
        </div>
        <?php


    }

    if (!$items) {
        ?>
        <div id="empty" class="d-none">1</div>
        <div class="bg-dark col-2" style="background: url() no-repeat center center; background-size:cover;">
            <a class="btn d-flex flex-column justify-content-end w-100 h-100 p-0 pl-1" href="#">
                <div class="item-title text-white text-left"></div>
            </a>
        </div>
        <?php
    }


}


function filter_trash($items) {

    $arr = [];
    foreach ($items as $item) {

        $post_id = $item['post_id'] ?? $item['viewed'];

        if (get_post_status( $post_id) == 'publish'){

            $arr[] = $item;
        }
    }

    return $arr;

}


function auto_login(){

    if (@$_GET['auto-login'] == 'final') {

            $username = @$_GET["username"];
        $password = @$_GET["password"];

            if($username){

//                $user = get_user_by('login',$username)
                $creds = array(
                    'user_login'    => $username,
                    'user_password' => $password,
                    'remember'      => true
                );


                $user = wp_signon($creds, false);

                if ( is_wp_error($user) ) {
//                    var_dump($user);
                }

                else {


                clean_user_cache($user->ID);

                wp_clear_auth_cookie();
                wp_set_current_user( $user->ID );
                wp_set_auth_cookie( $user->ID );

                update_user_caches($user);
                }

                if(is_user_logged_in()){

                    $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

                    $redirect_to = urldecode(explode('uri=', $_SERVER['REQUEST_URI'])[1]);

                    header('Location: '.$redirect_to);
                    exit();


                }else{
                    echo "<strong style='color: red'>Wrong username/password.</strong>";
                    exit();
                }

            }else {
                echo "<strong style='color: red'>Cannot access requested page, Unknown username $username</strong>";
                exit();
            }



    }
}

add_action('init', 'auto_login');


if (@$_GET['auto-login'] !== 'final'){

    function cron(){
        include_once 'Cron.php';
        die;

    }


    if (@$_GET['cron'] == 'true'){
        cron();
    }

}


add_filter( 'auth_cookie_expiration', 'keep_me_logged_in_for_1_year' );

function keep_me_logged_in_for_1_year( $expirein ) {

    return 31556926; // 1 year in seconds

}

?>

