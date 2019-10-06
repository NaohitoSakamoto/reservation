<?php
	/* セッションが始まっていなければ */
	if( !isset( $_SESSION ) ){
		session_start();
	}

	/* エラー文が設定される前 */
	if( !isset( $customerNameError ) ){
		$customerNameError = "";
		$customerPhoneNumberError = "";
		$customerEmailError = "";
		$customerEmailConfilmError = "";
		$customerPasswordError = "";
		$customerPasswordConfilmError = "";
	}

	/* セッションにユーザー名などが残っていた場合 */
	if( isset( $_SESSION["customerName"] ) ){
		/* 入力欄に書き込み、セッションを初期化 */
		$customerName = $_SESSION["customerName"];
		$customerPhoneNumber = $_SESSION["customerPhoneNumber"];
		$customerEmail = $_SESSION["customerEmail"];
		$customerPassword = $_SESSION["customerPassword"];
		unset( $_SESSION["customerName"] );
		unset( $_SESSION["customerPhoneNumber"] );
		unset( $_SESSION["customerEmail"] );
		unset( $_SESSION["customerPassword"] );
	}
	else{
		$customerName = "";
		$customerPhoneNumber = "";
		$customerEmail = "";
		$customerPassword = "";
	}

	$title = "新規登録 | ";
	require_once( 'header.php' );
?>
<main>
	<div id="signUp">
		<h2>新規登録</h2>
		<form action="signUpConfilm.php" method="post">
			<div class="textArea">
				<p>名前</p>
				<?php print $customerNameError; ?>
			</div><!-- .textArea -->
			<div class="inputArea">
				<input type="text" name="customerName" value="<?php print $customerName ?>">
			</div><!-- .inputArea -->
			<div class="textArea">
				<p>電話番号</p>
				<?php print $customerPhoneNumberError; ?>
			</div><!-- .textArea -->
			<div class="inputArea">
				<input type="text" name="customerPhoneNumber" value="<?php print $customerPhoneNumber ?>">
			</div><!-- .inputArea -->
			<div class="textArea">
				<p>メールアドレス</p>
				<?php print $customerEmailError; ?>
			</div><!-- .textArea -->
			<div class="inputArea">
				<input type="text" name="customerEmail" value="<?php print $customerEmail ?>">
			</div><!-- .inputArea -->
			<div class="textArea">
				<p>メールアドレス(確認)</p>
				<?php print $customerEmailConfilmError; ?>
			</div><!-- .textArea -->
			<div class="inputArea">
				<input type="text" name="customerEmailConfilm">
			</div><!-- .inputArea -->
			<div class="textArea">
				<p>パスワード</p>
				<?php print $customerPasswordError; ?>
			</div><!-- .textArea -->
			<div class="inputArea">
				<input type="password" name="customerPassword">
			</div><!-- .inputArea -->
			<div class="textArea">
				<p>パスワード(確認)</p>
				<?php print $customerPasswordConfilmError; ?>
			</div><!-- .textArea -->
			<div class="inputArea">
				<input type="password" name="customerPasswordConfilm">
			</div><!-- .inputArea -->
			<div class="submitArea">
				<button type="submit">登録</button>
			</div><!-- .submitArea -->
		</form>
	</div><!-- #signUp -->
</main>
<?php
	require_once( 'footer.php' );
?>