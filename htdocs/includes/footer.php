<?php 
# (c) C4G, Santosh Vempala, Ruban Monu and Amol Shintre
# (c) @iLabAfrica, Roy Rutto, Emaanuel Kitsao, Brian Kiprop
?>
</div>
			<!-- END PAGE CONTAINER-->		
		</div>
		<!-- END PAGE -->
	</div>
	<!-- END CONTAINER -->
	<!-- BEGIN FOOTER -->
	<div class="footer">
			<center>
				<small>
		<a href='userguide/BLIS_User_Guide.pdf' target='_blank' >User Guide |</a>
		<?php
		//Disabling language switching functionality
		/*
		if($_SESSION['locale'] == "en")
		{
			echo "<a href='userguide/BLIS_User_Guide.pdf' target='_blank' >User Guide |</a>";
		}
		else if($_SESSION['locale'] == "fr")
		{
			echo "<a href='userguide/BLIS_User_Guide.pdf' target='_blank' >Guide de l'utilisateur |</a>";
		}
		else
		{
			echo "<a href='userguide/BLIS_User_Guide.pdf' target='_blank'>User Guide |</a>";
		}
         * */
		?>
		
		<a rel='facebox' href='feedback/comments.php?src=<?php echo $_SERVER['PHP_SELF']; ?>'><?php echo "Comments" ?>?</a> |
		C4G BLIS v<?php echo $VERSION; ?> - <?php echo LangUtil::$allTerms["FOOTER_MSG"]; ?>
		<?php
		/*
		if($_SESSION['locale'] !== "en")
		{
			?>
			 | <a href='lang_switch?to=en'><?php echo "English"; ?></a>
			<?php
		}
		else
		{
			echo " | English";
		}
		if($_SESSION['locale'] !== "fr")
		{
			?>
			 | <a href='lang_switch?to=fr'><?php echo "Francais"; ?></a>
			<?php
		}
		else
		{
			echo " | Francais";
		}
		if($_SESSION['locale'] !== "default")
		{
			?>
			 | <a href='lang_switch?to=default'><?php echo "Default"; ?></a>
			<?php
		}
		else
		{
			echo " | Default";
		}
		/*Change Theme: <a href=javascript:changeTheme('Blue');>Blue</a> | <a href=javascript:changeTheme('Grey');>Grey*/
		
		if($TRACK_LOADTIME)
		{
			$endtime = microtime();
			$endarray = explode(" ", $endtime);
			$endtime = $endarray[1] + $endarray[0];
			$totaltime = $endtime - $starttime; 
			$totaltime = round($totaltime,5);
			$page_name = $_SERVER['PHP_SELF'];
			$page_name_parts = explode("/", $page_name);
			$file_name = $page_name_parts[count($page_name_parts)-1].".dat";
			$file_handle = fopen("../feedback/loadtimes/".$file_name, "a");
			fwrite($file_handle, $totaltime."\n");
			fclose($file_handle);
			echo "<br>$file_name This page loaded in $totaltime seconds.";
		}
		if($TRACK_LOADTIMEJS)
		{
			echo "<script type='text/javascript'>alert(new Date().getTime() - t.getTime());</script>";
		}
		?>
		</small>
		</center>
	</div>
	<!-- END FOOTER -->
	<div id="cancel" class="modal hide fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
	  <div class="modal-body">
	    <p>Are you sure you want to cancel? Unsaved changes will be lost</p>
	  </div>
	  <div class="modal-footer">
	    <button type="button" data-dismiss="modal" class="btn" onclick='javascript:cancel_hide()'>No</button>
	    <span id="yes"> </span>
	  </div>
	</div>
</body>
<!-- END BODY -->
</html>
<?php
#
# This is the footer file to display at the end of the file.
# Closes any open database connections, and-
# displays footer so the users know the page is done loading.
#

include("includes/db_close.php");
?>