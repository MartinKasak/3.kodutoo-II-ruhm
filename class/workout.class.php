<?php 
class workout {
	
	private $connection;
	
	function __construct($mysqli){
		
		$this->connection = $mysqli;
		
	}

	/*TEISED FUNKTSIOONID */
	function delete($id){

		$stmt = $this->connection->prepare("UPDATE workout SET deleted=NOW() WHERE id=? AND deleted IS NULL");
		$stmt->bind_param("i",$id);
		
		// kas õnnestus salvestada
		if($stmt->execute()){
			// õnnestus
			echo "kustutamine õnnestus!";
		}
		
		$stmt->close();
		
		
	}
		
	function get($w, $sort, $direction) {
		
		//mis sort ja järjekord
		$allowedSortOptions = ["id", "excercise", "duration"];
		//kas sort on lubatud valikute sees
		if(!in_array($sort, $allowedSortOptions)){
			$sort = "id";
		}
		echo "Sorteerin: ".$sort." ";
		
		//turvaliselt luban ainult 2 valikut
		$orderBy= "ASC";
		if($direction == "descending"){
			$orderBy= "DESC";
		}
		echo "Järjekord: ".$orderBy." ";
		
		if($w == ""){
		
			echo "ei otsi";
			
			$stmt = $this->connection->prepare("
				SELECT id, excercise, duration, date
				FROM workout
				WHERE deleted IS NULL 
				ORDER BY $sort $orderBy
			");
			echo $this->connection->error;
		}else{
			
			echo "Otsib: ".$w;
			
			//teen otsisõna
			// lisan mõlemale poole %
			$searchword = "%".$w."%";
			
			$stmt = $this->connection->prepare("
				SELECT id, excercise, duration, date
				FROM workout
				WHERE deleted IS NULL AND
				(excercise LIKE ? OR duration LIKE ?)
				ORDER BY $sort $orderBy
			");
			$stmt->bind_param("ss", $searchword, $searchword);
		
		}
				

		
		echo $this->connection->error;
		
		$stmt->bind_result($id, $excercise, $duration, $date);
		$stmt->execute();
		
		
		//tekitan massiivi
		$result = array();
		
		// tee seda seni, kuni on rida andmeid
		// mis vastab select lausele
		while ($stmt->fetch()) {
			
			//tekitan objekti
			$workout = new StdClass();
			
			$workout->id = $id;
			$workout->excercise = $excercise;
			$workout->duration = $duration;
			$workout->date = $date;
			
			//echo $plate."<br>";
			// iga kord massiivi lisan juurde nr märgi
			array_push($result, $workout);
		}
		
		$stmt->close();
		
		
		return $result;
	}
	
	function getSingle($edit_id){

		$stmt = $this->connection->prepare("SELECT excercise, duration, date FROM workout WHERE id=? AND deleted IS NULL");

		$stmt->bind_param("i", $edit_id);
		$stmt->bind_result($excercise, $duration, $date);
		$stmt->execute();
		
		//tekitan objekti
		$workout = new Stdclass();
		
		//saime ühe rea andmeid
		if($stmt->fetch()){
			// saan siin alles kasutada bind_result muutujaid
			$workout->excercise = $excercise;
			$workout->duration = $duration;
			$workout->date = $date;
		
			
		}else{
			// ei saanud rida andmeid kätte
			// sellist id'd ei ole olemas
			// see rida võib olla kustutatud
			header("Location: data.php");
			exit();
		}
		
		$stmt->close();
		
		
		return $workout;
		
	}

	function save ($excercise, $duration, $date) {
		
		$stmt = $this->connection->prepare("INSERT INTO workout (excercise, duration, date) VALUES (?, ?, ?)");
	
		echo $this->connection->error;
		
		$stmt->bind_param("sss", $excercise, $duration, $date);
		
		if($stmt->execute()) {
			echo "salvestamine õnnestus";
		} else {
		 	echo "ERROR ".$stmt->error;
		}
		
		$stmt->close();
		
		
	}
	
	function update($id, $excercise, $duration, $date){
    	
		$stmt = $this->connection->prepare("UPDATE workout SET excercise=?, duration=?, date=? WHERE id=? AND deleted IS NULL");
		$stmt->bind_param("sssi",$excercise, $duration, $date, $id);
		
		// kas õnnestus salvestada
		if($stmt->execute()){
			// õnnestus
			echo "salvestus õnnestus!";
		}
		
		$stmt->close();
		
		
	}
	
}
?>