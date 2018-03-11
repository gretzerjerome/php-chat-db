

<?php


try { $bdd = new PDO('mysql:host=localhost;dbname=minitchat', 'root', 'finish77');
 }
 catch(Exception $erreur)
 { die('Erreur: ' .$erreur->getMessage()); }

  session_start();

    if(isset($_POST['formconnexion'])) {
      $mailconnect = htmlspecialchars($_POST['mailconnect']);
      $mdpconnect =($_POST['mdpconnect']);//sha1=>mettre ici pour crypter
      if(!empty($mailconnect) AND !empty($mdpconnect)) {
             $requser = $bdd->prepare("SELECT * FROM tchat WHERE mail = ? AND motdepasse = ?");
             $requser->execute(array($mailconnect, $mdpconnect));
             $userexist = $requser->rowCount();
             if($userexist == 1) {
                $userinfo = $requser->fetch();
                $_SESSION['id'] = $userinfo['id'];
                $_SESSION['pseudo'] = $userinfo['pseudo'];
                $_SESSION['mail'] = $userinfo['mail'];
                header("Location: profil.php?id=".$_SESSION['id']);
             } else {
                $erreur = "Mauvais mail ou mot de passe !";
             }
          } else {
             $erreur = "Tous les champs doivent être complétés !";
          }
       }

if(isset($_POST['forminscription'])) {
   $pseudo = htmlspecialchars($_POST['pseudo']);
   $mail = htmlspecialchars($_POST['mail']);
   $mail2 = htmlspecialchars($_POST['mail2']);
   $mdp =($_POST['mdp']); //sha1 <== systeme pour crypter le mot de passe
   $mdp2 =($_POST['mdp2']); // à placer ici aussi
   if(!empty($_POST['pseudo']) AND !empty($_POST['mail']) AND !empty($_POST['mail2']) AND !empty($_POST['mdp']) AND !empty($_POST['mdp2'])) {
      $pseudolength = strlen($pseudo);
      if($pseudolength <= 255) {
         if($mail == $mail2) {
            if(filter_var($mail, FILTER_VALIDATE_EMAIL)) {
               $reqmail = $bdd->prepare("SELECT * FROM tchat WHERE mail = ?");
               $reqmail->execute(array($mail));
               $mailexist = $reqmail->rowCount();
               if($mailexist == 0) {
                  if($mdp == $mdp2) {
                     $insertmbr = $bdd->prepare("INSERT INTO tchat(pseudo, mail, motdepasse) VALUES(?, ?, ?)");
                     $insertmbr->execute(array($pseudo, $mail, $mdp));
                     $erreur = "Votre compte a bien été créé ! <a href=\"login.php\">Me connecter</a>";
                  } else {
                     $erreur = "Vos mots de passes ne correspondent pas !";
                  }
               } else {
                  $erreur = "Adresse mail déjà utilisée !";
               }
            } else {
               $erreur = "Votre adresse mail n'est pas valide !";
            }
         } else {
            $erreur = "Vos adresses mail ne correspondent pas !";
         }
      } else {
         $erreur = "Votre pseudo ne doit pas dépasser 255 caractères !";
      }
   } else {
      $erreur = "Tous les champs doivent être complétés !";
   }
}
?>

<!-- fin de la partie PHP qui gère l'inscription -->

<!-- Partie HTLM qui gère l'inscription -->

<html>
   <head>
      <title>Pop Team Tchat Epic</title>
      <meta charset="utf-8">
		  <meta name="viewport" content="width=device-width, initial-scale=1.0">
		  <meta http-equiv="X-UA-Compatible" content="ie=edge">
		  <link href="https://fonts.googleapis.com/css?family=Athiti" rel="stylesheet">
		  <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
	    <link rel="stylesheet" href="index.css" type="text/css" charset="utf-8" />
   </head>
   <body>
     <div align="center">
      <img src="https://i.imgur.com/2nzTbyy.jpg" align="center" alt="banniere">
         <h2><u>Inscription</u></h2>
         <form method="POST" action="">
            <table>
               <tr>
                  <td align="right">
                     <label for="pseudo">Pseudo :</label>
                  </td>
                  <td>
                     <input type="text" placeholder="Votre pseudo" id="pseudo" name="pseudo" value="<?php if(isset($pseudo)) { echo $pseudo; } ?>" />
                  </td>
               </tr>
               <tr>
                  <td align="right">
                     <label for="mail">Mail :</label>
                  </td>
                  <td>
                     <input type="email" placeholder="Votre mail" id="mail" name="mail" value="<?php if(isset($mail)) { echo $mail; } ?>" />
                  </td>
               </tr>
               <tr>
                  <td align="right">
                     <label for="mail2">Confirmation du mail :</label>
                  </td>
                  <td>
                     <input type="email" placeholder="Confirmez votre mail" id="mail2" name="mail2" value="<?php if(isset($mail2)) { echo $mail2; } ?>" />
                  </td>
               </tr>
               <tr>
                  <td align="right">
                     <label for="mdp">Mot de passe :</label>
                  </td>
                  <td>
                     <input type="password" placeholder="Votre mot de passe" id="mdp" name="mdp" />
                  </td>
               </tr>
               <tr>
                  <td align="right">
                     <label for="mdp2">Confirmation du mot de passe :</label>
                  </td>
                  <td>
                     <input type="password" placeholder="Confirmez votre mdp" id="mdp2" name="mdp2" />
                  </td>
               </tr>
               <tr>
                  <td></td>
                  <td align="center">
                     <br />
                     <input type="submit" name="forminscription" value="Je m'inscris" />
                  </td>
               </tr>
            </table>
         </form>
         <?php
         if(isset($erreur)) {
            echo '<font color="red">'.$erreur."</font>";
         }
         ?>

       </br>



  <!-- Partie HTML qui gère la connexion -->

       <body>
           <div align="center">
             <h2><u>Connexion</u></h2>
             <form method="POST" action="">
                <input type="email" name="mailconnect" placeholder="Mail" />
                <input type="password" name="mdpconnect" placeholder="Mot de passe" />
                <br /><br />
                <input type="submit" name="formconnexion" value="Se connecter !" />
             </form>
             <?php
             if(isset($erreur)) {
                echo '<font color="red">'.$erreur."</font>";
             }
             ?>
           </div>




<!-- Partie qui affiche le tchat -->

    <div align="center">
        <h2><u>Tchat</u></h2>

    <?php
         $bdd = new PDO('mysql:host=localhost;dbname=minitchat;charset=utf8', 'root', 'finish77');
    if (isset($_POST['pseudochat']) AND isset($_POST['message']) AND !empty($_POST['pseudochat']) AND !empty($_POST['message']))

    {
       $pseudo = htmlspecialchars($_POST['pseudochat']);
       $message = htmlspecialchars($_POST['message']);
       $insertmsg = $bdd->prepare('INSERT INTO chat(pseudochat, message) VALUES(?, ?)');
       $insertmsg->execute(array($pseudo, $message));
    }

?>



    <?php
         $message = str_replace( ":)", '<img src="emojis/emo_smile.png" alt=":)">', $message );
         $message = str_replace( ":(", '<img src="emojis/emo_sad.png" alt=":(">', $message );
         $allmsg = $bdd->query('SELECT * FROM chat ');
         while($msg = $allmsg->fetch())
         {
          ?>
         <b><?php echo $msg['pseudochat']; ?> : </
         <b><?php echo $msg['message'];?><br/>
         <?php
         }
    ?>

<!-- Fin de la partie qui affiche le tchat -->

      </div>
   </body>
</html>
