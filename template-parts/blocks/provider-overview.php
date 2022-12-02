<?php

$template = array(

);

$plan_page = isset($_GET['plans']);

?>

<?php if(is_admin() || (!$plan_page)) : ?>

<section class="provider-overview-block">
    <div class="admin-instructions">
        <h2>Provider Overview Page</h2>
        <p>Add new blocks within this block to appear on Plans Overview for provider.</p>
        <hr />
    </div>
    <InnerBlocks template="<?php echo esc_attr( wp_json_encode( $template )) ?>"/>
</section>

<?php endif; ?>
