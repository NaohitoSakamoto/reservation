<?php
	session_start();
	$title = "削除確認 | ";
	require_once( 'header.php' );
?>
<main>
	<div id="myReservationDeleteConfilm">
<?php
print <<< HTML
		<div class="reservationList">
			<h2>削除確認</h2>
			<form id="myReservationConfilm" action="myReservationDeleteResult.php" method="post">
				<div class="title">
					<p>チェックイン</p>
				</div><!-- .title -->
				<div class="data">
					<input type="hidden" value='{$_POST["checkinDate"]}' name="checkinDate"'>
					<p>{$_POST["checkinDate"]}</p>
				</div><!-- .data -->
				<div class="title">
					<p>チェックアウト</p>
				</div><!-- .title -->
				<div class="data">
					<p>{$_POST["checkoutDate"]}</p>
				</div><!-- .data -->
				<div class="title">
					<p>グレード</p>
				</div><!-- .title -->
				<div class="data">
					<p>{$_POST["roomGradeName"]}</p>
				</div><!-- .data -->
				<div class="title">
					<p>1人あたり</p>
				</div><!-- .title -->
				<div class="data">
					<p>¥{$_POST["feePerPerson"]}</p>
				</div><!-- .data -->
				<div class="title">
					<p>1部屋あたりの人数</p>
				</div><!-- .title -->
				<div class="data">
					<p>{$_POST["numberOfPeople"]}</p>
				</div><!-- .data -->
				<div class="title">
					<p>部屋番号</p>
				</div><!-- .title -->
				<div class="data">
					<input type="hidden" value='{$_POST["roomNum"]}' name="roomNum"'>
					<p>{$_POST["roomNum"]}</p>
				</div><!-- .data -->
				<div class="title">
					<p>合計</p>
				</div><!-- .title -->
				<div class="data">
					<p>¥{$_POST["sum"]}</p>
				</div><!-- .data -->
				<div class="delete">
					<button type="submit" form="myReservationConfilm">削除</button>
				</div><!-- .delete -->
				<div class="back">
					<button type="button" onClick="document.location='myPage.php'">戻る</button>
				</div><!-- .back -->
			</form>
		</div><!-- .reservationList -->
HTML;
	$_POST = array();
?>
	</div><!-- #myReservationDeleteConfilm -->
</main>
<?php
	require_once( 'footer.php' );
?>