<?php

/**
 * Page Banner Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

use ZipSearch\ProviderSearchController as ProviderSearchController;

$zip_type = ProviderSearchController::getZipType();
$types= array('Internet', 'TV', 'Bundle');
$title = get_the_title();

?>

<section id="zipsearch" class="banner vertical_page_banner">
    <div class="container">
        <?php get_template_part( 'template-parts/breadcrumbs', null, array( 'has_banner' => true ) ); ?>
        <div class="zipcode inner">
            <h3>Find Internet, Cable TV & Bundle Providers in your Area</h3>
            <form action="/zip-search" class="zip_search_form search_wrap" data-form="Search Banner">
                <input type="number" class="zip_search_input" id="zip" name="zip" minlength="5" maxlength="5" placeholder="Search by ZIP" pattern="\d*"/>
                <button type="button" class="submit-zip">Search</button>
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
    </div>
</section>
