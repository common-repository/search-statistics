<?php 

	//get searched terms
	$skomfare2_searchterms_text_array = get_option('_skomfare2_savedterms_query_text');

	//sort array based on key names
	if(is_array($skomfare2_searchterms_text_array)){
		ksort($skomfare2_searchterms_text_array );
	}
	

	
	//get saved options 
	$skomfare2_searchterms_saved_options_array = get_option('skomfare2_search_terms_settings_options');
	
	$search_count = (isset($skomfare2_searchterms_saved_options_array['skomfare2_searchterm_show_on_report_if_keyword_search_more_than'])) ? $skomfare2_searchterms_saved_options_array['skomfare2_searchterm_show_on_report_if_keyword_search_more_than'] : 3 ;

	
?>


<div class="wrap" style="background-color: #fff;padding: 20px;">
	<h1>Search Term Reports</h1>

	<form class="skomfare2_search_terms_delete_terms_form">
			<table class="" id="<?php echo (is_array($skomfare2_searchterms_text_array)) ? 'skomfare2_search_terms_results_page_has_results' : 'skomfare2_search_terms_no_results' ;?>">
				<thead>
					<tr>
						<th >&nbsp;</th>
						<th >Searched for</th>
						<th >Count</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th >&nbsp;</th>					
						<th >Searched for</th>
						<th >Count</th>			
					</tr>
				</tfoot>	
				<tbody id="the-list">	

					<?php

					if(!is_array($skomfare2_searchterms_text_array)){
						?>
							<tr class=" hentry  iedit widefat">
								<td>
									No searches yet
								</td>	
							</tr>
							</tbody>
						</table>	
						<?php
						return ;
					}

					foreach ($skomfare2_searchterms_text_array as $skomfare2_searchterms_text_array_k => $skomfare2_searchterms_text_array_v){ ?>
						
						<?php 
						foreach($skomfare2_searchterms_text_array_v as $skomfare2_searchterms_text_array_v_single_key  => $skomfare2_searchterms_text_array_v_single_value){ 
							//check if search count is higher than the option saved
							
							if($search_count < $skomfare2_searchterms_text_array_v_single_value){ ?>
								
								<tr >
									<td>
										<?php echo $skomfare2_searchterms_text_array_k; ?>
									</td>

									<?php foreach($skomfare2_searchterms_text_array_v as $skomfare2_searchterms_text_array_v_single_key  => $skomfare2_searchterms_text_array_v_single_value){ ?>
									<td >
										<?php echo  $skomfare2_searchterms_text_array_v_single_key ;?>
									</td>	
									<td >
										<?php echo  $skomfare2_searchterms_text_array_v_single_value ;?>
									</td>
									<?php } ?>

								</tr>
						
						
						
							<?php
						
							} 
						
						}
						
					} //end foreach
					
					?>
			
				</tbody>
			</table>	

		</form>
	
	<div>
		<div style="display:inline-block;float:left">
			<button class="button button-primary skomfare2_delete_terms">Delete Selected </button>
		</div>
		
		<div style="display:inline-block;float:right">
			<button class="button button-primary skomfare2_delete_all_terms">Delete all terms </button>
		</div>
		<div style="clear:both"></div>
	</div>
</div>