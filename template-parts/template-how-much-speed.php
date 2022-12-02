<?php
/**
 * The template for displaying the how much speed tool
 * Template Name: Template - How Much Speed Tool
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package HSO
 */

get_header();

$hero_sub_title = get_field('hero_sub_title');
$hero_title = get_field('hero_title');
$hero_body_text = get_field('hero_body_text');
$hero_cta_text = get_field('hero_cta_text');
$hero_image_id = get_field('hero_image');

$question_1_text = get_field('question_1_text');
$question_2_text = get_field('question_2_text');
$question_3_text = get_field('question_3_text');
$question_4_text = get_field('question_4_text');
$question_5_text = get_field('question_5_text');

$complete_title = get_field('form_complete_title');
$complete_subtitle = get_field('form_complete_sub_title');

// zip search
use ZipSearch\ProviderSearchController as ProviderSearchController;
$type = ProviderSearchController::getZipType();
?>


<section id="howmuchspeed_page_template">
    <div id="progress-bar-wrap"><div id="progress-bar-fill"></div></div>
    <div class="container">
        <div class="row row-breadcrumb">
            <div class="col col-12">
                <?php include get_template_directory() . '/template-parts/breadcrumbs.php'; ?>
            </div>
        </div>
        <div class="row row-hero" id="hmsdin-hero">
            <div class="col col-12 col-md-5 col-lg-5 offset-md-2 offset-lg-3 order-md-2">
                <div class="hero-image-cont">
                    <?php echo wp_get_attachment_image($hero_image_id, 'full'); ?>
                </div>
            </div>
            <div class="col col-12 col-md-5 col-lg-4 order-md-1">
                <div class="d-flex page-header-container">
                    <div class="page-header-left">
                        <h5 class="sub-title">Estimated duration: 1 - 2 minutes</h5>
                        <h1>What internet speed do I need?</h1>
                        <p class="content">Estimate the best speed for your needs based on your usage, devices, and lifestyle to find the perfect plan for you.</p>
                        <button id="hmsdin-start" class="cta_btn">Get Started</button>
                    </div>
                </div> 
            </div>
            
            
        </div>

        <div class="row row-form-cont" id="row-form-cont">
            <!-- question 1 cont -->
            <div class="col col-12 col-form-cont" >
                <div class="speed-counter-cont">
                    <p class="recommended">Recommended Speed</p>
                    <p class="speed"><span id="curr-speed">0</span> Mbps</p>
                </div>
                <p class="progress-counter"><span id="curr-step">1</span> / 5</p>
                
                <div class="hmsdin-form-cont">
                    <form action="" class="hmsdin-form" id="hmsdin-form">

                        <!-- question 1 -->
                        <div class="question-cont" id="question-1-cont">
                            <p class="question"><?= $question_1_text ?></p>
                            <?php 
                            if( have_rows('question_1_answers') ):
                                while( have_rows('question_1_answers') ): the_row();
                                    $answer = get_sub_field('answer_text');
                                    $value = get_sub_field('answer_value');?>
                            
                                    <label class="hmsdin-label label-lg label-radio" for="<?= $value ?>">
                                        <input type="radio" id="<?= $value ?>" name="daily_use" value="<?= $value ?>">
                                        <span class="checkmark"></span>
                                        <span class="label-txt"><?= $answer ?></span>
                                    </label>
                                <?php endwhile; ?>
                            <?php endif; ?>
                        </div>
                        
                        <!-- question 2 -->
                        <div class="question-cont" id="question-2-cont">
                            <p class="question"><?= $question_2_text ?></p>
                            <?php 
                            if( have_rows('question_2_answers') ):
                                $i = 1;
                                $trig = false;
                                $row_count = count(get_field('question_2_answers'));
                                echo '<div class="row">';
                                echo '<div class="col col-12 col-lg-6">';
                                while( have_rows('question_2_answers') ): the_row();
                                    $answer = get_sub_field('answer_text');
                                    $value = get_sub_field('answer_value');
                                    $icon = get_sub_field('answer_icon');?>
                                    <?php if( $i > ($row_count / 2) && $trig == false): ?>
                                        </div>
                                        <div class="col col-12 col-lg-6">
                                    <?php $trig = true; endif; ?>
                                    <label class="hmsdin-label label-lg label-checkbox" for="<?= $value . '-' . $i; ?>">
                                        <input type="checkbox" id="<?= $value . '-' . $i; ?>" name="daily_use" value="<?= $value ?>">
                                        <span class="checkmark"><span class="material-icons">check</span></span>
                                        <span class="label-txt"><?= $answer ?></span>
                                        <span class="material-icons"><?= $icon ?></span>
                                    </label>
                                    <?php $i++; ?>
                                <?php endwhile; ?>
                                </div>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- question 3 -->
                        <div class="question-cont" id="question-3-cont">
                            <p class="question"><?= $question_3_text ?></p>
                            <?php 
                            if( have_rows('question_3_answers') ):
                                while( have_rows('question_3_answers') ): the_row();
                                    $answer = get_sub_field('answer_text');
                                    $value = get_sub_field('answer_value');
                                    $rand_int = rand(0, 999999);?>
                            
                                    <label class="hmsdin-label label-lg label-radio" for="<?= $value . '-' . $rand_int ?>">
                                        <input type="radio" id="<?= $value . '-' . $rand_int ?>" name="daily_use" value="<?= $value ?>">
                                        <span class="checkmark"></span>
                                        <span class="label-txt"><?= $answer ?></span>
                                    </label>
                                <?php endwhile; ?>
                            <?php endif; ?>
                        </div>

                        <!-- question 4 -->
                        <div class="question-cont" id="question-4-cont">
                            <p class="question"><?= $question_4_text ?></p>
                            <?php 
                            if( have_rows('question_4_answers') ):
                                while( have_rows('question_4_answers') ): the_row();
                                    $answer = get_sub_field('answer_text');
                                    $value = get_sub_field('answer_value');
                                    $rand_int = rand(0, 999999);?>
                            
                                    <label class="hmsdin-label label-lg label-radio" for="<?= $value . '-' . $rand_int ?>">
                                        <input type="radio" id="<?= $value . '-' . $rand_int ?>" name="daily_use" value="<?= $value ?>">
                                        <span class="checkmark"></span>
                                        <span class="label-txt"><?= $answer ?></span>
                                    </label>
                                <?php endwhile; ?>
                            <?php endif; ?>
                        </div>

                        <!-- question 5 -->
                        <div class="question-cont" id="question-5-cont">
                            <p class="question"><?= $question_5_text ?></p>
                            <?php 
                            if( have_rows('question_5_answers') ):
                                while( have_rows('question_5_answers') ): the_row();
                                    $answer = get_sub_field('answer_text');
                                    $value = get_sub_field('answer_value');
                                    $rand_int = rand(0, 999999);?>
                            
                                    <label class="hmsdin-label label-lg label-radio" for="<?= $value . '-' . $rand_int ?>">
                                        <input type="radio" id="<?= $value . '-' . $rand_int ?>" name="daily_use" value="<?= $value ?>">
                                        <span class="checkmark"></span>
                                        <span class="label-txt"><?= $answer ?></span>
                                    </label>
                                <?php endwhile; ?>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>


                <!-- button container -->
                <div id="hmsdin-button-cont">
                    <div class="hmsdin-error-cont"><h4 id="error-notice">Please select an option to continue</h4></div>
                    <button class="cta_btn" id="hmsdin-back" data-step-1="0" data-step-2="0" data-step-3="0" data-step-4="0" data-step-5="0">Back</button>
                    <button class="cta_btn" id="hmsdin-next">Next</button>
                    <button class="cta_btn" id="complete_HMSDIN">Complete</button>
                </div>

            </div>
        </div>

        
    </div>
