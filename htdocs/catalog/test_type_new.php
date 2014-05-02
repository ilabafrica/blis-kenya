<?php
#
# Main page for adding new test type form
#
include("redirect.php");
include("includes/header.php");
LangUtil::setPageId("catalog");
?>
<!-- BEGIN PAGE TITLE & BREADCRUMB-->       
                        <ul class="breadcrumb">
                            <li>
                                <i class="icon-download-alt"></i>
                                <a href="index.php">Home</a> 
                            </li>
                        </ul>
                        <!-- END PAGE TITLE & BREADCRUMB-->
                    </div>
                </div>
                <!-- END PAGE HEADER-->
                <!-- BEGIN REGISTRATION PORTLETS-->   
                <div class="row-fluid">
                <div class="span12 sortable">
                	
    <div class="portlet box green">
		<div class="portlet-title">
			<h4><i class="icon-reorder"></i>Add test type</h4>
			<div class="tools">
				<a href="javascript:;" class="collapse"></a>
				<a href="javascript:;" class="reload"></a>
				
			</div>
		</div>
		<div class="portlet-body"> 	
                	
			| <a href='catalog.php?show_t=1'><?php echo LangUtil::$generalTerms['CMD_CANCEL']; ?></a>
			<br><br>
			<div class='pretty_box'>
			<form name='new_test_form' id='new_test_form' action='test_type_add.php' method='post'>
			<table cellspacing='4px' class="table table-bordered table-hover">
				<tr>
					<td><?php echo LangUtil::$generalTerms['NAME']; ?> <?php $page_elems->getAsterisk(); ?></td>
					<td><input type='text' name='test_name' id='test_name' class='uniform_width' /> 
						</td>
				</tr>
				<tr>
					<td><?php echo LangUtil::$generalTerms['LAB_SECTION']; ?> <?php $page_elems->getAsterisk(); ?></td>
					<td>
						<SELECT name='cat_code' id='cat_code' onchange="javascript:check_if_new_category(this);" class='uniform_width'>
							<?php $page_elems->getTestCategorySelect(); ?>
							<option value='-1'>--<?php echo LangUtil::$pageTerms['NEW_LAB_SECTION']; ?>--</option>
						</select>
						&nbsp;&nbsp;&nbsp;
						<span id='new_category'>
							<small><?php echo LangUtil::$generalTerms['NAME']; ?></small>&nbsp;
							<input type='text' id='new_category_textbox' name='new_category' class='uniform_width' />
						</span>
					</td>
				</tr>
				<tr valign='top'>
					<td><?php echo LangUtil::$generalTerms['DESCRIPTION']; ?></td>
					<td><textarea name='test_descr' id='test_descr' class='uniform_width'></textarea></td>
				</tr>
				<tr valign='top'>
					<td>Clinical Data [<a href='#clinical_help' rel='facebox'>?</a>] </td><td>
						<div id="text">
						<textarea name='clinical_data' id='clinical_data' class='uniform_width'></textarea>
						</div>
					</td>
					</tr>
	
				<tr valign='top'>
					<td><?php echo LangUtil::$generalTerms['MEASURES']; ?> [<a href='#measures_help' rel='facebox'>?</a>]</td>
					<td>
						<table id='new_measure_list' class='smaller_font table table-bordered'>
							<tr>
								<td><u>Delete</u></td>
								<td><u><?php echo LangUtil::$generalTerms['NAME']; ?></u><?php $page_elems->getAsterisk(); ?></td>
								<td><u><?php echo LangUtil::$generalTerms['TYPE']; ?></u><?php $page_elems->getAsterisk(); ?></td>
								<td><u><?php echo LangUtil::$generalTerms['VALUES']; ?></u><?php $page_elems->getAsterisk(); ?></td>
								<td><u><?php echo LangUtil::$generalTerms['UNIT']; ?> /Default Value</u>[<a href='#unit_help' rel='facebox'>?</a>]</td>
							</tr>
							<?php
							$max_num_measures = count($measure_list);
							for($i = 1; $i <= $max_num_measures; $i += 1)
							{
								$curr_measure = Measure::getById($measure_list[$i-1]);
								if($curr_measure!=NULL)
								{
								$ref_ranges = $curr_measure->getReferenceRanges($_SESSION['lab_config_id']);
								?>
								<input type='hidden' name='m_id[]' value='<?php echo $measure_list[$i-1]; ?>'></input>
								<?php
								echo "<tr valign='top' id='mrow_$i' ";
								echo ">";
								echo "<td align='center'>";
								echo "<input type=checkbox name='delete_".$curr_measure->measureId."'  />";
								echo "</td><td>";
                                                                
                                                                
								$encName = $curr_measure->name;
								$start_tag = "\$sub*";
								$end_tag = "/\$";
								if(strpos($encName, $start_tag) !==false)
								{
									$subm_end = strpos($encName, $end_tag);
									$decName = substr($encName, $subm_end + 2);
									$parent = substr($encName, 5, $end_tag - 5);
									$parent_int = intval($parent);

								}
								else
								{
									$decName = $encName;
									$parent_int = 0;
								}
								?>
									<input type='hidden' name='sm_id[]' value='<?php echo $parent_int; ?>'></input>
								<?php
								if(strpos($encName, $start_tag) !==false)
										echo "Sub:";

								echo "<input type='text' class='span12 m-wrap' name='measure[]' value='$decName' />";
								echo "</td>";
								echo "<td>";
								$range_string = $curr_measure->range;
								$range_values = array();
								$range_type = $curr_measure->getRangeType();
								switch($range_type)
								{
									case Measure::$RANGE_NUMERIC:
										$range_values = explode(":", $range_string);
										break;
									case Measure::$RANGE_OPTIONS:
										$range_values = explode("/", $range_string);
										break;
									case Measure::$RANGE_AUTOCOMPLETE:
										$range_values = explode("_", $range_string);
										break;
                                                                        case Measure::$RANGE_FREETEXT:
										$range_values = array("","");
										break;
								}
								?>
								<!--<select class='range_select' id='type_'<?php echo $i; ?>' name='mtype[]' onchange='javascript:add_label(<?php echo $i; ?>);'>-->
									<select class='range_select' class='span10 m-wrap' id='<?php echo $i; ?>' name='mtype[]'>
									<option value='<?php echo Measure::$RANGE_NUMERIC; ?>' <?php 
									if($range_type == Measure::$RANGE_NUMERIC)
										echo " selected='selected' ";
									?>><?php echo LangUtil::$generalTerms['RANGE_NUMERIC']; ?></option>
									
									<option value='<?php echo Measure::$RANGE_OPTIONS; ?>' <?php 
									if($range_type == Measure::$RANGE_OPTIONS)
										echo " selected='selected' ";
									?>><?php echo LangUtil::$generalTerms['RANGE_ALPHANUM']; ?></option>
									<option value='<?php echo Measure::$RANGE_AUTOCOMPLETE; ?>' <?php 
									if($range_type == Measure::$RANGE_AUTOCOMPLETE)
										echo " selected='selected' ";
									?>><?php echo LangUtil::$generalTerms['RANGE_AUTOCOMPLETE']; ?></option>
                                                                        <option value='<?php echo Measure::$RANGE_FREETEXT; ?>' <?php 
									if($range_type == Measure::$RANGE_FREETEXT)
										echo " selected='selected' ";
									?>><?php echo "Free Text"; ?></option>
								</select>
								<?php
								
								echo "</td>";
								echo "<td>";
								?>
								<span id='val_<?php echo $i; ?>' class='values_section_<?php echo $i; ?>'
								<?php if($range_type != Measure::$RANGE_NUMERIC) echo " style='display:none' "; ?>
								>
									<?php
									
									$ref_count = 0;
									if(count($ref_ranges) == 0 || $ref_ranges == null)
									{
										# Reference ranges not configured. 
										# Fetch default values from 'measure' table
										$lower_range="";
										$upper_range="";
										if($range_type == Measure::$RANGE_NUMERIC)
										{
											$lower_range = $range_values[0];
											$upper_range = $range_values[1];
										}
										?>
										<span id='numeric_<?php echo $i; ?>'>
											
											<input type='text' class='range_field span2 m-wrap' name='range_l_<?php echo $i; ?>[]' value='<?php echo $lower_range; ?>' /> :
											<input type='text' class='range_field span2 m-wrap' name='range_u_<?php echo $i; ?>[]' value='<?php echo $upper_range; ?>' />
											<input type='text' class='range_field span3 m-wrap' name='gender_<?php echo $i; ?>[]' value='B'/>
											<input type='text' class='range_field span2 m-wrap' name='age_l_<?php echo $i; ?>[]' value='0'/>
											<input type='text' class='range_field span2 m-wrap' name='age_u_<?php echo $i; ?>[]' value='100'/>
											<br>
											
										</span>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo LangUtil::$generalTerms['RANGE']; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Gender &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Age_Range
							<br>
									<?php
									}
									else
									{	
									?>
									<span id='numeric_<?php echo $i; ?>'>
									<?php
									foreach($ref_ranges as $ref_range)
										{
											
										?>
											<input type='text' class='range_field span2 m-wrap' name='range_l_<?php echo $i; ?>[]' value='<?php echo $ref_range->rangeLower; ?>' /> :
											<input type='text' class='range_field span2 m-wrap' name='range_u_<?php echo $i; ?>[]' value='<?php echo $ref_range->rangeUpper; ?>' />
											<input type='text' class='range_field span3 m-wrap' name='gender_<?php echo $i; ?>_<?php echo $ref_count; ?>' value='<?php echo $ref_range->sex; ?>'/>
											<input type='text' class='range_field span2 m-wrap agerange_l_<?php echo $i; ?>' name='agerange_l_<?php echo $i; ?>_<?php echo $ref_count; ?>' id='agerange_l_<?php echo $i; ?>_<?php echo $ref_count; ?>' value='<?php echo $ref_range->ageMin; ?>' /> :
											<input type='text' class='range_field span2 m-wrap agerange_u_<?php echo $i; ?>' name='agerange_u_<?php echo $i; ?>_<?php echo $ref_count; ?>' id='agerange_u_<?php echo $i; ?>_<?php echo $ref_count; ?>' value='<?php echo $ref_range->ageMax; ?>' />
											<br><br>
											<?php
											$ref_count++;
												
										}
										?>
										</span>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo LangUtil::$generalTerms['RANGE']; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Gender &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Age_Range
							<br>
										<?php
									}
									?>	<br>						
									<small><a href="javascript:add_range_field('<?php echo $i; ?>',<?php echo $ref_count+1; ?>);"><?php echo LangUtil::$generalTerms['ADDANOTHER']; ?> &raquo;</a></small>
									<br><br>
								</span>
								<span id='alpha_<?php echo $i; ?>' class='values_section_<?php echo $i; ?>'
								<?php if($range_type != Measure::$RANGE_OPTIONS) echo " style='display:none' "; ?>
								>
									<span id='alpha_list_<?php echo $i; ?>'>
									<?php
										$j = 0;
										foreach($range_values as $range_value)
										{ $range_value= str_replace("#", "/", $range_value);
											$j++;
										?>
											<input type='text' class='range_field span5 m-wrap' name='alpharange_<?php echo $i; ?>[]' value='<?php if($range_type == Measure::$RANGE_OPTIONS) echo str_replace("#", "/", $range_value); ?>' /> 
										<?php
											if($j < count($range_values))
												echo "/ ";
										}
										?>
									</span>
									<br>
									<small><a href="javascript:add_option_field('<?php echo $i; ?>');"><?php echo LangUtil::$generalTerms['ADDANOTHER']; ?> &raquo;</a></small>
								</span>
								<span id='autocomplete_<?php echo $i; ?>' class='values_section_<?php echo $i; ?>'
								<?php if($range_type != Measure::$RANGE_AUTOCOMPLETE) echo " style='display:none' "; ?>
								>
									<span id='autocomplete_list_<?php echo $i; ?>'>
									<?php
										$j = 0;
										foreach($range_values as $range_value)
										{
											$j++;
										?>
											<input type='text' class='uniform_width span6 m-wrap' name='autocomplete_<?php echo $i; ?>[]' value='<?php if($range_type == Measure::$RANGE_AUTOCOMPLETE) echo $range_value; ?>' /> <br>
										<?php
										}
										?>
									</span>
									<small><a href="javascript:add_autocomplete_field('<?php echo $i; ?>');"><?php echo LangUtil::$generalTerms['ADDANOTHER']; ?> &raquo;</a></small>
								</span>
                                                                <span id='freetext_<?php echo $i; ?>' class='values_section_<?php echo $i; ?>'
								<?php if($range_type != Measure::$RANGE_FREETEXT) echo " style='display:none' "; ?>
								>
									<span id='freetext_list_<?php echo $i; ?>'>
										<?php echo "<small>Will appear as a text box for result entry</small>"?>
									</span>
								</span>
								<?php
								echo "</td>";
								echo "<td id='unit_$i'>";
								echo "<input type='text' name='unit[]' class='span10 m-wrap' value='$curr_measure->unit' />";
								echo "</td>";
								echo "</tr>";
								
							}
							}
							# Space for adding new measures
							$max_num_measures = 8;
							for($i = 1; $i <= $max_num_measures; $i += 1)
							{
								echo "<tr valign='top' id='new_mrow_$i' ";
								//if($i != 1)
								if(true)
								{
									# Hide all rows except the first
									echo " style='display:none;' ";
								}
								echo ">";
								echo "<td align='center'>";
							//	echo "<input type=checkbox name='delete_".$curr_measure->name."'  />";
								echo "</td><td>";
								echo "<input type='text' class='span12 m-wrap' name='new_measure[]' value='' />";
                                                                echo "<br>";
                                        ?>
                                        <small><a id='new_submeasure_link' href='javascript:add_new_submeasure(<?php echo $i; ?>);'><?php echo 'Add Sub Measure'; ?> &raquo;</a></small>
                                        <?php
                                                                
								echo "</td>";
								echo "<td>";
								?>
								<select class='new_range_select span10 m-wrap' id='new_<?php echo $i; ?>' name='new_mtype[]'>
									<option value='<?php echo Measure::$RANGE_NUMERIC; ?>'><?php echo LangUtil::$generalTerms['RANGE_NUMERIC']; ?></option>
									<option value='<?php echo Measure::$RANGE_OPTIONS; ?>'><?php echo LangUtil::$generalTerms['RANGE_ALPHANUM']; ?></option>
									<option value='<?php echo Measure::$RANGE_AUTOCOMPLETE; ?>'><?php echo LangUtil::$generalTerms['RANGE_AUTOCOMPLETE']; ?></option>
                                                                        <option value='<?php echo Measure::$RANGE_FREETEXT; ?>'><?php echo "Free Text"; ?></option>
								</select>
								<?php
								echo "</td>";
								echo "<td>";
								?>
								<span id='new_val_new_<?php echo $i; ?>' class='new_values_section_new_<?php echo $i; ?>'>
									<div id='numeric_range_<?php echo $i; ?>' name=numeric_range_<?php echo $i; ?>'>
									<span id='new_num eric_<?php echo $i; ?>'>
											<input type='text' class='range_field span2 m-wrap' name='new_range_l_<?php echo $i; ?>[]' value='' /> :
											<input type='text' class='range_field span2 m-wrap' name='new_range_u_<?php echo $i; ?>[]' value='' />
											<input type='text' class='range_field span2 m-wrap' name='new_gender_<?php echo $i; ?>[]' value='B'/>
											<input type='text' class='range_field span2 m-wrap agerange_l_<?php echo $i; ?>' name='new_agerange_l_<?php echo $i; ?>[]' id='new_agerange_l_<?php echo $i; ?>[]' value='0' /> :
											<input type='text' class='range_field span2 m-wrap agerange_u_<?php echo $i; ?>' name='new_agerange_u_<?php echo $i; ?>[]' id='new_agerange_u_<?php echo $i; ?>[]' value='100' />
																
											<br>
								</span>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo LangUtil::$generalTerms['RANGE']; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Gender &nbsp;&nbsp;Age_Range
								<br>
											<small><a href="javascript:add_new_range_field('<?php echo $i; ?>', 0);"><?php echo LangUtil::$generalTerms['ADDANOTHER']; ?> &raquo;</a></small>
									<br><br>
								</div>
								</span>	
								
								<span id='new_alpha_new_<?php echo $i; ?>' style='display:none' class='new_values_section_new_<?php echo $i; ?>'>
									<span id='new_alpha_list_<?php echo $i; ?>'>
										<input type='text' class='range_field span2 m-wrap' name='new_alpharange_<?php echo $i; ?>[]' value='' /> /
										<input type='text' class='range_field span2 m-wrap' name='new_alpharange_<?php echo $i; ?>[]' value='' />
									</span>
									<br>
									<small><a href="javascript:add_new_option_field('<?php echo $i; ?>');"><?php echo LangUtil::$generalTerms['ADDANOTHER']; ?> &raquo;</a></small>
								</span>
								<span id='new_autocomplete_new_<?php echo $i; ?>' style='display:none' class='new_values_section_new_<?php echo $i; ?>'>
									<span id='new_autocomplete_list_new_<?php echo $i; ?>'>
										<input type='text' class='span4 m-wrap' name='new_autocomplete_<?php echo $i; ?>[]' value='' /><br>
										<input type='text' class='span4 m-wrap' name='new_autocomplete_<?php echo $i; ?>[]' value='' /><br>
									</span>
									<small><a href="javascript:add_new_autocomplete_field('<?php echo $i; ?>');"><?php echo LangUtil::$generalTerms['ADDANOTHER']; ?> &raquo;</a></small>
								</span>
                                                                <span id='new_freetext_new_<?php echo $i; ?>' style='display:none' class='new_values_section_new_<?php echo $i; ?>'>
									<span id='new_freetext_list_new_<?php echo $i; ?>'>
                                                                    		<?php echo "<small>Will appear as a text box for result entry</small>"?>
									</span>
								</span>
								<?php
								echo "</td>";
								echo "<td id='unit_$i'>";
								echo "<input type='text' class='span10 m-wrap' name='new_unit[]' value='' />";
								echo "</td>";
								echo "</tr>";
                                                                
                                                                
                                        # submeasures
                                        
                                        $max_num_submeasures = 5;
                                        $us = '_';
                                    for($y = 1; $y <= $max_num_submeasures; $y += 1)
                                    {
                                            echo "<tr valign='top' id='smrow_$i$us$y' style='display:none;'";
                                            
                                            echo ">";
                                            
                                            echo "<td align='center'>";
							//	echo "<input type=checkbox name='delete_".$curr_measure->name."'  />";
								echo "</td>";
                                            
                                            echo "<td>";
                                            ?>
                                            Sub: <input type='text' class='span12 m-wrap' name='submeasure[<?php echo $i; ?>][]' value='' />
                                            <?php
                                            echo "</td>";
                                            echo "<td>";
                                            ?>
                                            <select class='range_select span10 m-wrap' id='<?php echo $i.$us.$y; ?>' name='smtype[<?php echo $i; ?>][]'>
                                                    <option value='<?php echo Measure::$RANGE_NUMERIC; ?>'><?php echo LangUtil::$generalTerms['RANGE_NUMERIC']; ?></option>
                                                    <option value='<?php echo Measure::$RANGE_OPTIONS; ?>'><?php echo LangUtil::$generalTerms['RANGE_ALPHANUM']; ?></option>
                                                    <option value='<?php echo Measure::$RANGE_AUTOCOMPLETE; ?>'><?php echo LangUtil::$generalTerms['RANGE_AUTOCOMPLETE']; ?></option>
                                                    <option value='<?php echo Measure::$RANGE_FREETEXT; ?>'><?php echo "Free Text" ?></option>

                                            </select>
                                            <?php
                                            echo "</td>";
                                            echo "<td>";
                                            ?>
                                            <span id='val_<?php echo $i.$us.$y; ?>' class='values_section_<?php echo $i.$us.$y; ?>'>
                                                    <span id='numeric_<?php echo $i.$us.$y; ?>'>

                                                            <input type='text' class='range_field span2 m-wrap' name='range_l_<?php echo $i.$us.$y; ?>[]' value='' /> :
                                                            <input type='text' class='range_field span2 m-wrap' name='range_u_<?php echo $i.$us.$y; ?>[]' value=''/>
                                                            <input type='text' class='range_field span2 m-wrap' name='gender_<?php echo $i.$us.$y; ?>[]' value='B'/>
                                                            <input type='text' class='range_field span2 m-wrap'  name='agerange_l_<?php echo $i.$us.$y; ?>[]' id='agerange_l_<?php echo $i.$us.$y; ?>[]' value='0' /> :
                                                            <input type='text' class='range_field span2 m-wrap' name='agerange_u_<?php echo $i.$us.$y; ?>[]' id='agerange_u_<?php echo $i.$us.$y; ?>[]' value='100' />
                                                            <br>
                                                    </span>
                                                    &nbsp;&nbsp;&nbsp;&nbsp;<?php echo LangUtil::$generalTerms['RANGE']; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Gender &nbsp;&nbsp;Age_Range
                                                            <br>
                                                    <small><a href="javascript:add_new_range_field('<?php echo $i.$us.$y; ?>', '0');"><?php echo LangUtil::$generalTerms['ADDANOTHER']; ?> &raquo;</a></small>
                                                    <br><br>
                                            </span>
                                            <span id='alpha_<?php echo $i.$us.$y; ?>' style='display:none' class='values_section_<?php echo $i.$us.$y; ?>'>
                                                    <span id='alpha_list_<?php echo $i.$us.$y; ?>'>
                                                            <input type='text' class='range_field span2 m-wrap' name='alpharange_<?php echo $i.$us.$y; ?>[]' value='' /> /
                                                            <input type='text' class='range_field span2 m-wrap' name='alpharange_<?php echo $i.$us.$y; ?>[]' value='' />
                                                    </span>
                                                    <br>
                                                    <small><a href="javascript:add_new_option_field('<?php echo $i.$us.$y; ?>');"><?php echo LangUtil::$generalTerms['ADDANOTHER']; ?> &raquo;</a></small>
                                            </span>
                                            <span id='autocomplete_<?php echo $i.$us.$y; ?>' style='display:none' class='values_section_<?php echo $i.$us.$y; ?>'>
                                                    <span id='autocomplete_list_<?php echo $i.$us.$y; ?>'>
                                                            <input type='text' class='uniform_width span6 m-wrap' name='autocomplete_<?php echo $i.$us.$y; ?>[]' value='' /><br>
                                                            <input type='text' class='uniform_width span6 m-wrap' name='autocomplete_<?php echo $i.$us.$y; ?>[]' value='' /><br>
                                                    </span>
                                                    <small><a href="javascript:add_new_autocomplete_field('<?php echo $i.$us.$y; ?>');"><?php echo LangUtil::$generalTerms['ADDANOTHER']; ?> &raquo;</a></small>
                                            </span>
                                            <span id='freetext_<?php echo $i.$us.$y; ?>' style='display:none' class='values_section_<?php echo $i.$us.$y; ?>'>
                                                    <span id='freeetext_list_<?php echo $i.$us.$y; ?>'>
                                                            <?php echo "<small>Will appear as a text box for result entry</small>"?>
                                                    </span>
                                            </span>
                                            <?php
                                            echo "</td>";
                                            echo "<td id='unit_$i$us$y'>";
                                            ?>
                                            <input type='text' class='span10 m-wrap' name='sunit[<?php echo $i; ?>][]' value='' />
                                            <?php
                                            echo "</td>";
                                            echo "</tr>";
                                            ?>
                                            <div id='new_subentries' style='display:none;'>
                                            </div>
                                            

                                            
                                        <?php
                                        }// end of submeasures for each measure
                                        ?>
                                        
                                      <?php  
							}// end of new measures
						?>
						</table>
						<a id='new_measure_link' href='javascript:add_new_measure();'><?php echo LangUtil::$generalTerms['ADDANOTHER']; ?> &raquo;</a>
					</td>
				</tr>
				<?php
			?>
			<tr valign='top'>
				<td><?php echo "Include existing measures? (Select below)" ?>?</td>
				<td><input type='checkbox' name='ispanel' id='ispanel'></input></td>
			</tr>
			<tr valign='top' class='panel_row'>
				<td>
					<?php echo LangUtil::$generalTerms['MEASURES']; ?> <?php $page_elems->getAsterisk(); ?>
				</td>
				<td>
					<?php $page_elems->getMeasureCheckboxes(); ?>
				</td>
			</tr>
			
			<tr valign='top'>
				<td><?php echo LangUtil::$generalTerms['COMPATIBLE_SPECIMENS']; ?><?php $page_elems->getAsterisk(); ?>  [<a href='#specimen_help' rel='facebox'>?</a>] </td>
				<td>
					<?php $page_elems->getSpecimenTypeCheckboxes($lab_config_id, false,$test_type->testTypeId); ?>
					<br>
				</td>
			</tr>
			
			<tr valign='top'>
				<td>Hide Patient Name in Report </td>
				<td>
					<select name="hidePatientName" class='span6 m-wrap'>
						<?php if ( $test_type->hidePatientName == 0 ) { ?>
							<option value="0" selected>No</option>
							<option value="1">Yes</option>
						<?php } else { ?>
							<option value="0">No</option>
							<option value="1" selected>Yes</option>
						<?php } ?>
					</select>
				</td>		
			</tr>

			<tr valign='top'>
				<td>Prevalence Threshold </td>
				<td><input id='prevalenceThreshold' class='span6 m-wrap' name='prevalenceThreshold' type='text' size='3' maxLength='3' onkeypress="return isInputNumber(event);" 
				value=<?php if($test_type->prevalenceThreshold==null) {echo 0;} else echo $test_type->prevalenceThreshold; ?> />
					<span id='prevalenceThresholdError' class='error_string' style='display:none;'>
						<?php echo "Threshold Value cannot be more than 100"; ?>
					</span>
				</td>
			</tr>

			<tr valign='top'>
				<td>Target TAT</td>
				<td><input id='targetTat' name='targetTat' class='span6 m-wrap' type='text' size='3' maxLength='3' onkeypress="return isInputNumber(event);" 
				value=<?php if($test_type->targetTat==null) {echo 0;} else echo $test_type->targetTat; ?> />
				</td>
			</tr>

			<tr valign='top'>
                <td>Show culture worksheet?</td>
                <td><input id='cultureWorksheet' name='cultWork' class='span6 m-wrap' onclick="toggle('.drugsClass', this)" type='checkbox' <?php if($test_type->showCultureWorkSheet) echo 'checked' ?> > </td>
            </tr>

            <!-- Show Drug Checkboxes -->
            <tr valign='top' class='drugsClass' style="display:none;">
                <td><?php echo LangUtil::$generalTerms['COMPATIBLE_DRUGS']; ?><?php $page_elems->getAsterisk(); ?>  [<a href='#drugs_help' rel='facebox'>?</a>] </td>
                <td>
                    <?php $page_elems->getDrugsCheckboxes($lab_config_id, false,$test_type->testTypeId); ?>
                    <br>
                </td>
            </tr>
            <!-- End Drug Checkboxes -->

			<tr valign='top' <?php is_billing_enabled($_SESSION['lab_config_id']) ? print("") : print("style='display:none;'") ?>>
				<td>Cost to Patient</td>

				<input type="hidden" class='span6 m-wrap' name='cost_to_patient_dollars_old' value='<?php echo $cost_to_patient_dollars; ?>' />
				<input type="hidden" class='span6 m-wrap' name='cost_to_patient_cents_old' value='<?php echo $cost_to_patient_cents; ?>' />

				<td>
					<input id='cost_to_patient_dollars' class='span4 m-wrap' name='cost_to_patient_dollars' type='text' size='4' maxLength='4' onkeypress="return isInputNumber(event);" value='<?php echo $cost_to_patient_dollars; ?>' />
					<?php echo get_currency_delimiter_from_lab_config_settings(); ?>

					<input id='cost_to_patient_dollars' class='span4 m-wrap' name='cost_to_patient_cents' type='text' size='2' maxLength='2' onkeypress="return isInputNumber(event);" value='<?php echo get_cents_as_whole_number($cost_to_patient_cents); ?>' />
					<?php echo get_currency_type_from_lab_config_settings(); ?>
				</td>
			</tr>

			<tr valign='top'>
				<td></td>
				<td>
					<br><br>
					<input type='button' class="btn green" value='<?php echo LangUtil::$generalTerms['CMD_SUBMIT']; ?>' onclick='check_input()'></input>
					&nbsp;&nbsp;&nbsp;
					<a class="btn" href='catalog.php?show_t=1'><?php echo LangUtil::$generalTerms['CMD_CANCEL']; ?></a>
					&nbsp;&nbsp;&nbsp;
					<span id='update_ttype_progress' style='display:none;'>
						<?php $page_elems->getProgressSpinner(LangUtil::$generalTerms['CMD_SUBMITTING']); ?>
					</span>
				</td>
			</tr>
		</tbody>
	</table>
