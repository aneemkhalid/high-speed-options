<?php

use ZipSearch\ProviderSearchController as ProviderSearchController;

$zip_type = ProviderSearchController::getZipType();
$types= array('Internet', 'TV', 'Bundles');
$title = get_field('title');
$subtitle = get_field('subtitle');
$featured = get_field('featured');
$image = get_field('image');

?>

<section class="home-hero-block">
    <div class="zip-search-container">
        <div class="container">
            <div class="bg-container">
                <div class="md-container">
                    <h1><?php echo $title ?></h1>
                </div>
                <div class="zipcode inner">
                    <h1><?php echo $title ?></h1>
                    <h3><?php echo $subtitle ?></h3>
                    <form action="/zip-search" class="zip_search_form search_wrap" data-form="Search Banner">
                        <div class="input-container">
                            <div class="icon-container">
                                <span class="material-icons">search</span>
                            </div>
                            <input type="number" class="zip_search_input" id="zip" name="zip" minlength="5" maxlength="5" placeholder="Search by ZIP" pattern="\d*"/>
                            <button type="button" class="submit-zip">Search</button>
                        </div>
                        <div class="check-list">
                            <?php foreach($types as $type):
                                $type_lower = strtolower( $type );
                            ?>
                                <input type="radio" id="<?php echo $type_lower; ?>-radio" name="type" value="<?php echo $type_lower; ?>" <?php if($zip_type === $type_lower) echo 'checked'; ?> >
                                <label for="<?php echo $type_lower; ?>-radio"><?php echo $type; ?></label>
                            <?php endforeach; ?>
                        </div>
                    </form>	
                </div>
                <?php echo wp_get_attachment_image( $image, 'full', null, array("class" => 'hero-image')) ?>
            </div>
            <img src="<?php echo get_template_directory_uri() . '/images/dots.svg' ?>" alt="dots" class="dots dots-left" width="487" height="227">
            <img src="<?php echo get_template_directory_uri() . '/images/dots.svg' ?>" alt="dots" class="dots dots-right" width="487" height="227">
        </div>
    </div>

    <div class="featured-container">
        <div class="container">
            <div class="item-container">
                <?php foreach($featured as $item) : ?>
                    <div class="featured-item">
                        <img src="<?php echo $item['icon']['url'] ?>" alt="<?php echo $item['icon']['alt'] ?>" width="120" height="80">
                        <h3><?php echo $item['title'] ?></h3>
                        <div><?php echo $item['content'] ?></div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

</section>

