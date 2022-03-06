<div id="scraper">
    <?php

    if (@$_GET['cron-login'] == 'true'){
    $_POST = $_GET;
}

$default =  @$_POST['url'] ?: 'https://www.famousbirthdays.com/'.strtolower(date('Fd')).'.html';
?> <form method="post">
    <div style="width: 40%;">
        <input type="url" name="url" value="<?php echo $default; ?>" style="width: 100%">
        <input type="hidden" name="action" value="fetch">
        <button type="submit" style="margin: 8px 0 8px 0" name="submit">Go</button>
    </div>
</form>
<?php

if (isset($_POST['url'])) :

    require_once 'popular-birthdays-fetcher.php';
    require_once 'member-of-fetcher.php';

    $url = $_POST['url'];


//    if (is_localhost()){
//        $url = false;
//    }

    $is_url = false;
    if ($url){
        $o = (new PopularBirthdaysSource)->getLinks($url);
        $popular_birthdays_links = is_array($o) ? $o : ['links' => []];

        if (!is_array($o)){
            ?><div class="alert alert-danger" id="error">Oops! We are having trouble contacting the source, might you be offline?</div>
        <?php
    }

//        $popular_birthdays_links = limit($popular_birthdays_links, 2);

//        $popular_birthdays_links = [
//                'https://www.famousbirthdays.com/people/cardi-b.html/',
//            'https://www.famousbirthdays.com/people/aubrey-drake-graham.html/',
//            'https://www.famousbirthdays.com/people/nicki-minaj.html/',
//            'https://www.famousbirthdays.com/people/rihanna.html/'
//        ];

        $is_url = true;
    }else{
    $popular_birthdays_links = ['links' => [1]];
    }

echo "Links counts: ".count($popular_birthdays_links['links'])."<br>";

    $saved = 0;
    foreach (@limit($popular_birthdays_links['links'], 50) as $popular_birthdays_link) {

        echo "Loop...<br>";

        if($is_url){
            $url = $popular_birthdays_link;
        }else{
                $url = "http://localhost/cardi-b.html";
        }

//var_dump($url);die;

        $images_links = (new PopularBirthdaysSource)->getImages($url);

        $main_info = (new PopularBirthdaysSource)->getMainInfo($url);


//var_dump($images_links);die;

        $content = '';
        include 'person-profile.php';

//Get category ID if exists
        $cat_name = $main_info['title'];

        $cat_id = @get_category_by_slug(sanitize_title($cat_name))->term_id;
//Create category if not $exists

        if (!$cat_id)
        {
            $cat_id = wp_insert_term($cat_name, 'category', array(
                'description' => '',
                'slug' => sanitize_title($cat_name),
                'parent' => 0 // must be the ID, not name
            ))['term_id'];

        }

        $cat_name2 = $main_info['stats']->date_of_birth;
        $month_of_birth = date('F', strtotime($cat_name2));
        $day_of_birth = (int) date('d', strtotime($cat_name2));

        $cat_name2 = $month_of_birth.' '.$day_of_birth;
        $cat_id2 = get_category_by_slug(sanitize_title($cat_name2))->term_id;
//Create category if not $exists

        if (!$cat_id2)
        {
            $cat_id2 = wp_insert_term($cat_name2, 'category', array(
                'description' => '',
                'slug' => sanitize_title($cat_name2),
                'parent' => 0 // must be the ID, not name
            ))['term_id'];

        }

        global $wpdb;
        $exists = @$wpdb->get_results("SELECT * from {$wpdb->prefix}birthday_stats where url = '$url'")[0];

        $post_id = @$exists->post_id;

        $proceed = true;
        //Insert post, set category, and add attachments
//Lets check if post exits
        if ($post_id) {
            $proceed = false;
            echo  "Post with similar title exists.<br>";

        }elseif ($images_links == 'No images found.') {

            $proceed = false;
            echo  "No image links.<br>";

        }elseif (strlen($main_info['stats']->date_of_birth) < 4) {

            $proceed = false;
            echo  "No DOB Stats.<br>";

        }

//        if $proceed
        if ($proceed)
        {

// Create post object
            $my_post = array(
                'post_title'    => wp_strip_all_tags( $main_info['name'] ),
                'post_content'  => $content,
                'post_status'   => 'publish',
                'post_author'   => get_current_user_id(),
                'post_category' => array($cat_id, $cat_id2)
            );


// Insert the post into the database
            $post_id = wp_insert_post( $my_post, true );

// The ID of the post this attachment is for.
            $parent_post_id = $post_id;

if ($post_id){
    echo 'Successfully saved post ('.$post_id.').<br>';


    $images_links = limit($images_links, 200);

    shuffle($images_links);

    echo "Images counts: ".count($images_links).'<br>';
    $uploaddir = wp_upload_dir();

    $dir = $uploaddir['basedir'] . '/images/';

    if( is_dir($dir) === false )
    {
        mkdir($dir);
    }

    $image_arr = [];
    foreach ($images_links as $the_key => $imagePath) {

//        var_dump($imagePath);die;
//    $imagePath = 'https://i2-prod.irishmirror.ie/incoming/article7496503.ece/ALTERNATES/s810/Birthday.jpg';

        $filename = basename($imagePath);

        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        $filename = sanitize_title($main_info['name']).'_'.$the_key.'_'.$parent_post_id.'.'.$ext;

        $uploadfile = $dir . $filename;

        $contents= file_get_contents($imagePath);
        $savefile = fopen($uploadfile, 'w');
        fwrite($savefile, $contents);

        // Set perms with chmod()
        chmod($uploadfile, 0777);

        fclose($savefile);

        $image_arr[] = $filename;

    }

    $images = '';
    if ($image_arr){
        $images = implode(',', $image_arr);
    }

//Adding BirthDay Stats to DB

    $exists = @$wpdb->get_results("SELECT id from {$wpdb->prefix}birthday_stats where post_id = '$post_id'")[0];

    $arr = array( "post_id" => $post_id, "date_of_birth" => $main_info['stats']->date_of_birth,
        "city" => $main_info['stats']->city,
        "birth_place" => $main_info['stats']->birth_place,
        "birth_sign" => $main_info['stats']->birth_sign,
        "url" => $url,
        "images" => $images
    );

    if ( !$exists ) {

        $saved ++;

        // insert new stats
        $wpdb->insert("{$wpdb->prefix}birthday_stats",
            $arr
        );

        $exists = @$wpdb->get_results("SELECT id from {$wpdb->prefix}birthday_stats where post_id = '$post_id'")[0];

        echo '<div style="border: solid 3px orange;width: 80%;padding: 4px">';
        echo '<h4>Member of data</h4>';

        $member_of = (new MemberOfFetcher)->getMemberOfInfo($url, $exists->id);
//                echo $member_of.'<br>';

        echo '</div>';



    }
}
//endif post_id (attach images and insert stats)


        }
//        end if proceed


    }
//    endforeach loop


?>
<div class="row">
    <div class="col-3 bg-white">Total saved:
        &nbsp;<span id="saved"><?= $saved ?></span>

    </div>
</div>
    <?php

else:
    echo 'Input source url.';
endif;

?>
</div>