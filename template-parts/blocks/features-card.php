<?php

/**
 * Features Card Block Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */
$logo = get_field('logo');
$cta = get_field('cta_button');
?>
<section class="features-card">
	<div class="container">
		<div class="row features-card-title-row">
			<div class="col-lg-12">
				<div class="features-card-title">
					<h4><?php  echo get_field('title'); ?></h4>
				</div>
			</div>
		</div>
		<div class="row inner flex-d justify-content-center align-items-center">
			<div class="img_wrap col-md-4 col-sm-12">
				<?php if($logo) echo '<img src="'.$logo.'" alt="'.get_the_title().'">' ?>
			</div>
			<div class="col-md-4 col-sm-12 mt-4 mb-4 mt-md-0 mb-md-0 features-list">
			<?php
				// Check rows exists.
				if( have_rows('features') ):
					// Loop through rows.
					echo '<ul>';
					while( have_rows('features') ) : the_row();
						// Load sub field value.
						$feature = get_sub_field('feature');
						echo '<li>'.$feature.'</li>';
					// End loop.
					endwhile;
					echo '</ul>';
				endif;
			?>
			</div>
			<div class="col-md-4 col-sm-12">
			<?php if(!empty($cta['title'])) echo '<a href="'.$cta['url'].'" class="cta_btn" target="'.$cta['target'].'">'.$cta['title'].'</a>'; ?>
			</div>
		</div>
	</div>
</section>