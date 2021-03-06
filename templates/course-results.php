<?php
/**
 * The Template for displaying course archives, including the course page template.
 *
 * This is a genesis compatible version of: sensei-lms\templates\course-results.php
 * 
 * Override this template by copying it to yourtheme/sensei/archive-course.php
 *
 * @author      Automattic
 * @package     Sensei
 * @category    Templates
 * @version     1.12.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

remove_action( 'genesis_loop', 'genesis_do_loop' );

function dwp_sensei_do_course_results_loop() {

	/**
	 * This hook fire inside learner-profile.php before the content
	 *
	 * @since 1.9.0
	 *
	 * @hooked Sensei_Course_Results::deprecate_sensei_course_results_content_hook() - 20
	 */
	do_action( 'sensei_course_results_content_before' );

	global $course, $wp_query;
	$course = get_page_by_path( $wp_query->query_vars['course_results'], OBJECT, 'course' );

	?>
	<article <?php post_class( array( 'course', 'post', 'course-results' ) ); ?> >

		<section class="entry fix">

			<?php
			/**
			 * This hook fire inside learner-profile.php inside directly before the content
			 *
			 * @since 1.9.0
			 *
			 * @param integer $course_id
			 */
			do_action( 'sensei_course_results_content_inside_before', $course->ID );
			?>

			<header>

				<h1>
					<?php echo esc_html( $course->post_title ); ?>
				</h1>

			</header>

			<?php if ( is_user_logged_in() ) : ?>

			<?php
			/**
			 * This hook fire inside learner-profile.php inside directly before the content
			 *
			 * @since 1.9.0
			 *
			 * @param integer $course_id
			 *
			 * @hooked Sensei_Course_Results::course_info() - 20
			 */
			do_action( 'sensei_course_results_content_inside_before_lessons', $course->ID );
			?>

			<section class="course-results-lessons">
				<?php
				$started_course = Sensei_Course::is_user_enrolled( $course->ID, get_current_user_id() );
				if ( $started_course ) {

					sensei_the_course_results_lessons();

				}
				?>
			</section>

			<?php endif; ?>

			<?php
			/**
			 * This hook fire inside learner-profile.php inside directly after the content
			 *
			 * @since 1.9.0
			 *
			 * @param integer $course_id
			 *
			 * @hooked Sensei()->course_results->course_info - 20
			 */
			do_action( 'sensei_course_results_content_inside_after', $course->ID );

		?>

		</section>

	</article>

	<?php
	/**
	 * This hook fire inside course-results.php before the content
	 *
	 * @since 1.9.0
	 */
	do_action( 'sensei_course_results_content_after' );

	/**
	 * sensei_pagination hook
	 *
	 * @hooked sensei_pagination - 10 (outputs pagination)
	 */
	do_action( 'sensei_pagination' );

}
add_action( 'genesis_loop', 'dwp_sensei_do_course_results_loop' );

genesis();
