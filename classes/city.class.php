<?php
class City {
	private $mysqli;

	public function __construct($mysqli) {
		$this->mysqli = $mysqli;
	}

	public function get_result() {
		$query = "SELECT name FROM cities";
		$result = $this->mysqli->query($query);
		return $result;
	}

	public function get_json() {
		return $this->result_to_json($this->get_result());
	}

	public function find_or_create($city) {
		$stmp = $this->mysqli->prepare("SELECT id FROM cities WHERE name=?");
		$stmp->bind_param("s", $city);
		$stmp->execute();
		$result = $stmp->get_result();
		if($result->num_rows > 0) {
			return $result->fetch_array(MYSQLI_NUM)[0];
		} else {
			return $this->create($city);
		}
	}

	public function report() {
		$query = "
			SELECT A.id, A.name, COUNT(B.id) as contacts
			FROM cities as A
			INNER JOIN contacts as B ON A.id = B.city_id
			GROUP BY A.id
			ORDER BY contacts DESC
		";
		$result = $this->mysqli->query($query);
		return $result;
	}

	private function create($city) {
		$city = $this->prepare_city_name($city);
		$stmp = $this->mysqli->prepare("INSERT INTO cities VALUES ('', ?)");
		$stmp->bind_param("s", $city);
		$stmp->execute();
		return $stmp->insert_id;
	}

	private function prepare_city_name($city) {
		return ucfirst(strtolower($city));
	}

	private function result_to_json($result) {
		$rows = array();
		while($row = $result->fetch_assoc()) {
			$rows[] = array('value' => $row['name']);
		}
		return json_encode($rows);
	}
}