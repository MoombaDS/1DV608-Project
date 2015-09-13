<?php

class DateTimeView {


	public function show() {

		date_default_timezone_set('Europe/Stockholm');

		$date = getdate();
		$weekday = $date['weekday'];
		$dayofmonth = $date['mday'];
		$month = $date['month'];
		$year = $date['year'];
		$hour = $date['hours'];
		$minutes = $date['minutes'];
		$seconds = $date['seconds'];

		$timeString = $weekday . ', the ' . $dayofmonth . 'th of ' . $month . ' ' . $year . ', The time is ' . $hour . ':';

		if ($minutes < 10) {
			$timeString .= '0' . $minutes;
		} else {
			$timeString .= $minutes;
		}

		$timeString .= ':';

		if ($seconds < 10) {
			$timeString .= '0' . $seconds;
		} else {
			$timeString .= $seconds;
		}

		return '<p>' . $timeString . '</p>';
	}
}