<?php
function chargerClasse($classe) {
	require 'class/' .$classe . '.class.php';
}

spl_autoload_register('chargerClasse');
session_start();

$bdd= new PDO('mysql:host=localhost;dbname=mortalcombat','root','');
include ('function.php');
setlocale(LC_TIME, 'FR');
$manager = new PersonnagesManager($bdd);

//supprimer personnage
if(isset($_GET['delete']) AND !empty($_GET['delete'])) {
	$persoDelete = new Personnage(['id' => (int)$_GET['delete']]);
	$manager->delete($persoDelete);
	$_SESSION['$erreurupload'] = '<div class="alert alert-success"><strong>Success !!!</strong> Votre personnage a bien été supprimé.</div><br/>';
	header('location: index.php');
	die();
}

//ajouter un personnage
if (isset($_POST['bAdd'])) {
	if(!empty($_POST['eNom']) and isset($_POST['eNom'])) {
	  	$persoAdd = new Personnage([
		  'nom' => htmlspecialchars($_POST['eNom']),
		  'forcePerso' => 1,
		  'degats' => 0,
		  'niveau' => 1,
		  'experience' => 0,
		  'nb_coup_mis' => 0,
		  'nb_coup_recu' => 0,
		  'date_last_coup' =>  date("Y-m-d H:i:s")
		]);
		$persoAdd->gagnerNiveau();
		$persoAdd->gagnerForce();
		$manager->add($persoAdd);
		$_SESSION['$erreurupload'] = '<div class="alert alert-success"><strong>Success !!!</strong> Votre personnage '.htmlspecialchars($_POST['eNom']).' a bien été ajouté.</div><br/>';
	  	header('location: index.php');
	  	die();
	}
	else {
		echo "error";
		die();
	}
}

// rechercher le personnage a modifier
if(isset($_GET['update']) AND !empty($_GET['update']) and ($_GET['update'] >0)) {
  	$persoUpdate = $manager->getPerso((int)$_GET['update']);
}

//modifier un personnage
if (isset($_POST['bUpdate']) and isset($_GET['update'])) {
	if(!empty($_POST['eNom']) and isset($_POST['eNom'])) {
	  	$persoUpdate = new Personnage([
		  'nom' => htmlspecialchars($_POST['eNom']),
		  'forcePerso' => (int)$_POST['eForce'],
		  'degats' => (int)$_POST['eDegats'],
		  'niveau' => (int)$_POST['eNiveau'],
		  'experience' => (int)$_POST['eExperience'],
		  'nb_coup_mis' => (int)$_POST['eNbCoupMis'],
		  'nb_coup_recu' => (int)$_POST['eNbCoupRecu'],
		  'id' => (int)$_GET['update']
		]);
		$persoUpdate->gagnerNiveau();
		$persoUpdate->gagnerForce();
		$manager->update($persoUpdate);
		$_SESSION['$erreurupload'] = '<div class="alert alert-success"><strong>Success !!!</strong> Votre personnage '.htmlspecialchars($_POST['eNom']).' a bien été modifié.</div><br/>';
	  	header('location: index.php');
	  	die();
	}
	else {
		echo "error";
		die();
	}
}

//Lister les personnages
$persosList = $manager->getList();
$resultGetList = "<hr>Liste des personnages<hr/><table border='1' style='width:100%;'>";
$resultGetList .= "<th>ID</th>";
$resultGetList .= "<th>Nom</th>";
$resultGetList .= "<th>Force du perso</th>";
$resultGetList .= "<th>Dégâts</th>";
$resultGetList .= "<th>Niveau</th>";
$resultGetList .= "<th>Expérience</th>";
$resultGetList .= "<th>nb coup mis</th>";
$resultGetList .= "<th>nb coup recu</th>";
$resultGetList .= "<th>Date dernier coup donné</th>";
if(isset($_SESSION['login_admin'])) {
	$resultGetList .= "<th>Supprimer</th>";
	$resultGetList .= "<th>Modifier</th>";
}
$resultGetList .= "<th>Utiliser</th>";
foreach ($persosList as $unPerso)
{
	$resultGetList .= "<tr>";
	$resultGetList .=  '<td><center>'.$unPerso->getId();
	$resultGetList .= '</center></td><td><center>'.htmlspecialchars($unPerso->getNom());
	$resultGetList .= '</center></td><td><center>'.$unPerso->getForcePerso();
	$resultGetList .= '</center></td><td><center>'.$unPerso->getDegats();
	$resultGetList .= '</center></td><td><center>'.$unPerso->getNiveau();
	$resultGetList .= '</center></td><td><center>'.$unPerso->getExperience();
	$resultGetList .= '</center></td><td><center>'.$unPerso->getNbCoupMis();
	$resultGetList .= '</center></td><td><center>'.$unPerso->getNbCoupRecu();
	$resultGetList .= '</center></td><td><center>'.datePassedAvecHeure($unPerso->getDateLastCoup());
	if(isset($_SESSION['login_admin'])) {
		$resultGetList .= '</center></td><td><center><a type="button" href="?delete='.$unPerso->getId().'"> Supprimer </a>';
		$resultGetList .= '</center></td><td><center><a href="?update='.$unPerso->getId().'"> Modifier </a>';
	}
	$resultGetList .= '</center></td><td><center><a href="partie.php?utiliser='.$unPerso->getId().'"> Utiliser </a>';
	$resultGetList .= '</center></td></tr>';
}
$resultGetList .= '</table>';

