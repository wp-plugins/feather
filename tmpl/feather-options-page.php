<div class="wrap">
<?php
// Print tabs
FeatherAdmin::print_options_page_tabs('feather');
// Get current tab
$tab=FeatherAdmin::get_current_options_page_tab('feather');
?>

<div class="feather postbox">
	<form action="options.php" method="post">
	<?php settings_fields('feather-settings'); ?>
	<?php do_settings_sections('feather-'.$tab); ?>

	<input type="hidden" name="feather[tab]" value="<?php echo $tab; ?>">

	<p class="submit">
		<input type="submit" class="button-primary" value="Save Changes" />
	</p>
	</form>

	<p id="wpbandit">Feather created by <a href="http://wpbandit.com/">WPBandit</a></p>
</div>
</div>
