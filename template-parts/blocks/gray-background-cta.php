<?php

/**
 * Gray CTA Box Block Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

$provider = get_queried_object_id();
//$dataLayerCategory = get_field('main_title', $provider);
$dataLayerBtn = get_field('cta_link');
$btnDataLayerOutBound = dataLayerOutboundLinkClick( $provider, "Provider", $dataLayerBtn ); 
?>

<section class="row-full customer_support">
	<div class="container">
		<div class="border_heading">
			<h2><?php the_field('heading') ?></h2>
		</div>
		<?php the_field('description');
			if(get_field('cta_text'))
				echo '<a href="'.get_field('cta_link').'" class="cta_btn" target="_blank" onclick="'. $btnDataLayerOutBound .'">'.get_field('cta_text').'</a>';
			
		?>
		
	</div>
</section>