<?php
/**
 * Plugin Name: Remember Contactform7 Entries
 * Description: This plugin is used to Remember Contactform7 entries.
 * Author: Softound Solutions
 * Version: 1.0.0
 * Author URI: http://softound.com/
 */

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

class RememberContactform7Entry {
	
	public function __construct(){
		add_action('wp_head', array($this, 'keep_entry_js'));
		add_action('wp_head', array($this, 'unserialize_js_plugin'));
	}
	
	public function unserialize_js_plugin() {
		wp_enqueue_script ('jQuery-unserialize', plugin_dir_url( __FILE__ ) . 'assets/unserialize-jquery.js' , array('jquery'), '1.0' , false );
	}

	public function keep_entry_js(){
		?>
		<script>
		jQuery(function(){
			jQuery('.wpcf7-submit').click(function(){
				document.cookie="wpcf7-form="+jQuery('.wpcf7-form').serialize();
			});
			var tmp_form_data = getCookie('wpcf7-form');
			var from_data = jQuery.unserialize(tmp_form_data);
			console.log(from_data);
			jQuery.each(from_data, function(form_field_name, form_field_value){
				if(form_field_name.indexOf('_')!=0){
					/*
					For Check the all input field values
					 */
					if(jQuery('[name="'+form_field_name+'"]').attr('type') == 'text' ||
					   jQuery('[name="'+form_field_name+'"]').attr('type') == 'email' ||
					   jQuery('[name="'+form_field_name+'"]').attr('type') == 'url' ||
					   jQuery('[name="'+form_field_name+'"]').attr('type') == 'tel' ||
					   jQuery('[name="'+form_field_name+'"]').attr('type') == 'number' ||
					   jQuery('[name="'+form_field_name+'"]').attr('type') == 'date') {
						jQuery('input[name="'+form_field_name+'"]').val(form_field_value);
					} else if(jQuery('[name="'+form_field_name+'"]').attr('type') == 'radio'){
						radioBtn = jQuery('input:radio[name="'+form_field_name+'"]');
						jQuery.each(radioBtn,function (field_id,field) {
							if(field.value == form_field_value) {
								jQuery(field).attr('checked',true);
							}
						});
					} else if(jQuery('[name="'+form_field_name+'[]"]').attr('type') == 'checkbox'){
						checkBox = jQuery('input:checkbox[name="'+form_field_name+'[]"]');
						jQuery.each(checkBox,function (field_id,field) {
							jQuery.each(form_field_value,function(i,v){
								if(field.value == v) {
									jQuery(field).attr('checked',true);
								}
							});
						});
					} else {
						if(typeof(jQuery('[name="'+form_field_name+'"]')[0]) != 'undefined') {
							if(jQuery('[name="'+form_field_name+'"]')[0].type == 'textarea') {
								jQuery('textarea[name="'+form_field_name+'"]').val(form_field_value);
							} else if (jQuery('[name="'+form_field_name+'"]')[0].type == 'select-one') {
								var dropDownOne = jQuery('select[name="'+form_field_name+'"]')[0];
								jQuery.each(dropDownOne,function (i,v) {
									if(v.value == form_field_value) {
										jQuery(v).attr('selected',true);
									}
								});
							}
						} else {
							if (jQuery('[name="'+form_field_name+'[]"]')[0].type == 'select-multiple') {
								var dropDownOne = jQuery('select[name="'+form_field_name+'[]"]')[0];
								jQuery.each(dropDownOne,function (i,option) {
									jQuery.each(form_field_value,function (i,selectedval) {
										if(option.value == selectedval) {
											jQuery(option).attr('selected',true);
										}
									});
								});
							}
						}
					}					
				}
			});
		});	
		
		function getCookie(cname) {
			var name = cname + "=";
			var ca = document.cookie.split(';');
			for(var i = 0; i <ca.length; i++) {
				var c = ca[i];
				while (c.charAt(0)==' ') {
					c = c.substring(1);
				}
				if (c.indexOf(name) == 0) {
					return c.substring(name.length,c.length);
				}
			}
			return "";
		}
		</script>
		<?php
	}
}
$remember_contactform7_entry_obj = new RememberContactform7Entry();
?>