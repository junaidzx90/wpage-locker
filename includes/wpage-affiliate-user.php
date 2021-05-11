<?php
$lcr_id = intval( get_query_var( 'lcr_id' ) );
$lcr_pid = intval( get_query_var( 'post_id' ) );

$user_id = Wpage_Lcr_Admin::wpage_user_id();

global $wpdb,$wp_query;
if(isset($lcr_id) && isset($lcr_pid)){
    if(get_post_meta( $lcr_pid, 'wpage_locked', true )){

        if($user_id == $lcr_id){
            wp_safe_redirect( site_url(  Wpage_Lcr_Admin::get_post_slug($lcr_pid)) );
            exit;
        }else{
            $user_ip = Wpage_Lcr_Admin::getmy_ip();

            // Check my visit
            $get_locker = $wpdb->query("SELECT * FROM {$wpdb->prefix}wpage_locker WHERE post_id = $lcr_pid AND user_id = $user_id OR referer_ip = '$user_ip'");

            if(!$get_locker){
            
                $tbl = $wpdb->prefix.'wpage_locker';

                if($wpdb->insert($tbl, array("owner_id" => $lcr_id, "user_id" => $user_id, "post_id" => $lcr_pid, "referer_ip" => $user_ip), array("%d","%d", "%d", "%s"))){
                    wp_safe_redirect( site_url(  Wpage_Lcr_Admin::get_post_slug($lcr_pid)) );
                    exit;
                }else{
                    wp_safe_redirect( site_url(  Wpage_Lcr_Admin::get_post_slug($lcr_pid)) );
                    exit;
                }
            }else{
                wp_safe_redirect( site_url(  Wpage_Lcr_Admin::get_post_slug($lcr_pid)) );
                exit;
            }
        }

    }else{
        wp_safe_redirect( site_url(  '/' ) );
        exit;
    }
 
}else{
    wp_safe_redirect( site_url(  '/' ) );
    exit;
}
?>