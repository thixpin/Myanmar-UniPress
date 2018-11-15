<?php

if(is_admin()){
    add_action('admin_menu', 'unipress_options');
}

function unipress_options(){
	add_options_page('Myanmar UniPress', 'Myanmar UniPress', 'administrator', 'myanmar-unipress', 'unipress_adminpage');
}

function unipress_adminpage(){
	if(isset($_POST)){

		if(isset($_POST['Submit'])){
			update_option('IndicateConverted',$_POST['IndicateConverted']);
		}
		
	}
	
	if (get_option('unipress_init') =="") {
		//init
		update_option('IndicateConverted',0);
		update_option('unipress_init',1);
	}
?>
	 <div class="wrap" style="font-size:13px;">

			<div class="icon32" id="icon-options-general"><br/></div><h2>Settings for Myanmar UniPress</h2>
			<form method="post" action="options-general.php?page=myanmar-unipress">
			<table class="form-table">
				
				<tr valign="top">
					<th scope="row">
						Indicate convertedText
					</th>
					<td>
						<p>
						 <input type="checkbox" value="1"
						 <?php if (get_option('IndicateConverted') == '1') echo 'checked="checked"'; 
						 ?> name="IndicateConverted" id="IndicateConverted" group="IndicateConverted"/>

						 ( It will Indicate the converted text with the left border. )

					</td>
				</tr>
			</table>
			<p class="submit">
				<input type="submit" name="Submit" class="button button-primary" value="<?php _e('Save Changes') ?>" />		
			</p>
			</form>
<?php
}
?>