//rechercher un personnage
if (isset($_POST['bSearch'])) {
	if(!empty($_POST['eId']) and isset($_POST['eId'])) {
		if ($manager->exists(htmlspecialchars($_POST['eId']))) // Si celui-ci existe.
  		{
		  	$persoSearch = $manager->getPerso($_POST['eId']);
			$_SESSION['$result'] = "<a href=''>Fermer</a><hr>Info du personnage <b>".htmlspecialchars($persoSearch->getNom())."</b><hr>";
		    $_SESSION['$result'] .=  'Id :'.$persoSearch->getId().'<br/>';
		  	$_SESSION['$result'] .= ' Nom : '.htmlspecialchars($persoSearch->getNom()).'<br/>';
		  	$_SESSION['$result'] .= ' Force perso : '.$persoSearch->getForcePerso().'<br/>';
		  	$_SESSION['$result'] .= ' Dégâts : '.$persoSearch->getDegats().'<br/>';
		  	$_SESSION['$result'] .= ' Niveau : '.$persoSearch->getNiveau().'<br/>';
		  	$_SESSION['$result'] .= ' Experience : '.$persoSearch->getExperience().'<br/>';
		  	$_SESSION['$result'] .= ' nb coup mis : '.$persoSearch->getNbCoupMis().'<br/>';
		  	$_SESSION['$result'] .= ' nb coup recu : '.$persoSearch->getNbCoupRecu().'<br/>';
		  	$_SESSION['$result'] .= ' date derniere coup donné : '.datePassedAvecHeure($persoSearch->getDateLastCoup()).'<br/>';
		  	$_SESSION['$result'] .= '<hr>';
		  	header('location: index.php');
		  	die();
		}
		else {
			$_SESSION['$erreurupload'] = '<div class="alert alert-success"><strong>Erreur !!!</strong> Votre personnage n a pas été trouver.</div><br/>';
			header('location: index.php');
		  	die();
		}
	}
	else {
		echo "error";
		die();
	}
}
?>
<!DOCTYPE html>
<html>
	<head>
		<title></title>
		<meta charset="utf-8" />
		<script src="assets/jquery.min.js"></script>
	</head>
	<body>
		<?php
		if(isset($_GET['update']) AND !empty($_GET['update'])) { ?>
			<center>
			<h1>Modification du personnage <b><?= htmlspecialchars($persoUpdate->getNom()); ?></b></h1><hr><br/>
			<form method="POST" action="">
				Nom du personnage : <input type="text" name="eNom" placeholder="" value="<?= htmlspecialchars($persoUpdate->getNom()); ?>" required ><br/><br/>
				Force du personnage : <input type="text" name="eForce" placeholder="" value="<?= $persoUpdate->getForcePerso(); ?>" required ><br/><br/>
				Degats du personnage : <input type="text" name="eDegats" placeholder="" value="<?= $persoUpdate->getDegats(); ?>" required ><br/><br/>
				Niveau du personnage : <input type="text" name="eNiveau" placeholder="" value="<?= $persoUpdate->getNiveau(); ?>" required ><br/><br/>
				Experience du personnage : <input type="text" name="eExperience" placeholder="" value="<?= $persoUpdate->getExperience(); ?>" required ><br/><br/>
				nb coup mis du personnage : <input type="text" name="eNbCoupMis" placeholder="" value="<?= $persoUpdate->getNbCoupMis(); ?>" required ><br/><br/>
				nb coup recu du personnage : <input type="text" name="eNbCoupRecu" placeholder="" value="<?= $persoUpdate->getNbCoupRecu(); ?>" required ><br/><br/>
				<button type="submit" name="bUpdate" class="btn btn-lmSubmit">Modifier le personnage</button>
			</form>
			</center>
		<?php
		}
		else {
		?>
			<h1><center>Jeu de combat</center></h1><hr>
			<?php if (isset($_SESSION['login_admin'])) {
				echo '<p>Bonjour '.$_SESSION['login_admin'].'</p>';
				echo '<p><a href="partie.php?deconnexion=1">Déconnexion</a></p>';
			}
			else {
				require 'admin.php';
			}?>
			<p>Nombre de personnages créés : <b><?= $manager->count() ?></b></p>
			<form method="POST" action="">
				Créé un nouveau personnage : <input type="text" name="eNom" size="29" placeholder="Entrer le nom du personnage" required >
				<button type="submit" name="bAdd" class="btn btn-lmSubmit">Ajouter un personnage</button>
			</form>
			<br/>
			<form method="POST" action="">
				Rechercher un personnage : <input type="text" name="eId" size="32" placeholder="Entrer id ou le nom du personnage" required >
				<button type="submit" name="bSearch" class="btn btn-lmSubmit">Rechercher</button>
			</form>
			<br/>
			<?php
			if(isset($_SESSION['$erreurupload']) and !empty($_SESSION['$erreurupload'])) {
		        echo $_SESSION['$erreurupload'];
		        unset($_SESSION['$erreurupload']);
		    }
			if(isset($_SESSION['$result'])) {
				echo $_SESSION['$result'];
				unset($_SESSION['$result']);
			}
			?>
			<div id="block">
				<?= $resultGetList; ?>
			</div>
			<hr>
		<?php } ?>
	</body>
</html>
