<?php

/**
 * Blue Zip Block.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

use ZipSearch\ProviderSearchController as ProviderSearchController;
$type = ProviderSearchController::getZipType();

$heading = get_field('header');
$body = get_field('body', false, false);
$fhead = get_field('form_heading');
$select_background_color = get_field('select_background_color');

if($select_background_color == 'blue') {
    $color = '#f3faff';
} else {
    $color = 'transparent';
} 

global $post;
$picksPageSlug = $post->post_name;
$picksPageSlug = ucwords(str_replace("-", " ", $picksPageSlug));
if (get_field('enable_zip_search')):
?>
<section class="blue-zip-block" style="background-color: <?php echo $color ?>;">
    <div class="container">
        <div class="inner">
            <div class="content">
                <h2><?php echo $heading ?></h2>
                <p><?php echo $body ?></p>
            </div>
            <div class="zip-container">
                <div class="d-flex flex-column align-items-stretch">
                    <h2><?php echo $fhead ?></h2>
                    <form action="/zip-search" class="zip_search_form search_wrap justify-content-center" data-form="Blue Zip - Search Inline">
                        <div class="icon-container">
                            <span class="material-icons">search</span>
                        </div>
                        <input type="number" class="zip_search_input" id="zip" name="zip" minlength="5" maxlength="5" placeholder="Search by ZIP" pattern="\d*"/>
                        <input type="hidden" id="type" name="type" value="<?php echo $picksPageSlug; ?>">
                        <button type="button" class="submit-zip">Search</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<?php else: ?>
<div class="blue-container p-4 p-md-5">
    <div class="pl-md-5 pr-md-5">
        <h4><?php echo $heading; ?></h4>
        <div class="body-container">
            <p class="mb-0"><?php echo $body; ?></p>
        </div>
    </div>    
</div>
<?php endif; ?>