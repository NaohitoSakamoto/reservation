<?php
	/* GETの値を変数に */
	$roomId = $_GET["roomId"];
	$thisDate = $_GET["thisDate"]; /* 0000-00-00 */
	$reservationNum = $_GET["reservationNum"];

	/* 最大宿泊日数 */
	/* 予約するときのチェックイン日よりも後に予約がなかった場合この定数が使われる */
	define( "DAYDIFF", 3 );

	/* 関数ファイルを呼び出し(日付の差分を求める関数) */
	require_once( "function.php" );

	/* データベースに接続 */
	require_once( "connectDB.php" );

	/* 予約がある場合 */
	if( $reservationNum == 1 ){
		/* 予約情報を取得 */
$sql = <<< SQL
		SELECT
			A.reservation_id AS reservation_id,
			B.customer_name AS customer_name,
			C.room_num AS room_num,
			A.checkin_date AS checkin_date,
			A.checkout_date AS checkout_date
		FROM
			room_reservation AS A
		INNER JOIN
			customer_info AS B ON A.customer_id = B.customer_id
		INNER JOIN
			room_info AS C ON A.room_id = C.room_id
		WHERE
			A.room_id = :room_id
			AND(
					DATE ( :thisDate ) >= DATE ( A.checkin_date )
					AND DATE ( :thisDate ) < DATE ( A.checkout_date )
			)
SQL;
		$sth = $dbh->prepare( $sql );
		$sth->bindValue( ":room_id", $roomId, PDO::PARAM_STR );
		$sth->bindValue( ":thisDate", $thisDate, PDO::PARAM_STR );
		$sth->execute();
		$reservationInfo = $sth->fetchAll( PDO::FETCH_ASSOC );
	}
	/* 予約がない場合 */
	else{
		/* 選択した日付よりも後で最も近い日付を取得 */
		/* 選択した日付から何日滞在できるのかを計算する */
$sql = <<< SQL
		SELECT
			checkin_date
		FROM
			room_reservation
		WHERE
			room_id = :room_id
			AND DATE( :thisDate ) < DATE( checkin_date )
		ORDER BY
			checkin_date ASC
		LIMIT
			1
SQL;
		$sth = $dbh->prepare( $sql );
		$sth->bindValue( ":room_id", $roomId, PDO::PARAM_STR );
		$sth->bindValue( ":thisDate", $thisDate, PDO::PARAM_STR );
		$sth->execute();
		$reservationInfo = $sth->fetchAll( PDO::FETCH_ASSOC );

		/*データベースの接続を閉じる */
		$dbh = null;
		$sth = null;

		/* 選択した日付よりも後予約がない場合 */
		if( count( $reservationInfo ) == 0 ){
			$dayDiff = DAYDIFF; /* DAYDIFFはdefineで定義 */
		}
		/* 予約がある場合 */
		else{
			/* 滞在可能日数を計算(function.php) */
			/* 配列は2次元配列になっていることに注意 */
			$dayDiff = calculateDayDiff( $thisDate, $reservationInfo[0]["checkin_date"] );

			/* 差分が最大宿泊日数よりも大きい場合 */
			/* DAYDIFFはdefineで定義 */
			if( $dayDiff > DAYDIFF ){
				/* 差分を最大宿泊日数へ */
				$dayDiff = DAYDIFF;
			}
		}

		/* 日付加算のためにDateTimeクラスを使用 */
		$thisDateTime = new DateTime($thisDate);

		/* チェックアウトの選択肢を表示するための配列を作成 */
		for( $i = 1; $i <= $dayDiff; $i++ ){
			$thisDateTime->modify("+1 day");
			$checkoutDateSelect[$i] = $thisDateTime->format("Y-m-d");
		}
	}
?>
<!DOCTYPE html>
<html lang="ja">
	<head>
		<meta charset="utf-8">
		<title></title>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	</head>
	<body>
		<div id="administratorEdit">