</form>
</div>
<div id='measures_help' style='display:none'>
<small>
<b><?php echo LangUtil::$generalTerms['MEASURES']; ?></b>
<br><br>
Valid result ranges can be entered for each measure in the 'range' field. 
<br><br>
<u>Numeric ranges</u> can be specified as 'lower:upper'. 
For e.g., if the valid range lies between 0 and 1000, please enter '0:1000'.By default the gender is B for both and age_range is 0:100. You can modify any of the fields based on the measure. 
<br><br>
<u>Alphanumeric values</u> can be specified as 'value1/value2/value3'. 
For e.g., if test results can be either one from 'P','N' or 'D', please enter 'P/N/D'.
<br><br>
<u>Autocomplete</u> can be specified in the textboxes provided. They will be prompted while inputting the results.
<br><br>
<u>Removing</u> a measure can be done by using either selecting the delete box or else by simply leaving the measure name empty. For removing a range for particular measure leave the range box empty.
</small>
</div>
<div id='clinical_help' style='display:none'>
<small>
<u>Clinical Data </u> can be entered either in the table or text on both forms. If it is not requried please leave it blank.
</small>
</div>
<div id='specimen_help' style='display:none'>
<small>
More than one <u>Compatible Specimen </u> can be selected/deselected at a time. But atleast one speciemen has to be selected. In case a new added specimen is missing in the list then go to lab configuration to set it.
</small>
</div>
<div id='
_help' style='display:none'>
<small>
<u>Unit</u>
Inorder to represent ranges like 2mins30secs please enter the range as 2.30 and in the unit add as min,secs.<br>
To represent data like 56^5-65^5 ml add the range as 56-65 and the unit as 5:ml.<br><br>
<u>Default Value</u>
It is used for test which are alphanumeric and autocomplete. The default value for that measure can be recorded in this section.
</small>
</div>
		</div>
		</div>		
	</div>
	</div>
			
