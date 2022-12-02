<?php

/**
 * Browse Providers By Type Block.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

$browser_provider_types = get_field('browser_provider_types');

if(is_array($browser_provider_types)):
?>

<div class="browser-provider-types-container mt-5">
    <h3 class="provider-types-header">
        <?php echo $browser_provider_types['title']; ?>
    </h3>
    <div class="provider-types-container">
        <?php foreach($browser_provider_types['provider_type_groups'] as $group): ?>

            <a href="<?php echo $group['url']; ?>" class="group-container">
                <div class="group-content-container">
                    <div class="icon-container">
                        <img src="<?php echo $group['icon']; ?>" alt="<?php echo $group['label']; ?>" width="130" height="150">
                    </div>
                    <h2 class="label">
                        <?php echo $group['label']; ?>
                    </h2>
                </div>
            </a>
            
        <?php endforeach; ?>
    </div>
</div>

<?php endif; ?>
