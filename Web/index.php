<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
<head>
	<title>Oracle - interface de recherche</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" media="screen" type="text/css" title="Oracle" href="css.css" />
</head>
<body>
	<div id="corps">
		<h2> Oracle Recherche, Accepte et Consulte les Liens Etonnants</h2>
		<form method="get" action="#" id="outils">
			<div id="recherche">
				Recherche<br />
				<input type="text" name="recherche" id="champ_recherche" />
			</div>
			<div id="type">
				Type<br />
				<input type="radio" name="champ" value="keywords" id="r_tags" checked="checked" /><label for="r_tags">Tags</label>
				<input type="radio" name="champ" value="auteur" id="r_auteur" /> <label for="r_auteur">Auteur</label>
				<input type="radio" name="champ" value="chan_orig" id="r_chan" /><label for="r_chan">Salon</label>
			</div>
			<div id="valider">
				<input type="submit" value="Valider" />
			</div>
			<div id="tri">
				Trier par<br />
				<input type="radio" name="sort" value="id" id="date" /> <label for="date">Date</label>
				<input type="radio" name="sort" value="auteur" id="auteur" /> <label for="auteur">Auteur</label>
				<input type="radio" name="sort" value="chan_orig" id="chan" /> <label for="chan">Salon</label>
			</div>
		</form>
		<table>
			<tr>
				<th>#</th>
				<th>Auteur</th>
				<th>Salon</th>
				<th>Lien</th>
				<th>Tags</th>
				<th>Heure</th>
				<th>Modifier les tags</th>
			</tr>
<?php
// Configuration
include('config.php');

// Connexion � la BDD
$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
$bdd = new PDO("sqlite:".$base);

// Ajout de tags
if (isset($_POST['tags']) AND isset($_POST['id']))
{
	$tags = sqlite_escape_string(htmlspecialchars($_POST['tags']));
	$id = sqlite_escape_string(htmlspecialchars($_POST['id']));
	
	$reponse = $bdd->query("SELECT keywords FROM ".$table." WHERE id='".$id."'");
	$donnees = $reponse->fetch();

	$bdd->exec("UPDATE ".$table." SET keywords='".$donnees['keywords'].strtr($tags,  array(" " => ",")).",' WHERE id='".$id."'");
}

//Recherche
if (isset($_GET['recherche']))
{
	$recherche = sqlite_escape_string(htmlspecialchars($_GET['recherche']));
	$champ = sqlite_escape_string(htmlspecialchars($_GET['champ']));
	$search = " WHERE ".$champ." LIKE '%".strtr($recherche,  array(" " => "OR ".$champ." LIKE '%"))."%'";
}
else
{
	$search = "";
}

// Classement
if (isset($_GET['sort']))
{
	$classement = sqlite_escape_string(htmlspecialchars($_GET['sort']));
}
else
{
	$classement = 'id';
}

//Requ�te
$reponse = $bdd->query("SELECT * FROM '".$table."'".$search." ORDER BY ".$classement." DESC LIMIT 0, ".$lines);

// Affichage
while ($donnees = $reponse->fetch())
{
	$raw_link = htmlspecialchars($donnees['link']);
	if (strlen($raw_link) <= $max_link_length)	// Si le lien n'est pas trop long
	{ 
		$link = $raw_link;
	}
	else												// Si le lien est trop long
	{
		$fin = substr($donnees['link'], -4); 						// Les 4 derniers
		$debut = substr($donnees['link'], 0, $max_link_length-7);	// Les n-7 premiers
		$link = htmlspecialchars($debut.'...'.$fin);
	}
	echo '
			<tr id="row'.$donnees['id'].'">
				<td>'.$donnees['id'].'</td>
				<td>'.$donnees['auteur'].'</td>
				<td>'.$donnees['chan_orig'].' </td>
				<td><a href="'.$raw_link.'">'.$link.'</a></td>
				<td>'.strtr($donnees['keywords'], array("," => ", ")).'</td>
				<td>'.date('d/m/Y H\hi', $donnees['date']).'</td>
				<td>
					<form method="post" action="index.php#row'.$donnees['id'].'">
						<input type="hidden" name="id" value="'.$donnees['id'].'" />
						<input type="text" name="tags" />
						<input type="submit" value="Ajouter"/>
					</form>
				</td>
			</tr>';
}
$reponse->closeCursor(); ?>
		</table>
	</div>
	<div id="footer">
		<p><a href="http://trac.druil.net/Oracle/">Oracle</a> HTTP Interface</p>
	</div>
</body>
</html>
