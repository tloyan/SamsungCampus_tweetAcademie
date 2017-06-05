<?php
class Validator{
	
	private $data;
	private $errors = [];

	public function __construct($data){
		$this->data = $data;
	}	

	private function getField($field){
		if(!isset($this->data[$field])){
			return null;
		} else{
			return $this->data[$field];
		}
	}

	public function isAlphaNum($field, $errorMsg) {
		if(!preg_match('/^[a-zA-Z0-9_]+$/', $this->getField($field))) {
			$this->errors[$field] = $errorMsg;
		}
	}

	public function isUnic($field, $table, $db, $errorMsg){
		$record = $db->query("SELECT * FROM $table WHERE $field = ?", [$this->getField($field)])->fetch();
		if($record){
			$this->errors[$field] = $errorMsg;
		}
	}

	public function isEmail($field, $errorMsg){
		if(!filter_var($this->getField($field), FILTER_VALIDATE_EMAIL)){
			$this->errors[$field] = $errorMsg;
		}
	}

	public function isAlpha($field, $errorMsg) {
		if(!preg_match('/^[a-zA-Z- ]+$/', $this->getField($field))) {
			$this->errors[$field] = $errorMsg;
		}
	}

	public function isConfirmed($field, $errorMsg) {
		if(empty($this->getField($field)) || $this->getField($field) != $this->getField($field . "_confirm") || (strlen($this->getField($field)) != strlen($this->getField($field . "_confirm")))) {
			$this->errors[$field] = $errorMsg;
		}
	}

	public function isExist($field, $table, $db, $errorMsg) {
		$record = $db->query("SELECT * FROM $table WHERE $field = ?", [$this->getField($field)])->fetch();
		if(!$record) {
			$this->errors[$field] = $errorMsg;
		}
	}

	public function isConfirmedUp($field, $errorMsg){
		if(($this->getField($field) != $this->getField($field . "_confirm")) || (strlen($this->getField($field)) != strlen($this->getField($field . "_confirm")))) {
			$this->errors[$field] = $errorMsg;
		}
	}

	public function isDay($field, $errorMsg) {
		if(empty($this->getField($field)) || !is_numeric($this->getField($field)) || $this->getField($field) < 1 || $this->getField($field) > 31) {
			$this->errors[$field] = $errorMsg;
		}
	}

	public function isMonth($field, $errorMsg) {
		if(empty($this->getField($field)) || !is_numeric($this->getField($field)) || $this->getField($field) < 1 || $this->getField($field) > 12) {
			$this->errors[$field] = $errorMsg;
		}
	}

	public function isYears($field, $errorMsg) {
		if(empty($this->getField($field)) || !is_numeric($this->getField($field)) || $this->getField($field) < 1900 || $this->getField($field) > 2017) {
			$this->errors[$field] = $errorMsg;
		}
	}

	public function isMajer($day, $month, $year, $errorMsg) {
		$jour = date("j") - $this->getField($day);
		$mois = date("n") - $this->getField($month);
		$annee = date("Y") - $this->getField($year);
		if($annee > 18) {
			return true;
		} elseif($annee < 18) {
			$this->errors[$year] = $errorMsg;
			return false;
		} else {
			if($mois > 0) {
				return true;
			} elseif ($mois < 0) {
				$this->errors[$month] = $errorMsg;
				return false;
			} else {
				if($jour >= 0) {
					return true;
				}
				$this->errors[$day] = $errorMsg;
				return false;
			}
		}
	}

	public function isGenre($field, $errorMsg) {
		if(empty($this->getField($field)) || !is_string($this->getField($field)) || ($this->getField($field) != "homme" && $this->getField($field) != "femme" && $this->getField($field) != "autre")) {
			$this->errors[$field] = $errorMsg;
		}
	}

	public function isCountry($db, $field, $errorMsg) {
		$pays = $db->query("SELECT * FROM pays WHERE pays_nom = ?", [$this->getField($field)])->fetch();
		if(!$pays) {
			$this->errors[$field] = $errorMsg;
		}
	}

	public function isRegion($db, $field, $errorMsg) {
		$region = $db->query("SELECT * FROM departement WHERE departement_nom = ?", [$this->getField($field)])->fetch();
		if(!$region) {
			$this->errors[$field] = $errorMsg;
		}
	}

	public function isCity($db, $field, $errorMsg) {
		$city = $db->query("SELECT * FROM villes WHERE ville_nom = ?", [$this->getField($field)])->fetch();
		if(!$city) {
			$this->errors[$field] = $errorMsg;
		}
	}

	public function isValid(){
		return empty($this->errors);
	}

	public function getErrors(){
		return $this->errors;
	}
}