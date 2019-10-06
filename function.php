<?php
	/* 今週の月曜日を取得する関数 */
	function getThisWeekMonday() {

		$todayDateTime = new DateTime();
		$todayWeekNumber = $todayDateTime->format('w');

		/* 月曜日スタートに変換する */
		/* 日曜日の場合 */
		if ($todayWeekNumber == 0) {
			$todayWeekNumberMondayStart = 6;
		}
		else {
			$todayWeekNumberMondayStart = $todayWeekNumber - 1;
		}

		/* 今週の月曜日に日付を修正してリターン */
		$MonDayThisWeek = new DateTime();
		$MonDayThisWeek->modify( "-{$todayWeekNumberMondayStart} day" );
		return $MonDayThisWeek->format( 'Ymd' );
	}

	/* 日付を修正する関数 */
	function modifyDate($year, $month, $day, $modifyFormat) {
		$modifiedDate = new DateTime( $year . '-' . $month . '-' . $day );
		$modifiedDate->modify( $modifyFormat );
		return $modifiedDate->format('Ymd');
	}

	/* 日付の差分を求める関数 */
	function calculateDayDiff($date1, $date2) {

	    // 日付をUNIXタイムスタンプに変換
	    $timeStamp1 = strtotime($date1);
	    $timeStamp2 = strtotime($date2);

	    // 何秒離れているかを計算
	    $secondDiff = abs($timeStamp2 - $timeStamp1);

	    // 日数に変換
	    $dayDiff = $secondDiff / (60 * 60 * 24);

	    // 戻り値
	    return $dayDiff;
	}
?>
