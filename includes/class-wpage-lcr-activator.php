<?php

/**
 * Fired during plugin activation
 *
 * @link       https://www.fiverr.com/junaidzx90
 * @since      1.0.0
 *
 * @package    Wpage_Lcr
 * @subpackage Wpage_Lcr/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Wpage_Lcr
 * @subpackage Wpage_Lcr/includes
 * @author     Md Junayed <devjoo.cantact@gmail.com>
 */
class Wpage_Lcr_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		global $wpdb;
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

		$wpage_locked = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}wpage_locker` (
			`ID` INT NOT NULL AUTO_INCREMENT,
			`owner_id` INT NOT NULL,
			`user_id` INT NOT NULL,
			`post_id` INT NOT NULL,
			`referer_ip` VARCHAR(255) NOT NULL,
			PRIMARY KEY (`ID`)) ENGINE = InnoDB";
			dbDelta($wpage_locked);

	}

}
