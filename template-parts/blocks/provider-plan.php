<?php

$template = array(
	array('acf/provider-plan-connections'),
);

global $post;
$id = $post->ID;

$show_plans = get_field('show_plans_page', $id);
$plan_page = (get_query_var('plans') == 'show') ? true : isset($_GET['plans']);
//echo $plan_page;
//echo $show_plans;

?>

<?php if(is_admin() || ($plan_page && $show_plans)) : ?>

<section class="provider-plan-block">
    <div class="admin-instructions">
        <h2>Provider Plans Page</h2>
        <p>Add new blocks within this block to appear on Plans Page for provider.</p>
        <hr />
    </div>
    <InnerBlocks template="<?php echo esc_attr( wp_json_encode( $template )) ?>"/>
</section>

<?php endif; ?>
