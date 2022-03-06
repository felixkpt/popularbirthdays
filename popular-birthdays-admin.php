<?php
    // create custom plugin settings menu
    add_action( 'admin_menu', 'add_popular_settings' );
    function add_popular_settings(){

        $page_title = 'My popular Settings';
        $menu_title = 'Popular Birthdays';
        $capability = 'manage_options';
        $menu_slug  = 'popular-birthdays';
        $function   = 'popular_plugin_settings_page';
        $icon_url   = plugins_url('/popular-birthdays/images/rsz_icon.png');
        $position   = 4;

        add_menu_page( $page_title, $menu_title,  $capability,  $menu_slug,  $function,  $icon_url,  $position);

        //call register settings function
        add_action( 'admin_init', 'popular_register_settings_handler' );

    }

    function popular_register_settings_handler() {


        if (array_key_exists('update_settings', $_POST)) {

            update_option('homepage_categories', $_POST['homepage_categories']);
            update_option('homepage_posts_limit', $_POST['homepage_posts_limit']);
            update_option('profiles_posts_limit', $_POST['profiles_posts_limit']);
            update_option('votes_categories', $_POST['votes_categories']);


            ?>
            <div id="setting-error-settings_updated" class="notice notice-success settings-error is-dismissible">
                <p><strong>Settings have been saved.</strong></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
            </div>

            <?php
        }

    }

    function popular_plugin_settings_page() {

        $action = @$_GET['action'];

        ?>
    <div class="container-fluid" id="popular-birthdays-admin">

            <h3>Menu</h3>
            <div class="row">
                <div class="col-4"><a href="<?php echo admin_url('admin.php') ?>?page=popular-birthdays&action=general">General settings</a></div>
                <div class="col-4"><a href="<?php echo admin_url('admin.php') ?>?page=popular-birthdays&action=popular-birthdays-source">Popular BD Source</a></div>
                <div class="col-4"><a href="<?php echo admin_url('admin.php') ?>?page=popular-birthdays&action=popular-birthdays-categories">Popular BD Categories</a></div>
            </div>
            <hr style="margin: 10px">

        <?php

        if ($action == 'popular-birthdays-source') :

            include "popular-birthdays-source.php";

        elseif ($action == 'popular-birthdays-categories') :

//            include "popular-birthdays-categories.php";

        else:

            $homepage_categories = get_option('homepage_categories', 'none');
            $homepage_posts_limit = get_option('homepage_posts_limit', 'none');
            $profiles_posts_limit = get_option('profiles_posts_limit', 'none');
            $votes_categories = get_option('votes_categories', 'none');

            ?>
            <div class="wrap">
                <h1 style="font-weight: bold;font-size: 24px">Popular Birthdays settings</h1>

                <form method="post" action="">

                    <table class="form-table" style="width:50%!important">

                        <tr>
                            <th scope="row" style="color:green;font-size: 18px">Categories selection</th>
                        </tr>

                        <tr valign="top" style="border-top: solid 2px green">
                            <th scope="row">To appear on homepage</th>
                            <td>
                                <?php

                                $categories = get_categories(array('hide_empty' => false, 'child_of' => 0));

                                ?>

                                <?php


                                foreach ($categories as $key => $category) {
                                    ?>
                                    <input type="checkbox" id="cat<?php echo $key ?>" name="homepage_categories[]" value="<?php echo $category->category_nicename; ?>" <?php if (@in_array($category->category_nicename, $homepage_categories)): ?>
                                        checked="checked"
                                    <?php endif ?>>
                                    <label for="cat<?php echo $key ?>"><?php echo $category->name; ?></label><br>
                                    <?php


                                }
                                ?>


                            </td>
                        </tr>

                        <tr valign="top">
                            <th scope="row">Limit popular posts</th>
                            <td><input type="number" min="0" max="200" name="homepage_posts_limit" value="<?php echo esc_attr( get_option('homepage_posts_limit') ); ?>" /></td>
                        </tr>

                        <tr valign="top">
                            <th scope="row">Limit profiles posts</th>
                            <td><input type="number" min="0" max="200" name="profiles_posts_limit" value="<?php echo esc_attr( get_option('profiles_posts_limit') ); ?>" /></td>
                        </tr>


                        <tr valign="top">
                            <th scope="row">To show votes section</th>
                            <td>
                                <?php

                                $categories = get_categories(array('hide_empty' => false, 'child_of' => 0));
                                ?>

                                <?php


                                foreach ($categories as $key => $category) {
                                    ?>
                                    <input type="checkbox" id="cat<?php echo $key ?>" name="votes_categories[]" value="<?php echo $category->category_nicename; ?>" <?php if (@in_array($category->category_nicename, $votes_categories)): ?>
                                        checked="checked"
                                    <?php endif ?>>
                                    <label for="cat<?php echo $key ?>"><?php echo $category->name; ?></label><br>
                                    <?php


                                }
                                ?>


                            </td>
                        </tr>


                    </table>

                    <input type="submit" name="update_settings" class="button button-primary" value="UPDATE SETTINGS">

                </form>
            </div>
        <?php

        endif;

?>
    </div>
        <?php

    }

    ?>
