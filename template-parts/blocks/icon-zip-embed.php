<?php

use ZipSearch\ProviderSearchController as ProviderSearchController;

$zip_type = ProviderSearchController::getZipType();

$title = get_field('title');
$image = get_field('image');

?>

<section class="icon-zip-embed-block row-full">
    <div class="zip-search-container">
        <div class="container">
            <div class="flex-container">
                <div class="bg-container">
                    <div class="zipcode inner">
                        <h2><?php echo $title ?></h2>
                        <form action="/zip-search" class="zip_search_form search_wrap" data-form="Search Banner">
                            <div class="input-container">
                                <div class="icon-container">
                                    <span class="material-icons">search</span>
                                </div>
                                <input type="number" class="zip_search_input" id="zip" name="zip" minlength="5" maxlength="5" placeholder="Search by ZIP" pattern="\d*"/>
                                <input type="hidden" id="type" name="type" value="internet">
                                <button type="button" class="submit-zip">Search</button>
                            </div>
                        </form>	
                    </div>
                </div>
                <div class="icons-container">
                    <img src="<?php echo $image['url'] ?>" alt="<?php echo $image['alt'] ?>">
                    <div class="blue-banner"></div>
                </div>
            </div>
        </div>
    </div>
</section>