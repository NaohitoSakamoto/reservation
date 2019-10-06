<?php
	session_start();

	/* 部屋選択画面から来た場合 */
	if( isset( $_SESSION["checkInDate"] ) ){
		$siteURL = "reservationConfilm.php";
		$site = "予約確認画面";
	}
	else{
		$siteURL = "myPage.php";
		$site = "マイページ";
	}

	/* セッションの値を取得し、初期化 */
	$customerName = $_SESSION["customerName"];
	$customerPhoneNumber = $_SESSION["customerPhoneNumber"];
	$customerEmail = $_SESSION["customerEmail"];
	$customerPassword = $_SESSION["customerPassword"];
	unset( $_SESSION["customerName"] );
	unset( $_SESSION["customerPhoneNumber"] );
	unset( $_SESSION["customerEmail"] );
	unset( $_SESSION["customerPassword"] );

	/* データベースに接続 */
	require_once( "connectDB.php" );

/* ユーザーデータを登録 */
$sql = <<< SQL
	INSERT INTO customer_info (
		customer_name,
		customer_phone_number,
		customer_email,
		customer_password
	)
	VALUES (
		:customerName,
		:customerPhoneNumber,
		:customerEmail,
		:customerPassword
	)
SQL;

	$sth = $dbh->prepare( $sql );
	$sth->bindValue( ":customerName", $customerName, PDO::PARAM_STR );
	$sth->bindValue( ":customerPhoneNumber", $customerPhoneNumber, PDO::PARAM_STR );
	$sth->bindValue( ":customerEmail", $customerEmail, PDO::PARAM_STR );
	$sth->bindValue( ":customerPassword", $customerPassword, PDO::PARAM_STR );
	$sth->execute();
	$result = $sth->rowCount();

	/* 登録に成功した場合 */
	if( $result == 1 ){
/* user_idを取得 */
$sql = <<< SQL
		SELECT
			customer_id
		FROM
			customer_info
		WHERE
			customer_email = :customerEmail;
SQL;
		$sth = $dbh->prepare( $sql );
		$sth->bindValue( ":customerEmail", $customerEmail, PDO::PARAM_STR );
		$sth->execute();
		$userId = $sth->fetchAll( PDO::FETCH_ASSOC );
		
		/* ユーザーIDをセッションに格納 */
		$_SESSION["userId"] = $userId[0]["customer_id"];
	}

	/*データベースの接続を閉じる */
	$dbh = null;
	$sth = null;

	$title = "新規登録結果 | ";
	require_once( 'header.php' );
?>
<main>
	<div id="signUpResult">
<?php
	if( $result == 1 ){
		print "<p>登録成功</p>";
		print '<p><a href="' . $siteURL . '">' . $site . 'へ</a></p>';
	}
	else{
		print "<p>登録失敗</p>";
		print "<p><a href='signUp.php'>新規登録画面へ</a></p>";
	}
?>
	</div><!-- #signUpResult -->
</main>
<?php
	require_once( 'footer.php' );
?>