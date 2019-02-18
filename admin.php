<?php
if (!isset($_SESSION['login_admin'])) {
  if (isset($_POST['bConnecter'])) {  
    if(!empty($_POST['eName']))
    {
      $loginuser = htmlspecialchars($_POST['eName']);

      $login="test";

      if($loginuser == $login AND $passworduser == $password) {

          $_SESSION['login_admin'] = $loginuser;
          header('location: index.php');
      }
      else
      {
        $_SESSION['$erreurupload'] = '<div class="alert alert-danger"><strong><i class="fa fa-warning"></i> Erreur !!!</strong> Votre nom d\'utilisateur ou mot de passe est incorrect.</div>';
      }
    }
    else
    {
      $_SESSION['$erreurupload'] = '<div class="alert alert-danger"><strong><i class="fa fa-warning"></i> Erreur !!!</strong>  Tous les champs ne sont pas remplis!</div>';
    }
  }
?>
<form action="" method="post">
		<input placeholder="Administrer le jeu" name="eName" type="text" autofocus required>
		<input type="submit" name="bConnecter" value="Connexion">
</form>
<?php
}
?>
