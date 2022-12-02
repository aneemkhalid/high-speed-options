<?php

use ZipSearch\ProviderSearchController as ProviderSearchController;
$type = ProviderSearchController::getZipType();

$zip = get_field('basic_zip_embed');

?>

<section class="basic-zip-embed-block row-full">
    <div class="container">
        <div class="zip-container">
            <h2><?php echo $zip['title'] ?></h2>
            <div class="zipcode inner">
                <form action="/zip-search" class="zip_search_form search_wrap" data-form="Search Banner">
                    <div class="input-container">
                        <div class="icon-container">
                            <span class="material-icons">search</span>
                        </div>
                        <input type="number" class="zip_search_input" id="zip" name="zip" minlength="5" maxlength="5" placeholder="Search by ZIP" pattern="\d*"/>
                        <button type="button" class="submit-zip">Search</button>
                    </div>
                    <div class="check-list">
                            <?php $type_lower = strtolower( $type );
                        ?>
                            <input type="radio" id="<?php echo $type_lower; ?>-radio" name="type" value="<?php echo $type_lower; ?>" <?php if($zip_type === $type_lower) echo 'checked'; ?> >
                            <label for="<?php echo $type_lower; ?>-radio"><?php echo $type; ?></label>
                    </div>
                </form>	
            </div>
        </div>
    </div>
</section>