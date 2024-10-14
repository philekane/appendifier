<?php
/**
 * BWE Appendifier
 *
 * @package   BWE Add To Head
 * @version   1.0.0.1
 * @copyright 2022 Business Websites Et-cetera
 * @license   GPL-2.0-or-later
 */

?>
<div class="wrap | dep_inventory_dealer_settings">
<h2><?php echo esc_html__( 'Business Websites, Et cetera - Appendifier Plugin', 'my-textdomain' ); ?></h2>
	<form method="post" class="add-page" action="options.php" autocomplete="off">
		<?php settings_fields( 'bwe_appendifier_settings-group' ); ?>
		<?php do_settings_fields( 'bwe_appendifier_settings-group', '' ); ?>
		<?php do_settings_sections( 'bwe_appendifier_settings' ); ?>
		<?php submit_button(); ?>
	</form>
</div>