</section>


<section class="blue-zip-block" id="section-form-complete">
    <div class="container">
        <div class="inner row">
            <div class="col col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-0 col-rec-speed">
                <div class="card-left">
                    <h4>Recommended download speed</h4>
                    <h2><span id="recommended-speed"></span> Mbps+</h2>
                    <button class="cta_btn" id="hmsdin-restart">Restart</button>
                </div>
            </div>

            <div class="col col-12 col-md-6 zip-container">
                <div class="d-flex flex-column align-items-stretch">
                    <h4>Find services in your area</h4>
                    <form action="/zip-search" class="zip_search_form search_wrap justify-content-center" data-form="Blue Zip - Search Inline">
                        <div class="icon-container">
                            <span class="material-icons">search</span>
                        </div>
                        <input type="number" class="zip_search_input" id="zip" name="zip" minlength="5" maxlength="5" placeholder="Search by ZIP" pattern="\d*"/>
                        <input type="hidden" id="type" name="type" value="internet">
                        <button type="button" class="submit-zip">Search</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="container">
            <div class="row" id="row-recommendation">
                <div class="col col-12 col-title">
                    <h2><?= $complete_title ?></h2>
                    <h4><?= $complete_subtitle ?></h4>
                </div>
                <?php 
                if( have_rows('speed_recommendations') ):
                    $i = 0;
                    while( have_rows('speed_recommendations') ): the_row();
                        $speed = get_sub_field('speed');
                        $rec = get_sub_field('recommendation');
                        $col_offset = '';
                        if($i % 2 == 0){
                            $col_offset = 'offset-md-2';
                        }
                        ?>
                            <div class="col col-10 offset-1 col-md-4 <?= $col_offset ?> offset-lg-0 col-lg-3 col-rec">
                                <h4><?= $speed ?></h4>
                                <p><?= $rec ?></p>
                            </div>
                        <?php $i++; endwhile; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
get_footer();