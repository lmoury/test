<?php
try { 
	$bdd= new PDO('mysql:host=localhost;dbname=mortalcombat','root','');
	}
catch (exception $e){
	die('Erreur:'.$e->getMessage());
	}