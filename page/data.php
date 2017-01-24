<?php 
	
	require("../functions.php");
	
	require("../class/workout.class.php");
	$workout = new workout($mysqli);
	
	//kui ei ole kasutaja id'd
	if (!isset($_SESSION["userId"])){
		
		//suunan sisselogimise lehele
		header("Location: login.php");
		exit();
	}
	
	
	//kui on ?logout aadressireal siis login välja
	if (isset($_GET["logout"])) {
		
		session_destroy();
		header("Location: login.php");
		exit();
	}
	
	$msg = "";
	if(isset($_SESSION["message"])){
		$msg = $_SESSION["message"];
		
		//kui ühe näitame siis kustuta ära, et pärast refreshi ei näitaks
		unset($_SESSION["message"]);
	}
	
	
	if ( isset($_POST["excercise"]) && 
		isset($_POST["duration"]) && 
		isset($_POST["date"]) && 
		!empty($_POST["excercise"]) && 
		!empty($_POST["duration"]) &&
		!empty($_POST["date"])
	  ) {
		  
		$workout->save($Helper->cleanInput($_POST["excercise"]), $Helper->cleanInput($_POST["duration"]), $Helper->cleanInput($_POST["date"]));
		
	}
	
	// sorteerib
	if(isset($_GET["sort"]) && isset($_GET["direction"])){
		$sort = $_GET["sort"];
		$direction = $_GET["direction"];
	}else{
		// kui ei ole määratud siis vaikimis id ja ASC
		$sort = "id";
		$direction = "ascending";
	}
	
	//kas otsib
	if(isset($_GET["w"])){
		
		$w = $Helper->cleanInput($_GET["w"]);
		
		$workoutData = $workout->get($w, $sort, $direction);
	
	} else {
		$w = "";
		$workoutData = $workout->get($w, $sort, $direction);
	
	}
	
	
	
	
?>
<?php require("../header.php"); ?>

<div class="container">

	<h1>Treeninguplaan</h1>
	<?=$msg;?>
	<p>
		Tere tulemast <a href="user.php"><?=$_SESSION["userEmail"];?>!</a>
		<a href="?logout=1">Logi välja</a>
	</p>


	<h2>Andmete sisestamine</h2>
	<form method="POST">
		
		<label>Salvesta harjutuse nimi</label> <br> 
		<input type="text" placeholder = "Nt 'ujumine'" class ="form-control"  name="excercise"><br>
		<label>Sisesta minutid</label>
		<br> <input type="text" placeholder = "Nt '15'" class ="form-control" name="duration" ><br>
		<label>Salvesta kuupäev</label><br>
		<input type="date" name="date"  class ="form-control" ><br>
		
		<input type="submit"  class="btn btn-default" value="Salvesta">
		
		
	</form>

	<h2>Treeningu tabel</h2>

	<form>
		<input type="search"  placeholder = "Otsing" class ="form-control" name="w" value="<?=$w;?>"><br>
		<input type="submit"   class="btn btn-default" value="Otsi">
	</form>
	
	<?php 
		
		$direction = "ascending";
		if (isset($_GET["direction"])){
			if ($_GET["direction"] == "ascending"){
				$direction = "descending";
			}
		}
		
		$html = "<table class='table table-striped table-bordered'>";
		
		$html .= "<tr>";
			$html .= "<th>
						<a href='?w=".$w."&sort=id&direction=".$direction."'>
							id
						</a>
					</th>";
			$html .= "<th>
						<a href='?w=".$w."&sort=excercise&direction=".$direction."'>
							harjutus
						</a>
					</th>";
			$html .= "<th>
						<a href='?w=".$w."&sort=duration&direction=".$direction."'>
							date
						</a>
					</th>";
			$html .= "<th>
						<a href='?w=".$w."&sort=date&direction=".$direction."'>
							duration
						</a>
					</th>";
		$html .= "</tr>";
		
		//iga liikme kohta massiivis
		foreach($workoutData as $w){
			// iga auto on $c
			//echo $c->plate."<br>";
			
			$html .= "<tr>";
			
				$html .= "<td>".$w->id."</td>";
				$html .= "<td>".$w->excercise."</td>";
				$html .= "<td>".$w->date."</td>";
				$html .= "<td>".$w->duration."</td>";
				

				$html .= "<td>
							<a href='edit.php?id=".$w->id."' class='btn btn-default'>
							
								<span class='glyphicon glyphicon-cog'></span>
								Change
								
							</a>
						</td>";
				
			$html .= "</tr>";
		}
		
		$html .= "</table>";
		
		echo $html;
		
		
		$listHtml = "<br><br>";

		

	?>

	<br>
	<br>
	<br>
	<br>
	<br>
</div>
<?php require("../footer.php"); ?>