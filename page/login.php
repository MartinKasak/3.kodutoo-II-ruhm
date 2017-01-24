<?php 
	
	require("../functions.php");
	
	require("../class/User.class.php");
	$User = new User($mysqli);
	
	// kui on juba sisse loginud siis suunan data lehele
	if (isset($_SESSION["userId"])){
		
		//suunan sisselogimise lehele
		header("Location: data.php");
		exit();
		
	}
	

	//echo hash("sha512", "b");
	
	
	//GET ja POSTi muutujad
	//var_dump($_GET);
	//echo "<br>";
	//var_dump($_POST);
	
	//echo strlen("äö");
	
	// MUUTUJAD
	$signupEmailError = "";
	$loginEmailError = "";
	$signupPasswordError = "";
	$loginPasswordError = "";
	$weightError= "";
	$signupEmail = "";
	$loginEmail = "";
	$ageError = "";
	
	$firstNameError = "";
	$lastNameError = "";
	$gender = "";
	$firstName = "";
	$lastName = "";
	$weight = "";
	$age = "";
	
	

	// on üldse olemas selline muutja
	if( isset( $_POST["signupEmail"] ) ){
		
		//jah on olemas
		//kas on tühi
		if( empty( $_POST["signupEmail"] ) ){
			
			$signupEmailError = "See väli on kohustuslik";
			
		} else {
			
			// email olemas 
			$signupEmail = $_POST["signupEmail"];
			
		}
		
	} 
	if( isset( $_POST["firstName"] ) ){
		if( empty( $_POST["firstName"] ) ){
			$firstNameError = "See väli on kohustuslik";
		} else {
			// sisselogimise email olemas 
			$firstName = $_POST["firstName"];
		}
	}
	if( isset( $_POST["lastName"] ) ){
		if( empty( $_POST["lastName"] ) ){
			$lastNameError = "See väli on kohustuslik";
		} else { 
			$lastName = $_POST["lastName"];
		}
	}
	if(isset($_POST["weight"])){
		if(empty($_POST["weight"])){
		$weightError = "kohustuslik";
	}else{	
		$weight = $_POST["weight"];	
		}
	}
	
	if(isset($_POST["age"])){
		if(empty($_POST["age"])){
		$ageError = "kohustuslik";
	}else{	
		$age = $_POST["age"];	
		}
	}
	
	if( isset( $_POST["loginEmail"] ) ){
		//jah on olemas, kas on t??
		if( empty( $_POST["loginEmail"] ) ){
			$loginEmailError = "E-post on kohustuslik";
		} else {
			// sisselogimise email olemas 
			$loginEmail = $_POST["loginEmail"];
		}
	}
	
	
	if( isset( $_POST["signupPassword"] ) ){
		
		if( empty( $_POST["signupPassword"] ) ){
			
			$signupPasswordError = "Parool on kohustuslik";
			
		} else {
			
			// siia jõuan siis kui parool oli olemas - isset
			// parool ei olnud tühi -empty
			
			// kas parooli pikkus on väiksem kui 8 
			if ( strlen($_POST["signupPassword"]) < 8 ) {
				
				$signupPasswordError = "Parool peab olema vähemalt 8 tähemärkki pikk";
			
			}
			
		}
		
	}
	
	
	// GENDER
	if( isset( $_POST["gender"] ) ){
		if(empty( $_POST["gender"] ) ){
			$genderError = "";
			}else {
			$gender = $_POST["gender"];
		}
	} 
	
	// peab olema email ja parool
	// ühtegi errorit
	
	if ( isset($_POST["signupEmail"]) && 
		 isset($_POST["signupPassword"]) && 
		 isset($_POST["firstName"]) &&
		 isset($_POST["lastName"]) &&
		 isset($_POST["weight"]) &&
		 isset($_POST["age"])&&
		 $signupEmailError == "" && 
		 empty($signupPasswordError) &&
		 empty($firstNameError)&&
		 empty($lastNameError)&&
		 empty($weightError)&&
		 empty($ageError)
		) {
		
		// salvestame ab'i
		echo "Salvestan... <br>";
		
		echo "email: ".$signupEmail."<br>";
		echo "password: ".$_POST["signupPassword"]."<br>";
		
		$password = hash("sha512", $_POST["signupPassword"]);
		
		echo "password hashed: ".$password."<br>";
		
		
		//echo $serverUsername;
		
		// KASUTAN FUNKTSIOONI
		$signupEmail = $Helper->cleanInput($signupEmail);
		
		$User->signUp($signupEmail, $Helper->cleanInput($password),$Helper->cleanInput($firstName), $Helper->cleanInput($lastName),$Helper->cleanInput($age),$Helper->cleanInput($weight),$Helper-> cleanInput($gender));	
		
	
	}
	
	
	$error ="";
	if ( isset($_POST["loginEmail"]) && 
		isset($_POST["loginPassword"]) && 
		!empty($_POST["loginEmail"]) && 
		!empty($_POST["loginPassword"])
	  ) {
		  
		$error = $User->login($Helper->cleanInput($_POST["loginEmail"]), $Helper->cleanInput($_POST["loginPassword"]));
		
	}
	

