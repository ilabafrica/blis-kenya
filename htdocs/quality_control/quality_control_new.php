<?php
#
# Main page for adding new test category
#
include("redirect.php");
include("includes/header.php");
LangUtil::setPageId("quality");
?>
<br>
<div class="portlet box green">
							<div class="portlet-title">
								<h4><i class="icon-reorder"></i><?php echo "New Quality Control"; ?></h4>
								<div class="tools">
									<a href="javascript:;" class="collapse"></a>
									<a href="javascript:;" class="reload"></a>
								</div>
							</div>
							<div class="portlet-body">
							<p style="text-align: right;"><a href='#' title='Tips'><?php echo "Use the wizard to define a quality control form"; ?></a>
		|<a href='quality.php?show_qc=1'><?php echo LangUtil::$generalTerms['CMD_CANCEL']; ?></a></p>
<div class='pretty_box'>
<!-- BEGIN FORM-->
                        <form action="#" class="form-horizontal" />
                        <fieldset>
    					<legend>Quality Control Properties</legend>
                           <div class="control-group">
                              <label class="control-label"><?php echo "Quality Control Name"; ?></label>
                              <div class="controls">
                                 <input type="text" class="span6 m-wrap" />
                              </div>
                           </div>
                           <div class="control-group">
                              <label class="control-label"><?php echo "Instrument or Reagent or Lot"; ?></label>
                              <div class="controls">
                                 <select data-placeholder="Your Favorite Football Team" class="chosen span6" tabindex="-1" id="selS0V">
                                    <option value="" />
                                    <optgroup label="NFC EAST">
                                       <option />Dallas Cowboys
                                       <option />New York Giants
                                       <option />Philadelphia Eagles
                                       <option />Washington Redskins
                                    </optgroup>
                                    <optgroup label="NFC NORTH">
                                       <option />Chicago Bears
                                       <option />Detroit Lions
                                       <option />Green Bay Packers
                                       <option />Minnesota Vikings
                                    </optgroup>
                                    <optgroup label="NFC SOUTH">
                                       <option />Atlanta Falcons
                                       <option />Carolina Panthers
                                       <option />New Orleans Saints
                                       <option />Tampa Bay Buccaneers
                                    </optgroup>
                                    <optgroup label="NFC WEST">
                                       <option />Arizona Cardinals
                                       <option />St. Louis Rams
                                       <option />San Francisco 49ers
                                       <option />Seattle Seahawks
                                    </optgroup>
                                    <optgroup label="AFC EAST">
                                       <option />Buffalo Bills
                                       <option />Miami Dolphins
                                       <option />New England Patriots
                                       <option />New York Jets
                                    </optgroup>
                                    <optgroup label="AFC NORTH">
                                       <option />Baltimore Ravens
                                       <option />Cincinnati Bengals
                                       <option />Cleveland Browns
                                       <option />Pittsburgh Steelers
                                    </optgroup>
                                    <optgroup label="AFC SOUTH">
                                       <option />Houston Texans
                                       <option />Indianapolis Colts
                                       <option />Jacksonville Jaguars
                                       <option />Tennessee Titans
                                    </optgroup>
                                    <optgroup label="AFC WEST">
                                       <option />Denver Broncos
                                       <option />Kansas City Chiefs
                                       <option />Oakland Raiders
                                       <option />San Diego Chargers
                                    </optgroup>
                                 </select>
                              </div>
                           </div>
                           
                           <div class="control-group">
                              <label class="control-label"><?php echo "Quality Control Description"; ?></label>
                              <div class="controls">
                                 <textarea class="span6 m-wrap" rows="3"></textarea>
                              </div>
                           </div>
                           <!--<div class="form-actions">
                              <button type="submit" class="btn blue">Submit</button>
                              <button type="button" class="btn">Cancel</button>
                           </div>-->
                           </fieldset>
                        </form>
                        <!-- END FORM-->  
                        <fieldset>
    <legend>Quality Control Form Definition</legend>
    <br />
<div id="my_form_builder">
</div>
<div id='quality_control_help' style='display:none'>
<small>
Use Ctrl+F to search easily through the list. Ctrl+F will prompt a box where you can enter the test category you are looking for.
</small>
</div>
</div>
<script type='text/javascript'>
function check_input()
{
	// Validate
	var category_name = $('#category_name').val();
	if(category_name == "")
	{
		alert("<?php echo "Error: Missing quality control category name"; ?>");
		return;
	}
	// All OK
	$('#new_quality_control_category_form').submit();
}

</script>

<?php 
$script_elems->enableFormBuilder();
$script_elems->enableFacebox();
$script_elems->enableBootstrap();
include("includes/footer.php"); ?>