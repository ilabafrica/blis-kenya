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
                    <div class="portlet-body form" style="height: 400px">
				
                    <?php #$page_elems->getSideTip(LangUtil::getGeneralTerm("TIPS"), $profile_tip); ?>
                    <!-- DASH BOARD -->
                    <div class="row-fluid">
									<div class="span12">
										<!--BEGIN TABS-->
										<div class="tabbable tabbable-custom">
											<ul class="nav nav-tabs">
												<li class="active"><a href="#tab_1_1" data-toggle="tab">Director's Note</a></li>
												<li><a href="#tab_1_2" data-toggle="tab">Organizational Chart</a></li>
                                                <li><a href="#tab_1_3" data-toggle="tab">Laboratory Master Calendar</a></li>
												<li><a href="#tab_1_4" data-toggle="tab">Wall of Fame/Shame</a></li>
											</ul>
											<div class="tab-content">
												<div class="tab-pane active" id="tab_1_1">
													<div class="portlet-body" id="chats">
									<div class="scroller" data-height="343px" data-always-visible="1" data-rail-visible1="1">
										<ul class="chats">
											<li class="in">
												<img class="avatar" alt="" src="../img/avatar.png" />
												<div class="message">
													<span class="arrow"></span>
													<a href="#" class="name"><h4>David Kibet Koech</h4></a>
													<span class="body"><h5>
													Director's message to be here....</h5>
													</span>
												</div>
											</li>
											<li class="out">
												<img class="avatar" alt="" src="../img/avatar.png" />
												<div class="message">
													<span class="arrow"></span>
													<a href="#" class="name">Our Mission</a>
													<span class="body">
													Mission goes here...
													</span>
												</div>
											</li>
											<li class="in">
												<img class="avatar" alt="" src="../img/avatar.png" />
												<div class="message">
													<span class="arrow"></span>
													<a href="#" class="name">Our Vision</a>
													<span class="body">
													Vision goes here...
													</span>
												</div>
											</li>
											
											<li class="out">
												<img class="avatar" alt="" src="../img/avatar.png" />
												<div class="message">
													<span class="arrow"></span>
													<a href="#" class="name">Our Values</a>
													<span class="body">
													Values go here...
													</span>
												</div>
											</li>
											
										</ul>
									</div>
                                    </div>
												</div>
												<div class="tab-pane" id="tab_1_2">
													<p>
												    <img src="../logos/kdh_organogram_transparent.png" height="600px;" alt="Kapsabet District Hospital Organogram" /> </p>
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