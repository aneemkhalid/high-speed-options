<?php

/**
 * Zip Search Popup Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */
if (!isset($internet_checked)){
    $internet_checked  = '';
}
if (!isset($tv_checked)){
    $tv_checked  = '';
}
if (!isset($bundle_checked)){
    $bundle_checked  = '';
}
if (!$internet_checked && !$tv_checked && !$bundle_checked){
    $internet_checked = 'checked';
}

$zip_settings = get_field('zip_search', 'options');
$zip_tv = $zip_settings['show_tv'];
$zip_bundle = $zip_settings['show_bundles'];

$tab_hide = false;
if(empty($zip_tv) && empty($zip_bundle)) {
	$tab_hide = true;
}
?>  


<!-- Modal -->
<div class="modal fade zip-popup-modal" id="zipPopupModal-<?php echo $rand; ?>" tabindex="-1" role="dialog" aria-labelledby="zipPopupModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="zipcode inner">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span class="material-icons">close</span>
                </button>
                <h3>Search internet, cable & bundle providers in your area</h3>
                <form action="/zip-search" class="zip_search_form search_wrap" data-form="Search Pop Up">
                    <input type="number" class="zip_search_input modal-zip-search-input" id="zip-<?php echo $rand; ?>" name="zip" minlength="5" maxlength="5" placeholder="Search by ZIP" pattern="\d*"/>
                    <button type="button" class="submit-zip">Search</button>
                    <?php if(!$tab_hide) : ?>
                        <div class="check-list">
                            <div class="check-list">
                                <input type="radio" id="internet-radio-<?php echo $rand; ?>" class="check-internet product-type-check" name="type" value="internet" <?php echo $internet_checked; ?> >
                                <label for="internet-radio-<?php echo $rand; ?>">Internet</label>
                                <input type="radio" id="tv-radio-<?php echo $rand; ?>" class="check-tv product-type-check" name="type" value="tv" <?php echo $tv_checked; ?>>
                                <label for="tv-radio-<?php echo $rand; ?>">TV</label>
                                <input type="radio" id="bundle-radio-<?php echo $rand; ?>" class="check-bundle product-type-check" name="type" value="bundle" <?php echo $bundle_checked; ?>>
                                <label for="bundle-radio-<?php echo $rand; ?>">Bundle</label>
                            </div>
                        </div>
                    <?php endif; ?>
                </form> 
            </div>
        </div>
    </div>
</div>