?>
<?php require("../header.php"); ?>

<div class="container">
	
	<div class="row">
	
		<div class="col-sm-3">	
			

			<h1>Logi sisse</h1>
			<form method="POST">
			
				
				<label>E-post</label>
				<br>
				<div class="form-group">
					<input class="form-control" name="loginEmail" type="text">
				</div>
				<br><br>
				
				<input type="password" class="form-control" name="loginPassword" placeholder="Parool">
				<br><br>
				
				<input class="btn btn-success btn-block visible-xs-block" type="submit" value="Logi sisse1">
				<input class="btn btn-success btn-sm hidden-xs" type="submit" value="Logi sisse">
				
				
			</form>
			
		</div>
		
		<div class="col-sm-3 col-sm-offset-3">	
			
			
			<h1>Loo kasutaja</h1>
	<form method="POST">
		<label>E-post</label>
		<br>
		
		<input name="signupEmail" class="form-control" type="text" value="<?=$signupEmail;?>"> <?=$signupEmailError;?>
		<br>
		<br>
		
		<label>Parool</label>
		<br>
		<input type="password" class="form-control" name="signupPassword" placeholder="Parool"> <?php echo $signupPasswordError; ?>
		<br><br>
		
		<label> Eesnimi</label>
		<br>
		<input name="firstName" type="text" class="form-control" value= "<?=$firstName;?>"> <?php echo $firstNameError; ?>
		<br>
		<label> Perekonnanimi</label>
		<br>
		<input name="lastName" type="text" class="form-control" value= "<?=$lastName;?>"> <?php echo $lastNameError; ?>
		<br>
		<label>Vanus</label>
		<br>
		<input name="age" type="text" class="form-control" value= "<?=$age;?>"> <?php echo $ageError; ?>
		<br>
		<label>Kaal</label>
		<br>
		<input name="weight" type="text" class="form-control" value= "<?=$weight;?>"> <?php echo $weightError; ?>
		
		
		
		
		
		
		<br>
		<?php if($gender == "male") { ?>
			<input type="radio" name="gender"  value="male" checked> Male<br>
		<?php }else { ?>
			<input type="radio" name="gender" value="male"> Male<br>
		<?php } ?>
		
		<?php if($gender == "female") { ?>
			<input type="radio" name="gender" value="female" checked> Female<br>
		<?php }else { ?>
			<input type="radio" name="gender" value="female"> Female<br>
		<?php } ?>
		
		<?php if($gender == "other") { ?>
			<input type="radio" name="gender" value="other" checked> Other<br>
		<?php }else { ?>
			<input type="radio" name="gender" value="other"> Other<br>
		<?php } ?>
		
		
		<br>
	
		
		
		<input type="submit" value="Loo kasutaja">
		
		
	</form>
		</div>	
	</div>
</div>
<?php require("../footer.php"); ?>