<?php 


if(isset($_POST)){
	
	
	if(isset($_POST['skomfare2_searchterm_save_only_on_404']) && $_POST['skomfare2_searchterm_save_only_on_404']!=''){
	
		$skomfare2_search_term_options_array = array();
	
		//save only if not found
		$skomfare2_search_term_options_array['skomfare2_searchterm_save_only_on_404'] = (isset($_POST['skomfare2_searchterm_save_only_on_404'])) ? sanitize_text_field($_POST['skomfare2_searchterm_save_only_on_404']) : 'no';
		
		//save option for notification
		//$skomfare2_search_term_options_array['skomfare2_searchterm_notify_admin'] = (isset($_POST['skomfare2_searchterm_notify_admin'])) ? sanitize_text_field($_POST['skomfare2_searchterm_notify_admin']) : 'no';
		
		//save option for keyword length
		$skomfare2_search_term_options_array['skomfare2_searchterm_save_only_if_keyword_bigger_than'] = (isset($_POST['skomfare2_searchterm_save_only_if_keyword_bigger_than'])) ? sanitize_text_field($_POST['skomfare2_searchterm_save_only_if_keyword_bigger_than']) : '3';

		
		
		//show keyword on report page if searched more than X
		$skomfare2_search_term_options_array['skomfare2_searchterm_show_on_report_if_keyword_search_more_than'] = (isset($_POST['skomfare2_searchterm_show_on_report_if_keyword_search_more_than'])) ? sanitize_text_field($_POST['skomfare2_searchterm_show_on_report_if_keyword_search_more_than']) : '3';
		
		
		
		//update option 
		update_option('skomfare2_search_terms_settings_options',$skomfare2_search_term_options_array);
	}
	
}



?><div class="wrap" style="background-color: #fff;padding: 20px;">
	
	<h1>Search Term Settings</h1>
	
	<?php 


		//get saved options
		$skomfare2_search_term_saved_options = get_option('skomfare2_search_terms_settings_options');
		
		
		//get if enabled for not found
		$skomfare2_searchterm_save_only_on_404 =  (isset($skomfare2_search_term_saved_options['skomfare2_searchterm_save_only_on_404'])) ? $skomfare2_search_term_saved_options['skomfare2_searchterm_save_only_on_404']: 'no';
		
		//get keyword length
		$skomfare2_searchterm_save_only_if_keyword_bigger_than =  (isset($skomfare2_search_term_saved_options['skomfare2_searchterm_save_only_if_keyword_bigger_than'])) ? $skomfare2_search_term_saved_options['skomfare2_searchterm_save_only_if_keyword_bigger_than']: '3';
		
		//get if email notifications
		//$skomfare2_searchterm_notify_admin =  (isset($skomfare2_search_term_saved_options['skomfare2_searchterm_notify_admin'])) ? $skomfare2_search_term_saved_options['skomfare2_searchterm_notify_admin']: 'no';

		//get keyword search count
		$skomfare2_searchterm_show_on_reports_if_search_count_is_bigger_than =  (isset($skomfare2_search_term_saved_options['skomfare2_searchterm_show_on_report_if_keyword_search_more_than'])) ? $skomfare2_search_term_saved_options['skomfare2_searchterm_show_on_report_if_keyword_search_more_than']: '3';
		
		
		
	?>	
	
	
	<form method="post" >
		<table class="form-table">

		
			<tr valign="top">
				<th scope="row">Only when not found</th>
				<td  colspan="3">
					
						<select name="skomfare2_searchterm_save_only_on_404" >
							
							<option value="no"  <?php echo ( $skomfare2_searchterm_save_only_on_404 =='no') ? 'selected="selected" ' : ''    ?>>No</option>
							<option value="yes"  <?php echo ( $skomfare2_searchterm_save_only_on_404 =='yes') ? 'selected="selected" ' : ''    ?>>Yes</option>
						
						</select>
					
					<p class="description">If nothing found for the keyword the user searched then save the keyword </p>
				</td>
			</tr>			
		

			<tr valign="top">
				<th scope="row">Keyword length</th>
				<td  colspan="3">
					
					<input type="number" name="skomfare2_searchterm_save_only_if_keyword_bigger_than" min="1" max="50000" value="<?php echo $skomfare2_searchterm_save_only_if_keyword_bigger_than; ?>">
					
					<p class="description">Save only if the keyword is bigger than X characters </p>
				</td>
			</tr>			
		
			<tr valign="top">
				<th scope="row">Search count</th>
				<td  colspan="3">
					
					<input type="number" name="skomfare2_searchterm_show_on_report_if_keyword_search_more_than" min="0" max="50000" value="<?php echo $skomfare2_searchterm_show_on_reports_if_search_count_is_bigger_than; ?>">
					
					<p class="description">Show on report  only if the keyword is searched more than X times </p>
				</td>
			</tr>			
		
		
		
		<?php
		
			/*
				<tr valign="top">
					<th scope="row">Email notification</th>
					<td  colspan="3">
						
							<select name="skomfare2_searchterm_notify_admin" >
								<option value="yes"  <?php echo ( $skomfare2_searchterm_notify_admin =='yes') ? 'selected="selected" ' : ''  ?>>Yes</option>
								<option value="no"  <?php echo ( $skomfare2_searchterm_notify_admin =='no') ? 'selected="selected" ' : ''    ?>>No</option>
							
							</select>
						
						<p class="description">Send email to admin for keywords not found </p>
					</td>
				</tr>	

			*/
		?>
			
			
		</table>
		
		
		<p class="submit">
			<input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes">
		</p>
		
		
		
	</form>
	
	
	
	
	<div>
		<p> RATE IF YOU LIKE IT </p> 
	</div>
	
</div>