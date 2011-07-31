<form method="post" action="index.php" id="formulaire">
<p>Votre recherche :</p>
<input type="text" name="recherche" /><br/>
<p>Effectuer une recherche par :</p>
<input type="radio" name="base_choisie" value="keywords" checked="checked"/><label for="keywords">Tags</label>
<input type="radio" name="base_choisie" value="auteur"/> <label for="auteur">Auteur</label>
<input type="radio" name="base_choisie" value="chan_orig"/><label for="chan_orig">Channel</label><br/>
<p>Classer les r&eacute;sultats par :</p>
<input type="radio" name="classer" value="id" checked="checked"/> <label for="auteur">Date</label>
<input type="radio" name="classer" value="auteur"/> <label for="auteur">Auteur</label>
<input type="radio" name="classer" value="chan_orig"/> <label for="auteur">Channel</label>
<input type="submit" value="Rechercher" name="rechercher" />
</form><br/><br/>


<?php
if ($_POST['recherche'] == ''){

} 
else {

{
echo "<table>";
	$recherche=  htmlspecialchars ( $_POST [ 'recherche' ]);

	$base_choisie = $_POST['base_choisie'];
	$classer = $_POST['classer'];
	
$mots = explode(' ', $recherche); //s�paration des mots de la recherche � chaque espace gr�ce � explode
$nombre_mots = count ($mots); //comptage du nombre de mots
 
$valeur_requete = '';
for($nombre_mots_boucle = 0; $nombre_mots_boucle < $nombre_mots; $nombre_mots_boucle++) //tant que le nombre de mots de la recherche est sup�rieur � celui de la boucle, on continue en incr�mentant � chaque fois la variable $nombre_mots_boucle
{
$valeur_requete .= 'OR '.$base_choisie.' LIKE \'%' . $mots[$nombre_mots_boucle] . '%\''; //modification de la variable $valeur_requete
}
$valeur_requete = ltrim($valeur_requete,'OR'); //suppression de AND au d�but de la boucle

$search = $bdd->query("SELECT * FROM ".$table." WHERE ".$valeur_requete." ORDER BY ".$classer." "); 	//On envoie une requ�te qui, selon la recherche lira la table indiqu�e, cherchera les mot cl�s et classera les r�sultats en fonction de ce qu'aura indiqu� l'utilisateur

$arr = $results[0];


	echo "
	<caption>R&eacute;sultat de la recherche pour ".$recherche." :</caption>

   <tr>
       <th>Auteur</th>
       <th>Channel</th>
	   <th>Lien</th>
       <th>Tags</th>
       <th>Heure</th>
   </tr>";

// On affiche chaque entr�e
    while ($donnees = $search->fetch())
    {
	$fin = substr("".$donnees['link']."", -4); 
$debut = substr("".$donnees['link']."", 0, 35);  
$trans = array("," => ", ");	
  $date = date('d/m/Y H\hi', $donnees['date']); 
  
if (strlen($donnees['link']) <= 36)
 { 
 echo  '<tr> <td>'.$donnees['auteur'].'</td><td>'.$donnees['chan_orig'].' </td><td><a href="'.$donnees['link'].'">'.$donnees['link'].'</a></td><td>'.strtr($donnees['keywords'], $trans).'</td><td>'.$date.'</td></tr>';
}
else
{
 echo  '<tr> <td>'.$donnees['auteur'].'</td><td>'.$donnees['chan_orig'].' </td><td><a href="'.$donnees['link'].'">'.$debut.'...'.$fin.'</a></td><td>'.strtr($donnees['keywords'], $trans).'</td><td>'.$date.'</td></tr>';
}

}
	echo "</table>";
	
	
	}

}

    ?>
	

	
	