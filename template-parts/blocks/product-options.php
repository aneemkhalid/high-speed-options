<?php

/**
 * Product Options Block.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

$product_options = get_field('product_options');
$source = $product_options['source'];

if(is_array($product_options)):
?>

<div class="product-options-container mt-5">
    <h1 class="options-header bridge-till-redesign">
        <?php echo $product_options['title']; ?>
    </h1>
    <div class="options-container">
        <?php foreach($product_options['options'] as $option): ?>

            <div class="option-container">
                <div class="icon-container">
                    <img src="<?php echo $option['icon']; ?>" alt="<?php echo $option['subtitle']; ?>">
                </div>
                <h5 class="subtitle">
                    <?php echo $option['subtitle']; ?>
                </h5>
                <p class="description">
                    <?php echo $option['description']; ?>
                </p>
            </div>
            
        <?php endforeach; ?>
    </div>
</div>
<?php 
if ($source){
    echo '<figcaption class="figcaption-source product-options-source">'.$source.'</figcaption>';
}
?>

<?php endif; ?>
