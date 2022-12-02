<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package HSO
 */
use ZipSearch\ProviderSearchController as ProviderSearchController;
$type = ProviderSearchController::getZipType();
$footer_logo = get_field('footer_logo', 'options');
$footer_logo = ($footer_logo == '') ? get_template_directory_uri().'/images/Highspeedoptions_footer_logo.svg' : $footer_logo;
?>

<a class="back-to-top" href="<?php echo esc_url('#0'); ?>">
	<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
				viewBox="0 0 402.9 945" enable-background="new 0 0 402.9 945" xml:space="preserve" width="8" height="18.75">
		<path fill="#fff" d="M399.1,249c-3.1,6.6-8.1,9.8-15,9.8H268.2v645.7c0,4.8-1.6,8.8-4.7,11.9c-3.1,3.1-7.1,4.7-11.9,4.7h-99.3
			c-4.8,0-8.8-1.6-11.9-4.7c-3.1-3.1-4.7-7.1-4.7-11.9V258.8H19.8c-7.2,0-12.2-3.3-15-9.8c-2.8-6.6-1.9-12.6,2.6-18.1L188.5,32.2
			c3.4-3.4,7.4-5.2,11.9-5.2c4.8,0,9,1.7,12.4,5.2l183.7,198.7C401,236.4,401.8,242.4,399.1,249z"/>
	</svg>
</a>