<?php include("includes/scripts.php");
?>
<script type='text/javascript'>
var num_measures = 1;
var num_submeasures = new Array(100);
var len = 100;
 while (--len >= 0) {
        num_submeasures[len] = 0;
    }

var num_ranges = new Array();
for(var k = 0; k < 100; k++)
{
	num_ranges[k] = 0;
}

$(document).ready(function() {
	$('#new_category_textbox').hide();
	$('#new_entries').show();
	$('.panel_row').hide();
	$('#ispanel').change( function() {
		toggle_panel();
	});
	$('.range_select').change( function() {
		toggle_range_type(this);
	});
});

function toggle_range_type(select_elem)
{
	var elem_id = select_elem.id;
	$('.values_section_'+elem_id).hide();
	if(select_elem.value == <?php echo Measure::$RANGE_OPTIONS; ?>)
		$('#alpha_'+elem_id).show();	
	else if(select_elem.value == <?php echo Measure::$RANGE_NUMERIC; ?>)
		$('#val_'+elem_id).show();
	else if(select_elem.value == <?php echo Measure::$RANGE_AUTOCOMPLETE; ?>)
		$('#autocomplete_'+elem_id).show();
        else if(select_elem.value == <?php echo Measure::$RANGE_FREETEXT; ?>)
		$('#freetext_'+elem_id).show();
                
}



