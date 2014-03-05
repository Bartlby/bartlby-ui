<?
require_once ("xajax/xajax.inc.php");
include "xajax.common.php";

$xajax=$xajax->getJavascript("../xajax");
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Bartlby Mobile</title>

    <!-- Sets initial viewport load and disables zooming  -->
    <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">

    <!-- Makes your prototype chrome-less once bookmarked to your phone's home screen -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    
    <!-- Set a shorter title for iOS6 devices when saved to home screen -->
    <meta name="apple-mobile-web-app-title" content="Ratchet">

    <!-- Set Apple icons for when prototype is saved to home screen -->
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="touch-icons/apple-touch-icon-114x114.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="touch-icons/apple-touch-icon-72x72.png">
    <link rel="apple-touch-icon-precomposed" sizes="57x57" href="touch-icons/apple-touch-icon-57x57.png">

    <!-- Include the compiled Ratchet CSS -->
    <link rel="stylesheet" href="css/ratchet.css">
    <link rel="stylesheet" href="css/ratchet-theme-ios.css">
    

    <!-- Include the compiled Ratchet JS -->
    <script src="js/modals.js"></script>
    <script src="js/popovers.js"></script>
    <script src="js/push.js"></script>
    <script src="js/segmented-controllers.js"></script>
    <script src="js/toggles.js"></script>

    <script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
    <script src="js/btl_mobile.js"></script>

    <script>
    
    </script>
    <?=$xajax?>
    <!-- Intro paragraph styles. Delete once you start using this page -->
    <style type="text/css">
        .welcome {
          line-height: 1.5;
          color: #555;
        }
        .badge-warning {
          background-color: orange;
          color: white;
        }
        .p1 {
          margin: 2px;
        }
    </style>

  </head>
  <body>

    <!-- Make sure all your bars are the first things in your <body> -->
    <header class="bar bar-nav">
      <?=$_MOBILE[NAVBARBUTTONS]?>
      <h1 class="title"><?=$_MOBILE[TITLE]?></h1>
      <?=$_MOBILE[NAVBARBUTTONS1]?>
    </header>

    <!-- Wrap all non-bar HTML in the .content div (this is actually what scrolls) -->
    <?=$_MOBILE[TOP_CONTENT]?>
    <div class="content">
      <?=$_MOBILE[CONTENT]?>
    </div>

    

  </body>
</html>