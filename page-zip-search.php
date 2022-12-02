<?php
/**
 * The template for the zip search page
 * @package HSO
 */

get_header();

use ZipSearch\ProviderSearchController as ProviderSearchController;
use ZipSearch\PostgreSQLConnection as PostgreSQLConnection;
use ZipSearch\BDAPIConnection as BDAPIConnection;

require get_theme_file_path( '/template-parts/zip-search-popup.php' );

//get params for display
(isset($_GET['zip'])) ? $zipcode = $_GET['zip'] : $zipcode = false;
(isset($_GET['type']) && $_GET['type']) ? $type = $_GET['type'] : $type = 'internet';
(isset($_GET['city']) && $_GET['city']) ? $city = $_GET['city'] : $city = '';
(isset($_GET['state']) && $_GET['state']) ? $state = $_GET['state'] : $state = '';

$zip_settings = get_field('zip_search', 'options');
$zip_tv = $zip_settings['show_tv'];
$zip_bundle = $zip_settings['show_bundles'];

$tab_hide = false;
if(empty($zip_tv) && empty($zip_bundle)) {
	$tab_hide = true;
}

if ($type == 'tv'){
	$type_mod = strtoupper($type);
} else {
	$type_mod = $type;
}

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
$zip_search_loader_progress_text = get_field('zip_search_loader_progress_text');
(isset($zip_search_loader_progress_text['h3_text']) && $zip_search_loader_progress_text['h3_text'] != 
'') ? $h3_text = $zip_search_loader_progress_text['h3_text'] : $h3_text = 'Finding the Best Deals for You.';
(isset($zip_search_loader_progress_text['h4_text']) && $zip_search_loader_progress_text['h4_text'] != '') ? $h4_text = $zip_search_loader_progress_text['h4_text'] : $h4_text = 'This should only take a sec.';
?>
	<main class="site-main zip_search">

		<section class="banner">
			<div class="container">
				<?php get_template_part( 'template-parts/breadcrumbs', null, array( 'has_banner' => true, 'is_location' => false )); ?>
				<div class="">
					<h1 class="text-left">Internet Providers in <?php echo $zipcode; ?></h1>
					<div class="d-flex justify-content-start mt-3">
						<a href="#" class="cta_btn zip-popup-btn btn-outline mt-2" target="_blank" data-toggle="modal" data-target="#zipPopupModal-<?php echo $rand; ?>">Change Zip Code</a>
					</div>
					<div class="input_wrap zip_search_form_wrapper" style="display:none;">
						<form action="" class="zip_search_form" data-form="Search Zip Results">
							<input type="number" class="zip_search_input" id="zip" name="zip" minlength="5" maxlength="5" placeholder="Search by ZIP">
							<input type="hidden" id="type" name="type" value="<?php echo $type; ?>">
							<span class="material-icons submit-zip">
								search
							</span>
						</form>
					</div>
				</div>
			</div>
		</section>

		<section class="zip_search_nav grey-background">
			<div class="container p-0">
				<div class="row no-gutters">
					<?php if(!$tab_hide) : ?>
						<div class="col-md-6">
							<ul class="nav nav-tabs" id="typeTab" role="tablist">
								<li class="nav-item">
									<a class="nav-link ripple-el <?php echo $internet_active; ?>" id="internet-search-tab" data-toggle="tab" href="#internet-search" role="tab" aria-controls="internet-search" aria-selected="true">Internet</a>
								</li>
								<?php if($zip_tv) : ?>
									<li class="nav-item">
										<a class="nav-link ripple-el <?php echo $tv_active; ?>" id="tv-search-tab" data-toggle="tab" href="#tv-search" role="tab" aria-controls="tv-search" aria-selected="false">TV</a>
									</li>
								<?php endif; ?>
								<?php if($zip_bundle) : ?>
									<li class="nav-item">
										<a class="nav-link ripple-el <?php echo $bundle_active; ?>" id="bundle-search-tab" data-toggle="tab" href="#bundle-search" role="tab" aria-controls="bundle-search" aria-selected="false">Bundle</a>
									</li>
								<?php endif; ?>
							</ul>
						</div>
					<?php endif; ?>
					<div class="sort-by-wrapper col-md-6 d-flex justify-content-md-end justify-content-center align-items-center pt-4 pb-0 pt-md-0 <?php if($tab_hide){ echo 'tab-hidden'; } ?>"">
						<div class="dropdown sort-by-dropdown">
							<div id="dropdownMenuButton" class="dropdown-btn d-flex justify-content-between" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<span class="placeholder-text">Sort By</span>
								<i aria-hidden="true" class="notranslate material-icons">arrow_drop_down</i>
							</div>
							<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
								<button data-dataGroup="data-download" class="dropdown-item <?php echo $internet_show ?>" data-sortOrder="highest" data-dropdownType="internet-search">Fastest Download Speeds</button>
								<button data-dataGroup="data-upload" class="dropdown-item <?php echo $internet_show ?>" data-sortOrder="highest" data-dropdownType="internet-search">Fastest Upload Speeds</button>
								<button data-dataGroup="data-cost" class="dropdown-item <?php echo $internet_show ?>" data-sortOrder="lowest" data-dropdownType="internet-search">Lowest Price</button>
								<button data-dataGroup="data-channel" class="dropdown-item <?php echo $tv_show ?>" data-sortOrder="highest" data-dropdownType="tv-search">Most Channels</button>
								<button data-dataGroup="data-cost" class="dropdown-item <?php echo $tv_show ?>" data-sortOrder="lowest" data-dropdownType="tv-search">Lowest Price</button>
								<button data-dataGroup="data-download" class="dropdown-item <?php echo $bundle_show ?>" data-sortOrder="highest" data-dropdownType="bundle-search">Fastest Download Speeds</button>
								<button data-dataGroup="data-channel" class="dropdown-item <?php echo $bundle_show ?>" data-sortOrder="highest" data-dropdownType="bundle-search">Most Channels</button>
								<button data-dataGroup="data-cost" class="dropdown-item <?php echo $bundle_show ?>" data-sortOrder="lowest" data-dropdownType="bundle-search">Lowest Price</button>
						</div>
					</div>
				<div>
			</div>
		</section>
		<section class="zip_search_overview common-style mt-md-5 mt-4" id="accordion">
			<div class="zip-search-loader-container d-flex flex-column justify-content-center align-items-center">
				<img class="zip-search-load-gif" src="<?php echo get_template_directory_uri() ?>/images/726-wireless-connection-outline.gif" alt="loading animation" height="100" width="100" />
				<h3 class="mt-3"><?php echo $h3_text; ?></h3>
				<h4 class="mt-3"><?php echo $h4_text; ?></h4>
			</div>
		</section>
	</main><!-- #main -->

<?php
get_footer();
