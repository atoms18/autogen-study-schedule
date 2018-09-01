<?php

use Spatie\PdfToText\Pdf;

require_once("vendor/autoload.php");

if(isset($_FILES) && !empty($_FILES['pdffile'])) {
	$text = Pdf::getText($_FILES['pdffile']['tmp_name']);
	$arr_text = explode("\n", $text);
	array_pop($arr_text);
	$arr_text = array_filter($arr_text);

	$sortfromid_data = array();
	$i = 0;
	$j = 0;
	$started = false;
	foreach($arr_text as $line) {
		if(strpos($line, "ตึก") !== false) {
			$started = true;
			continue;
		}
		if($started) {
			$sortfromid_data[$i][$j] = $line;
			$j++;
			if($j > 6) {
				$i++;
				$j = 0;
			}
		}
	}

	$days = array(
		"อา." => "อาทิตย์",
		"จ." => "จันทร์",
		"อ." => "อังคาร",
		"พ." => "พุธ",
		"พฤ." => "พฤหัสบดี",
		"ศ." => "ศุกร์",
		"ส." => "เสาร์",
	);

	$finished_data = array();
	foreach($days as $v) {
		$finished_data[$v] = array();
	}

	foreach($sortfromid_data as $each) {
		$arr_date = explode(" ", $each[4]);
		$finished_data[$days[$arr_date[0]]][$arr_date[1]] = $each;
	}
}

$is_read_from_file = false;
if($is_read_from_file) {
	$finished_data = json_decode(file_get_contents('test.json'), true);
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang="th">
    <head>
        <meta charset="UTF-8">
        <title>ระบบแปลงตารางสอน</title>

        <link href="css/style_title.css" rel="stylesheet" type="text/css">
        <link href="css/style.css" rel="stylesheet" type="text/css">
    </head>
    <body>
        <table align="center" border="0" style="width:1366px;">
            <tbody>
								<tr>
									<td>
										<form action="" method="post" enctype="multipart/form-data">
											<input type="file" name="pdffile" accept="application/pdf">
											<input type="submit" value="Send">
										</form>
									</td>
								</tr>
                <tr>
                    <td bgcolor="#ccccff">
                        <div id="wrapper-title">
                            <div id="scheduler-wrapper-title">
                                <div id="timeline-title"></div>
                                <div id="scheduler-title">
                                    <div class="bar"></div>
                                    Sunday
                                </div>
                                <div id="scheduler-title2">
                                    <div class="bar"></div>
                                    Monday
                                </div>
                                <div id="scheduler-title3">
                                    <div class="bar"></div>
                                    Tuesday
                                </div>
                                <div id="scheduler-title4" style="font-size: 18px;">
                                    <div class="bar"></div>
                                    Wednesday
                                </div>
                                <div id="scheduler-title5">
                                    <div class="bar"></div>
                                    Thursday
                                </div>
                                <div id="scheduler-title6">
                                    <div class="bar"></div>
                                    Friday
                                </div>
                                <div id="scheduler-title7">
                                    <div class="bar"></div>
                                    Saturday
                                </div>
                            </div>
                        </div>
                        <div id="wrapper" style="width:1260px;">
                            <div class="tt-times">
                                <div class="tt-time" style="left:45px;"></div>
                                <div class="tt-time" style="left:135px;"></div>
                                <div class="tt-time" style="left:225px;"></div>
                                <div class="tt-time" style="left:315px;"></div>
                                <div class="tt-time" style="left:405px;"></div>
                                <div class="tt-time" style="left:495px;"></div>
                                <div class="tt-time" style="left:585px;"></div>
                                <div class="tt-time" style="left:675px;"></div>
                                <div class="tt-time" style="left:765px;"></div>
                                <div class="tt-time" style="left:855px;"></div>
                                <div class="tt-time" style="left:945px;"></div>
                                <div class="tt-time" style="left:1035px;"></div>
                                <div class="tt-time" style="left:1125px;"></div>
                            </div>
                            <div id="scheduler-wrapper">
                                <div id="timeline2">
                                    <div class="major" style="left:0px;">07:00 - 08:00</div>
                                    <div class="major" style="left:90px;">08:00 - 09:00</div>
                                    <div class="major" style="left:180px;">09:00 - 10:00</div>
                                    <div class="major" style="left:270px;">10:00 - 11:00</div>
                                    <div class="major" style="left:360px;">11:00 - 12:00</div>
                                    <div class="major" style="left:450px;">12:00 - 13:00</div>
                                    <div class="major" style="left:540px;">13:00 - 14:00</div>
                                    <div class="major" style="left:630px;">14:00 - 15:00</div>
                                    <div class="major" style="left:720px;">15:00 - 16:00</div>
                                    <div class="major" style="left:810px;">16:00 - 17:00</div>
                                    <div class="major" style="left:900px;">17:00 - 18:00</div>
                                    <div class="major" style="left:990px;">18:00 - 19:00</div>
                                    <div class="major" style="left:1080px;">19:00 - 20:00</div>
                                    <div class="major" style="left:1170px;">20:00 - 21:00</div>
                                </div>
																<?php
																$sec_day_begin = strtotime("07:00");

																$total_time = 0;
																$day_counter = 1;
																foreach($finished_data as $value) {
																	echo '<div id="scheduler' . $day_counter . '">';
																	if(!$is_read_from_file) {
																		$r = rand(0, 255);
																		$g = rand(0, 255);
																		$b = rand(0, 255);
																		$color = 'rgb('. $r .', '. $g .', '. $b .')';
																	}
																	foreach($value as $time => $data) {
																		if($is_read_from_file) {
																			$color_arr = explode(',', $data[0]);
																			$color = $color_arr[0];
																			if(isset($color_arr[1])) {
																				$text_color = $color_arr[1];
																			} else {
																				$text_color = "#FFF";
																			}
																		}

																		$time_arr = explode('-', $time);
																		$sec_begin = strtotime($time_arr[0]);
																		$sec_end = strtotime($time_arr[1]);

																		$gap_between = (($sec_begin - $sec_day_begin) / 3600);
																		$class_time = (($sec_end - $sec_begin) / 3600);

																		$total_time += $class_time;

																		$subject_id = mb_substr($data[1], 0, 8);
																		$subject_name = mb_substr($data[1], 9);

																		echo '
																			<div class="event" style="top: 0px; left: ' . $gap_between * 90 . 'px; height: 80px; width: ' . $class_time * 90 . 'px">
																					<div class="bar"></div>
																					<div class="content" style="height: 80px; background-color: '.$color.'; color: '.$text_color.';">
																							<div class="inner-content">
																								<span class="event-title">
																									<u><b>'. $subject_id .'</b></u> '. $subject_name .'
																								</span>
																								<span class="event-location">
																									Sec ' . $data[2] . ' Room '. $data[5] .' Building '. $data[6] .'<br> Time:  '. $time .'
																								</span>
																							</div>
																					</div>
																			</div>
																		';
																	}
																	echo '</div>';
																	$day_counter++;
																}
																?>
                            </div>
                        </div>
                    </td>
                </tr>
								<tr>
									<td><?php echo "Total time: ", $total_time, " hrs"; ?>/week</td>
								</tr>
            </tbody>
        </table>
    </body>
</html>
