<?php
class Contact {
	private $mysqli;
	public function __construct($mysqli) {
		$this->mysqli = $mysqli;
	}

	public function create($city_id, $name, $email, $phone) {
		$stmp = $this->mysqli->prepare("INSERT INTO contacts VALUES ('', ?, ?, ?, ?)");
		$stmp->bind_param("isss", $city_id, $name, $email, $phone);
		$stmp->execute();
		return $stmp->insert_id;
	}
}