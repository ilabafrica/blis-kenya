<?php 
include("redirect.php");
include("includes/header.php");
LangUtil::setPageId("home");
$page_elems = new PageElems();
$profile_tip = LangUtil::getPageTerm("TIPS_PWD");
?>
<!-- BEGIN PAGE TITLE & BREADCRUMB-->		
						<h3>
						</h3>
						<ul class="breadcrumb">
							<li>
								<i class="icon-home"></i>
								<a href="index.php">Home</a> 
							</li>
							<!--li><a href="#">Home</a></li-->
							<li class="pull-right no-text-shadow">
								
							</li>
						</ul>
						<!-- END PAGE TITLE & BREADCRUMB-->
					</div>
				</div>
				<!-- END PAGE HEADER-->
				
<?php $page_elems->getSideTip(LangUtil::getGeneralTerm("TIPS"), $profile_tip); ?>
<!-- DASH BOARD -->
<?php // include('dashboard.php');?>
<?php
include("includes/scripts.php");
$script_elems->enableLatencyRecord();
$script_elems->enableDatePicker();
?>
<script type='text/javascript'>

$(document).ready(function(){
    $.ajax({
		type : 'POST',
		url : 'update/check_version.php',
		success : function(data) {
			if ( data=='0' ) 
                        {
                            $('#update_div').show();
			}
			else 
                        {
                             $('#update_div').hide();
			}
		}
	});
    //$('#update_div').show();
});

function blis_update_t()
{
    $('#update_spinner').show();
    setTimeout( "blis_update();", 5000); 
}

function blis_update()
{
    $.ajax({
		url : '../update/blis_update.php',
		success : function(data) {
			if ( data=="true" ) {
                            $('#update_failure').hide();
                             $('#update_div').hide();
                            $('#update_spinner').hide();
                            $('#update_success').show();
			}
			else {
                                $('#update_success').hide();
                                 $('#update_div').hide();
                                $('#update_spinner').hide();
				$('#update_failure').show();
			}
		}
	});
        
    //$('#update_button').show();
}
</script>
<?php
include("includes/footer.php");
?>