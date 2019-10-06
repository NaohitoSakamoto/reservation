<?php
	session_start();

	/* セッションに格納されている値を取得し、セッションを初期化 */
	$checkInDate = $_SESSION["checkInDate"];
	$checkOutDate = $_SESSION["checkOutDate"];
	$numberOfPeople = $_SESSION["numberOfPeople"];
	$numberOfRooms = $_SESSION["numberOfRooms"];
	$roomGradeId = $_SESSION["roomGradeId"];
	unset( $_SESSION["checkInDate"] );
	unset( $_SESSION["checkOutDate"] );
	unset( $_SESSION["numberOfPeople"] );
	unset( $_SESSION["numberOfRooms"] );
	unset( $_SESSION["roomGradeId"] );

	/* データベースに接続 */
	require_once( "connectDB.php" );

	/* 予約に割りあてるroom_idを取得 */
$sql = <<< SQL
	SELECT
		A.room_id AS room_id
	FROM
		room_info AS A
	INNER JOIN
		room_grade AS B ON A.room_grade_id = B.room_grade_id
	WHERE
		A.number_of_people = :numberOfPeople
		AND B.room_grade_id = :room_grade_id
		AND A.room_id NOT IN(
				SELECT
					room_id
				FROM
					room_reservation
				WHERE
					DATE ( :checkOutDate ) > DATE ( checkin_date )
					AND DATE ( :checkInDate ) < DATE ( checkout_date )
				GROUP BY room_id
			)
	ORDER BY
		A.room_id
	LIMIT
		:numberOfRooms
SQL;
	$sth = $dbh->prepare( $sql );
	$sth->bindValue( ":numberOfPeople", $numberOfPeople, PDO::PARAM_STR );
	$sth->bindValue( ":room_grade_id", $roomGradeId, PDO::PARAM_STR );
	$sth->bindValue( ":checkInDate", $checkInDate, PDO::PARAM_STR );
	$sth->bindValue( ":checkOutDate", $checkOutDate, PDO::PARAM_STR );
	$sth->bindValue( ":numberOfRooms", $numberOfRooms, PDO::PARAM_STR );
	$sth->execute();
	$remainingRoomId = $sth->fetchAll( PDO::FETCH_ASSOC );

	/* 予約データを登録 */
	$resultCount = 0;
	foreach( $remainingRoomId as $value ){
$sql = <<< SQL
		INSERT INTO room_reservation (
			customer_id,
			room_id,
			checkin_date,
			checkout_date
		)
		VALUES (
			:customerId,
			:roomId,
			:checkInDate,
			:checkOutDate
		)
SQL;
		$sth = $dbh->prepare( $sql );
		$sth->bindValue( ":customerId", $_SESSION["userId"], PDO::PARAM_STR );
		$sth->bindValue( ":roomId", $value["room_id"], PDO::PARAM_STR );
		$sth->bindValue( ":checkInDate", $checkInDate, PDO::PARAM_STR );
		$sth->bindValue( ":checkOutDate", $checkOutDate, PDO::PARAM_STR );
		$sth->execute();
		$result = $sth->rowCount();
		if( $result == 1 ){
			$resultCount++;
		}
	}

	$title = "予約結果 | ";
	require_once( 'header.php' );
?>
<main>
<?php
	/* テーブルが変化した行の数と予約部屋数が同じの場合 */
	if( $resultCount == $numberOfRooms ){
		print "<p>登録成功</p>";
		print "<p><a href='myPage.php'>個人画面へ</a></p>";
	}
	else{
		print "<p>登録失敗</p>";
		print "<p><a href='index.php'>ホームへ</a></p>";
	}
?>
</main>
<?php
	require_once( 'footer.php' );
?>