<?php
use Goutte\Client;

use \Symfony\Component\DomCrawler\Crawler;
require_once (__DIR__ . "/fabpot_goutte/vendor/autoload.php");


class MemberOfFetcher
{

    protected $member_of;
    protected $stats_id;

    public function getMemberOfInfo($url, $stats_id)
{

    $this->stats_id = $stats_id;

//    var_dump(
// [$url, $stats_id]);die;

    $client = new Client();
    $crawler = $client->request('GET', $url);

    //  Getting member_of
    $member_of_title =  $crawler->filter('.row .member-of')->each(function ($node, $i) {

        $html = $node->html();

        $crawler2 = new Crawler($html);

        return $crawler2->filter('h3')->each(function($node) {
            return $node->text();
        });

    })[0];

    $title = $member_of_title;

    $member_of_content =  $crawler->filter('.row .member-of')->each(function ($node, $i) {

        $html = $node->html();

        $crawler2 = new Crawler($html);

        return $crawler2->filter('.row > a')->each(function($node) {
            return $node->attr('href');
        });

    })[0];

    $member_of_link = $member_of_content[count($member_of_content) - 1];

    $member_of_items = [];

    foreach ($member_of_content as $value){
        $inc = false;
        $member_of = null;
        if (!preg_match("#\/people\/#", $value)){

            if (preg_match("/songs\//", $value)){
                $inc = true;$member_of = 'songs';
            }elseif (preg_match("/movies\//", $value)){
                $inc = true;$member_of = 'movies';
            }elseif (preg_match("/schools\//", $value)){
                $inc = true;$member_of = 'schools';
            }elseif (preg_match("/shows\//", $value)){
                $inc = true;$member_of = 'shows';
            }
        }

        if ($inc){

            if (!preg_match("#famousbirthdays#", $value)){
                $value = "https://www.famousbirthdays.com".$value;
            }
            $member_of_items[] = ['url' => $value, 'member_of' => $member_of];
        }

    }

//    var_dump($member_of_items);die;
        echo 'Member of counts: '.count($member_of_items).'<br>';

//            fetch the member of Items/Posts
        foreach ($member_of_items as $member_of_item){
//            var_dump($member_of_item);die;
            echo $this->getMemberOfInfoLoop($member_of_item);

        }




}

public function getMemberOfInfoLoop($member_of_item) {

    $url = $member_of_item['url'];
    $this->member_of = $member_of_item['member_of'];

//    $url = "http://localhost/in-my-feelings-drake.html";
//var_dump($url,$this->member_of);die;
    $client = new Client();
    $crawler = $client->request('GET', $url);

// Get the people post links

    $element_count = $crawler->filter('.col-xs-12 .page-title')->count();
    $element_contents = array();

    if ($element_count == 1) {
        $title = $crawler->filter('h3.page-title')->each(function ($node, $i) {

            return $node->html();

        })[0];
    }


    $images_links = $crawler->filter('.col-xs-12 div.col-xs-4 img')->each(function ($node) {
        $srcs = [];
        // lets check if current node link doesnt exist in links array
        if (!in_array($node->attr('src'), $srcs))
        {
            return $srcs[] = $node->attr('src');
        }
    })[0];

//                var_dump($images_links);die;


//                Getting article headers
    $article_headers =  $crawler->filter('.col-sm-5.group-about')->each(function ($node, $i) {

        $html = $node->html();

        $crawler2 = new Crawler($html);

        return $crawler2->filter('h2')->each(function($node) {
            return $node->text();
        });

    })[0];
//            var_dump($article_headers);die;


//            Getting article contents
    $article_content =  $crawler->filter('.col-sm-5.group-about')->each(function ($node, $i) {

        $html = $node->html();

        $crawler2 = new Crawler($html);

        return $crawler2->filter('p')->each(function($node) {
            return wp_strip_all_tags($node->html());
        });

    })[0];


//if $article_headers && $article_content get $member_of

    if ($article_headers && $article_content) {

        //    Getting group_stats
        $group_stats = $crawler->filter('.list-sub-nav.mobile-stats')->each(function ($node, $i) {

            $html = $node->html();

            return $group_stats = $this->member_of_pre_save($html);

        })[0];

//        var_dump($group_stats);die;


//        Looks like everything's good
        if ($images_links && $title && $article_headers && $article_content && $group_stats){

            echo 'Looks like everything\'s good...<br>';

            return $this->save($images_links, $title, $article_headers, $article_content, $group_stats);

        }else{
            return 'Could not save, 1 or more errors found.<br>';
        }

    }else{
        return 'Member of info not found.<br>';
    }
}

public function member_of_pre_save($html){

    $member_of = $this->member_of;

    $group_stats = new stdClass;

        $crawler = new Crawler($html);

        $stats_head =  $crawler->filter('.col-xs-4 .sn-label')->each(function ($node, $i) {

                  return trim(preg_replace("#:#", "", $node->text()));

            });

        $stats_content =  $crawler->filter('.col-xs-4 .sn-value')->each(function ($node, $i) {

            $html = $node->html();


//            $res = trim(preg_replace("#<(.*)>(.*)</(.*)>#", "", $html));
//var_dump($html);die;
            $res = $node->text();
            return  $res;


        });

//var_dump($stats_content);die;

//    echo $title;

    if ($member_of == 'schools') {
        echo "Is member of schools:<br>";

        foreach ($stats_head as $key => $item) {
            if(preg_match("#ESTABLISHED#i", $item)) {
            $group_stats->established = $stats_content[$key];
            }elseif(preg_match("#LOCATION#i", $item)) {
                $group_stats->location = $stats_content[$key];
            }elseif(preg_match("#NICKNAME#i", $item)) {
                $group_stats->nickname = $stats_content[$key];
            }

        }

    }

    elseif ($member_of == 'movies') {
        echo "Is member of movies:<br>";

        foreach ($stats_head as $key => $item) {
            if(preg_match("#RELEASED#i", $item)) {
                $group_stats->released = date("Y-m-d", strtotime($stats_content[$key]));
            }elseif(preg_match("#GENRE#i", $item)) {
                $group_stats->genre = $stats_content[$key];
            }elseif(preg_match("#RATING#i", $item)) {
                $group_stats->rating = $stats_content[$key];
            }

        }

    }

    elseif ($member_of == 'songs') {
        echo "Is member of songs:<br>";

        foreach ($stats_head as $key => $item) {
            if(preg_match("#RELEASED#i", $item)) {
                $group_stats->released = date("Y-m-d", strtotime($stats_content[$key]));
            }elseif(preg_match("#^ARTIST#i", $item)) {
                $group_stats->artist = $stats_content[$key];
            }elseif(preg_match("#ALBUM#i", $item)) {
                $group_stats->album = $stats_content[$key];
            }elseif(preg_match("#RUNTIME#i", $item)) {
                $group_stats->runtime = $stats_content[$key];
            }elseif(preg_match("#LABEL#i", $item)) {
            $group_stats->label = $stats_content[$key];
            }elseif(preg_match("#GENRE#i", $item)) {
                $group_stats->genre = $stats_content[$key];
            }

        }

    }
    elseif ($member_of == 'shows') {
        echo "Is member of shows:<br>";

        foreach ($stats_head as $key => $item) {
            if(preg_match("#PREMIERED#i", $item)) {
                $group_stats->premiered = date("Y-m-d", strtotime($stats_content[$key]));
            }elseif(preg_match("#NETWORK#i", $item)) {
                $group_stats->network = $stats_content[$key];
            }elseif(preg_match("#GENRE#i", $item)) {
                $group_stats->genre = $stats_content[$key];
            }

        }

    }

    return $group_stats;


    }

