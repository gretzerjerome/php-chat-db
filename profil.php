<?php
session_start();

$bdd = new PDO('mysql:host=localhost;dbname=minitchat;charset=utf8', 'root', 'finish77');
$requser = $bdd->prepare("SELECT * FROM tchat WHERE id = ?");
$requser->execute(array($_SESSION['id']));
$user = $requser->fetch();

$message = str_replace( ":)", '<img src="emojis/emo_smile.png" alt=":)">', $message );
$message = str_replace( ":(", '<img src="emojis/emo_sad.png" alt=":(">', $message );


if(isset($_GET['id']) AND $_GET['id'] > 0) {
   $getid = intval($_GET['id']);
   $requser = $bdd->prepare('SELECT * FROM tchat WHERE id = ?');
   $requser->execute(array($getid));
   $userinfo = $requser->fetch();
?>
<html>
   <head>
      <title>Profil</title>
      <meta charset="utf-8">
   </head>
   <body>
      <div align="center">
         <h2>Profil de <?php echo $userinfo['pseudo']; ?></h2>
         <br /><br />
         Pseudo = <?php echo $userinfo['pseudo']; ?>
         <br />
         Mail = <?php echo $userinfo['mail']; ?>
         <br />
         <?php
         if(isset($_SESSION['id']) AND $userinfo['id'] == $_SESSION['id']) {
         ?>
         <br />
         <a href="deconnexion.php">Se déconnecter</a>
         <?php
         }

     if (isset($_POST['message']) AND !empty($_POST['message']))
     {
       $pseudo = htmlspecialchars($userinfo['pseudo']);
       $message = htmlspecialchars($_POST['message']);
       $insertmsg = $bdd->prepare('INSERT INTO chat(pseudochat, message) VALUES(?, ?)');
       $insertmsg->execute(array($pseudo, $message));
     }

     ?>
     <html>
         <head>
             <title>Chat PHP</title>
             <meta charset="utf-8">
		         <meta name="viewport" content="width=device-width, initial-scale=1.0">
		         <meta http-equiv="X-UA-Compatible" content="ie=edge">
		         <link href="https://fonts.googleapis.com/css?family=Athiti" rel="stylesheet">
		         <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
	           <link rel="stylesheet" href="profil.css" type="text/css" charset="utf-8" />
         </head>
         <body>
           <h2>Tapez votre texte ici :</h2>
           <form method="post" action"">
               <textarea type="text" name="message" placeholder="message"> </textarea>
                 <br />
               <input type="submit" value="Envoi"/>
           </form>

           <h3>Tchat :</h3>
           <?php
           $allmsg = $bdd->query('SELECT * FROM chat ');
           while($msg = $allmsg->fetch())
           {
            ?>
           <b><?php echo $userinfo['pseudo']; ?> : </
           <b><?php echo $msg['message'];?><br/>
           <?php
           }
           ?>
         

      </div>
   </body>
</html>
<?php
}
?>
