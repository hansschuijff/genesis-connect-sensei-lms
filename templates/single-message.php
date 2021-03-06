<?php
/**
 * The Template for displaying all single messages.
 *
 * This is a genesis compatible version of: sensei-lms\templates\single-message.php
 * 
 * Override this template by copying it to yourtheme/sensei/single-message.php
 *
 * @author      Automattic
 * @package     Sensei
 * @category    Templates
 * @version     1.12.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

remove_action( 'genesis_loop', 'genesis_do_loop' );

function dwp_sensei_do_single_message_loop() {

	the_post();
	?>

	<article <?php post_class(); ?> >

		<?php
		/**
		 * Action inside the single message template before the content
		 *
		 * @since 1.9.0
		 *
		 * @param integer $message_id
		 *
		 * @hooked Sensei_Messages::the_title                 - 20
		 * @hooked Sensei_Messages::the_message_sent_by_title - 40
		 */
		do_action( 'sensei_single_message_content_inside_before', get_the_ID() );
		?>

		<section class="entry">

			<?php the_content(); ?>

		</section>

		<?php

		/**
		 * action inside the single message template after the content
		 *
		 * @since 1.9.0
		 *
		 * @param integer $message_id
		 */
		do_action( 'sensei_single_message_content_inside_after', get_the_ID() );

		?>
	</article><!-- .post -->

	<?php 

	/**
	 * sensei_pagination hook
	 *
	 * @hooked sensei_pagination - 10 (outputs pagination)
	 */
	do_action( 'sensei_pagination' );

}
add_action( 'genesis_loop', 'dwp_sensei_do_single_message_loop' );

genesis();
