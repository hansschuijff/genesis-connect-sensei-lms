<?php
/**
 * These functions manage loading of templates for WooCommerce.
 *
 * @package Genesis_Connect_WooCommerce
 * @since 0.9.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Load the Genesis-fied templates, instead of the Sensei defaults.
 *
 * Hooked to 'template_include' filter.
 *
 * This template loader determines which template file will be used for the requested page,
 * and uses the following hierarchy to find the template:
 * 1. First looks in the child theme's 'sensei' folder.
 * 2. If no template found, fall back to GCFWS's templates.
 *
 * For taxonomy templates, first looks in child theme's 'sensei' folder and searches for term
 * specific template, then taxonomy specific template, then taxonomy.php. If no template found,
 * falls back to GCFWS's taxonomy-module.php.
 *
 * GCFWS provides the following templates in the plugin's 'templates' directory:
 * - archive-course.php
 * - archive-lesson.php
 * - archive-message.php
 * - course-results.php
 * - learner-profile.php
 * - single-course.php
 * - single-lesson.php
 * - single-message.php
 * - single-quiz.php
 * - taxonomy-module.php
 * - teacher-archive.php
 *
 * Users can override GCFWS templates by placing their own templates in their child theme's
 * 'sensei' folder. The 'sensei' folder must be a folder in the child theme's root
 * directory, eg themes/my-child-theme/sensei.
 *
 * Note that in the case of taxonomy templates, this function accommodates ALL taxonomies
 * registered to the 'course' custom post type. This means that it will cater for users' own
 * custom taxonomies as well as Sensei taxonomies.
 *
 * @since 1.2.1
 *
 * @param string $template Template file as per template hierarchy.
 *
 * @return string $template Template file as per template hierarchy.
 */
function gcfws_template_loader( $template ) {

	global $wp_query;

	// if ( class_exists( 'WC_Embed' ) && WC_Embed::is_embedded_product() ) {
	// 	return $template;
	// }

	$custom_template = '';
	if ( is_singular( [ 'course', 'lesson', 'quiz', 'sensei_message' ] ) ) {

		$post_type = get_post_type();
		$custom_template  = "single-{$post_type}.php";

	} elseif ( is_post_type_archive( [ 'course', 'lesson', 'sensei_message' ] ) ) {

		$post_type = get_post_type();
		if ( 'sensei_message' === $post_type ) {
			$post_type = 'message';
		}
		$custom_template  = "archive-{$post_type}.php";

	} elseif ( is_page( Sensei()->get_page_id( 'courses' ) ) || is_tax('course-category') ) {

		$custom_template  = 'archive-course.php';

	} elseif ( is_tax( 'lesson-tag' ) ) {

		$custom_template  = 'archive-lesson.php';

	} elseif ( is_tax('module') ) {

		$custom_template  = 'taxonomy-module.php';

	} elseif ( isset( $wp_query->query_vars['learner_profile'] ) ) {

		$custom_template  = 'learner-profile.php';

	} elseif ( isset( $wp_query->query_vars['course_results'] ) ) {

		$custom_template  = 'course-results.php';

	} elseif ( is_author() && !user_can( get_query_var( 'author' ), 'manage_options' ) ) {

		$user = get_user_by( 'id', get_query_var( 'author' ) );

		if ( isset( $user->roles ) && in_array( 'teacher', $user->roles ) ) {

			$custom_template  = 'teacher-archive.php';

		}

	}

	if ( $custom_template ) {

		// prefer a custom template in the theme
		$template = locate_template( array( 'sensei/' . $custom_template ) );

		// if not available: use the genesis connect plugin template
		if ( ! $template ) {
			$template = trailingslashit(GCFWS_TEMPLATE_DIR) . $custom_template;
		}
	}
	return $template;

}
add_filter( 'template_include', 'gcfws_template_loader', 20 );