<?php
	session_start();

	if( isset( $_POST["feePerPerson"] ) ){
		/* POSTの変数をセッション・変数に格納し、初期化 */
		$_SESSION["roomGradeId"] = $_POST["roomGradeId"];
		$feePerPerson = $_POST["feePerPerson"];
		$_POST = array();
	}

	if( isset( $_SESSION["feePerPerson"] ) ){
		$feePerPerson = $_SESSION["feePerPerson"];
		unset( $_SESSION["feePerPerson"] );
	}

	/* 日付変換 */
	$checkInDate = $_SESSION["checkInDate"];
	$checkInDateconverted = date( 'Y年n月j日', strtotime( $checkInDate ) );
	$checkOutDate = $_SESSION["checkOutDate"];
	$checkOutDateconverted = date( 'Y年n月j日', strtotime( $checkOutDate ) );

	/* 合計料金を計算 */
	$sum = $feePerPerson * $_SESSION["numberOfPeople"] * $_SESSION["numberOfRooms"];

	$title = "予約内容確認 | ";
	require_once( 'header.php' );
?>
<main>
	<div id="reservationConfilm">
		<h2>予約確認</h2>
		<div class="confilmTable">
			<div class="title">
				<p>チェックイン</p>
			</div><!-- .title -->
			<div class="data">
				<p><?php print $checkInDateconverted; ?></p>
			</div><!-- .data -->
			<div class="title">
				<p>チェックアウト</p>
			</div><!-- .title -->
			<div class="data">
				<p><?php print $checkOutDateconverted; ?></p>
			</div><!-- .data -->
			<div class="title">
				<p>大人一人</p>
			</div><!-- .title -->
			<div class="data">
				<p><?php print "¥" . $feePerPerson; ?></p>
			</div><!-- .data -->
			<div class="title">
				<p>部屋</p>
			</div><!-- .title -->
			<div class="data">
				<p><?php print $_SESSION["numberOfPeople"]; ?>人部屋&emsp;<?php print $_SESSION["numberOfRooms"]; ?>部屋</p>
			</div><!-- .data -->
			<div class="title last">
				<p>合計</p>
			</div><!-- .title -->
			<div class="data last">
				<p><?php print "¥" . $sum; ?></p>
			</div><!-- .data -->
		</div><!-- .confilmTable -->
		<button type="button" onClick="document.location='reservationResult.php'">確定</button>
	</div><!-- #reservationConfilm -->
</main>
<?php
	require_once( 'footer.php' );
?>