<?php

	/* セッションに格納 */
	session_start();
	$_SESSION["checkInDate"] = $_POST["checkInDate"];
	print $_SESSION["checkInDate"];
	$checkOutDate = date( "Y-m-d", strtotime( $_SESSION["checkInDate"] . " +" . $_POST["numberOfNights"] . " day" ) );
	$_SESSION["checkOutDate"] = $checkOutDate;
	$_SESSION["numberOfPeople"] = $_POST["numberOfPeople"];
	$_SESSION["numberOfRooms"] = $_POST["numberOfRooms"];

	$_POST = array();

	/* ログインしているかどうかで、POST送信先を変更 */
	if( !isset( $_SESSION['userId'] ) ){
		$site = "login.php";
	}
	else{
		$site ="reservationConfilm.php";
	}

	/* データベースへ接続 */
	require_once( "connectDB.php" );

	/* 条件にあった予約済みの部屋を取得 */
$sql = <<< SQL
	SELECT
		A.room_grade_id AS room_grade_id,
		B.room_grade_name AS room_grade_name,
		B.fee_per_person AS fee_per_person,
		COUNT(
			A.room_id NOT IN(
				SELECT
					room_id
				FROM
					room_reservation
				WHERE
					DATE ( :checkOutDate ) > DATE ( checkin_date )
					AND DATE ( :checkInDate ) < DATE ( checkout_date )
				GROUP BY room_id
			)
			OR NULL
		) AS remainingRoomNum
	FROM
		room_info AS A
	INNER JOIN
		room_grade AS B ON A.room_grade_id = B.room_grade_id
	WHERE
		number_of_people = :numberOfPeople
	GROUP BY
		A.room_grade_id
SQL;
	$sth = $dbh->prepare( $sql );
	$sth->bindValue( ":checkInDate", $_SESSION["checkInDate"], PDO::PARAM_STR );
	$sth->bindValue( ":checkOutDate", $_SESSION["checkOutDate"], PDO::PARAM_STR );
	$sth->bindValue( ":numberOfPeople", $_SESSION["numberOfPeople"], PDO::PARAM_INT );
	$sth->execute();
	$remainingRoom = $sth->fetchAll( PDO::FETCH_ASSOC );
	
	/*データベースの接続を閉じる */
	$dbh = null;
	$sth = null;

	$title = "部屋選択 | ";
	require_once( 'header.php' );
?>
<!doctype html>
<main>
	<div id="selectRoom">
		<h2>部屋選択</h2>
		<table>
			<tr>
				<th></th>
				<th>グレード</th>
				<th>大人1名</th>
				<th>合計料金</th>
				<th></th>
			</tr>
<?php
	foreach( $remainingRoom as $value ){
		/* 部屋の合計料金を計算 */
		$sum = $value["fee_per_person"] * $_SESSION["numberOfPeople"];

		/* 予約済み部屋の予約ボタンのスタイルを変更する */
		if( $value["remainingRoomNum"] < $_SESSION["numberOfRooms"] ){
			$buttonStyle = 'class="reserved"';
			$buttonValue = '空室なし';
			$clickEvent = 'onsubmit="return false"';
		}
		else{
			$buttonStyle = '';
			$buttonValue = '空室' . $value["remainingRoomNum"] . 'つ';
			$clickEvent = '';
		}
print <<< HTML
			<tr>
				<td>{$_SESSION["numberOfPeople"]}人部屋</td>
				<td data-label="グレード">{$value["room_grade_name"]}<input type="hidden" name="roomGradeId" value={$value["room_grade_id"]} form={$value["room_grade_id"]}></td>
				<td data-label="大人1名">¥{$value["fee_per_person"]}<input type="hidden" name="feePerPerson" value={$value["fee_per_person"]} form={$value["room_grade_id"]}></td>
				<td data-label="合計料金">¥{$sum}</td>
				<td><button type="submit" form={$value["room_grade_id"]} {$buttonStyle}>{$buttonValue}</button></td>
			</tr>
HTML;
	}
?>
		</table>
<?php
	foreach( $remainingRoom as $value ){
		print '<form id="' . $value["room_grade_id"] . '" action="' . $site . '" method="post" ' . $clickEvent . '></form>';		
	}
?>
		<p><a href="index.php">ホームへ</a></p>
	</div><!--/selectRoom-->
</main>
<?php
	require_once( 'footer.php' );
?>