function add_option_field(mrow_num)
{
	var html_code = " / <input type='text' class='range_field' name='alpharange_"+mrow_num+"[]' value='' />";
	$('#alpha_list_'+mrow_num).append(html_code);
}

function add_autocomplete_field(mrow_num)
{
	var html_code = "<input type='text' class='uniform_width' name='autocomplete_"+mrow_num+"[]' value='' /><br>";
	$('#autocomplete_list_'+mrow_num).append(html_code);
}

function add_freetext_field(mrow_num)
{
	var html_code = "<input type='text' class='uniform_width' name='freetext_"+mrow_num+"[]' value='' /><br>";
	$('#freetext_list_'+mrow_num).append(html_code);
}


function add_range_field(mrow_num)
{

		num_ranges[mrow_num]++;

	var num_row = num_ranges[mrow_num];
	
//		var map=map_offset-1;									
	var html_code = "<input type='text' class='range_field' name='range_l_"+mrow_num+"[]' value='' /> : <input type='text' class='range_field' name='range_u_"+mrow_num+"[]' value='' /> <input type='text' class='range_field' name='gender_"+mrow_num+"[]' value='B'/> <input type='text' class='range_field agerange_l_"+mrow_num+"' name='agerange_l_"+mrow_num+"[]' id='agerange_l_"+mrow_num+"[]' value='0' /> : <input type='text' class='range_field agerange_u_"+mrow_num+"' name='agerange_u_"+mrow_num+"[]' id='agerange_u_"+mrow_num+"[]' value='100' /> <br>";
	$('#numeric_'+mrow_num).append(html_code);
}

