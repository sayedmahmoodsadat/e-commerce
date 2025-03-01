<?php
/**
 * @see https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/block-api/block-metadata.md#render
 */

$copyright = isset( $attributes['copyright'] ) ? $attributes['copyright'] : '';
?>
<p <?php echo get_block_wrapper_attributes(); ?>>
	<?php echo esc_html__( $copyright ) . ' '. esc_html( date( 'Y' )  ); ?>
</p>
