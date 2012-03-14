<?php
$iTunes = new COM('iTunes.Application');

if (isset($_GET['play'])) { 
	$iTunes->Play();
}
if (isset($_GET['pause'])) { 
	$iTunes->Pause();
}
if (isset($_GET['next'])) { 
	$iTunes->NextTrack();
}
if (isset($_GET['last'])) { 
	$iTunes->PreviousTrack();
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>iTunes Controller</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="../assets/css/bootstrap.css" rel="stylesheet">
    <style>
      body {
        padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
      }
    </style>
    <link href="../assets/css/bootstrap-responsive.css" rel="stylesheet">

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- Le fav and touch icons -->
    <link rel="shortcut icon" href="../assets/ico/favicon.ico">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="../assets/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="../assets/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="../assets/ico/apple-touch-icon-57-precomposed.png">
  </head>

  <body>

    <div class="navbar navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <a class="brand" href="/">iTunes Controller</a>
        </div>
      </div>
    </div>

    <div class="container">
	<?php
	$nowplaying = $iTunes->CurrentTrack();
	echo ($nowplaying->Name . " by " . $nowplaying->Artist . " from " . $nowplaying->Album . " [" .$nowplaying->Genre() . "]");
	?>
	<p>
	<?php 
	if($iTunes->PlayerState() == "0")
		{
		$state = "play";
		}else{
		$state = "pause";
		}
		?>
		<a href="?last"<button class="btn btn-success">Last</button></a>
		<a href="?<?php echo $state ?>"<button class="btn btn-success"><?php echo ucfirst($state) ?></button></a>
		<a href="?next"<button class="btn btn-success">Next</button></a>
		<input type="range"  min="0" max="100" value="<?php echo $iTunes->SoundVolume(); ?>" onchange="<?php $itunes->SoundVolume = this.value; ?>" step="5"/>

    </div> <!-- /container -->

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="../assets/js/bootstrap.js"></script>
  </body>
</html>
