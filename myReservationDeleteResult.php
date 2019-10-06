<?php
	session_start();

	$checkinDate = $_POST["checkinDate"];
	$roomNum = $_POST["roomNum"];

	/* データベースに接続 */
	require_once( "connectDB.php" );

/* 予約を削除 */
$sql = <<< SQL
	DELETE
	FROM
		room_reservation
	WHERE
		customer_id = :customerId
		AND checkin_date = :checkinDate
SQL;

	$sth = $dbh->prepare( $sql );
	$sth->bindValue( ":customerId", $_SESSION["userId"], PDO::PARAM_STR );
	$sth->bindValue( ":checkinDate", $checkinDate, PDO::PARAM_STR );
	$sth->execute();
	$result = $sth->rowCount();

	$title = "予約削除結果 | ";
	require_once( 'header.php' );
?>
<main>
<?php
	if( $result == 1 ){
		print "<p>登録成功</p>";
		print "<p><a href='myPage.php'>個人画面へ</a></p>";
	}
	else{
		print "<p>登録失敗</p>";
		print "<p><a href='myPage.php'>個人画面へ</a></p>";
	}
?>
</main>
<?php
	require_once( 'footer.php' );
?>