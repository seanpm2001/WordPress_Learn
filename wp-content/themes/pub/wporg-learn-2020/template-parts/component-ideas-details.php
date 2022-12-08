<?php
/**
 * Template part for displaying the idea submission form.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WPBBP
 */

global $current_user;
wp_get_current_user();

// Get author details
$author_user = get_user_by( 'id', $post->post_author );

// Get array of users who have already voted for this idea (includes author)
$voted_users = get_post_meta( $post->ID, 'voted_users', true );

// Get idea status
$status = wp_get_post_terms( $post->ID, 'wporg_idea_status' )[0];

// Get idea status
$type = wp_get_post_terms( $post->ID, 'wporg_idea_type' )[0];

// Get taxonomy terms
$data = array(
	'type'      => array(
		'label' => esc_html__( 'Type', 'wporg-learn' ),
		'value' => $type->name,
	),
	'status'    => array(
		'label' => esc_html__( 'Status', 'wporg-learn' ),
		'value' => $status->name,
	),
	'author'    => array(
		'label' => esc_html__( 'Submitted by', 'wporg-learn' ),
		'value' => wp_kses_post( '<a href="httsp://profiles.wordpress.org/' . esc_attr( $author_user->user_login ) . '" target="_blank" rel="nofollow noopener">' . esc_html( $author_user->display_name ) . '</a>' ),
	),
	'votes'    => array(
		'label' => esc_html__( 'Votes', 'wporg-learn' ),
		'value' => absint( get_post_meta( $post->ID, 'vote_count', true ) ),
	),
);

$enable_vote = true;
$class_tail = ' increment-vote';
if ( $current_user->user_login && in_array( $current_user->user_login, $voted_users ) ) {
	$enable_vote = false;
	$class_tail = ' disabled';
}

?>

<ul class="wporg-idea-details">
	<?php
	foreach ( $data as $key => $item ) { ?>
		<li>
			<strong><?php echo esc_html( $item['label'] ); ?></strong>
			<span class="<?php echo esc_attr( $key ); ?>-details-item">
				<span id="<?php echo esc_attr( $key ); ?>-value"><?php echo wp_kses_post( $item['value'] ); ?></span>
				<?php if ( 'votes' == $key && ! in_array( $status->slug, array( 'rejected', 'complete' ) ) ) { ?>
					<span id="increment-vote" class="dashicons dashicons-plus vote-increment-button<?php echo esc_attr( $class_tail ); ?>"></span>
				<?php } ?>
			</span>
		</li>
		<?php
	}
	?>
</ul>
