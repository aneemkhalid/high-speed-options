<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package HSO
 */
use ZipSearch\ProviderSearchController as ProviderSearchController;

$menu = prepare_menu_items( 'Header' );
$type = ProviderSearchController::getZipType();
$header_logo = get_field('header_logo', 'options');
$header_logo = ($header_logo  == '') ? get_template_directory_uri().'/images/Highspeedoptions_header_logo.svg' : $header_logo;
$sticky_logo = get_stylesheet_directory_uri().'/images/Highspeedoptions_lcon.svg';

$migration = get_field('migration_on', 'options');
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;700&family=Roboto:wght@400;500;700&display=swap" rel="stylesheet" media="print" onload="this.onload=null;this.media='all';">
  <!--  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;700&display=swap" rel="stylesheet"> -->
<!--	<link rel="preconnect" href="https://fonts.gstatic.com">
	<link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,300;0,400;0,600;0,700;0,800;1,300;1,400;1,600;1,700;1,800&display=swap" rel="stylesheet">-->
	<!-- <link href="https://fonts.googleapis.com/icon?family=Material+Icons&display=swap" rel="stylesheet"> -->
	<link rel="preload" href="<?php echo get_template_directory_uri() ?>/MaterialIcons-Regular.woff2" as="font" type="font/woff2" crossorigin>
	<link rel="apple-touch-icon" sizes="180x180" href="<?php echo get_template_directory_uri() ?>/images/favicons/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="<?php echo get_template_directory_uri() ?>/images/favicons/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="<?php echo get_template_directory_uri() ?>/images/favicons/favicon-16x16.png">
	<link rel="manifest" href="<?php echo get_template_directory_uri() ?>/images/favicons/site.webmanifest">
	<link rel="mask-icon" href="<?php echo get_template_directory_uri() ?>/images/favicons/safari-pinned-tab.svg" color="#5bbad5">
	<meta name="msapplication-TileColor" content="#da532c">
	<meta name="theme-color" content="#ffffff">
	<?php 
		if(get_the_post_thumbnail_url(get_the_ID())){
			echo '<meta name="twitter:image" content="'.get_the_post_thumbnail_url(get_the_ID()).'">';
		}else{ ?>
			<meta property="og:image" content="<?php echo get_template_directory_uri() ?>/images/Highspeedoptions_lcon.svg">
			<meta name="twitter:image" content="<?php echo get_template_directory_uri() ?>/images/Highspeedoptions_lcon.svg">
			<?php 
		}
	?>
	<?php wp_head(); ?>	
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<?php if($migration): ?>
	<?php get_template_part('/template-parts/migration-banner'); ?>
<?php endif; ?>
<div class="overlay">
	<?php echo create_primary_nav_html('Header', true); ?>
</div>
<header class="main-nav-header">
	<div class="header-container">
		<div class="header-logo-container hide-on-tablet-search">
			<a href="<?php echo get_home_url(); ?>" class="custom-logo-link" rel="home">
				<noscript>
					<img src="<?php echo $header_logo; ?>" class="custom-logo" alt="highspeedoptions logo" width="221" height="25">
				</noscript>
				<img src="<?php echo $header_logo; ?>" data-src="<?php echo $header_logo; ?>" class="custom-logo lazyloaded" alt="highspeedoptions logo" width="221" height="25">
				<img src="<?php echo $sticky_logo; ?>" data-src="<?php echo $sticky_logo; ?>"" class="sticky-logo lazyloaded" alt="highspeedoptions logo" width="62" height="34">
			</a>
		</div>
		<div class="primary-menu-container hide-on-tablet-search">
			<?php echo create_primary_nav_html('Header'); ?>
		</div>
		
		<div class="header-container-mobile">
			<div class="header-zip-search-container">
				<form id="header-zip-search-form" class="show-on-tablet-search zip_search_form" action="/zip-search" data-form="Search Header">
					<input type="number" id="header-zip-search-input" class="header-zip-search-input zip_search_input" name="zip" minlength="5" maxlength="5" placeholder="Find Services in your ZIP Code" pattern="\d*"/>
					<input type="hidden" id="type" name="type" value="<?php echo $type; ?>">
					<button type="button" id="header-zip-search">
						<span class="material-icons submit-zip">
							search
						</span>
					</button>
				</form>
				<button id="cancel-header-zip-search" class="show-on-tablet-search">
					Cancel
				</button>
				<button type="button" id="open-header-zip-search" class="hide-on-tablet-search">
					<span class="material-icons">
						search
					</span>
				</button>
			</div>

			<div class="hamburger-menu-container hide-on-tablet-search">
				<button type="button" id="toggle-hamburger" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
					<div id="navbar-hamburger">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</div>
					<div id="navbar-close">
						<span class="material-icons">
							clear
						</span>
					</div>
				</button>
			</div>
		</div>
	</div>
</header>
