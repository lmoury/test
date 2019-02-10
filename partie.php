<?php 
function chargerClasse($classe) {
	require 'class/' .$classe . '.class.php';
}
spl_autoload_register('chargerClasse');

session_start();
include ('function.php');
setlocale(LC_TIME, 'FR');
if (isset($_GET['deconnexion'])) {
	session_destroy();
	header('Location: index.php');
	exit();
}

if (isset($_SESSION['perso'])) {
	$perso = $_SESSION['perso'];
}

$bdd= new PDO('mysql:host=localhost;dbname=mortalcombat','root','');

$manager = new PersonnagesManager($bdd);

//utiliser personnage
if (isset($_GET['parler']) and !empty($_GET['parler'])) {
	$_SESSION['$persoParler'] = 'test';
	header('location: partie.php');
	die(); 
}

//utiliser personnage
if (isset($_GET['utiliser']) and !empty($_GET['utiliser'])) {
	if ($manager->exists((int)$_GET['utiliser'])) {
		$perso = $manager->getPerso($_GET['utiliser']);
		$perso->verifNbCoup();
		$_SESSION['perso'] = $perso;
	}
	else {
		$_SESSION['$erreurupload'] = '<div class="alert alert-success"><strong>Erreur !!!</strong> Votre personnage n a pas été trouver.</div><br/>';
		header('location: partie.php');
		die(); 
	}
}

// Si on a cliqué sur un personnage pour le frapper.
if (isset($_GET['frapper'])) {
	if (!isset($perso)) {
		$_SESSION['$erreurupload'] = 'Merci de créer un personnage ou de vous identifier.<br/>';
	}
	else {
		if($perso->getNbCoupMis()>2){
			$_SESSION['$erreurupload'] = 'Vous avez utilisé vos trois coups quotidien !';
		}
		elseif (!$manager->exists((int) $_GET['frapper'])) {
		  $_SESSION['$erreurupload'] = 'Le personnage que vous voulez frapper n\'existe pas !';
		}
   		else {
    		$persoAFrapper = $manager->getPerso((int) $_GET['frapper']);
      		$retour = $perso->frapper($persoAFrapper); // On stocke dans $retour les éventuelles erreurs ou messages que renvoie la méthode frapper.
      
      		switch ($retour) {
        		case Personnage::CEST_MOI :
	          		$_SESSION['$erreurupload'] = 'Mais... pourquoi voulez-vous vous frapper ???';
	          		break;
        
        		case Personnage::PERSONNAGE_FRAPPE :
	          		$_SESSION['$erreurupload'] = 'Le personnage a bien été frappé !';
	          		$manager->update($perso);
	          		$manager->update($persoAFrapper);
	          		break;
        
        		case Personnage::PERSONNAGE_TUE :
          			$_SESSION['$erreurupload'] = 'Vous avez tué ce personnage !';
					$manager->update($perso);
					$manager->delete($persoAFrapper);
					break;
      		}
    	}
  	}
  	header('location: partie.php');
	die();
}
?>
<!DOCTYPE html>
<html>
	<head>
		<title></title>
		<meta charset="utf-8" />
		<script src="assets/jquery.min.js"></script>
		<script>$("#block").hide();$("#listePerso").click(function() {
						$("#block").toggle();
					});</script>
	</head>
	<body>
		<p>Nombre de personnages créés : <?= $manager->count() ?></p>
		<?php
		if(isset($_SESSION['$erreurupload']) and !empty($_SESSION['$erreurupload'])) { 
	        echo $_SESSION['$erreurupload']; 
	        //unset($_SESSION['$erreurupload']);
	    } 
		if (isset($perso)) { 
		?>
			<p><a href="?deconnexion=1">Déconnexion</a></p>
			<p><a href="?parler=1">Dire phrase</a></p>
		    <fieldset>
				<legend>Mes informations</legend>
					<p>
						Mon Nom : <?= htmlspecialchars($perso->getNom()); ?><br />
						Ma Force : <?= $perso->getForcePerso(); ?><br />
						Mes Dégâts : <?= $perso->getDegats() ?><br />
						Mon Niveau : <?= $perso->getNiveau(); ?><br />
						Mon Experience : <?= $perso->getExperience(); ?><br />
						Nombre de coup recu : <?= $perso->getNbCoupRecu(); ?><br />
						Nombre de coup mis : <?= $perso->getNbCoupMis(); ?><br />
						Date dernier coup : <?= datetimeAvecHeure($perso->getDateLastCoup()); ?>
					</p>
		    </fieldset>
		    <center>
		    <?php if(isset($_SESSION['$persoParler']) and !empty($_SESSION['$persoParler'])) { 
		        Personnage::parler(); 
		        unset($_SESSION['$persoParler']);
	    	} ?> </center>
		    <fieldset>
				<legend>Qui frapper ?</legend>
				<p>
					<?php
					$persos = $manager->getList();

					if (empty($persos)) {
						echo 'Personne à frapper !';
					}

					else {
						foreach ($persos as $unPerso) {
							echo '<a href="?frapper=', $unPerso->getId(), '">', htmlspecialchars($unPerso->getNom()), '</a> (dégâts : ', $unPerso->getDegats(), ' nb de coup recu : '.$unPerso->getNbCoupRecu().')<br />'; 
						}
					}
					?>
				</p>
		    </fieldset>
		<?php
		}
		else {
			header('Location: index.php');
			exit();
	    } 
		?>
	</body>
</html>
