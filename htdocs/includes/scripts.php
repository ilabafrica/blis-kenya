<?php
#Loads all required javascript
?>
<!-- BEGIN JAVASCRIPTS -->
	<!-- Load javascripts at bottom, this will reduce page load time -->
	<?php 
	$script_elems->enableJQuery();
	$script_elems->enableFacebox();
	$script_elems->enableAutoScrollTop();
	$script_elems->enableMultiSelect();
	$script_elems->enablePageloadIndicator();
	if(strpos($_SERVER['PHP_SELF'], "login.php") === false)
	{
		if($AUTO_LOGOUT === true)
			$script_elems->enableAutoLogout();
	}
	?>
	<script type='text/javascript'>
	<?php 
	if($TRACK_LOADTIMEJS)
	{
		echo "var t = new Date();";
	}
	?>
	$(document).ready(function(){
		$('.globalnav_option').click( function() {
			$('.globalnav_option').removeClass('globalnav_option_current');
			$(this).addClass('globalnav_option_current');
		});
	});	
	</script>
	<!-- script src="assets/js/jquery-1.8.3.min.js"></script-->
	<!--[if lt IE 9]>
			<script src="assets/js/excanvas.js"></script>
			<script src="assets/js/respond.js"></script>	
			<![endif]-->	
			<script src="assets/breakpoints/breakpoints.js"></script>		
			<script src="assets/jquery-slimscroll/jquery-ui-1.9.2.custom.min.js"></script>	
			<script src="assets/jquery-slimscroll/jquery.slimscroll.min.js"></script>
			<script src="assets/fullcalendar/fullcalendar/fullcalendar.min.js"></script>
			<script src="assets/bootstrap/js/bootstrap.min.js"></script>
			<script src="assets/js/jquery.blockui.js"></script>	
			<script src="assets/js/jquery.cookie.js"></script>
			<script type="text/javascript" src="assets/gritter/js/jquery.gritter.js"></script>
			<script type="text/javascript" src="assets/uniform/jquery.uniform.min.js"></script>	
			<script type="text/javascript" src="assets/js/jquery.pulsate.min.js"></script>
			<script type="text/javascript" src="assets/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
			<script type="text/javascript" src="assets/bootstrap-daterangepicker/date.js"></script>
			<script type="text/javascript" src="assets/bootstrap-daterangepicker/daterangepicker.js"></script>
			<script type="text/javascript" src="assets/bootstrap-timepicker/js/bootstrap-timepicker.js"></script>
			<script type="text/javascript" src="assets/data-tables/jquery.dataTables.js"></script>
			<script type="text/javascript" src="assets/data-tables/DT_bootstrap.js"></script>
			<script type="text/javascript" src="assets/chosen-bootstrap/chosen/chosen.jquery.min.js"></script>
			<script src="assets/bootstrap/js/bootstrap-modalmanager.js"></script>
			<script src="assets/bootstrap/js/bootstrap-modal.js"></script>
			<script src="assets/bootstrap/js/bootstrap-fileupload.js"></script>
			<script src="assets/js/app.js"></script>				
			<script>
				jQuery(document).ready(function() {		
					//App.setPage("index");  // set current page
					App.init(); // init the rest of plugins and elements
				});
			</script>
