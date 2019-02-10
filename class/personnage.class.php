<?php
class Personnage 
{

  private $_id;
  private $_nom;
  private $_forcePerso;
  private $_degats;
  private $_niveau;
  private $_experience;
  private $_nbCoupMis;
  private $_nbCoupRecu;
  private $_dateLastCoup;

  const CEST_MOI = 1;
  const PERSONNAGE_TUE = 2;
  const PERSONNAGE_FRAPPE = 3;

  // Variable statique PRIVÉE.
  private static $_texteADire = 'Je vais tous vous tuer !<br/>';

  public static function parler() {
    echo self::$_texteADire;
  }

  public function hydrate(array $donnees) {
    foreach ($donnees as $key => $value) {

      $method = 'set'.ucfirst($key);

      if (method_exists($this, $method)) {
        $this->$method($value);
      }
    }
  }

  public function __construct(array $donnees) {
    $this->hydrate($donnees);
  }
  
  public function frapper(Personnage $perso) {
    if ($perso->getId() == $this->_id) {
      return self::CEST_MOI;
    }
    $this->_dateLastCoup = date("Y-m-d H:i:s");
    // On indique au personnage qu'il doit recevoir des dégâts.
    // Puis on retourne la valeur renvoyée par la méthode : self::PERSONNAGE_TUE ou self::PERSONNAGE_FRAPPE
    return $perso->recevoirDegats($this->_forcePerso)+$this->gagnerExperience()+$this->gagnerNiveau()+$this->gagnerForce()+$this->nombreCoupMis()+$perso->nombreCoupRecu();
  }

  public function recevoirDegats($info) {
    $this->_degats += $info;
    if ($this->_degats >= 100) {
      return self::PERSONNAGE_TUE;
    }
    return self::PERSONNAGE_FRAPPE;
  }

  public function nombreCoupMis() {
    $this->_nbCoupMis += 1;
  }
  public function nombreCoupRecu() {
    $this->_nbCoupRecu += 1;
  }

  public function verifNbCoup() {
    $dateVerif = date('Y-m-d H:i:s', time()-1*24*3600); 
    if($dateVerif > $this->_dateLastCoup) {
      $this->_nbCoupMis= 0;
      $this->_degats += -10;
    }
  }

  public function gagnerExperience() {
    $this->_experience = $this->_experience + 20;
  }

  public function gagnerNiveau() {
    if($this->_experience >= 100) {
      $this->_niveau = $this->_niveau + 1;
      $this->_experience = 0;
    }
  }

  public function gagnerForce() {
    $this->_forcePerso = $this->_niveau * 2;
  }

  //Mutateur
  public function setId($id) {
    // On convertit l'argument en nombre entier.Sinon, la conversion donnera le nombre 0 (à quelques exceptions près)
    $id = (int) $id;
    if ($id > 0) {
      $this->_id = $id;
    }
  }
  public function setNom($nom) {
    // On vérifie qu'il s'agit bien d'une chaîne de caractères.
    if (is_string($nom)) {
      $this->_nom = $nom;
    }
  }
  public function setForcePerso($force) {
    $force = (int) $force;
    if ($force >= 1 && $force <= 100) {
        $this->_forcePerso = $force;
    }
  }
  public function setDegats($degats) {
    $degats = (int) $degats;
    if ($degats >= 0 && $degats <= 100) {
      $this->_degats = $degats;
    }
  }
  public function setNiveau($niveau) {
    $niveau = (int) $niveau;
    if ($niveau >= 1 && $niveau <= 100) {
      $this->_niveau = $niveau;
    }
  }
  public function setExperience($experience) {
    $experience = (int) $experience;
    if ($experience >= 0 && $experience <= 1000) {
      $this->_experience = $experience;
    }
  }
  public function setNb_coup_mis($nbCoupMis) {
    $nbCoupMis = (int) $nbCoupMis;
    if ($nbCoupMis >= 0) {
      $this->_nbCoupMis = $nbCoupMis;
    }
  }
  public function setNb_coup_recu($nbCoupRecu) {
    $nbCoupRecu = (int) $nbCoupRecu;
    if ($nbCoupRecu >= 0) {
      $this->_nbCoupRecu = $nbCoupRecu;
    }
  }
  public function setDate_last_coup($dateLastCoup) {
      $this->_dateLastCoup = $dateLastCoup;
  }

  
  //Accesseurs
  public function getDegats() {
    return $this->_degats;
  }
  public function getForcePerso() {
    return $this->_forcePerso;
  }
  public function getExperience() {
    return $this->_experience;
  }
  public function getNom() {
    return $this->_nom;
  }
  public function getNiveau() {
    return $this->_niveau;
  }
  public function getId() {
    return $this->_id;
  }
  public function getNbCoupMis() {
    return $this->_nbCoupMis;
  }
  public function getNbCoupRecu() {
    return $this->_nbCoupRecu;
  }
  public function getDateLastCoup() {
    return $this->_dateLastCoup;
  }

}





