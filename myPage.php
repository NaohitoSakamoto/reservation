<?php
	session_start();

	/* データベースに接続 */
	require_once( "connectDB.php" );

$sql = <<< SQL
	SELECT
		A.checkin_date AS checkin_date,
		A.checkout_date AS checkout_date,
		C.room_grade_name AS room_grade_name,
		C.fee_per_person AS fee_per_person,
		B.number_of_people AS number_of_people,
		B.room_num AS room_num
	FROM
		room_reservation AS A
		INNER JOIN room_info AS B ON A.room_id = B.room_id
		INNER JOIN room_grade AS C ON B.room_grade_id = C.room_grade_id
	WHERE
		A.customer_id = :customerId
SQL;

	$sth = $dbh->prepare( $sql );
	$sth->bindValue( ":customerId", $_SESSION["userId"], PDO::PARAM_STR );
	$sth->execute();
	$reservedRoom= $sth->fetchAll( PDO::FETCH_ASSOC );

	/*データベースの接続を閉じる */
	$dbh = null;
	$sth = null;

	$title = "マイページ | ";
	require_once( 'header.php' );
?>
<main>
	<div id="myPage">
		<h2>マイページ</h2>
		<div class="reservation">
			<h3>予約</h3>
			<form action="selectRoom.php" method="post">
				<p>チェックイン</p>
				<input type="date" name="checkInDate">
				<p>宿泊数</p>
				<div class="selectForm">
					<select name="numberOfNights">
						<option value="1">1泊</option>
						<option value="2">2泊</option>
					</select>
				</div><!--/selectForm-->
				<p>大人</p>
				<div class="selectForm">
					<select name="numberOfPeople">
						<option value="1">1人</option>
						<option value="2">2人</option>
					</select>
				</div><!--/selectForm-->
				<p>部屋数</p>
				<div class="selectForm">
					<select name="numberOfRooms">
						<option value="1">1部屋</option>
						<option value="2">2部屋</option>
					</select>
				</div><!--/selectForm-->
				<button type="submit">検索</button>
			</form>
		</div><!--/reservation-->
		<div class="myReservationList">
			<h3>予約一覧</h3>
<?php
	foreach( $reservedRoom as $key => $line ){
		/* 料金を計算 */
		$sum = $line["fee_per_person"] * $line["number_of_people"];
		
print <<< HTML
			<div class="reservationListItem">
				<form id="{$key}" action="myReservationDeleteConfilm.php" method="post">
					<div class="title">
						<p>チェックイン</p>
					</div><!-- .title -->
					<div class="data">
						<input type="hidden" value='{$line["checkin_date"]}' name="checkinDate" form="{$key}">
						<p>{$line["checkin_date"]}</p>
					</div><!-- .data -->
					<div class="title">
						<p>チェックアウト</p>
					</div><!-- .title -->
					<div class="data">
						<input type="hidden" value='{$line["checkout_date"]}' name="checkoutDate" form="{$key}">
						<p>{$line["checkout_date"]}</p>
					</div><!-- .data -->
					<div class="title">
						<p>グレード</p>
					</div><!-- .title -->
					<div class="data">
						<input type="hidden" value='{$line["room_grade_name"]}' name="roomGradeName" form="{$key}">
						<p>{$line["room_grade_name"]}</p>
					</div><!-- .data -->
					<div class="title">
						<p>1人あたり料金</p>
					</div><!-- .title -->
					<div class="data">
						<input type="hidden" value='{$line["fee_per_person"]}' name="feePerPerson" form="{$key}">
						<p>¥{$line["fee_per_person"]}</p>
					</div><!-- .data -->
					<div class="title">
						<p>1部屋あたりの人数</p>
					</div><!-- .title -->
					<div class="data">
						<input type="hidden" value='{$line["number_of_people"]}' name="numberOfPeople" form="{$key}">
						<p>{$line["number_of_people"]}</p>
					</div><!-- .data -->
					<div class="title">
						<p>部屋番号</p>
					</div><!-- .title -->
					<div class="data">
						<input type="hidden" value='{$line["room_num"]}' name="roomNum" form="{$key}">
						<p>{$line["room_num"]}</p>
					</div><!-- .data -->
					<div class="title">
						<p>料金</p>
					</div><!-- .title -->
					<div class="data">
						<input type="hidden" value='{$sum}' name="sum" form="{$key}">
						<p>¥{$sum}</p>
					</div><!-- .data -->
					<div class="submit">
						<button type="submit">削除</button>
					</div>
				</form>
			</div><!-- .reservationListItem -->
HTML;
	}
?>
		</div><!-- myReservationList -->
	</div><!-- #myPage -->
</main>
<?php
	require_once( 'footer.php' );
?>