<?php
ob_start();
?>

<!--Content-->
<div class="main-content">
    [stats field='styles-and-scripts']
    <div class="row">
        <div class="col-12">

            <div id="header-section" class="mx-3 mb-2">
                <div class="row bg-light shadow awesome-bg py-3 rounded">
                    <div class="ad col-sm-12 p-1 mb-1">[stats field='slot1']</div>
                    <div class="col-sm-5 col-md-4 col-lg-4">
                        [stats field='image-slider']
                    </div>

                    <div class="col-sm-7 col-md-8 m-auto">
                        <div class="main-info row">
                            <div class="col-md-6 m-auto">
                                <div class="row">
                                    <div class="col-12 mb-2">
                                        <div class="row justify-content-center">
                                            <div class="col-11 border-primary-custom mb-auto">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-block flex-column m-2 p-2">
                                        [stats field='header']
                                    </div>

                                </div>
                            </div>
                            <div class="col-md-6 d-none d-md-block">

                                <div class="stats mx-1">
                                    <div class="row main-stats">
                                        <div class="w-100">
                                            <div class="border shadow-sm mx-2 p-2 h-100 bg-light rounded-lg">
                                                <div class="row mt-1 h-100">
                                                    [stats field='stats']
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="col-md-6 d-md-none">

                        <div class="stats mx-1">
                            <div class="row mobile-stats">
                                <div class="w-100">
                                    <div class="border shadow-sm mx-2 p-2 h-100 bg-light rounded-lg">
                                        <div class="row mt-1 h-100">
                                            [stats field='stats-mobile']
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="ad d-sm-none d-md-none d-lg-none p-1 mb-1">[stats field='slot2']</div>

            </div><!-- #header-section -->

            <div id="content-section" class="mx-2">
                <div class="row rounded-lg">

                    <div class="col-lg-3 d-none d-lg-block d-xl-block mb-1 p-1">
                        <div class="d-flex h-100 justify-content-center">
                            [stats field='popularity']
                        </div>
                    </div>

                    <div class="col-sm-7 col-md-8 col-lg-6 mb-1 p-1">
                        <div class="d-flex h-100 justify-content-center">
                            <div class="col-12 shadow-sm rounded">
                                <?php
                                foreach ($main_info['article_headers'] as $key => $info)
                                {
                                    ?>
                                    <div class="article_content">
                                        <h4><?php echo $info; ?></h4>
                                        <p><?php echo $main_info['article_content'][$key]; ?></p>
                                    </div>
                                    <?php
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-5 col-md-4 col-lg-3 mb-1 p-1">
                        <div class="d-flex h-100 justify-content-md-end justify-content-lg-end justify-content-xl-end">
                            [stats field='sidenav']
                        </div>
                    </div>
                </div>

            </div><!-- #content-section -->

            <hr class="my-2">
            <div id="after-content-section" class="mx-2">
                <div class="row">
                    <div class="col-sm-12 d-sm-block d-md-block d-lg-none d-xl-none p-1 mb-1">
                        <div class="d-flex h-100 justify-content-center">
                            [stats field='popularity-mobile']
                        </div>
                    </div>
                    <div class="ad col-sm-12 p-1 mb-1">
                        <div class="row">
                            <div class="col-12 d-none d-xl-block">[stats field='slot3']</div>
                        </div>
                    </div>

                    [stats field='items-and-also-viewed']

                    [stats field='similarities']

                    <div class="ad col-12 p-1 mb-1">[stats field='slot5']</div>

                </div>
            </div><!-- #after-content-section -->


        </div>
        <!-- /.col-md-8 -->

    </div>
</div>

<?php

$content = ob_get_clean();

