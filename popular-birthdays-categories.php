<div class="row">
    <div class="col-6">
        <form method="post">
            <div class="row">
                <div class="col-10">
                    <div class="form-group">
                        <input type="url" class="form-control" name="url" value="" placeholder="Enter source url">
                    </div>

                </div>

                <div class="col-2">
                    <input type="hidden" name="action" value="fetch">
                    <button type="submit" class="btn btn-info" name="submit">Go</button>

                </div>

            </div>
        </form>
    </div>
</div>
<?php
global $wpdb;

$string = ["billboard-music",
    "emmy",
    "golden-globe",
    "grammy",
    "index",
    "index.php",
    "mtv-video-music",
    "oscar",
    "radio-disney-music",
    "streamy",];

$cities = [];
//foreach ($string as $arr){
//
//    $cities[] = "https://www.famousbirthdays.com/awards/type/".strtolower(trim($arr)).".html";
//
//}

$loop = @$_GET['loop'];


foreach (limit($cities, 10000) as $links_name){

    $cat_name = "Awards by Type";
    $cat_slug = sanitize_title($cat_name);

    $url = $links_name;
    //     var_dump($url);die;




    if (!$loop){
        die('No loop');
    }

//        $deleted = $wpdb->query("truncate table {$wpdb->prefix}categories_{$loop}");
//die();

    $exists = @$wpdb->get_results("SELECT id from {$wpdb->prefix}categories_{$loop} where url = '$url'")[0];

    $arr = array( "url" => "$url",
        "cat_name" => $cat_name,
        "cat_slug" => $cat_slug,
        "counts_source" => 0
    );

    echo '<div style="border: solid 3px orange;width: 80%;padding: 4px">';

    if ( !$exists ) {

        // insert new stats
        $wpdb->insert("{$wpdb->prefix}categories_{$loop}",
            $arr
        );

        echo 'Saved category details: '.$url.' (counts 0).';


    }else{
        echo $url.' is already saved.';
    }


    echo '</div>';



}
//endforeach



if ( $loop ) {

    $a = count($wpdb->get_results("select * from {$wpdb->prefix}categories_{$loop} where 1"));
    $b = count($wpdb->get_results("select * from {$wpdb->prefix}categories_{$loop} where counts_source != '0'"));
    $c = count($wpdb->get_results("select * from {$wpdb->prefix}categories_{$loop} where counts_source > '0'"));
    $d = count($wpdb->get_results("select * from {$wpdb->prefix}categories_{$loop} where counts_source = '-1'"));

    echo strtoupper($loop)."::<br>";
    echo "Total: $a<br>";
    echo "Scanned: $b<br>";
    echo "Found: $c<br>";
    echo "Failed: $d<br>";

}



if (isset($_POST['url'])) :

    require_once 'popular-birthdays-fetcher.php';


    if ( $loop == '1' ){
        //loop days
        $loop = 365;
        for ($i=0;$i<=$loop;$i++):

            $url = 'https://www.famousbirthdays.com/'.strtolower(date('Fd', strtotime('2020-01-01 +'.$i.' days'))).'.html';
            echo $url;echo "<br>";
            post_url($url);
        endfor;

    }

    if ( $loop == '2' ) {

        //loop ages
        $loop = 120;
        for ($i = 1; $i <= $loop; $i++):

            $url = 'https://www.famousbirthdays.com/age/' . $i . '.html';
            echo $url;
            echo "<br>";
            post_url($url);
        endfor;

    }elseif ( $loop ) {

        $names = $wpdb->get_results("select * from {$wpdb->prefix}categories_{$loop} where counts_source = '0'");

        foreach ($names as $name):

            $url = $name->url;

            echo $url;
            echo "<br>";
            post_url($url);
        endforeach;

    }

    //looped entities

    //    normal post
    else{

        $url = $_POST['url'];

        $url = $url ?: "http://localhost/awards.html";
        post_url($url);
    }



endif;


function post_url($url){

    global $wpdb;
    $loop = @$_GET["loop"];

    if (preg_match("#famousbirthdays.com/$|famousbirthdays.com$#", $url)){
        $url = '';
    }

    $is_url = false;
    if ($url){
        $popular_birthdays_links = (new PopularBirthdaysSource)->getLinks($url);

        //        $popular_birthdays_links = limit($popular_birthdays_links, 2);

        //        $popular_birthdays_links = [
        //                'https://www.famousbirthdays.com/people/cardi-b.html/',
        //            'https://www.famousbirthdays.com/people/aubrey-drake-graham.html/',
        //            'https://www.famousbirthdays.com/people/nicki-minaj.html/',
        //            'https://www.famousbirthdays.com/people/rihanna.html/'
        //        ];


        $is_url = true;
    }
//var_dump($url);
//        var_dump($popular_birthdays_links);die;

    $cat_name = $popular_birthdays_links['title'];
    $cat_slug = sanitize_title($cat_name);

    if (strlen($cat_name) > 2 && !preg_match("#Page Not Found#i", $cat_name) && count($popular_birthdays_links['links']) > 0){

        $exists = @$wpdb->get_results("SELECT id from {$wpdb->prefix}categories where cat_slug = '$cat_slug'")[0];

        $arr = array( "url" => "$url",
            "cat_name" => $cat_name,
            "cat_slug" => $cat_slug,
            "counts_source" => count($popular_birthdays_links['links'])
        );

        echo '<div style="border: solid 3px orange;width: 80%;padding: 4px">';

        if ( !$exists ) {

            // insert new stats
            $wpdb->insert("{$wpdb->prefix}categories",
                $arr
            );

            echo 'Saved category details: '.$cat_name.' (counts '.count($popular_birthdays_links['links']).').';


        }else{
            echo $cat_name.' is already saved.';
        }


        echo '</div>';
    }
    else{

        echo "Invalid page / Page not found.<br>";
    }


    if ($loop){
        $wpdb->update("{$wpdb->prefix}categories_{$loop}", ['counts_source' => count($popular_birthdays_links['links']) ?: '-1'], ['url' => $url]);
    }


}
//end post_url

