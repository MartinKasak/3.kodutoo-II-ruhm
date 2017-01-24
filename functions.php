<?php
	
	require("/home/martkasa/config.php");

	/* ALUSTAN SESSIOONI */
	session_start();
		
	/* ÜHENDUS */
	$database = "if16_martkasa";
	$mysqli = new mysqli($serverHost, $serverUsername, $serverPassword, $database);
	
	/* KLASSID */
	
	require("class/Helper.class.php");
	$Helper = new Helper();

?>