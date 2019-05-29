<?php

if(is_admin() && current_user_can('update_plugins')){
    add_action('admin_menu', 'unipress_options');
}

function unipress_options(){
	add_options_page('Myanmar UniPress', 'Myanmar UniPress', 'administrator', 'myanmar-unipress', 'unipress_adminpage');
}

function unipress_adminpage(){
	if(isset($_POST) && current_user_can('update_plugins')){

		if(isset($_POST['Submit'])){
			update_option('IndicateConverted',	(int)$_POST['IndicateConverted']);
			update_option('BunnyDisabled',		(int)$_POST['BunnyDisabled']);
			update_option('ShareAsZawgyi',		(int)$_POST['ShareAsZawgyi']);
		}
		
	}
	
	if (get_option('unipress_init') =="") {
		//init
		update_option('IndicateConverted',0);
		update_option('BunnyDisabled',0);
		update_option('ShareAsZawgyi',0);
		update_option('unipress_init',1);
	}
?>
	 <div class="wrap" style="font-size:13px;">

			<div class="icon32" id="icon-options-general"><br/></div><h2>Settings for Myanmar UniPress</h2>
			<form method="post" action="options-general.php?page=myanmar-unipress">
			<table class="form-table">
				<tr valign="top">
					<th scope="row">
						Disable BunnyJS 
					</th>
					<td>
						<p>
						 <input type="checkbox" value="1"
						 <?php if (get_option('BunnyDisabled') == '1') echo 'checked="checked"'; 
						 ?> name="BunnyDisabled" id="BunnyDisabled" group="BunnyDisabled"/>

						 ( <b>BunnyJs</b> will convert the myanmar texts to your drowser default font. <br> 
						 If you already embedded the myanmar unicode in your Css, you should disable <b>BunnyJs</b>. )
						 </p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						Indicate convertedText
					</th>
					<td>
						<p>
						 <input type="checkbox" value="1"
						 <?php if (get_option('IndicateConverted') == '1') echo 'checked="checked"'; 
						 ?> name="IndicateConverted" id="IndicateConverted" group="IndicateConverted"/>

						 ( It will Indicate the converted text by <b>BunnyJs</b> with the left border. )
						 </p>

					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						Share As Zawgyi text
					</th>
					<td>
						<p>
						 <input type="checkbox" value="1"
						 <?php if (get_option('ShareAsZawgyi') == '1') echo 'checked="checked"'; 
						 ?> name="ShareAsZawgyi" id="ShareAsZawgyi" group="ShareAsZawgyi"/>

						 ( Preview of post title and excerpt will be appear as <b>Zawgyi Font</b> on social media. )
						 </p>

					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
					</th>
					<td>
						<p class="submit">
							<input type="submit" name="Submit" class="button button-primary" value="<?php _e('Save Changes') ?>" />		
						</p>
					</td>
				</tr>
			</table>
			
			</form>
			<div style="font-size: 1.2em; padding: 5em 1em; max-width: 52em;">
				&nbsp &nbsp &nbsp &nbsp
				Myanmar Unipress will detect and convert Zawgyi-One text to Myanmar unicode text before saving to database.
				If you not disable <b>BunnyJs</b>, this will convert to Zawgyi text for display when the default font of user browser is Zawgyi.
			<br><br>
			<h3>Credits : </h3>
			&nbsp &nbsp - <a href="https://github.com/Rabbit-Converter/" > <b>Rabbit Converter</b></a> was used for Unicode<==>Zawgyi converting. <br>
			&nbsp &nbsp - Myanmar font detecting and converting functions are come from <a href="https://github.com/thixpin/MUA-Web-Unicode-Converter" > <b>MUA-Web-Unicode-Converter</b></a><br>
			&nbsp &nbsp - Browser font detecting idea from <b>Ko Ei maung</b>
			
				<br><br><br>&nbsp &nbsp &nbsp &nbsp
				Thank you for using <a href="https://github.com/thixpin/Myanmar-UniPress/"> Myanmar-UniPress</a> that developed by <a href="//fb.com/thixpin"> thixpin</a>.
			</div>
<?php
}
?>