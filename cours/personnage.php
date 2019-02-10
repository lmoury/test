
<?php

class Personnage 
{

  private $_id;
  private $_nom;
  private $_force;
  private $_degats;
  private $_niveau;
  private $_experience;

  // Déclarations des constantes en rapport avec la force.
  const FORCE_PETITE = 20;
  const FORCE_MOYENNE = 50;
  const FORCE_GRANDE = 80;

  // Variable statique PRIVÉE.
  private static $_texteADire = 'Je vais tous vous tuer !<br/>';

  //constructeur
  public function __construct($nom, $forceInitiale, $degats){
    $this->setNom($nom);
    $this->setForcePerso($forceInitiale);
    $this->setDegats($degats);
    $this->_experience = 1;
  }

  // fonction du jeu
  public function afficheInfo() {
    echo 'Proprietes de '.$this->_nom.'<br />';
    echo "Degats : ".$this->_degats."<br/>";
    echo "Experience : ".$this->_experience."<br/>";
    echo "Force : ".$this->_force."<hr>";
  }
  
  public function frapper(Personnage $persoAFrapper) {
    $persoAFrapper->_degats += $this->_force;
  }  

  public function gagnerExperience() {
    // On ajoute 1 à notre attribut $_experience.
    $this->_experience = $this->_experience + 1;
  }


  //Mutateur
  public function setId($id) {
    // On convertit l'argument en nombre entier.
    // Si c'en était déjà un, rien ne changera.
    // Sinon, la conversion donnera le nombre 0 (à quelques exceptions près, mais rien d'important ici).
    $id = (int) $id;
    // On vérifie ensuite si ce nombre est bien strictement positif.
    if ($id > 0) {
      // Si c'est le cas, c'est tout bon, on assigne la valeur à l'attribut correspondant.
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
      if (in_array($force, [self::FORCE_PETITE, self::FORCE_MOYENNE, self::FORCE_GRANDE])) {
        $this->_force = $force;
      }
      else {
        $this->_force = $force;
      }
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
    
    if ($experience >= 1 && $experience <= 100) {
      $this->_experience = $experience;
    }
  }

  
  //Accesseurs
  public function getDegats() {
    return $this->_degats;
  }
  public function getForcePerso() {
    return $this->_force;
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

  // Notez que le mot-clé static peut être placé avant la visibilité de la méthode (ici c'est public).
  public static function parler()
  {
    echo self::$_texteADire;
  }
}


