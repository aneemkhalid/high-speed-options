<?php

/**
 * Things to Consider Block Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

$header_text = get_field('header_text');
$select_dot_pattern = get_field('select_dot_pattern');
$vertical_image = get_field('vertical_image');
$vertical_text_block = get_field('vertical_text_block');
$disclaim = get_field('disclaimer_text');
?>
<section class="things-to-consider row-full">
	<div class="container">
		<div class="row">
			<div class="col-lg-4">
				<div class="img_wrap">
                <?php if($select_dot_pattern == 'include') { ?>
					<div class="pattern">
						<img src="/wp-content/uploads/2022/02/Group-31.png" alt="Dots Image">
					</div>
                    <?php } else {
                            echo "";
                        } ?>
					<img src="<?php echo $vertical_image['url']; ?>" alt="<?php echo $vertical_image['alt']; ?>">
				</div>
			</div>
			<div class="col-lg-8">
				<div class="things-to-consider-content">
                <h3><?php echo $header_text; ?></h3>
					<?php echo $vertical_text_block; ?>
				</div>
			</div>
		</div>
		<?php if($disclaim) : ?>
		<div class="row">
			<div class="disclaimer-text col-12">
				<?php echo $disclaim; ?>
			</div>
		</div>
		<?php endif; ?>
	</div>
</section>