function toggle_panel()
{
	var checkbox_val = $('#ispanel').attr("checked");
	if(checkbox_val == true)
	{
		$('.panel_row').show();
		$('.nonpanel_row').hide();
	}
	else
	{
		$('.panel_row').hide();
		$('.nonpanel_row').show();
	}
}

function show_new_measure_elems()
{
	$('#new_measure_list').show();
	$('#new_entries').show();
	$('#new_measure_link').show();
}

function check_if_new_category(select_obj)
{
	var value = $('#cat_code').val();
	if(value == -1)
	{
		$('#new_category').show();
	}
	else
	{
		$('#new_category_textbox').val("");
		$('#new_category').hide();
	}
}

function add_new_measure()
{
	num_measures++;
	$('#mrow_'+num_measures).show();
}

function add_new_submeasure(mrow_num)
{
	num_submeasures[mrow_num]++;
	$('#smrow_'+mrow_num+'_'+num_submeasures[mrow_num]).show();
}


 function addRowToTable()
{
  var tbl = document.getElementById('tblSample');
  var lastRow = tbl.rows.length;
  // if there's no header row in the table, then iteration = lastRow + 1
  var iteration = lastRow;
  var row = tbl.insertRow(lastRow);
  // right cell
  var cellRight = row.insertCell(0);
  
  var el = document.createElement('input');
  el.type = 'text';
  el.name = 'txtRow' + iteration+'1';
  el.id = 'txtRow' + iteration+'1';
  el.size = 40;
  cellRight.appendChild(el);
  // select cell
  var cellRightSel = row.insertCell(1);
  var sel = document.createElement('input');
  sel.type = 'text';
  sel.name = 'txtRow' + iteration+'2';
  sel.id = 'txtRow' + iteration+'2';
  sel.size = 40;
  cellRightSel.appendChild(sel);
}

