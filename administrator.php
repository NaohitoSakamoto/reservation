<?php
	session_start();

	$title = "管理者画面 | ";
	require_once( 'header.php' );
	require_once( 'function.php' );

	/* データベースへ接続 */
	require_once( "connectDB.php" );

	/* タイトルテーブルのデータを取得 */
$sql = <<< SQL
	SELECT
		A.number_of_people AS number_of_people,
		B.room_grade_name AS room_grade_name,
		A.room_num AS room_num
	FROM
		room_info AS A
		INNER JOIN room_grade AS B
	ON
		A.room_grade_id = B.room_grade_id
SQL;
	$sth = $dbh->prepare( $sql );
	$sth->execute();
	$titleInfo = $sth->fetchAll( PDO::FETCH_ASSOC );

	/* 今週を表示する場合 */
	if( !isset( $_GET["date"] ) ){
		$mondayDate = getThisWeekMonday();
	}
	else{
		$mondayDate = $_GET["date"];
	}

	/* 年・月・日にそれぞれ変換 */
	$mondayYear = substr($mondayDate, 0, 4);
	$mondayMonth = substr($mondayDate, 4, 2);
	$mondayDay = substr($mondayDate, 6, 2);

	/* 先週・次週の日付を取得 */
	$nextMondayDate = modifyDate($mondayYear, $mondayMonth, $mondayDay, '+1 week');
	$previousMondayDate = modifyDate($mondayYear, $mondayMonth, $mondayDay, '-1 week');

	/* 曜日配列作成と表示文字列初期化 */
	$weeks = [
		"月",
		"火",
		"水",
		"木",
		"金",
		"土",
		"日"
	];
	$dateList = "";

	for( $i = 0; $i < 7; $i++ ){
		$thisDate = modifyDate( $mondayYear, $mondayMonth, $mondayDay, '+' . $i . ' day' );
		$thisYear = substr($thisDate, 0, 4);
		$thisMonth = substr($thisDate, 4, 2);
		$thisDay = substr($thisDate, 6, 2);
		$thisDateSQL = $thisYear . "-" . $thisMonth . "-" . $thisDay;

		/* 部屋ごと予約しているかどうか調べる */
$sql = <<< SQL
	SELECT
		A.room_id AS room_id,
		COUNT(
			(
				date ( :thisDateSQL ) >= date ( B.checkin_date )
				AND date ( :thisDateSQL ) < date ( B.checkout_date )
			)
			OR NULL
		) AS reservation_num
	FROM
		room_info AS A
		LEFT OUTER JOIN room_reservation AS B
	ON
		A.room_id = B.room_id
	GROUP BY
		A.room_id
	ORDER BY
		A.room_id
SQL;
		$sth = $dbh->prepare( $sql );
		$sth->bindValue( ":thisDateSQL", $thisDateSQL, PDO::PARAM_STR );
		$sth->execute();
		$roomReservation = $sth->fetchAll( PDO::FETCH_ASSOC );

$dateList .= <<< HTML
					<div class="reservationItems">
						<p>{$thisMonth}/{$thisDay}({$weeks[$i]})</p>
HTML;
		foreach( $roomReservation as $line ){

			/* データベースから取得したroom_idと予約数を変数に格納 */
			$roomId = $line["room_id"];
			$reservationNum = $line["reservation_num"];

			/* 予約があった場合 */
			if( $reservationNum  == 1 ){
				$dateList .= "<p><a href='administratorEdit.php?thisDate=" . $thisDateSQL . "&roomId=" . $roomId . "&reservationNum=" . $reservationNum . "' onclick='return false;'>予約済み</a></p>";
			}
			else{
				$dateList .= "<p><a href='administratorEdit.php?thisDate=" . $thisDateSQL . "&roomId=" . $roomId . "&reservationNum=" . $reservationNum . "' onclick='return false;'>空室</a></p>";
			}
		}

		$dateList .= "</div>";
	}

	/*データベースの接続を閉じる */
	$dbh = null;
	$sth = null;

?>
<!-- ポップアップ関数読み込み -->
<script type="text/javascript" src="js/popup.js"></script>
<main>
	<div id="administrator">
		<div class="operateCalender">
			<button type="button" onClick="location.href='<?php print $_SERVER['SCRIPT_NAME'] . "?date=" . $previousMondayDate; ?>'">前週へ</button>
			<button type="button" onClick="location.href='showDB.php'">データベース確認</button>
			<button type="button" onClick="location.href='<?php print $_SERVER['SCRIPT_NAME'] . "?date=" . $nextMondayDate; ?>'">次週へ</button>
		</div><!-- .operateCalender -->
		<div class="selectArea">
			<div class="titleTable">
				<div class="roomNumberTitle">
					<p>部屋番号</p>
				</div><!-- .roomNumberTitle -->
				<div class="roomGradeTitle">
					<p>グレード</p>
				</div><!-- .roomGradeTitle -->
				<div class="numberOfPeopleTitle">
					<p>人数</p>
				</div><!-- .numberOfPeopleTitle -->
<?php
	foreach( $titleInfo as $line ){
print <<< HTML
				<div class="roomNumberTitle">
					<p>{$line["room_num"]}</p>
				</div><!-- .roomNumberTitle -->
				<div class="roomGradeTitle">
					<p>{$line["room_grade_name"]}</p>
				</div><!-- .roomGradeTitle -->
				<div class="numberOfPeopleTitle">
					<p>{$line["number_of_people"]}</p>
				</div><!-- .numberOfPeopleTitle -->
HTML;
	}
?>
			</div><!-- .titleTable -->
			<div class="dataTable">
				<div class="reservationList">
<?php
	print $dateList;
?>
				</div><!-- .reservationList -->
			</div><!-- .dataTable -->
		</div><!-- .selectArea -->
		<div id="jsEditArea" class="editArea">
			<div class="editAreaInner">
				<div id="jsLoadHTML" class="loadHTML">
				</div><!-- .loadHTML -->
				<div id="jsCloseBtn" class="closeBtn"><i class="fas fa-times"></i></div>
			</div><!-- .editAreaInner -->
			<div id="jsBackground" class="background"></div>
		</div><!-- .editArea -->
	</div><!-- #administrator -->
</main>
<?php
	require_once( 'footer.php' );
?>