<script>
function handleDataTable(table_id) {
    if (!jQuery().dataTable) {
        return;
    }
    (function($) {
    	/*
    	 * Function: fnGetColumnData
    	 * Purpose:  Return an array of table values from a particular column.
    	 * Returns:  array string: 1d data array 
    	 * Inputs:   object:oSettings - dataTable settings object. This is always the last argument past to the function
    	 *           int:iColumn - the id of the column to extract the data from
    	 *           bool:bUnique - optional - if set to false duplicated values are not filtered out
    	 *           bool:bFiltered - optional - if set to false all the table data is used (not only the filtered)
    	 *           bool:bIgnoreEmpty - optional - if set to false empty values are not filtered from the result array
    	 * Author:   Benedikt Forchhammer <b.forchhammer /AT\ mind2.de>
    	 */
    	$.fn.dataTableExt.oApi.fnGetColumnData = function ( oSettings, iColumn, bUnique, bFiltered, bIgnoreEmpty ) {
    		// check that we have a column id
    		if ( typeof iColumn == "undefined" ) return new Array();
    		
    		// by default we only wany unique data
    		if ( typeof bUnique == "undefined" ) bUnique = true;
    		
    		// by default we do want to only look at filtered data
    		if ( typeof bFiltered == "undefined" ) bFiltered = true;
    		
    		// by default we do not wany to include empty values
    		if ( typeof bIgnoreEmpty == "undefined" ) bIgnoreEmpty = true;
    		
    		// list of rows which we're going to loop through
    		var aiRows;
    		
    		// use only filtered rows
    		if (bFiltered == true) aiRows = oSettings.aiDisplay; 
    		// use all rows
    		else aiRows = oSettings.aiDisplayMaster; // all row numbers

    		// set up data array	
    		var asResultData = new Array();
    		
    		for (var i=0,c=aiRows.length; i<c; i++) {
    			iRow = aiRows[i];
    			var aData = this.fnGetData(iRow);
    			var sValue = aData[iColumn];
    			
    			// ignore empty values?
    			if (bIgnoreEmpty == true && sValue.length == 0) continue;

    			// ignore unique values?
    			else if (bUnique == true && jQuery.inArray(sValue, asResultData) > -1) continue;
    			
    			// else push the value onto the result data array
    			else asResultData.push(sValue);
    		}
    		
    		return asResultData;
    	}}(jQuery));


    	function fnCreateSelect( aData )
    	{
    		var r='<select class="chosen" data-placeholder="Select" tabindex="1"><option value=""/>All</option>', i, iLen=aData.length;
    		for ( i=0 ; i<iLen ; i++ )
    		{
    			r += '<option value="'+aData[i]+'">'+aData[i]+'</option>';
    		}
    		return r+'</select>';
    	}
    // begin first table
    var oTable = $('#'+table_id).dataTable({
        "sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>",
        /*"sPaginationType": "bootstrap",
        "oLanguage": {
            "sLengthMenu": "_MENU_ records per page",
            "oPaginate": {
                "sPrevious": "Prev",
                "sNext": "Next"
            }
        
        },*/
        "bPaginate":false,
        "bInfo":false,
        "bFilter": false,
        "aaSorting": [],
        "aoColumnDefs": [{
            'bSortable': false,
            'aTargets': [0]
        }],
    });

    jQuery('#'+table_id+' .group-checkable').change(function () {
        var set = jQuery(this).attr("data-set");
        var checked = jQuery(this).is(":checked");
        jQuery(set).each(function () {
            if (checked) {
                $(this).attr("checked", true);
            } else {
                $(this).attr("checked", false);
            }
        });
        jQuery.uniform.update(set);
    });

    jQuery('#'+table_id+'_wrapper .dataTables_filter input').addClass("m-wrap medium"); // modify table search input
    jQuery('#'+table_id+'_wrapper .dataTables_length select').addClass("m-wrap xsmall"); // modify table per page dropdown

    /* Add a select menu for each TH element in the table footer */
//     $("#section").each( function ( i ) {
// 		this.innerHTML = fnCreateSelect( oTable.fnGetColumnData(0) );
// 		$(".chosen").chosen();
// 		$('select', this).change( function () {
// 			oTable.fnFilter( $(this).val(), 0 );
// 			$section = $(this).val();
// 			$('.section-name').html('All Sections');
// 			if($section==''){
// 			}else
// 			$('.section-name').html($section);
// 		} );
// 	} );
	$("#specimen_type").each( function ( i ) {
		this.innerHTML = fnCreateSelect( oTable.fnGetColumnData(4) );
		$(".chosen").chosen();
		$('select', this).change( function () {
			oTable.fnFilter( $(this).val(), 4 );
		} );
	} );
	$("#test_type").each( function ( i ) {
		this.innerHTML = fnCreateSelect( oTable.fnGetColumnData(5) );
		$(".chosen").chosen();
		$('select', this).change( function () {
			oTable.fnFilter( $(this).val(), 5);
		} );
	} );
	$("#status").each( function ( i ) {
		var test_status = new Array();
		test_status[0] = "Pending";
		test_status[1] = "Started";
		test_status[2] = "Tested";
		test_status[3] = "Tested & Verified";
		this.innerHTML = fnCreateSelect( test_status );
		$('select', this).change( function () {
			//oTable.fnFilter($(this).val() , 6);
			var val = $(this).val();
			if(val=="Pending"){
				fetch_tests(<?php echo Specimen::$STATUS_PENDING?>);
			} else if (val=="Started"){
				fetch_tests(<?php echo Specimen::$STATUS_STARTED?>);
			}else if (val=="Tested"){
				fetch_tests(<?php echo Specimen::$STATUS_TOVERIFY?>);
			}else if (val=="Tested & Verified"){
				fetch_tests(<?php echo Specimen::$STATUS_VERIFIED?>);
			}
		} );
	} );
}