<footer>
	<div class="container">
		<div class="row">
			<div class="col-lg-4 col-sm-12">
				<div class="footer-logo">
					<a href="<?php echo site_url(); ?>">
						<img src="<?php echo $footer_logo; ?>" alt="hso logo" width="283" height="31">
					</a>
				</div>	
				<div class="left">
				<p>All trademarks remain the property of their respective owners, and are used by HighSpeedOptions only to describe products and services offered by each respective trademark holder. HighSpeedOptions provides information for comparison purposes and does not offer internet, television, bundling, or streaming services directly, or endorse any service over others. HighSpeedOptions is supported by compensation from our internet, TV, and streaming partners.</p>
				<p>© Copyright <?php echo date('Y'); ?> HighSpeedOptions</p>
				<?php 
						wp_nav_menu( $args = array(
							'menu'              => "privacy", // (int|string|WP_Term) Desired menu. Accepts a menu ID, slug, name, or object.
							'menu_class'        => "privacy-menu", // (string) CSS class to use for the ul element which forms the menu. Default 'menu'.
							'menu_id'           => "privacy-menu", // (string) The ID that is applied to the ul element which forms the menu. Default is the menu slug, incremented.
							'container'         => "ul", // (string) Whether to wrap the ul, and what to wrap it with. Default 'div'.
							'theme_location'    => "privacy", // (string) Theme location to be used. Must be registered with register_nav_menu() in order to be selectable by the user.
						) );
					?>	
				</div>
			</div>
			<div class="col-lg-2 col-md-3 col-sm-6">
				<h6>Services</h6>
				<?php 
					wp_nav_menu( $args = array(
						'menu'              => "services", // (int|string|WP_Term) Desired menu. Accepts a menu ID, slug, name, or object.
						'menu_class'        => "listing", // (string) CSS class to use for the ul element which forms the menu. Default 'menu'.
						'menu_id'           => "services-menu", // (string) The ID that is applied to the ul element which forms the menu. Default is the menu slug, incremented.
						'container'         => "ul", // (string) Whether to wrap the ul, and what to wrap it with. Default 'div'.
						'theme_location'    => "services", // (string) Theme location to be used. Must be registered with register_nav_menu() in order to be selectable by the user.
					) );
				?>
				<div class="input_wrap">
					<form class="zip_search_form" action="/zip-search" data-form="Search Footer">
						<input type="number" class="zip_search_input" name="zip" minlength="5" maxlength="5" placeholder="Search by ZIP" pattern="\d*"/>
						<input type="hidden" id="type" name="type" value="<?php echo $type; ?>">
						<button type="button" id="footerZipSearch">
							<span class="material-icons submit-zip">
								search
							</span>
						</button>
					</form>
				</div>
			</div>
			<div class="col-lg-2 col-md-3 col-sm-6">
				<h6>Internet</h6>
				<?php 
					wp_nav_menu( $args = array(
						'menu'              => "internet", // (int|string|WP_Term) Desired menu. Accepts a menu ID, slug, name, or object.
						'menu_class'        => "listing", // (string) CSS class to use for the ul element which forms the menu. Default 'menu'.
						'menu_id'           => "internet-menu", // (string) The ID that is applied to the ul element which forms the menu. Default is the menu slug, incremented.
						'container'         => "ul", // (string) Whether to wrap the ul, and what to wrap it with. Default 'div'.
						'theme_location'    => "internet", // (string) Theme location to be used. Must be registered with register_nav_menu() in order to be selectable by the user.
					) );
				?>
			</div>
			<div class="col-lg-2 col-md-3 col-sm-6">
				<h6>Providers</h6>
				<?php 
					wp_nav_menu( $args = array(
						'menu'              => "providers", // (int|string|WP_Term) Desired menu. Accepts a menu ID, slug, name, or object.
						'menu_class'        => "listing", // (string) CSS class to use for the ul element which forms the menu. Default 'menu'.
						'menu_id'           => "providers-menu", // (string) The ID that is applied to the ul element which forms the menu. Default is the menu slug, incremented.
						'container'         => "ul", // (string) Whether to wrap the ul, and what to wrap it with. Default 'div'.
						'theme_location'    => "providers", // (string) Theme location to be used. Must be registered with register_nav_menu() in order to be selectable by the user.
					) );
				?>
			</div>
			<div class="col-lg-2 col-md-3 col-sm-6">
				<div class="follow">
					<h6>Follow Us</h6>
					<div class="social-icons">
						<a href="https://www.facebook.com/highspeedoptions">
							<img src="<?php echo get_template_directory_uri() ?>/images/fb.svg" alt="facebook logo" height="24" width="24">
						</a>
						<a href="https://twitter.com/HighSpeedOption">
							<img src="<?php echo get_template_directory_uri() ?>/images/twitter.svg" alt="twitter logo" height="24" width="24">
						</a>
						<a href="https://www.linkedin.com/company/high-speed-options/">
							<img src="<?php echo get_template_directory_uri() ?>/images/linkedin.svg" alt="linkedin logo" height="24" width="24">
						</a>
					</div>
				</div>
				<div class="resources">
					<h6>Resources</h6>
					<?php 
						wp_nav_menu( $args = array(
							'menu'              => "resources", // (int|string|WP_Term) Desired menu. Accepts a menu ID, slug, name, or object.
							'menu_class'        => "listing", // (string) CSS class to use for the ul element which forms the menu. Default 'menu'.
							'menu_id'           => "resources-menu", // (string) The ID that is applied to the ul element which forms the menu. Default is the menu slug, incremented.
							'container'         => "ul", // (string) Whether to wrap the ul, and what to wrap it with. Default 'div'.
							'theme_location'    => "resources", // (string) Theme location to be used. Must be registered with register_nav_menu() in order to be selectable by the user.
						) );
					?>
				</div>
				<div class="company">
					<h6>Company</h6>
					<?php 
						wp_nav_menu( $args = array(
							'menu'              => "company", // (int|string|WP_Term) Desired menu. Accepts a menu ID, slug, name, or object.
							'menu_class'        => "listing", // (string) CSS class to use for the ul element which forms the menu. Default 'menu'.
							'menu_id'           => "company-menu", // (string) The ID that is applied to the ul element which forms the menu. Default is the menu slug, incremented.
							'container'         => "ul", // (string) Whether to wrap the ul, and what to wrap it with. Default 'div'.
							'theme_location'    => "company", // (string) Theme location to be used. Must be registered with register_nav_menu() in order to be selectable by the user.
						) );
					?>
				</div>
			</div>
			<div class="col-lg-2 col-md-3 col-sm-6">
				<div class="follow mobile">
					<h6>Follow Us</h6>
					<div class="social-icons">
						<a href="https://www.facebook.com/highspeedoptions">
							<img src="<?php echo get_template_directory_uri() ?>/images/fb.svg" alt="facebook logo" height="24" width="24">
						</a>
						<a href="https://twitter.com/HighSpeedOption">
							<img src="<?php echo get_template_directory_uri() ?>/images/twitter.svg" alt="twitter logo" height="24" width="24">
						</a>
						<a href="https://www.linkedin.com/company/high-speed-options/">
							<img src="<?php echo get_template_directory_uri() ?>/images/linkedin.svg" alt="linkedin logo" height="24" width="24">
						</a>
					</div>
				</div>
			</div>
			<div class="col-xl-5 col-lg-4 col-sm-12">
				<div class="left mobile">
					<p>All trademarks remain the property of their respective owners, and are used by HighSpeedOptions only to describe products and services offered by each respective trademark holder. HighSpeedOptions provides information for comparison purposes and does not offer internet, television, bundling, or streaming services directly, or endorse any service over others. HighSpeedOptions is supported by compensation from our internet, TV, and streaming partners.</p>
					<p>© Copyright <?php echo date('Y'); ?> HighSpeedOptions</p>
					<?php 
							wp_nav_menu( $args = array(
								'menu'              => "privacy", // (int|string|WP_Term) Desired menu. Accepts a menu ID, slug, name, or object.
								'menu_class'        => "privacy-menu", // (string) CSS class to use for the ul element which forms the menu. Default 'menu'.
								'menu_id'           => "privacy-menu", // (string) The ID that is applied to the ul element which forms the menu. Default is the menu slug, incremented.
								'container'         => "ul", // (string) Whether to wrap the ul, and what to wrap it with. Default 'div'.
								'theme_location'    => "privacy", // (string) Theme location to be used. Must be registered with register_nav_menu() in order to be selectable by the user.
							) );
						?>
				</div>
			</div>
		</div>
	</div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
