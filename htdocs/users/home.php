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
				
                <div class="portlet box blue">
                    <div class="portlet-title">
                        <h4><i class="icon-reorder"></i>Kapsabet District Hospital Laboratory</h4>
                        <div class="tools">
                        <a href="javascript:;" class="collapse"></a>
                        <a href="javascript:;" class="reload"></a>
                        </div>
                    </div>
                    <div class="portlet-body form" style="height: auto;">
				
                    <?php #$page_elems->getSideTip(LangUtil::getGeneralTerm("TIPS"), $profile_tip); ?>
                    <!-- DASH BOARD -->
                    <div class="row-fluid">
									<div class="span12">
										<!--BEGIN TABS-->
										<div class="tabbable tabbable-custom">
											<ul class="nav nav-tabs">
												<li class="active"><a href="#tab_1_1" data-toggle="tab">BLIS Launch</a></li>
												<li><a href="#tab_1_2" data-toggle="tab">Organizational Chart</a></li>
                                                <li><a href="#tab_1_3" data-toggle="tab">Laboratory Master Calendar</a></li>
												<li><a href="#tab_1_4" data-toggle="tab">Wall of Fame/Shame</a></li>
											</ul>
											<div class="tab-content">
												<div class="tab-pane active" id="tab_1_1">
													<div class="slider-wrapper theme-default">
            <div id="slider" class="nivoSlider">
                <img src="nivo/images/kdh/1.JPG" data-thumb="nivo/images/kdh/1.JPG" alt="" title="BLIS Launch photo."/>
                <img src="nivo/images/kdh/2.JPG" data-thumb="nivo/images/kdh/2.JPG" alt="" title="Specimen ready for test." />
                <img src="nivo/images/kdh/3.JPG" data-thumb="nivo/images/kdh/3.JPG" alt="" data-transition="slideInLeft" title="Full Haemogram machine - CELLTAC F."/>
                <img src="nivo/images/kdh/4.JPG" data-thumb="nivo/images/kdh/4.JPG" alt="" title="Laboratory staff during the BLIS Launch." />
                <img src="nivo/images/kdh/5.JPG" data-thumb="nivo/images/kdh/5.JPG" alt="" title="Specimen in preparation for testing." />
                <img src="nivo/images/kdh/6.JPG" data-thumb="nivo/images/kdh/6.JPG" alt="" title="Humalyzer 2000 machine at the Laboratory." />
                <img src="nivo/images/kdh/7.JPG" data-thumb="nivo/images/kdh/7.JPG" alt="" title="BLIS Launch Photoshoot session." />
                <img src="nivo/images/kdh/8.JPG" data-thumb="nivo/images/kdh/8.JPG" alt="" title="Vincent Tieni processing tests using the Celltac F machine." />
                
                <img src="nivo/images/kdh/9.JPG" data-thumb="nivo/images/kdh/9.JPG" alt="" title="BLIS Launch photo."/>
                <img src="nivo/images/kdh/10.JPG" data-thumb="nivo/images/kdh/10.JPG" alt="" title="Specimen ready for test." />
                <img src="nivo/images/kdh/11.JPG" data-thumb="nivo/images/kdh/11.JPG" alt="" data-transition="slideInLeft" title="Full Haemogram machine - CELLTAC F."/>
                <img src="nivo/images/kdh/12.JPG" data-thumb="nivo/images/kdh/12.JPG" alt="" title="Laboratory staff during the BLIS Launch." />
                <img src="nivo/images/kdh/13.JPG" data-thumb="nivo/images/kdh/13.JPG" alt="" title="Specimen in preparation for testing." />
                <img src="nivo/images/kdh/14.JPG" data-thumb="nivo/images/kdh/14.JPG" alt="" title="Humalyzer 2000 machine at the Laboratory." />
                <img src="nivo/images/kdh/15.JPG" data-thumb="nivo/images/kdh/15.JPG" alt="" title="BLIS Launch Photoshoot session." />
                <img src="nivo/images/kdh/16.JPG" data-thumb="nivo/images/kdh/16.JPG" alt="" title="Vincent Tieni processing tests using the Celltac F machine." />
                
                <img src="nivo/images/kdh/17.JPG" data-thumb="nivo/images/kdh/17.JPG" alt="" title="Specimen in preparation for testing." />
                <img src="nivo/images/kdh/18.JPG" data-thumb="nivo/images/kdh/18.JPG" alt="" title="Humalyzer 2000 machine at the Laboratory." />
                <img src="nivo/images/kdh/19.JPG" data-thumb="nivo/images/kdh/19.JPG" alt="" title="BLIS Launch Photoshoot session." />
                <img src="nivo/images/kdh/20.JPG" data-thumb="nivo/images/kdh/20.JPG" alt="" title="Vincent Tieni processing tests using the Celltac F machine." />
            </div>
        </div>
												</div>
												<div class="tab-pane" id="tab_1_2">
													<p>
												    <img src="logos/kdh_organogram_transparent.png" height="600px;" alt="Kapsabet District Hospital Organogram" /> </p>
												</div>
												<div class="tab-pane" id="tab_1_3">
													
													<p>
													Laboratory Master Calendar to be here...	
													</p>
												</div>
                                                <div class="tab-pane" id="tab_1_4">
													
													<p>
													Laboratory wall of fame/shame...	
													</p>
												</div>
											</div>
										</div>
										<!--END TABS-->
									</div>
									
								</div>
                    </div>
                </div>
                    <?php
                    include("includes/scripts.php");
                    $script_elems->enableLatencyRecord();
                    $script_elems->enableDatePicker();
					$script_elems->enableNivoSlider();
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