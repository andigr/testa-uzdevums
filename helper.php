<?php
function make_connection() {
	$username = "root";
	$password = "root";
	$hostname = "localhost";
	$dbname = "digibrand_uzdevums";

	$mysqli = new mysqli($hostname, $username, $password, $dbname)
		or die("Unable to connect to MySQL");
	return $mysqli;	
}

function mysqli_result_to_array($result) {
	$rows = array();
	while($row = $result->fetch_assoc()) {
	  $rows[]=$row;
	}
	return $rows;
}

function cities_report_html($report) {
	$html = '<table>';
	$html .= '<thead><tr>';
	$html .= '<th>Pilsēta</th><th>Kontaktu skaits</th>';
	$html .= '</tr></thead>';
	$html .= '<tbody>';
	foreach ($report as $key => $value) {
		$html .= '<tr>';
			$html .= '<td>';
			$html .= $value['name'];
			$html .= '</td>';
			$html .= '<td>';
			$html .= $value['contacts'];
			$html .= '</td>';
		$html .= '</tr>';
	}
	$html .= '</tbody>';
	$html .= '</table>';
	return $html;
}

function set_value($field) {
	if(isset($_POST['add-contact']) && isset($_POST['add-contact'][$field])) {
		return $_POST['add-contact'][$field];
	} else {
		return '';
	}
}