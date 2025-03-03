<?php
// enable gutenberg for woocommerce
function urben_style_blocks_activate_gutenberg_product( $can_edit, $post_type ) {
    if ( $post_type == 'product' ) {
        $can_edit = true;
    }
    return $can_edit;
}
add_filter( 'use_block_editor_for_post_type', 'urben_style_blocks_activate_gutenberg_product', 10, 2 );