function removeRowFromTable()
{
  var tbl = document.getElementById('tblSample');
  var lastRow = tbl.rows.length;
  if (lastRow > 2) tbl.deleteRow(lastRow - 1);
}

  
 function addData(list)
{
var dat=list.split('###');
var name=dat[0].split(',');
var valu =dat[1].split(',');
$('#extra').show();
$('#tblSample1').show();
for(var i=1; i<name.length+1;i++)
{
$('#txtRow'+i+'1').attr("value",name[i-1]);
$('#txtRow'+i+'2').attr("value",valu[i-1]);
addRowToTable();
}
removeRowFromTable();
}

 function addTable()
 {
if($('#extra').is(":visible")==true)
{
$('#extra').hide();
$('#tblSample1').hide();
$('#text').show();

}
	else
	{
	$('#tblSample1').show();
$('#extra').show();
$('#text').hide();
	}
}



function check_input()
{
	var test_name = $('#test_name').attr("value");
	if(test_name == "")
	{
		alert("<?php echo LangUtil::$pageTerms['TIPS_MISSING_TESTNAME']; ?>");
		return;
	}
	var cat_code = $('#cat_code').attr("value");
	var new_cat_name = $('#new_category_textbox').attr("value");
	if(cat_code == -1 && new_cat_name == "")
	{
		alert("<?php echo LangUtil::$pageTerms['TIPS_MISSING_CATNAME']; ?>");
		return;
	}
		var measure_elems = $("input[name='measure[]']");
		var range_type_elems = $("select[name='mtype[]']");
		var measure_entered = false;
		for(var j = 0; j < measure_elems.length; j++)
		{
			if(measure_elems[j].value.trim() != "")
			{
				measure_entered = true;
				if(range_type_elems[j].value == <?php echo Measure::$RANGE_NUMERIC; ?>)
				{
					// Check numeric ranges
					// Check all age ranges specified
					var range_l_elems = $("input[name='range_l_"+(j+1)+"[]']");
					var range_u_elems = $("input[name='range_u_"+(j+1)+"[]']");
					for(var k = 0; k < range_l_elems.length; k++)
					{
						var range_l = range_l_elems[k].value;
						var range_u = range_u_elems[k].value;
						if(range_l.trim()=="" && range_u.trim()=="")
						{
							alert("If you do not want to add new measure then please delete the name");
							return;
						}
						if(isNaN(range_l))
						{
							
							alert("Lower Range value should be numeric: Not '"+range_l+"'!");
							return;
						}
						if(isNaN(range_u))
						{
							
							alert("Upper Range value should be numeric: Not '"+range_u+"'!");
							return;
						}
						if((range_l.trim()== "")&&(isNaN(range_u)==false))
						{
						
							alert("Lower bound cannot be blank");
							return;
						}
						if((range_u.trim()== "")&&(isNaN(range_l)==false))
						{
						
							alert("Upper bound cannot be blank");
							return;
						}
						if((range_u.trim()-range_l.trim())<=0)
						{
							alert("Upper bound cannot be less than or equal to lower bound");
							return;
						}
						if($("#agerange_l_"+(j+1)+"_"+k).is(":disabled") == true)
						{
							continue;
						}
						var lower_value = $("#agerange_l_"+(j+1)+"_"+k).attr("value");
						var upper_value = $("#agerange_u_"+(j+1)+"_"+k).attr("value");
						if(lower_value == undefined || upper_value == undefined)
						{
							continue;
						}
						if(lower_value.trim() == "" && upper_value.trim() == "")
						{
							continue;
						}
						else if(lower_value.trim() == "" || upper_value.trim() == "")
						{
							alert("<?php echo LangUtil::$generalTerms['INVALID']." ".LangUtil::$generalTerms['AGE']; ?>: '"+lower_value+"':'"+upper_value+"'");
						    return;
						}
						else if(isNaN(lower_value))
						{
							alert("<?php echo LangUtil::$generalTerms['INVALID']." ".LangUtil::$generalTerms['AGE']; ?>: '"+lower_value+"'");
							return;
						}
						else if(isNaN(upper_value))
						{
							alert("<?php echo LangUtil::$generalTerms['INVALID']." ".LangUtil::$generalTerms['AGE']; ?>: '"+upper_value+"'");
							return;
						}
						else if((upper_value.trim()-lower_value.trim())<=0)
						{
							alert("Age range cannot be negative.");
							return;
						}
					}				
				}
			
				else if(range_type_elems[j].value == <?php echo Measure::$RANGE_OPTIONS; ?>)
				{
					//Check option values
					var option_elems = $("input[name='alpharange_"+(j+1)+"[]']");
					var option_exist = false;
					for(var k = 0; k < option_elems.length; k++)
					{
						var option_val = option_elems[k].value;
						if(option_val.trim() != "")
						{
							option_exist = true;
							break;
						}
					}
					if(option_exist == false)
					{
						alert("<?php echo LangUtil::$generalTerms['INVALID']." ".LangUtil::$generalTerms['DROPDOWN']; ?>");
						return;
					}
				}
				else if(range_type_elems[j].value == <?php echo Measure::$RANGE_AUTOCOMPLETE; ?>)
				{
					//Check autocomplete values
					var option_elems = $("input[name='autocomplete_"+(j+1)+"[]']");
					var option_exist = false;
					for(var k = 0; k < option_elems.length; k++)
					{
						var option_val = option_elems[k].value;
						if(option_val.trim() != "")
						{
							option_exist = true;
							break;
						}
					}
					if(option_exist == false)
					{
						alert("<?php echo LangUtil::$generalTerms['INVALID']." ".LangUtil::$generalTerms['RANGE_AUTOCOMPLETE']; ?>");
						return;
					}
				}
			}			
		}
		
			var mtype_entries = $('.m_entry');
			var mtype_selected = false;
			for(var i = 0; i < mtype_entries.length; i++)
			{
				if(mtype_entries[i].checked)
				{
					mtype_selected = true;
					break;
				}
			}
			if(mtype_selected == false && measure_entered == false)
			{
				alert("<?php echo LangUtil::$pageTerms['TIPS_MISSING_SELECTEDMEASURES']; ?>");
				return;
			}
	
	var stype_entries = $('.stype_entry');
	var stype_selected = false;
	for(var i = 0; i < stype_entries.length; i++)
	{
		if(stype_entries[i].checked)
		{
			stype_selected = true;
			break;
		}
	}
	if(stype_selected == false)
	{
		alert("<?php echo LangUtil::$pageTerms['TIPS_MISSING_SELECTEDSPECIMEN']; ?>");
		return;
	}
	$('#new_test_form').submit();
}

function toggle_agerange(measure_num, row_num)
{
	var field_id = "agerange_l_"+measure_num+"_"+row_num;
	if($('#'+field_id).is(":disabled") == false)
	{
		$('#'+field_id).attr("disabled", "true");
	}
	else
	{
		$('#'+field_id).removeAttr("disabled");
	}
	field_id = "agerange_u_"+measure_num+"_"+row_num;
	if($('#'+field_id).is(":disabled") == false)
	{
		$('#'+field_id).attr("disabled", "true");
	}
	else
	{
		$('#'+field_id).removeAttr("disabled");
	}	
}

function toggle(className, obj) {
    var $input = $(obj);
    if ($input.prop('checked')) $(className).show();
    else $(className).hide();
}

function isInputNumber(evt) {
	var characterCode = (evt.which) ? evt.which : event.keyCode

	if (characterCode > 31 && (characterCode < 48 || characterCode > 57))
		return false;

	return true;
}

</script>
<?php include("includes/footer.php"); ?>
