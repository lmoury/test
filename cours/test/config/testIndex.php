<?php 
function chargerClasse($classe) {
	require $classe . '.php';
}
spl_autoload_register('chargerClasse');


$perso1 = new Personnage('Laurent le BG', Personnage::FORCE_MOYENNE, 0); // Un premier personnage  (nom force degat)
$perso2 = new Personnage('David le Noob', Personnage::FORCE_MOYENNE, 0); // Un second personnage (nom force degat)

//modifier la valeur de force du personnage
$perso1->setForcePerso(91);

Personnage::parler();

$perso1->afficheInfo();
$perso2->afficheInfo();


$perso1->frapper($perso2); // $perso1 frappe $perso2
$perso1->gagnerExperience(); // $perso1 gagne de l'expérience
$perso1->afficheInfo();
$perso2->afficheInfo();

$perso2->frapper($perso1); // $perso2 frappe $perso1
$perso2->gagnerExperience(); // $perso2 gagne de l'expérience
$perso1->afficheInfo();
$perso2->afficheInfo();

$perso1->frapper($perso2); // $perso1 frappe $perso2
$perso1->gagnerExperience(); // $perso1 gagne de l'expérience
$perso1->afficheInfo();
$perso2->afficheInfo();

echo $perso1->getNom(),' a ', $perso1->getForcePerso(), ' de force, contrairement a ',$perso2->getNom(),' qui a ', $perso2->getForcePerso(), ' de force.<br />';
echo $perso1->getNom(),' a ', $perso1->getExperience(), ' d\'expérience, contrairement a ',$perso2->getNom(),' qui a ', $perso2->getExperience(), ' d\'expérience.<br />';
echo $perso1->getNom(),' a ', $perso1->getDegats(), ' de dégâts, contrairement a ',$perso2->getNom(),' qui a ', $perso2->getDegats(), ' de dégâts.<br />';

echo ($perso1->getDegats() > $perso2->getDegats()) ? "<br/>Le gagnant est ".$perso2->getNom()." et le looser est ".$perso1->getNom() : "<br/>Le gagnant est ".$perso1->getNom()." et le looser est ".$perso2->getNom();