    function save($images_links, $title, $article_headers, $article_content, $group_stats){

        echo "Saving the group_stats... ($title)<br>";
//var_dump($images_links, $title, $article_headers, $article_content, $group_stats);die;
        $cat_name = ucfirst($this->member_of);
            //Get category ID if exists

            $cat_id = get_category_by_slug(sanitize_title($cat_name))->term_id;
//Create category if not $exists

            if (!$cat_id)
            {
                $cat_id = wp_insert_term($cat_name, 'category', array(
                    'description' => '',
                    'slug' => sanitize_title($cat_name),
                    'parent' => 0 // must be the ID, not name
                ))['term_id'];

            }

//            var_dump($images_links, $title, $article_headers, $article_content, $group_stats);


        $arr = [];
        ob_start();
        ?>
        <!--Content-->
        <div class="main-content">
            <div class="row">
                <div class="col-12">
                    <div id="header-section" class="mx-3 mb-2">
                        <div class="col-12">
                            <div class="row bg-white shadow py-3 rounded">
                                <div class="ad col-sm-12 p-1 mb-1">[group_stats field='slot1']</div>
                                <div class="col-md-3 mb-1">
                                    <div class="row justify-content-center">
                                        <div class="col-12">
                                            <div class="d-flex py-1">

                                                <div class="col-12 m-auto p-0">
                                                    [group_stats field='image']
                                                </div>
                                            </div>
                                            <div class="row m-stats d-md-none bg-light rounded-lg">
                                                [group_stats field='stats_mobile']
                                            </div>
                                            </div>
                                        </div>
                                    </div>
                                <div class="col-md-6 grp-about mb-1">
                                    <div class="row justify-content-center">
                                        <div class="col-12 shadow-sm rounded">
                                            <?php
                                            foreach ($article_headers as $key => $info)
                                            {
                                                ?>
                                                <div class="article_content">
                                                    <h4><?php echo $info; ?></h4>
                                                    <p><?php echo $article_content[$key]; ?></p>
                                                </div>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 grp-stats-container d-none d-md-block mb-1">
                                    <div class="row justify-content-center">
                                        <div class="col-12">
                                            <div class="d-flex flex-column bg-light py-1 rounded">
                                                [group_stats field='stats']
                                                [group_stats field='boost']

                                            </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <div class="bottom">
                        <div class="row">
                            <div class="col-12">[group_stats field='cast']</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php

        $arr[] = ob_get_clean();



        //Insert post, set category, and add attachments
//Lets check if post exits
            if ($post_id = post_exists($title)) {
                echo  "Member of post with similar title exists.<br>";
            }else{

                $content = implode('', $arr);

// Create post object
                $my_post = array(
                    'post_title'    => wp_strip_all_tags( $title ),
                    'post_content'  => $content,
                    'post_status'   => 'publish',
                    'post_author'   => get_current_user_id(),
                    'post_category' => array($cat_id)
                );

// Insert the post into the database
                $post_id = wp_insert_post( $my_post );

if ($post_id){
    echo 'Successfully saved ('.$post_id.').<br>';
}

// The ID of the post this attachment is for.
                $parent_post_id = $post_id;


                $image_arr = [];

                if ($images_links){

    $imagePath = $images_links;

                    $filename = basename($imagePath);

                    $ext = pathinfo($filename, PATHINFO_EXTENSION);
                    $filename = sanitize_title($title).'_'.$key.'_'.$parent_post_id.'.'.$ext;

                    $uploaddir = wp_upload_dir();
                    $uploadfile = $uploaddir['basedir'] . '/images/' . $filename;

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


//Adding Group Stats to DB
                global $wpdb;

                if ($this->member_of == 'schools'){

                    $exists = @$wpdb->get_results("SELECT * from {$wpdb->prefix}{$this->member_of} where name = '$title'")[0];

                    $arr = array("post_id" => $post_id, "name" => "$title", "established" => $group_stats->established,
                        "location" => $group_stats->location,
                        "nickname" => $group_stats->nickname,
                        "images" => $images
                    );

                    if (! $exists) {

                        // insert new group stats
                        $wpdb->insert("{$wpdb->prefix}$this->member_of",
                            $arr
                        );

                    }else{

                        // update existing stats
                        $wpdb->update("{$wpdb->prefix}$this->member_of",
                            $arr,
                            array( "id" => $exists->id )
                        );

                    }

                }
//                endif schools

                elseif ($this->member_of == 'movies'){

                    $exists = @$wpdb->get_results("SELECT * from {$wpdb->prefix}{$this->member_of} where name = '$title'")[0];

                    $stats_ids = $this->stats_id;
                    if (@$exists->stats_id) {
                        $stats_ids = $exists->stats_ids.','.$this->stats_id;
                    }
                    $arr = array("post_id" => $post_id, "name" => "$title",
                        "stats_ids" => $stats_ids, "released" => $group_stats->released,
                        "genre" => $group_stats->genre,
                        "rating" => $group_stats->rating,
                        "images" => $images
                    );

                    if (! $exists) {

                        // insert new group stats
                        $wpdb->insert("{$wpdb->prefix}{$this->member_of}",
                            $arr
                        );

                    }else{

                        // update existing stats
                        $wpdb->update("{$wpdb->prefix}{$this->member_of}",
                            $arr,
                            array( "id" => $exists->id )
                        );

                    }

                }
//                endif movies

                elseif ($this->member_of == 'songs'){

                    $exists = @$wpdb->get_results("SELECT * from {$wpdb->prefix}{$this->member_of} where name = '$title'")[0];
                    $stats_ids = $this->stats_id;
                    if (@$exists->id) {
                        $stats_ids = $exists->stats_ids.','.$this->stats_id;
                    }
                    $arr = array("post_id" => $post_id, "name" => "$title",
                        "stats_ids" => $stats_ids, "released" => $group_stats->released,
                        "artist" => $group_stats->artist,
                        "album" => $group_stats->album,
                        "runtime" => $group_stats->runtime,
                        "label" => $group_stats->label,
                        "genre" => $group_stats->genre,
                        "images" => $images
                    );
                    if (! $exists) {

                        // insert new group stats
                        $wpdb->insert("{$wpdb->prefix}{$this->member_of}",
                            $arr
                        );

                    }else{

                        // update existing stats
                        $wpdb->update("{$wpdb->prefix}{$this->member_of}",
                            $arr,
                            array( "id" => $exists->id )
                        );

                    }

                }
//                endif songs

                elseif ($this->member_of == 'shows'){

                    $exists = @$wpdb->get_results("SELECT * from {$wpdb->prefix}{$this->member_of} where name = '$title'")[0];
                    $stats_ids = $this->stats_id;
                    if (@$exists->id) {
                        $stats_ids = $exists->stats_ids.','.$this->stats_id;
                    }

                    $arr = array("post_id" => $post_id, "name" => "$title",
                        "stats_ids" => $stats_ids, "premiered" => $group_stats->premiered,
                        "network" => $group_stats->network,
                        "genre" => $group_stats->genre,
                        "images" => $images
                    );

                    if (! $exists) {

                        // insert new group stats
                        $wpdb->insert("{$wpdb->prefix}{$this->member_of}",
                            $arr
                        );

                    }else{

                        // update existing stats
                        $wpdb->update("{$wpdb->prefix}{$this->member_of}",
                            $arr,
                            array( "id" => $exists->id )
                        );

                    }

                }
//                endif shows

                $exists = @$wpdb->get_results("SELECT * from {$wpdb->prefix}birthday_stats where id = $this->stats_id")[0];

                // create instance member of
                $member_of = $this->member_of;
                if (strlen($exists->member_of) > 0){

                    // append member of
                    $list = explode(',', $exists->member_of);

                    if (!in_array($member_of, $list)) {
                        $member_of = implode(',', $list).','.$member_of;
                    }
                }

                // update stats member_of
                $wpdb->update("{$wpdb->prefix}birthday_stats",
                    ['member_of' => $member_of],
                    array( "id" => $this->stats_id )
                );

            }

            echo '<hr>';

    }

}
?>