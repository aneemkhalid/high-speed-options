<?php

/**
 * ZIP Search Box Block Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */
use ZipSearch\ProviderSearchController as ProviderSearchController;
$type = ProviderSearchController::getZipType();

if (is_singular('post')):

$style = get_field('style');
$edges = '';
if ($style == 'blue-background'){
	$edges = 'border-radius-20';
}
?>
<section class="zipcode_wrap <?php echo $style; ?>">
	<div class="inner <?php echo $edges; ?>">
		<div class="d-flex flex-column align-items-stretch zip-container">
			<?php if($heading = get_field('search_heading')) : ?>
				<h3><?php echo $heading ?></h3>
			<?php else : ?>
				<h3>Enter your zip code to see providers near you</h3>
			<?php endif; ?>
			<form action="/zip-search" class="zip_search_form search_wrap justify-content-center" data-form="Search Inline">
				<div class="icon-container">
					<span class="material-icons">search</span>
				</div>
				<input type="number" class="zip_search_input" id="zip" name="zip" minlength="5" maxlength="5" placeholder="Search by ZIP" pattern="\d*"/>
				<input type="hidden" id="type" name="type" value="<?php echo $type; ?>">
				<button type="button" class="submit-zip">Search</button>
			</form>
		</div>
	</div>
</section>
<?php else: ?>

<section class="row-full zipcode_wrap">
	<div class="container">
		<div class="inner">
			<h3><?php the_field('search_heading') ?></h3>
			<form action="/zip-search" class="zip_search_form search_wrap" data-form="Search Inline">
				<input type="number" class="zip_search_input" id="zip" name="zip" minlength="5" maxlength="5" placeholder="Search by ZIP" pattern="\d*"/>
				<input type="hidden" id="type" name="type" value="<?php echo $type; ?>">
				<button type="button" class="submit-zip">Search</button>
			</form>
		</div>
	</div>
</section>

<?php endif; ?>