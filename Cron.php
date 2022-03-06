<?php
use Goutte\Client;

use \Symfony\Component\DomCrawler\Crawler;
require_once (__DIR__ . "/fabpot_goutte/vendor/autoload.php");

class Cron
{

    public function get_popular_birthdays() {


        $username = @$_GET['username'];
        $password = @$_GET['password'];

        if (!$username || !$password){
            die("<strong style='color: red'>Missing username or password</strong>");
        }

        echo '<h3>Cron task</h3>';


        global $wpdb;

        $limit = 3;

        $items = @$wpdb->get_results("SELECT * from {$wpdb->prefix}categories where counts_saved = '0' order by updated_at asc limit 100");

        echo "Fetching data using cats, limited to: $limit<br>";
        shuffle($items);

        $counts = 0;
        foreach ($items as $item){
            $counts++;

            if ($counts > $limit){
                break;
            }

//            lets update the update @ (the column is auto update)
            @$wpdb->update("{$wpdb->prefix}categories",
                ['updated_at' => now()],
                ['id' => $item->id]
            )[0];


            $source = $item->url;
//            $source = "https://www.famousbirthdays.com/december25.html"
            ;

            echo "<h4>#$counts. $item->cat_name (Source: $source)</h4>";



            $domain = site_url();
//            $domain = "http://popularbirthdays.com/";

            $uri = admin_url()."admin.php?page=popular-birthdays&action=popular-birthdays-source&url=$source&cron-login=true";
        $url = $domain."?cron=true&auto-login=final&username=$username&password=$password&uri=$uri";

            $client = new Client();

            /** @var Goutte\Client $client */
            $crawler = $client->request('GET', $url, [
                'form_params' => [
                    'auto-login' => 't',
                    'name' => 'Test user',
                    'password' => 'testpassword',
                ]]);


            // Get the people post links
            $results = $crawler->filter('#error')->each(function ($node) {

                return $node->html();
            });
            $offline = $results[0];

            if ($offline){
                ?><div class="alert alert-danger" id="error">Oops! We are having trouble contacting the source, might you be offline?</div>
                <?php
            }


            // Get the people post links
            $results = $crawler->filter('#popular-birthdays-admin')->each(function ($node) {

                return $node->html();
            });

            if (!$results){

                $results = $crawler->filter('body')->each(function ($node) {

                    return $node->html();
                });
            }

            print_r($results);

            echo '<br>';

            $saved = @$results[0] ?: 0;


            ?>

            <div class="row">
                <h5>Category summary</h5>
                <div class="col-3 bg-white">Total saved:
                    &nbsp;<span id="saved"><?= $saved ?></span>

                </div>
            </div>
            <?php

//        update the counts saved
            if ($saved > 0){
                @$wpdb->update("{$wpdb->prefix}categories",
                    ['counts_saved' => $saved],
                    ['id' => $item->id]
                )[0];
            ?>
                <div style="color: orangered">Updated category counts_saved</div>
<?php
            }

            echo '#End single cat<hr>';

        }

            echo '<br><hr style="border: dashed orangered 3px"><span style="color: orangered">#End all cats ('.$limit.')</span><br>';

    }

}

$Cron = new Cron;

$Cron->get_popular_birthdays();