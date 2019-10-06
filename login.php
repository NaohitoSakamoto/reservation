<?php
	session_start();

	/* ログインしていた場合user_id,user_name初期化 */
	if( isset( $_SESSION["userId"] ) ){
		unset( $_SESSION["userId"] );
		unset( $_SESSION["userName"] );
	}

	/* 部屋選択から来た場合 */
	if( isset($_POST["feePerPerson"]) ){
		/* ログイン後は予約確認画面へ */
		$site = "reservationConfilm.php";
		/* セッションに部屋情報を格納 */
		$_SESSION["roomGradeId"] = $_POST["roomGradeId"];
		$_SESSION["feePerPerson"] = $_POST["feePerPerson"];
	}
	/* 部屋選択から来てログインした場合 */
	elseif( isset( $_SESSION["feePerPerson"] ) ){
		/* ログイン後は予約確認画面へ */
		$site = "reservationConfilm.php";
	}
	else{
		/* ログイン後は個人画面へ */
		$site = "myPage.php";
	}

	/* エラー文初期化 */
	$error = "";

	/* ログインした場合 */
	if( isset( $_POST["loginId"] ) ){
		/* データベースに接続 */
		require_once( "connectDB.php" );

/* ID(email)とパスワードが同じcustomer_Id,customer_nameを出力 */
$sql = <<< SQL
	SELECT
	customer_id,
	customer_name
	FROM customer_info
	WHERE
		customer_email = :email
		AND customer_password = :pass
SQL;
		$sth = $dbh->prepare( $sql );
		$sth->bindValue( ":email", $_POST["loginId"], PDO::PARAM_STR );
		$sth->bindValue( ":pass", $_POST["loginPassword"], PDO::PARAM_STR );
		$sth->execute();
		$userId = $sth->fetchAll( PDO::FETCH_ASSOC );
		$loginResult = count( $userId );
		
		/* 出力されなければエラー文出力、出力されればサイト移動 */
		if( $loginResult == 1 ){
			print $userId[0]["customer_id"];
			$_SESSION["userId"] = $userId[0]["customer_id"];
			$_SESSION["userName"] = $userId[0]["customer_name"];
			
			/* ユーザーIDが管理者の場合移動するサイトを変更 */
			if( $_SESSION["userId"] == 1 ){
				$site = "administrator.php";
			}
			header( "Location: " . $site );
		}
		else{
			$error = "<p>IDもしくはパスワードが正しくありません</p>";
		}
	}

	/* POST初期化 */
	$_POST = array();

	$title = "ログイン | ";
	require_once( 'header.php' );
?>
<main>
	<div id="login">
		<h2>ログイン</h2>
<?php
	print $error;
?>
		<form action="login.php" method="post">
			<div class="textArea">
				<p>ID(メールアドレス)</p>
			</div><!-- .textArea -->
			<div class="inputArea">
				<input type="text" name="loginId">
			</div><!-- .inputArea -->
			<div class="textArea">
				<p>パスワード</p>
			</div><!-- .textArea -->
			<div class="inputArea">
				<input type="password" name="loginPassword">
			</div><!-- .inputArea -->
			<div class="signUp">
				<p><a href="signUp.php">新規登録へ</a></p>
			</div><!-- .signUp -->
			<div class="submitArea">
				<button type="submit">ログイン</button>
			</div><!-- .submitArea -->
		</form>
	</div><!-- #login -->
</main>
<?php
	require_once( 'footer.php' );
?>