/*
 * Includes pagination
 */
function handlePaginateDataTable(table_id) {
    if (!jQuery().dataTable) {
        return;
    }
    (function($) {
    	/*
    	 * Function: fnGetColumnData
    	 * Purpose:  Return an array of table values from a particular column.
    	 * Returns:  array string: 1d data array 
    	 * Inputs:   object:oSettings - dataTable settings object. This is always the last argument past to the function
    	 *           int:iColumn - the id of the column to extract the data from
    	 *           bool:bUnique - optional - if set to false duplicated values are not filtered out
    	 *           bool:bFiltered - optional - if set to false all the table data is used (not only the filtered)
    	 *           bool:bIgnoreEmpty - optional - if set to false empty values are not filtered from the result array
    	 * Author:   Benedikt Forchhammer <b.forchhammer /AT\ mind2.de>
    	 */
    	$.fn.dataTableExt.oApi.fnGetColumnData = function ( oSettings, iColumn, bUnique, bFiltered, bIgnoreEmpty ) {
    		// check that we have a column id
    		if ( typeof iColumn == "undefined" ) return new Array();
    		
    		// by default we only wany unique data
    		if ( typeof bUnique == "undefined" ) bUnique = true;
    		
    		// by default we do want to only look at filtered data
    		if ( typeof bFiltered == "undefined" ) bFiltered = true;
    		
    		// by default we do not wany to include empty values
    		if ( typeof bIgnoreEmpty == "undefined" ) bIgnoreEmpty = true;
    		
    		// list of rows which we're going to loop through
    		var aiRows;
    		
    		// use only filtered rows
    		if (bFiltered == true) aiRows = oSettings.aiDisplay; 
    		// use all rows
    		else aiRows = oSettings.aiDisplayMaster; // all row numbers

    		// set up data array	
    		var asResultData = new Array();
    		
    		for (var i=0,c=aiRows.length; i<c; i++) {
    			iRow = aiRows[i];
    			var aData = this.fnGetData(iRow);
    			var sValue = aData[iColumn];
    			
    			// ignore empty values?
    			if (bIgnoreEmpty == true && sValue.length == 0) continue;

    			// ignore unique values?
    			else if (bUnique == true && jQuery.inArray(sValue, asResultData) > -1) continue;
    			
    			// else push the value onto the result data array
    			else asResultData.push(sValue);
    		}
    		
    		return asResultData;
    	}}(jQuery));


    	function fnCreateSelect( aData )
    	{
    		var r='<select class="chosen" data-placeholder="Select" tabindex="1"><option value=""/>All</option>', i, iLen=aData.length;
    		for ( i=0 ; i<iLen ; i++ )
    		{
    			r += '<option value="'+aData[i]+'">'+aData[i]+'</option>';
    		}
    		return r+'</select>';
    	}
    // begin first table
    var oTable = $('#'+table_id).dataTable({
        "sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>",
        "sPaginationType": "bootstrap",
        "oLanguage": {
            "sLengthMenu": "_MENU_ records per page",
            "oPaginate": {
                "sPrevious": "Prev",
                "sNext": "Next"
            }
        
        },
       
        "aaSorting": [],
        "aoColumnDefs": [{
            'bSortable': false,
            'aTargets': [0]
        }],
    });

    jQuery('#'+table_id+' .group-checkable').change(function () {
        var set = jQuery(this).attr("data-set");
        var checked = jQuery(this).is(":checked");
        jQuery(set).each(function () {
            if (checked) {
                $(this).attr("checked", true);
            } else {
                $(this).attr("checked", false);
            }
        });
        jQuery.uniform.update(set);
    });

    jQuery('#'+table_id+'_wrapper .dataTables_filter input').addClass("m-wrap medium"); // modify table search input
    jQuery('#'+table_id+'_wrapper .dataTables_length select').addClass("m-wrap xsmall"); // modify table per page dropdown  
}
</script>
<SCRIPT type="text/javascript" charset="utf-8">
	    <!--
		function isNumberKey(evt)
		{
			var charCode = (evt.which) ? evt.which : event.keyCode
			if (charCode > 31 && (charCode < 48 || charCode > 57))
				return false;

				return true;
		}
		//-->

	Date.format = '<?php echo $date_format_js; ?>';
	$(function()
	{
		$('#<?php echo $picker_id; ?>').datePicker({startDate:'<?php echo $start_date; ?>'});
	});

	$(function()
	{
		$('#<?php echo $picker_id; ?>')
			.datePicker({createButton:false})
			.bind(
				'click',
				function()
				{
					$(this).dpDisplay();
					this.blur();
					return false;
				}
			)
			.bind(
				'dateSelected',
				function(e, selectedDate, $td)
				{
					var date_selected = $('#<?php echo $picker_id; ?>').val();
					var date_parts = date_selected.split('-');
					$('#<?php echo $id_list[$order_list[0]]; ?>').attr("value", date_parts[0]);
					$('#<?php echo $id_list[$order_list[1]]; ?>').attr("value", date_parts[1]);
					$('#<?php echo $id_list[$order_list[2]]; ?>').attr("value", date_parts[2]);
				}
			);
	});
	
