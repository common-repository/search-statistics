jQuery(document).ready(function() {
	
    var tabela = jQuery('#skomfare2_search_terms_results_page_has_results').DataTable({
		"columns": [
			{ "width": "5%" },
			null,
			{ "width": "20%" },
			
		],
		responsive: true,
		//"processing": true,
		//"serverSide": true,
		//"ajax": skomfare2SearchTermsJsStrings.admin_url
		
		"columnDefs": [
            {
                // The `data` parameter refers to the data for the cell (defined by the
                // `data` option, which defaults to the column being worked with, in
                // this case `data: 0`.
                "render": function ( data, type, row ) {
                    return  '<input type="checkbox" name="skomfare2_search_terms_delete_terms[]" value="'+row[0]+'">';
                },
                "targets": 0
            },
			 { orderable: false, targets: [0] }
            //{ "visible": false,  "targets": [ 1 ] }
        ],
		
		"order": [[ 2, "desc" ]]
		
	});
	
	
	//PERFORM DELETE FOR SELECTED TERMS
	
	jQuery('button.skomfare2_delete_terms').click(function(){
		
		var selected = [];
		
		jQuery('input[name^="skomfare2_search_terms_delete_terms"]:checked').each(function(){
			 selected.push(jQuery(this).val());
		});

		
		jQuery.ajax({
			url : skomfare2SearchTermsJsStrings.admin_url,
			type: "POST",
			dataType : "json",
			data : {array_index_to_delete:selected,action: 'skomfare2_delete_terms_ajax'},
			success: function(data, textStatus, jqXHR){
				console.log( data);
				console.log(data.skomfare2_search_terms_delete_status);
				if(data.skomfare2_search_terms_delete_status == 'OK' ){
					 location.reload();
				}
			},
			error: function (jqXHR, textStatus, errorThrown){
		 
			}
		});	
	
	});
	
	
	
	//PERFORM DELETE FOR ALL TERMS	
	jQuery('button.skomfare2_delete_all_terms').click(function(){

		jQuery.ajax({
			url : skomfare2SearchTermsJsStrings.admin_url,
			type: "POST",
			dataType : "json",
			data : {action: 'skomfare2_delete_all_terms_ajax'},
			success: function(data, textStatus, jqXHR){
				console.log( data);
				console.log(data.skomfare2_search_terms_delete_all_status);
				if(data.skomfare2_search_terms_delete_all_status == 'OK' ){
					 location.reload();
				}
			},
			error: function (jqXHR, textStatus, errorThrown){
		 
			}
		});	
	
	});
	
	
} );