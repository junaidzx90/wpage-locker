<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://www.fiverr.com/junaidzx90
 * @since      1.0.0
 *
 * @package    Wpage_Lcr
 * @subpackage Wpage_Lcr/admin/partials
 */

wp_enqueue_style( WPAGE_NAME );
?>
<div class="notice notice-success" style="padding: 5px 10px; width: fit-content;margin-left: 2px;">Developer: <input type="email" name="developer" value="admin@easeare.com" readonly></div>

<h1>WP PAGE LOCKER</h1>
<hr>
<div class="wpagewrapper">

    <?php
    echo '<form action="options.php" method="post">';
    settings_fields( 'wpage_section' );
    do_settings_fields( 'wpage-settings', 'wpage_section' );
    submit_button();
    echo '</form>';
    ?>

    <div class="information">
        <h2>Lock Page</h2>
        <hr>
        <h4>For wp page: <span style="color:orangered" class="shortcode"> <input type="text" readonly value="[wpage_locked url='example.com']"> </span></h4>
        <small>The page where this shortcode is placed will be locked.!</small>
    </div>

    <div class="locked_page">
        <h3 class="wpagelinks">Locked Pages</h3>
        <hr>
        
        <table>
            <tbody>
                <?php
                global $post,$wp_query,$wpdb;

                $posts = new WP_Query(['post_type' => 'page','post_status' => 'publish']);
                
                if($posts){
                    $i = 1;
                    while($posts->have_posts(  )){
                        $posts->the_post();
                        if ( has_shortcode( get_the_content(), 'wpage_locked' )) {
                            ?>
                            <tr>
                                <td><?php echo esc_html( $i ) ?></td>
                                <td><?php echo esc_html(the_title()); ?></td>
                            </tr>
                            <?php
                        }
                        $i++;
                    }
                }
            ?>
            </tbody>
        </table>
    </div>

</div>