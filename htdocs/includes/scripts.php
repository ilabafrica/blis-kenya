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
<!-- 			<script src="assets/jqvmap/jqvmap/jquery.vmap.js" type="text/javascript"></script>	 -->
<!-- 			<script src="assets/jqvmap/jqvmap/maps/jquery.vmap.russia.js" type="text/javascript"></script> -->
<!-- 			<script src="assets/jqvmap/jqvmap/maps/jquery.vmap.world.js" type="text/javascript"></script> -->
<!-- 			<script src="assets/jqvmap/jqvmap/maps/jquery.vmap.europe.js" type="text/javascript"></script> -->
<!-- 			<script src="assets/jqvmap/jqvmap/maps/jquery.vmap.germany.js" type="text/javascript"></script> -->
<!-- 			<script src="assets/jqvmap/jqvmap/maps/jquery.vmap.usa.js" type="text/javascript"></script> -->
<!-- 			<script src="assets/jqvmap/jqvmap/data/jquery.vmap.sampledata.js" type="text/javascript"></script>	 -->
<!-- 			<script src="assets/flot/jquery.flot.js"></script> -->
<!-- 			<script src="assets/flot/jquery.flot.resize.js"></script> -->
			<script type="text/javascript" src="assets/gritter/js/jquery.gritter.js"></script>
			<script type="text/javascript" src="assets/uniform/jquery.uniform.min.js"></script>	
			<script type="text/javascript" src="assets/js/jquery.pulsate.min.js"></script>
			<script type="text/javascript" src="assets/bootstrap-daterangepicker/date.js"></script>
			<script type="text/javascript" src="assets/bootstrap-daterangepicker/daterangepicker.js"></script>	
			<script src="assets/js/app.js"></script>				
			<script>
				jQuery(document).ready(function() {		
					App.setPage("index");  // set current page
					App.init(); // init the rest of plugins and elements
				});
			</script>
			<script type="text/javascript">
			  var _gaq = _gaq || [];
			  _gaq.push(['_setAccount', 'UA-37564768-1']);
			  _gaq.push(['_setDomainName', 'keenthemes.com']);
			  _gaq.push(['_setAllowLinker', true]);
			  _gaq.push(['_trackPageview']);
			  (function() {
			    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
			    ga.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'stats.g.doubleclick.net/dc.js';
			    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
			  })();
			</script>
	
<!-- END JAVASCRIPTS -->