<?php
// Pretty link generates
add_filter( 'generate_rewrite_rules', function ( $wp_rewrite ){
    $wp_rewrite->rules = array_merge(
        ['lcr/(\d+)/p/(\d+)/?$' => 'index.php?lcr_id=$matches[1]&post_id=$matches[2]'],
        $wp_rewrite->rules
    );
});

add_filter( 'query_vars', function( $query_vars ){
    $query_vars[] = 'lcr_id';
    $query_vars[] = 'post_id';
    return $query_vars;
});

add_action( 'template_redirect', function(){
    $lcr_id = intval( get_query_var( 'lcr_id' ) );
    $post_id = intval( get_query_var( 'post_id' ) );
    if ( $lcr_id && $post_id ) {
        //lcr counting redirecting template includes
        include WPAGE_PATH . 'includes/wpage-affiliate-user.php';
        exit;
    }
});
// Pretty link generates ends
?>