<?php
	/* 予約がある場合 */
	if( $reservationNum == 1 ){
		/* 予約情報を表示 */
		/* 2次元配列であることに注意 */
print <<< HTML
			<div class="administratorTable">
				<div class="title">
					<p>名前</p>
				</div>
				<div class="answer">
					<p>{$reservationInfo[0]["customer_name"]}</p>
				</div>
				<div class="title">
					<p>部屋番号</p>
				</div>
				<div class="answer">
					<p>{$reservationInfo[0]["room_num"]}</p>
				</div>
				<div class="title">
					<p>チェックイン</p>
				</div>
				<div class="answer">
					<p>{$reservationInfo[0]["checkin_date"]}</p>
				</div>
				<div class="title">
					<p>チェックアウト</p>
				</div>
				<div class="answer">
					<p>{$reservationInfo[0]["checkout_date"]}</p>
				</div>
			</div><!-- .administratorTable -->
			<button type="button" name="delete">削除</button>
HTML;
	}
	/* 予約がない場合 */
	else{
		/* 入力欄を表示 */
print <<< HTML
			<div class="administratorTable">
				<div class="title">
					<p>名前</p>
				</div>
				<div class="answer">
					<p><input type="text" name="customerName"></p>
					<p class="error errorCustomerName">名前を入力してください</p>
				</div>
				<div class="title">
					<p>電話番号</p>
				</div>
				<div class="answer">
					<p><input type="text" name="phoneNumber"></p>
					<p class="error errorPhoneNumber">電話番号を入力してください</p>
				</div>
				<div class="title">
					<p>チェックイン</p>
				</div>
				<div class="answer">
					<p>{$thisDate}</p>
					<p class="error"></p>
				</div>
				<div class="title">
					<p>チェックアウト</p>
				</div>
				<div class="answer">
					<p>
						<select name="checkoutDate">
							<option value="none">選択してください</option>
HTML;
		foreach( $checkoutDateSelect as $value ){
			print "<option value='" . $value . "'>" . $value . "</option>";
		}
print <<< HTML
						</select>
					</p>
					<p class="error errorCheckoutDate">チェックアウトを入力してください</p>
				</div>
			</div><!-- .administratorTable -->
			<button type="button" name="reservation">予約</button>
HTML;
	}
?>
		</div><!-- #administratorEdit -->
	</body>
</html>
<script type="text/javascript">
$(function(){
	/* ポップアップの予約ボタンが押された場合 */
	$("#administratorEdit button[name='reservation']").click(function(){

		/* エラーフラグ */
		var errorFlag = false;
		/* 入力値格納変数 */
		var roomId;
		var customerName;
		var phoneNumber;
		var checkinDate;
		var checkoutDate;

		/* 名前入力欄が空白だった場合 */
		if($("input[name='customerName']").val() == ""){
			$(".errorCustomerName").css("display","block");
			errorFlag = true;
		}
		else{
			$(".errorCustomerName").css("display","none");
		}

		/* 電話番号が入力されなかった場合 */
		if($("input[name='phoneNumber']").val() == ""){
			$(".errorPhoneNumber").css("display","block");
			errorFlag = true;
		}
		else{
			$(".errorPhoneNumber").css("display","none");
		}

		/* チェックアウトが入力されなかった場合 */
		if($("select[name='checkoutDate']").val() == "none"){
			$(".errorCheckoutDate").css("display","block");
			errorFlag = true;
		}
		else{
			$(".errorCheckoutDate").css("display","none");
		}

		/* エラーが無かったら */
		if( errorFlag == false ){

			/* 入力値を変数に格納 */
			roomId = "<?php print $roomId; ?>";
			customerName = $("input[name='customerName']").val();
			phoneNumber = $("input[name='phoneNumber']").val();
			checkinDate = "<?php print $thisDate; ?>";
			checkoutDate = $("select[name='checkoutDate']").val();

			/* 結果を送信 */
			$("#administratorEdit").load("administratorResult.php",{
				roomId:roomId,
				customerName:customerName,
				phoneNumber:phoneNumber,
				checkinDate:checkinDate,
				checkoutDate:checkoutDate
			});
		}

	});

	/* ポップアップの削除ボタンが押された場合 */
	$("#administratorEdit button[name='delete']").click(function(){
<?php
	if( isset( $reservationInfo[0]["reservation_id"] ) ){
		$reservationId = $reservationInfo[0]["reservation_id"];
	}
	else{
		$reservationId = "";
	}
?>
		/* 予約番号を変数に格納 */
		var reservationId = "<?php print $reservationId; ?>";

		/* 予約番号を送信 */
		$("#administratorEdit").load("administratorResult.php",{
			reservationId:reservationId
		});
	});
});
</script>
