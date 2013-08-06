<?php
#Contains HTML for including required stylesheets
#For use in includes/header.php
?>
<link rel="stylesheet" type="text/css" href="css/styles_blue.css" />
<link rel="stylesheet" type="text/css" href="css/msg_boxes.css" />
<!-- Added bootstrap styling -->

<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
<link href="css/bootstrap-glyphicons.css" rel="stylesheet">
<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]>
	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
 <style>
        body
        {
            padding-top: 70px; /* 60px to make the container go all the way to the bottom of the topbar */
              height: 100%;
        }
        
        .user-buttons{
        	padding-top: 10px;
        }
         /* Wrapper for page content to push down footer */
         #wrap {
           min-height: 100%;
           height: auto !important;
           height: 100%;
           /* Negative indent footer by it's height */
           margin: 0 auto -60px;
         }

         /* Set the fixed height of the footer here */
         #push,
         #footer {
           height: 60px;
         }
         
         /* Lastly, apply responsive CSS fixes as necessary */
         @media (max-width: 767px) {
           #footer {
             margin-left: -20px;
             margin-right: -20px;
             padding-left: 20px;
             padding-right: 20px;
           }
         }
</style>