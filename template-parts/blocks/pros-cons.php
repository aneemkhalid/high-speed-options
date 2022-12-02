<?php

/**
 * Pros & Cons Block Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */
if (is_singular('post')){
	$pros_cons_cols = 'col-lg-6 col-md-12';
} else {
	$pros_cons_cols = 'col-lg-12 col-md-6';
}

if (get_field('pros-cons-style') == 'blue-background'):
		$pros = get_field('pros');
		$cons = get_field('cons');
		?>
		<div class="proscons row">
			<?php if ($pros): ?>
			<div class="col-md-6 mb-3 mb-md-0">
				<div class=" border-radius-20  p-4 pl-5">
					<h3 class="mb-3 pl-4 pros-heading">Pros</h3>
					<ul class="dashed">
					<?php foreach($pros as $row): ?>
						<li class="pl-4"><h6 class="mb-2"><?php echo str_replace(['<p>', '</p>'], '', $row['pros']); ?></h6></li>
					<?php endforeach; ?>
					</ul>
				</div>
			</div>
			<?php endif; ?>
			<?php if ($cons): ?>
			<div class="col-md-6">
				<div class=" border-radius-20  p-4 pl-5">
					<h3 class="mb-3 pl-4 cons-heading">Cons</h3>
					<ul class="dashed">
					<?php foreach($cons as $row): ?>
						<li class="pl-4"><h6 class="mb-2"><?php echo str_replace(['<p>', '</p>'], '', $row['cons']); ?></h6></li>
					<?php endforeach; ?>
					</ul>
				</div>
			</div>
			<?php endif; ?>
		</div>
	<?php
else: ?>
	<section class="pros_cons_wrap">
		<div class="row">
			<div class="col-lg-12">
				<div class="pros_cons row">	
					<div class="<?php echo $pros_cons_cols; ?>">
						<div class="pros">
	                       <div class="pros_cons_head">
							  <h3><span class="material-icons">thumb_up</span>Pros</h3>
						   </div>
							<ul>
							<?php
								// Check rows exists.
								if( have_rows('pros') ):

									// Loop through rows.
									while( have_rows('pros') ) : the_row();

										// Load sub field value.
										$pros = get_sub_field('pros');
										echo '<li><span class="material-icons">check_circle</span>'.$pros.'</li>';
										// Do something...

									// End loop.
									endwhile;
								endif;
							?>
							</ul>
						</div>
					</div>
					<div class="<?php echo $pros_cons_cols; ?>">
						<div class="cons">
							<div class="pros_cons_head">
							   <h3><span class="material-icons">thumb_down</span>Cons</h3>
						   </div>
							<ul>
								<?php
									// Check rows exists.
									if( have_rows('cons') ):

										// Loop through rows.
										while( have_rows('cons') ) : the_row();

											// Load sub field value.
											$cons = get_sub_field('cons');
											echo '<li><span class="material-icons">cancel</span>'.$cons.'</li>';
											// Do something...

										// End loop.
										endwhile;
										// Do something...
									endif;
								?>
							</ul>
						</div>
					</div>				
				</div>
			</div>
		</div>
	</section>
<?php	
endif;