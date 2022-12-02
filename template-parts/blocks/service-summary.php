<?php

$title = get_field('title');
$descrip = get_field('description');
$provider = get_field('provider');

$tiles = get_field('tiles');

$count = 'tiles-' . count($tiles);

if($provider) {
    $logo = get_field('logo', $provider);
}

?>

<section class="service-summary-block <?php echo $count ?> row-full">
    <div class="container">
        <div class="header">
            <div class="title-container">
                <div class="pretitle">Services</div>
                <h2><?php echo $title ?></h2>
                <div class="descrip">
                    <?php echo $descrip ?>
                </div>
            </div>
            <?php if($logo) : ?>
            <div class="logo-container">
                <div class="logo">
                    <img src="<?php echo $logo ?>" alt="logo" height="50" width="auto">
                </div>
                <div class="dots-container">
                    <svg width="100%" height="100%"><pattern id="a" x="0" y="0" width="14" height="14" patternUnits="userSpaceOnUse" patternContentUnits="userSpaceOnUse"><circle cx="2" cy="2" r="2" fill="#D1D3D4"/></pattern><rect width="100%" height="100%" fill="url(#a)"/></svg>
                </div>
            </div>
            <?php endif; ?>
        </div>
        <div class="tiles-container">
            <?php foreach($tiles as $item) : ?>
                <a href="<?php echo $item['link'] ?>" class="tile-item">
                    <div class="img-container">
                        <?php $icon = ($item['icon'] == 'other') ? $item['custom_icon'] : get_template_directory_uri() . '/images/connections/ss-'. $item['icon'] . '.svg'; ?>
                        <img src="<?php echo $icon ?>" alt="<?php echo $icon; ?> icon" height="40" width="40">
                    </div>
                    <div class="content-container">
                        <h4>
                            <span><?php echo $item['title'] ?></span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                        </h4>
                        <div class="descrip"><?php echo $item['description'] ?></div>
                    </div>

                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>