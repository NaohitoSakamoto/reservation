<?php

	/* データベースに接続 */
	require_once( "connectDB.php" );

	/* 結果を格納する変数 */
	$result = "";

	/* 削除ボタンが押された場合 */
	if( isset($_POST["reservationId"]) ){

		/* POST情報を変数に格納 */
		$reservationId = $_POST["reservationId"];

		/* 予約を削除 */
$sql = <<< SQL
		DELETE FROM room_reservation
		WHERE reservation_id = :reservation_id
SQL;
		$sth = $dbh->prepare( $sql );
		$sth->bindValue( ":reservation_id", $reservationId, PDO::PARAM_STR );
		$sth->execute();
		$deleteResult = $sth->rowCount();

		if( $deleteResult != 0 ){
			$result .= "予約削除成功";
		}
		else{
			$result .= "予約削除失敗";
		}
	}

	/* 予約ボタンが押された場合 */
	if( isset($_POST["customerName"]) ){

		/* POST情報を変数に格納 */
		$roomId = $_POST["roomId"];
		$customerName = $_POST["customerName"];
		$phoneNumber = $_POST["phoneNumber"];
		$checkinDate = $_POST["checkinDate"];
		$checkoutDate = $_POST["checkoutDate"];

		/* 客情報を登録 */
$sql = <<< SQL
		INSERT INTO customer_info (
			customer_name,
			customer_phone_number
		)
		VALUES(
			:customer_name,
			:customer_phone_number
		)
SQL;
		$sth = $dbh->prepare( $sql );
		$sth->bindValue( ":customer_name", $customerName, PDO::PARAM_STR );
		$sth->bindValue( ":customer_phone_number", $phoneNumber, PDO::PARAM_STR );
		$sth->execute();
		$insertResult = $sth->rowCount();

		/* 客情報の登録に成功した場合 */
		if( $insertResult != 0 ){
			/* 登録した客番号を取得 */
$sql = <<< SQL
			SELECT
			customer_id
			FROM
			customer_info
			WHERE
			customer_name = :customer_name
SQL;
			$sth = $dbh->prepare( $sql );
			$sth->bindValue( ":customer_name", $customerName, PDO::PARAM_STR );
			$sth->execute();
			$insertResult = $sth->fetch( PDO::FETCH_ASSOC );

			/* 客番号の取得に成功した場合 */
			if( $insertResult != false ){
				/* 客番号を変数に格納 */
				$customerId = $insertResult["customer_id"];

				/* 予約データを挿入 */
$sql = <<< SQL
				INSERT INTO room_reservation (
					customer_id,
					room_id,
					checkin_date,
					checkout_date
				)
				VALUES(
					:customer_id,
					:room_id,
					:checkin_date,
					:checkout_date
				)
SQL;
				$sth = $dbh->prepare( $sql );
				$sth->bindValue( ":customer_id", $customerId, PDO::PARAM_STR );
				$sth->bindValue( ":room_id", $roomId, PDO::PARAM_STR );
				$sth->bindValue( ":checkin_date", $checkinDate, PDO::PARAM_STR );
				$sth->bindValue( ":checkout_date", $checkoutDate, PDO::PARAM_STR );
				$sth->execute();
				$insertResult = $sth->rowCount();

				/* 予約登録成功した場合 */
				if( $insertResult != 0 ){
					$result .= "予約登録成功";
				}
				else{
					$result .= "予約登録失敗";
				}
			}
			else{
				$result .= "客番号の取得に失敗";
			}
		}
		else{
			$result .= "客情報の登録に失敗";
		}
	}

	/* POSTの初期化 */
	$_POST = array();

	/*データベースの接続を閉じる */
	$dbh = null;
	$sth = null;
?>
<!DOCTYPE html>
<html lang=ja>
	<head>
		<meta charset="utf-8">
		<title></title>
	</head>
	<body>
		<div id="administratorResult">
<?php
	print $result;
?>
		</div><!-- #administratorEdit -->
	</body>
</html>
