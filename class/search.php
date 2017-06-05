<?php 

class search {

	public function __construct($data) {
		$this->data = $data;
	}

	private function getField($field){
		if(!isset($this->data[$field])){
			return null;
		} else{
			return $this->data[$field];
		}
	}

	public function search($db, $sex, $pays, $region, $departement, $ville, $age){
		$req = "SELECT * FROM users WHERE ";
		if($this->getField($sex)){
			$req .= "(";
			foreach ($this->getField($sex) as $key => $value) {
				$req .= "sex = '$value' OR ";
			}
			$req = substr($req, 0, strlen($req) - 4);
			$req .= ") AND ";
		}
		if($this->getField($pays)){
			$req .= "(";
			foreach ($this->getField($pays) as $key => $value) {
				$req .= "pays = '$value' OR ";
			}
			$req = substr($req, 0, strlen($req) - 4);
			$req .= ") AND ";
		}
		if($this->getField($region)){
			$req .= "(";
			foreach ($this->getField($region) as $key => $value) {
				$req .= "region = '$value' OR ";
			}
			$req = substr($req, 0, strlen($req) - 4);
			$req .= ") AND ";
		}
		if($this->getField($departement)){
			$req .= "(";
			foreach ($this->getField($departement) as $key => $value) {
				$req .= "departement = '$value' OR ";
			}
			$req = substr($req, 0, strlen($req) - 4);
			$req .= ") AND ";
		}
		if($this->getField($ville)){
			$req .= "(";
			foreach ($this->getField($ville) as $key => $value) {
				$req .= "ville = '$value' OR ";
			}
			$req = substr($req, 0, strlen($req) - 4);
			$req .= ") AND ";
		}
		if($this->getField($age)){
			$req .= "(";
			foreach ($this->getField($age) as $key => $value) {
				if(substr($value, 0, 2) == '18') { 
					$y = date('Y') - 25;
					$date1 = $y . "-" . date('m-d');
					$y = date('Y') - 18;
					$date2 = $y . "-" . date('m-d');
					$req .= "(date BETWEEN '$date1' AND '$date2') OR ";
				}
				elseif(substr($value, 0, 2) == '25') {
					$y = date('Y') - 35;
					$date1 = $y . "-" . date('m-d');
					$y = date('Y') - 25;
					$date2 = $y . "-" . date('m-d');
					$req .= "(date BETWEEN '$date1' AND '$date2') OR ";
				}
				elseif(substr($value, 0, 2) == '35') {
					$y = date('Y') - 45;
					$date1 = $y . "-" . date('m-d');
					$y = date('Y') - 35;
					$date2 = $y . "-" . date('m-d');
					$req .= "(date BETWEEN '$date1' AND '$date2') OR ";
				}
				elseif(substr($value, 0, 2) == '45') {
					$y = date('Y') - 45;
					$date1 = $y . "-" . date('m-d');
					$req .= "(date <= '$date1') OR ";
				}
			}
			$req = substr($req, 0, strlen($req) - 4);
			$req .= ") AND ";
		}
		$req .= "active = 1";
		return $db->query($req)->fetchall();
	}
}