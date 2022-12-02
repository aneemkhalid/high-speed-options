<?php
use ZipSearch\ProviderSearchController as ProviderSearchController;

$header = get_field('header');
$provider_id = get_field('provider');

$house_img = get_template_directory_uri() . '/images/zip-house.svg';
$search_img = get_template_directory_uri() . '/images/zip-search.svg';
$question_img = get_template_directory_uri() . '/images/zip-question.svg';

(isset($_GET['type']) && $_GET['type']) ? $type = $_GET['type'] : $type = 'internet';
$internet_active='';$tv_active='';$bundle_active='';$internet_show='';$tv_show='';$bundle_show='';
if ($type == 'internet'){
	$internet_active = 'active dataLayer-sent';
	$internet_show = 'show';
} elseif($type == 'tv'){
	$tv_active = 'active dataLayer-sent';
	$tv_show = 'show';
} elseif ($type == 'bundle'){
	$bundle_active = 'active dataLayer-sent';
	$bundle_show = 'show';
}

$zip_settings = get_field('zip_search', 'options');
$zip_tv = $zip_settings['show_tv'];
$zip_bundle = $zip_settings['show_bundles'];


?>

<section class="zip-qualifier-block row-full">
    <div class="top-content">
        <div class="">
            <h3><?php echo $header ?></h2>
            <div class="zip-outer">
                <div class="zipcode inner">
                    <form action="" class="zip_search_qualifier search_wrap" data-form="Search Banner" data-provider="<?php echo $provider_id; ?>">
                        <div class="input-container">
                            <div class="icon-container">
                                <span class="material-icons">search</span>
                            </div>
                            <input type="number" class="zip_search_input" id="zip" name="zip" minlength="5" maxlength="5" placeholder="Enter zip code" pattern="\d*"/>
                            <input type="hidden" id="type" name="type" value="internet">
                            <button type="submit" class="submit-zip">Search</button>
                        </div>
                    </form>	
                </div>
            </div>

        </div>
    </div>

    <div class="blue-container">
        <div class="container">
            <div class="unavailable">
                <h4 class="prov">This provider is unavailable in</h4>
                <div class="city-info"></div>
            </div>
            <div class="top-container">
                <div class="icon-container">
                    <img src="<?php echo $search_img ?>" alt="search icon" height="41" width="41" class="search-icon">
                    <img src="<?php echo $question_img ?>" alt="question icon" height="41" width="41" class="question-icon">
                </div>
                <div class="zip-search-loader-container d-flex flex-column justify-content-center align-items-center">
                    <img class="zip-search-load-gif" src="<?php echo get_template_directory_uri() ?>/images/wireless-outline-no-bg.gif" alt="loading" style="display:none;" height="100" width="100" />
                </div>
                <img src="<?php echo $house_img ?>" width="425" height="150" alt="zip search house" class="house">
                <div class="blue-zip-text">Enter your zip code above</div>

                <div class="more-providers">
                    <h4>Find providers available to you</h4>
                    <form action="/zip-search" class="zip_search_form">
                        <input type="hidden" class="zip_search_input" id="zip" name="zip" minlength="5" maxlength="5" placeholder="Search by ZIP" pattern="\d*" value=""/>
                        <input type="hidden" id="type" name="type" value="<?php echo $type; ?>">
                        <button type="submit" class="submit-zip cta_btn">More Providers</button>
                    </form>
                </div>
            </div>

            <div class="zip-results">
                <div>
                    <ul class="nav nav-tabs top-nav" id="typeTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link <?php echo $internet_active; ?>" id="internet-search-tab" data-toggle="tab" href="#internet-search" role="tab" aria-controls="internet-search" aria-selected="true">Internet</a>
                        </li>
                        <?php if($zip_tv) : ?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo $tv_active; ?>" id="tv-search-tab" data-toggle="tab" href="#tv-search" role="tab" aria-controls="tv-search" aria-selected="false">TV</a>
                        </li>
                        <?php endif; ?>
                        <?php if($zip_bundle) : ?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo $bundle_active; ?>" id="bundle-search-tab" data-toggle="tab" href="#bundle-search" role="tab" aria-controls="bundle-search" aria-selected="false">Bundle</a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </div>

                <div class="zip_search_overview_qualifier common-style mt-md-4 mt-4" id="accordion"></div>
            </div>
        </div>
    </div>  
</section>