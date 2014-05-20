<?php
/**
 * The main template file
 */

get_header(); ?>

<div class="main">
<?php
	if ( have_posts() ) {

		while ( have_posts() ) {
			the_post();

			echo '<div class="post">';
			the_content();
			echo '</div>';
		}

	} else {

		echo 'No posts where found';

	}
?>
</div>

<?php
get_footer();
