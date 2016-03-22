$(document).ready(function() {
  
	var table_data = <?= $data; ?>; 

	console.log(table_data);

	var table_options = {       
	  "processing": true, 
	  "autoWidth": false, 
	  "bSort": true, 
	  "data": table_data,
	  "columns": [      
	    { "title": "Title", "data": "name"}
	  ]
	};

	$(function() {   
	  var projects_report_table = $('#project_reports-table').DataTable(table_options);   
	});

});