<?php
    $title = get_field('title');
    $cities = get_field('select_cities');
    $link = get_field('all_cities_link');
    $count = count($cities);

    $cities_data = [];
    if($cities && $count == 11) {
        foreach($cities as $city) {
            $id = $city->ID;
            $cities_data[] = [
                'title' => get_the_title($id),
                'link' => get_the_permalink($id),
            ];
        }
    }
    else {
        $args = array(
            'post_type' => 'locations',
            'post_status' => 'publish',
            'posts_per_page' => '11',
            'order' => 'ASC',
            'orderby' => 'title',
            'tax_query' => array(
                array(
                    'taxonomy' => 'location_type',
                    'field'    => 'slug',
                    'terms'    => 'city',
                ),
            ),
        );

        $posts = new \WP_Query($args);

        
        while($posts->have_posts()): $posts->the_post();
            $id = get_the_ID();
            $cities_data[] = [
                'title' => get_the_title($id),
                'link' => get_the_permalink($id),
            ];
        endwhile;
        wp_reset_query();

    }
?>

<div class="home-cities-block">
    <div class="container">
        <h3><?php echo $title ?></h3>
        <div class="cities-container">
            <?php foreach($cities_data as $item) : ?>
                <a href="<?php echo $item['link'] ?>" class="city-item">
                    <div class="title">
                        <span><?php echo $item['title'] ?></span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </div>
                    <div>Internet Providers</div>
                </a>
            <?php endforeach; ?>
            <?php if($link) : ?>
                <a href="<?php echo $link['url'] ?>" class="city-item">
                    <div class="title">
                        <span><?php echo $link['title'] ?></span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </div>
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>