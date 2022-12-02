<?php

/**
 * Comparison Tabel Block Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

$providers = get_field('providers');
?>

<div class="comparison-table">
	<table>
        <thead>
        	<th>Provider</th>
        	<th>Speeds</th>
        	<th>Credit Check Required</th>
        	<th>Data Caps</th>
        	<th>Fixed Price Guarantee</th>
        	<th>Starting Internet Plan Cost*</th>
        	<th>Installation Fees</th>
        	<th>American Cust. Satisfaction Index (ACSI)</th>
        </thead>
		<?php
			if( $providers ): ?>
				<?php foreach( $providers as $provider ): 
					// Setup this post for WP functions (variable must be named $post).
					setup_postdata($provider);
					$post_id = $provider->ID;

					$internet = get_field('internet',$post_id);
					$credit_check_required = get_field('credit_check_required',$post_id);
					$fixed_price_guarentee = get_field('fixed_price_guarentee',$post_id);
					$acsi_rating = get_field('acsi_rating',$post_id);

					if(!empty($internet['data_caps'])) 
						$data_caps = $internet['data_caps'];
					if(!empty($internet['starting_price'])) 
						$starting_price = $internet['starting_price'];
					if(!empty($internet['installation_fee'])) 
						$installation_fee = $internet['installation_fee'];
					

					?>
					<tr>
						<td><?php if(get_field('logo',$post_id)){ ?><a href="<?php the_permalink($post_id); ?>"><img src="<?php if(!empty(get_field('logo',$post_id))) echo get_field('logo',$post_id); ?>" alt="<?php the_title($post_id) ?>"></a><?php } ?></td>

						<td><?php if(!empty($internet['max_upload_speed'])){ echo $internet['max_upload_speed'].' - ';} if(!empty($internet['max_download_speed'])){ echo $internet['max_download_speed']; } ?></td>
						
						<td><?php if(!empty($credit_check_required['label'])) echo $credit_check_required['label']; ?></td>
						<td><?php if(!empty($data_caps['label'])) echo $data_caps['label']; ?></td>
						<td><?php if(!empty($fixed_price_guarentee['label'])) echo $fixed_price_guarentee['label']; ?></td>
						<td><?php if(!empty($starting_price)) echo '$'.$starting_price;  ?></td>
						<td><?php if(!empty($installation_fee)) echo $installation_fee;  ?></td>
						<td><?php if(!empty($acsi_rating)) echo $acsi_rating;  ?></td>


					</tr>
				<?php endforeach; ?>
				<?php 
				// Reset the global post object so that the rest of the page works correctly.
				wp_reset_postdata(); ?>
			<?php endif; 
		?>
    </table>
    <div class="table_desc">
		<?php the_field('features') ?>
    </div>
</div>