if (@$_POST['action'] == 'delete_category'){

    $id = $_REQUEST['id'];

    if ($id > 0){

        $deleted = $wpdb->delete("{$wpdb->prefix}categories", ['id' => $id]);

        if ($deleted){
            ?>
            <div class="alert alert-danger">Deleted category</div>
            <?php
        }else{
            ?>
            <div class="alert alert-danger">Error deleting category</div>
            <?php
        }
    }

}

if (@$_POST['action'] == 'delete_all'){

    $deleted = $wpdb->query("truncate table {$wpdb->prefix}categories");

    if ($deleted){
        ?>
        <div class="alert alert-danger">Deleted all categories</div>
        <?php
    }else{
        ?>
        <div class="alert alert-danger">Error deleting categories</div>
        <?php
    }
}

$items = @$wpdb->get_results("select * from {$wpdb->prefix}categories where 1 order by id desc");

?>
<?php
$nb_elem_per_page = isset($_GET['l'])? $_GET['l'] : 1000;

$page = isset($_GET['p'])? intval($_GET['p']-1) : 0;
$number_of_pages = intval(count($items)/$nb_elem_per_page)+1;

 ?>


<h1>All categories list (<?= count($items) ?>)</h1>
<table class="table table-striped table-success">
    <thead>
    <tr>
        <th>#</th><th>Name</th><th>Uri</th><th>Saved/Total</th><th>Action</th>
    </tr>
    </thead>
    <?php
    $all_counts_source = $all_counts_saved = 0;
    foreach ($items as $item) {

        $all_counts_source += $item->counts_source;
        $all_counts_saved += $item->counts_saved;
    }


    $counts = $counts_saved = $counts_source = 0;
    foreach (array_slice($items, $page*$nb_elem_per_page, $nb_elem_per_page) as $item) {

    ++$counts;
        $counts_source += $item->counts_source;
        $counts_saved += $item->counts_saved;

        $uri = parse_url($item->url)['path'];

            ?>
            <tr>
                <td><?= $counts ?>.</td><td><?= $item->cat_name ?></td><td><?= $uri ?></td>

                <td><?php
                    echo $item->counts_saved.'/'.$item->counts_source;

                    ?></td>

                <td>
                    <form action="" method="post"><input type="hidden" value="<?= $item->id ?>" name="id">
                        <input type="hidden" name="action" value="delete_category">
                        <input type="submit"  class="btn btn-danger" name="delete" value="Delete"></form></td>
            </tr>

            <?php

    }

    if (!$items){
        ?>
        <tr>
            <td colspan="5" class="bg-white">Looks like there are no saved categories yet</td>
        </tr>

        <?php
    }

    ?>
</table>

<div class="row">
    <div class="col-12">
        <div class="row justify-content-center">
            <div class="col-11 bg-white rounded-lg shadow m-1">
                <div class="row justify-content-center">
                    <h5>--Pages---</h5>
                </div>
                <ul class="nav justify-content-center">
                    <?php
                    for($i=1;$i<$number_of_pages;$i++){

                        $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                        $actual_link = preg_replace("#p=[0-9]+#", "p=".$i, $actual_link);

//                        adding the limit to url
                        if (preg_match("#l=[0-9]+#", $actual_link)){

                        $actual_link = preg_replace("#l=[0-9]+#", "&l=1000", $actual_link);
                        }else{
                            $actual_link = $actual_link."&l=1000";

                        }

                        $actual_link = preg_replace("#&&#", "&", $actual_link);

                        ?>
                        <li class="nav-item"><a class="nav-link border" href='<?=$actual_link?>'><span class="bg-light px-1 w-100"><?=$i?></span></a></li>
                    <?php }?>
                </ul>

            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="row justify-content-center">

<div class="col-10 rounded-lg shadow m-1 bg-white">
    <div class="row">

        <div class="col-4">
            <div class="row justify-content-center">
                <div>
                    <h6>Current page</h6>
                    <span class="bg-dark text-white" style="font-size: 120%">
    Saved / Totals:&nbsp;<?= $counts_saved.' / '.$counts_source ?>
    </span>
                </div>
            </div>

        </div>

        <div class="col-4">
            <div class="row justify-content-center">
                <div>

                    <h5>Grand totals</h5>
                    <span class="bg-dark text-white" style="font-size: 120%">
    Saved / Totals:&nbsp;<?= $all_counts_saved.' / '.$all_counts_source ?>
    </span>
                </div>
            </div>
        </div>

        <?php if ($items): ?>
            <div class="col-4">
                <div class="row justify-content-center">
                    <div class="mb-2">
                        <h5>Mass action</h5>
                        <span method="post" class="bg-success">
                <button onclick="return confirm('Are you sure you want to delete all categories? This action can\'t be undone.')" class="btn btn-sm btn-danger p-0" type="submit" value="delete_all" name="action">Delete all categories</button>
            </span>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
        </div>

    </div>
</div>