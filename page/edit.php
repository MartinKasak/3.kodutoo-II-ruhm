<?php
	//edit.php
	require("../functions.php");
	
	require("../class/workout.class.php");
	$workout = new workout($mysqli);
	
	//var_dump($_POST);
	
	//kas kasutaja uuendab andmeid
	if(isset($_POST["update"])){
		
		$workout->update($Helper->cleanInput($_POST["id"]), $Helper->cleanInput($_POST["excercise"]), $Helper->cleanInput($_POST["duration"]), $Helper->cleanInput($_POST["date"]));
		
		header("Location: edit.php?id=".$_POST["id"]."&success=true");
        exit();	
		
	}
	
	//kustutan
	if(isset($_GET["delete"])){
		
		$workout->delete($_GET["id"]);
		
		header("Location: data.php");
		exit();
	}
	
	
	
	// kui ei ole id'd aadressireal siis suunan
	if(!isset($_GET["id"])){
		header("Location: data.php");
		exit();
	}
	
	//saadan kaasa id
	$w = $workout->getSingle($_GET["id"]);
	//var_dump($c);
	
	if(isset($_GET["success"])){
		echo "salvestamine õnnestus";
	}

	
?>
<?php require("../header.php"); ?>
<br><br>
<div class="container">
	<div class="row">

        <div class="col-sm-6">
			<a href="data.php" class="btn btn-default"> Tagasi </a>

<h2>Muuda harjutus</h2>
  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" >
	<input type="hidden" name="id" value="<?=$_GET["id"];?>" > 
  	<label for="excercise" >Muuda harjutus</label><br>
	<input id="excercise" name="excercise" type="text" class="form-control" value="<?php echo $w->excercise;?>" ><br><br>
	
  	<label for="duration" >kestvus/min</label><br>
	<input id="duration" name="duration" type="text" class="form-control" value="<?=$w->duration;?>"><br><br>
	
  	<label for="date" >date</label><br>
	<input id="date" name="date" type="date" class="form-control" value="<?=$w->date;?>"><br><br>
	
	<input type="submit" name="update" class="btn btn-default" value="Salvesta">
  </form>
  
  </div>
  </div>
 <br>
 <br>
 <br>
 <a href="?id=<?=$_GET["id"];?>&delete=true"  class="glyphicon glyphicon-trash">KUSTUTA</a><br><br></div>
 <?php require("../footer.php"); ?>