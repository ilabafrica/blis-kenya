<?php
#Contains HTML for including required stylesheets
#For use in includes/header.php
?>
<link rel="stylesheet" type="text/css" href="css/styles_blue.css">
<link rel="stylesheet" type="text/css" href="css/msg_boxes.css">
<!-- Added bootstrap styling -->

<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
<link href="css/bootstrap-glyphicons.css" rel="stylesheet">
<link href="css/docs.css" rel="stylesheet">
<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]>
	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
 <style>
         html,body
        {
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
         #footer {
        	background-color: #f5f5f5;
     	 }
     	 .footer_message{
     	 	margin-top:25px;
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
         .bs-sidenav{
			border: 1px solid rgb(221, 221, 221);
			border-radius: 4px 4px 4px 4px;
			box-shadow: 0px 1px 1px rgba(0, 0, 0, 0.05);
         	border-color: rgb(66, 139, 202);
         }
         
         .form-signin {
	        max-width: 350px;
	        padding: 19px 29px 29px;
	        margin: 0 auto 20px;
	        background-color: rgb(235, 235, 235);
	        border: 1px solid rgb(221, 221, 221);
	        -webkit-border-radius: 5px;
	           -moz-border-radius: 5px;
	                border-radius: 5px;
	        -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.05);
	           -moz-box-shadow: 0 1px 2px rgba(0,0,0,.05);
	                box-shadow: 0 1px 2px rgba(0,0,0,.05);
	                border-radius: 4px 4px 4px 4px;
			box-shadow: 0px 1px 1px rgba(0, 0, 0, 0.05);
         	border-color: rgb(66, 139, 202);
         	text-align: center ;
      		}
      	.form-signin .form-signin-heading,
      	.form-signin .checkbox {
       	 		margin-bottom: 10px;
      		}
      		.form-signin input[type="text"],
      	.form-signin input[type="password"] {
	        font-size: 16px;
	        height: auto;
	        margin-bottom: 15px;
	        padding: 7px 9px;
      	}
      	
      	.form-signin-heading{
      		text-align: center ;
      	}
      	
      	.dropdown-toggle{
      		height:34px;
      	}
      	
      	.context{
      		top: 30px;
      		margin-bottom: 30px;
      	}
         
</style>