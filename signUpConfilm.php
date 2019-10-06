<?php
	session_start();

	/* 名前が入力されていなかった場合 */
	if( $_POST["customerName"] == "" ){
		$customerNameError = "<p>名前を入力してください</p>";
	}
	else{
		$customerNameError = "";
	}
	/* 電話番号が入力されていなかった場合 */
	if( $_POST["customerPhoneNumber"] == "" ){
		$customerPhoneNumberError = "<p>電話番号を入力してください</p>";
	}
	else{
		$customerPhoneNumberError = "";
	}
	/* 最初のメールアドレスが入力されていない場合 */
	if( $_POST["customerEmail"] == "" ){
		$customerEmailError = "<p>メールアドレスを入力してください</p>";
	}
	else {
		$customerEmailError = "";
	}
	/* 確認のメールアドレスが入力されていない場合 */
	if( $_POST["customerEmailConfilm"] == "" ){
		$customerEmailConfilmError = "<p>確認メールアドレスを入力してください</p>";
	}
	/* 確認のメールアドレスが最初のメールアドレスと異なる場合 */
	elseif ( $_POST["customerEmail"] != $_POST["customerEmailConfilm"] ) {
		$customerEmailConfilmError = "<p>最初に入力したメールアドレスと異なります</p>";
	}
	else {
		$customerEmailConfilmError = "";
	}
	/* 最初のパスワードが入力されていない場合 */
	if( $_POST["customerPassword"] == "" ){
		$customerPasswordError = "<p>パスワードを入力してください</p>";
	}
	else {
		$customerPasswordError = "";
	}
	/* 確認のパスワードが入力されていない場合 */
	if( $_POST["customerPasswordConfilm"] == "" ){
		$customerPasswordConfilmError = "<p>確認パスワードを入力してください</p>";
	}
	/* 確認のパスワードが最初のパスワードと異なる場合 */
	elseif ( $_POST["customerPassword"] != $_POST["customerPasswordConfilm"] ) {
		$customerPasswordConfilmError = "<p>最初に入力したパスワードと異なります</p>";
	}
	else {
		$customerPasswordConfilmError = "";
	}

	/* データベースに接続 */
	require_once( "connectDB.php" );
		
/* ユーザーデータを取得する */
$sql = <<< SQL
	SELECT
		customer_phone_number,
		customer_email,
		customer_password
	FROM
		customer_info;
SQL;

	$sth = $dbh->prepare( $sql );
	$sth->execute();
	$customerInfo = $sth->fetchAll( PDO::FETCH_ASSOC );

	foreach( $customerInfo as $line ){
		/* 電話番号が重複している場合 */
		if( $_POST["customerPhoneNumber"] == $line["customer_phone_number"] ){
			$customerPhoneNumberError = "<p>既にその電話番号は登録されています</p>";
		}
		/* メールアドレスが重複している場合 */
		if( $_POST["customerEmail"] == $line["customer_email"] ){
			$customerEmailError = "<p>既にそのメールアドレスは登録されています</p>";
		}
	}

	/* 条件文を作成 */
	$condition = ( $customerNameError == "" ) &&
				 ( $customerPhoneNumberError == "" ) &&
				 ( $customerEmailError == "" ) &&
				 ( $customerEmailConfilmError == "" ) &&
				 ( $customerPasswordError == "" ) &&
				 ( $customerPasswordConfilmError == "" );

	/* 正しく入力されていた場合 */
	if( $condition ){
		/* セッションに値を格納 */
		$_SESSION["customerName"] = $_POST["customerName"];
		$_SESSION["customerPhoneNumber"] = $_POST["customerPhoneNumber"];
		$_SESSION["customerEmail"] = $_POST["customerEmail"];
		$_SESSION["customerPassword"] = $_POST["customerPassword"];
		$_POST = array();
	}
	else{
		$_POST = array();
		/* 入力画面に戻って終了 */
		require_once( "signUp.php" );
		exit();
	}

	$title = "新規登録確認 | ";
	require_once( 'header.php' );
?>
<main>
	<div id="signUpConfilm">
<?php
print <<< HTML
		<h2>登録確認</h2>
		<div class="signUpList">
			<div class="title">
				<p>名前</p>
			</div><!-- .title -->
			<div class="data">
				<p>{$_SESSION["customerName"]}</p>
			</div><!-- .data -->
			<div class="title">
				<p>電話番号</p>
			</div><!-- .title -->
			<div class="data">
				<p>{$_SESSION["customerPhoneNumber"]}</p>
			</div><!-- .data -->
			<div class="title">
				<p>メールアドレス</p>
			</div><!-- .title -->
			<div class="data">
				<p>{$_SESSION["customerEmail"]}</p>
			</div><!-- .data -->
			<div class="title">
				<p>パスワード</p>
			</div><!-- .title -->
			<div class="data">
				<p>********</p>
			</div><!-- .data -->
			<div class="registration">
				<button type="button" onClick="location.href='signUpResult.php'">登録</button>
			</div><!-- .registration -->
			<div class="back">
				<button type="button" onClick="history.back()">戻る</button>
			</div><!-- .back -->
		</div><!-- .signUpList -->
HTML;
?>
	</div><!-- #signUpConfilm -->
</main>
<?php
	require_once( 'footer.php' );
?>