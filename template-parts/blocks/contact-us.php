<?php

/**
 * Contact Us Block.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

$contact_data = get_field('contact_us_block');

if(is_array($contact_data)):
?>
<div class="contact-us-boxes-wrap row-full">
    <div class="container">
        <div class="contact-us-boxes-container row">
            <?php foreach($contact_data as $contact): ?>
                <div class="contact-us-box-container">
                    <div class="contact-us-content-container">
                        <div class="contact-us-icon-container">
                            <img src="<?php echo $contact['icon']['url'];?>" alt="<?php echo $contact['icon']['alt'];?>" class="contact-us-icon" height="100" width="auto">
                        </div>
                        <div class="contact-us-title-container">
                            <h2 class="contact-us-title">
                                <?php echo $contact['title']; ?>
                            </h2>
                        </div>
                        <div class="contact-us-description-container">
                            <?php echo $contact['description']; ?>
                        </div>
                        <div class="contact-us-button-container">
                            <a href="mailto:<?php echo $contact['contact_email']; ?>" class="contact-us-button">
                                <?php echo $contact['button_text']; ?>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>


<?php endif; ?>
