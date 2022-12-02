<?php

$title = ($override = get_field('faqs_title')) ? $override : 'You have questions, we have answers';
$section_title = ($section_title_override = get_field('faqs_section_title')) ? $section_title_override : 'FAQ';
$faqs = get_field('faqs');

if($args['unique']) {
	$title = ($override = get_field('plan_faq_title')) ? $override : 'You have questions, we have answers';
	$section_title = ($section_title_override = get_field('plan_faq_section_title')) ? $section_title_override : 'FAQ';
	$faqs = get_field('plan_faqs');
}

if( $faqs ): ?>
	<section class="faq-block-container row-full">
		<div class="faq-block">
			<div class="container">
				<div class="title-container">
					<div class="pretitle new"><?php echo $section_title ?></div>
					<h2><?php echo $title ?></h2>
				</div>
				<div id="accordion">
					<?php
						$counter = 1;
						// Loop through rows.
						//while( have_rows('faqs') ) : the_row();
						foreach($faqs as $row) : 

							// Load sub field value.
							$question = $row['question']; 
							$answer = $row['answer'] ?>

							<div class="card">
								<div class="card-header" id="heading<?php echo $counter ?>">
								<h4>
									<button class="btn btn-link" data-toggle="collapse" data-target="#collapse<?php echo $counter ?>" aria-expanded="true" aria-controls="collapse<?php echo $counter ?>">
										<div class="question"><?php echo $question; ?></div>
										<span class="material-icons">expand_less</span>
									</button>
								</h4>
								</div>
								<div id="collapse<?php echo $counter ?>" class="collapse  show<?php //if($counter == 1) echo 'show'; ?>" aria-labelledby="heading<?php echo $counter ?>" data-parent="#accordion">
									<div class="card-body"><?php echo $answer; ?></div>
								</div>
							</div>

						<?php $counter++; endforeach; //endwhile;	?>
				
				</div>
			</div>
		</div>	
	</section>
<?php endif; ?>