</SCRIPT>
<script>
function enableAdvancedDatePicker(date_from, date_to){
	$('#form-date-range').daterangepicker({
	    ranges: {
	        'Today': ['today', 'today'],
	        'Yesterday': ['yesterday', 'yesterday'],
	        'Last 7 Days': [Date.today().add({
	            days: -6
	        }), 'today'],
	        'Last 30 Days': [Date.today().add({
	            days: -29
	        }), 'today'],
	        'This Month': [Date.today().moveToFirstDayOfMonth(), Date.today().moveToLastDayOfMonth()],
	        'Last Month': [Date.today().moveToFirstDayOfMonth().add({
	            months: -1
	        }), Date.today().moveToFirstDayOfMonth().add({
	            days: -1
	        })]
	    },
	    opens: 'right',
	    format: 'MM/dd/yyyy',
	    separator: ' to ',
	    startDate: Date.today().add({
	        days: -29
	    }),
	    endDate: Date.today(),
	    minDate: '01/01/2011',
	    maxDate: '12/31/2014',
	    locale: {
	        applyLabel: 'Submit',
	        fromLabel: 'From',
	        toLabel: 'To',
	        customRangeLabel: 'Custom Range',
	        daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
	        monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
	        firstDay: 1
	    },
	    showWeekNumbers: true,
	    buttonClasses: ['btn-danger']
	},
	
	function (start, end) {
	    $('#form-date-range span').html(start.toString('MMMM d, yyyy') + ' - ' + end.toString('MMMM d, yyyy'));
	});
	
	$('#form-date-range span').html(Date.today().add({
	    days: -6
	}).toString('MMMM d, yyyy') + ' - ' + Date.today().toString('MMMM d, yyyy'));	

	if(date_from!=null && date_to!=null){
		nd_f = new Date(Date.parse(date_from));
   		nd_t = new Date(Date.parse(date_to));

   		$('#form-date-range span').html(nd_f.toString('MMMM d, yyyy') + ' - ' + nd_t.toString('MMMM d, yyyy'));
	}
	
	
	
	$('#form-date-range span').bind('DOMSubtreeModified', function(event) {
	   		var html =  this.innerHTML;
	   		var dates = html.split(" - ");
	   		date_from = dates[0];
	   		date_to = dates[1];
	   		nd_f = new Date(Date.parse(date_from));
	   		nd_t = new Date(Date.parse(date_to));
	   		ndf = nd_f.toString('yyyy-MM-dd')+' '+'00:00:00';
	   		ndt = nd_t.toString('yyyy-MM-dd')+' '+'23:59:59';
	        refresh_date_range(ndf,ndt);
	    
	});
}


</script>
<!-- END JAVASCRIPTS -->