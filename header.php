<?php
	/* debug
	if( isset( $_SESSION ) ){
		print "<pre>";
		print_r( $_SESSION );
		print "</pre>";
	}

	if( isset( $_POST ) ){
		print "<pre>";
		print_r( $_POST );
		print "</pre>";
	}
	*/
?>
<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<meta name="description" content="神戸市にあるビジネスホテルです">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="robots" content="noindex,nofollow">
<meta name="google" content="nositelinkssearchbox" />
<title><?php echo $title; ?>ホテルニュー神戸</title>
<link rel="stylesheet" href="css/reset.css">
<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
</head>
<body>
	<div id="wrapper">
		<header>
			<div id="headerInner">
				<h1>ホテルニュー神戸</h1>
				<div id="headerInnerRight">
					<p><a href="tel:078-381-9820">TEL:078-381-9820</a></p>
<?php
	if( isset( $_SESSION["userId"] ) ){
print <<< HTML
					<p>{$_SESSION["userName"]}としてログイン中<a href='login.php' style="font-size:1em; text-decoration:underline; color:#00f;">ログアウト</a></p>
HTML;
	}
	else{
print <<< HTML
					<p><button type="button" onClick="document.location='login.php'">ログイン</button></p>
HTML;
	}
?>
				</div><!--/headerInnerRight-->
			</div><!-- #headerInner -->
			<nav id="gNavi">
				<ul>
					<li><a href="index.php">ホーム</a></li>
					<li><a href="#">宿泊プラン</a></li>
					<li><a href="#">館内案内</a></li>
					<li><a href="#">館内施設</a></li>
				</ul>
			</nav>
			<img class="mainVisual" src="images/mainVisualEdited_ver2.jpg" alt="">
		</header>
