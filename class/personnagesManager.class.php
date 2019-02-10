<?php
class PersonnagesManager
{
  private $bdd;

  public function __construct($bdd) {
    $this->setBdd($bdd);
  }

  public function count() {
    return $this->bdd->query('SELECT COUNT(*) FROM personnages')->fetchColumn();
  }

   public function exists($info) {
   		if($info > 0){
      		return (bool) $this->bdd->query('SELECT COUNT(*) FROM personnages WHERE id = '.$info)->fetchColumn();
   		}
   		else {
   			$q = $this->bdd->prepare('SELECT COUNT(*) FROM personnages WHERE nom = :nom');
	    	$q->execute([':nom' => $info]);
	    	return (bool) $q->fetchColumn();
   		}
  }

  public function add(Personnage $perso) {
    $q = $this->bdd->prepare('INSERT INTO personnages(nom, forcePerso, degats, niveau, experience, nb_coup_mis, nb_coup_recu, date_last_coup) VALUES(:nom, :forcePerso, :degats, :niveau, :experience, :coupMis, :coupRecu, :dateLastCoup)');

    $q->bindValue(':nom', $perso->getNom());
    $q->bindValue(':forcePerso', $perso->getForcePerso(), PDO::PARAM_INT);
    $q->bindValue(':degats', $perso->getDegats(), PDO::PARAM_INT);
    $q->bindValue(':niveau', $perso->getNiveau(), PDO::PARAM_INT);
    $q->bindValue(':experience', $perso->getExperience(), PDO::PARAM_INT);
    $q->bindValue(':coupMis', $perso->getNbCoupMis(), PDO::PARAM_INT);
    $q->bindValue(':coupRecu', $perso->getNbCoupRecu(), PDO::PARAM_INT);
    $q->bindValue(':dateLastCoup', $perso->getDateLastCoup());
    $q->execute();
  }

  public function delete(Personnage $perso) {
    $this->bdd->exec('DELETE FROM personnages WHERE id = '.$perso->getId());
  }

  public function update(Personnage $perso) {
    $q = $this->bdd->prepare('UPDATE personnages SET nom = :nom, forcePerso = :forcePerso, degats = :degats, niveau = :niveau, experience = :experience, nb_coup_mis = :coupMis, nb_coup_recu = :coupRecu, date_last_coup = :dateLastCoup WHERE id = :id');

    $q->bindValue(':nom', $perso->getNom());
    $q->bindValue(':forcePerso', $perso->getForcePerso(), PDO::PARAM_INT);
    $q->bindValue(':degats', $perso->getDegats(), PDO::PARAM_INT);
    $q->bindValue(':niveau', $perso->getNiveau(), PDO::PARAM_INT);
    $q->bindValue(':experience', $perso->getExperience(), PDO::PARAM_INT);
    $q->bindValue(':coupMis', $perso->getNbCoupMis(), PDO::PARAM_INT);
    $q->bindValue(':coupRecu', $perso->getNbCoupRecu(), PDO::PARAM_INT);
    $q->bindValue(':id', $perso->getId(), PDO::PARAM_INT);
    $q->bindValue(':dateLastCoup', $perso->getDateLastCoup());
    $q->execute();
  }

  public function getPerso($info) {
    if ($info > 0) {
      $q = $this->bdd->query('SELECT * FROM personnages WHERE id = '.$info);
      $donnees = $q->fetch(PDO::FETCH_ASSOC);
      return new Personnage($donnees);
    }
    else {
      $q = $this->bdd->prepare('SELECT * FROM personnages WHERE nom = :nom');
      $q->execute([':nom' => $info]);    
      return new Personnage($q->fetch(PDO::FETCH_ASSOC));
    }
  }


  public function getList() {
    $persos = [];
    $q = $this->bdd->prepare('SELECT * FROM personnages');
	$q->execute();

    while ($donnees = $q->fetch()) {
      $persos[] = new Personnage($donnees);
    }
    return $persos;
  }

  public function setBdd(PDO $bdd) {
    $this->bdd = $bdd;
  }
}