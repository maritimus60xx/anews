<?php
	print '<ul>';
	//вывод курса для USD
	if($usdch > 0) { print '<li><b>USD</b> '. $usd .'<img src="/'. drupal_get_path('module', 'ua_courses') . '/img/up.png" /></li>';}
	if($usdch < 0) { print '<li><b>USD</b> '. $usd .'<img src="/'. drupal_get_path('module', 'ua_courses') . '/img/down.png" /></li>';}
	if($usdch == 0) { print '<li><b>USD</b> '. $usd;}
	//вывод курса для EUR
	if($eurch > 0) { print '<li><b>EUR</b> '. $eur .'<img src="/'. drupal_get_path('module', 'ua_courses') . '/img/up.png" /></li>';}
	if($eurch < 0) { print '<li><b>EUR</b> '. $eur .'<img src="/'. drupal_get_path('module', 'ua_courses') . '/img/down.png" /></li>';}
	if($eurch == 0) { print '<li><b>EUR</b> '. $eur;}
	//вывод курса для RUB
	if($rubch > 0) { print '<li><b>RUB</b> '. $rub .'<img src="/'. drupal_get_path('module', 'ua_courses') . '/img/up.png" /></li>';}
	if($rubch < 0) { print '<li><b>RUB</b> '. $rub .'<img src="/'. drupal_get_path('module', 'ua_courses') . '/img/down.png" /></li>';}
	if($rubch == 0) { print '<li><b>RUB</b> '. $rub;}
	print '</ul>';
?>