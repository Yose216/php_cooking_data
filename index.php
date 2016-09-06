<?php
/*************************************************************************************************************************
**************************************************************************************************************************
******************************************* DICO *************************************************************************
**************************************************************************************************************************
*************************************************************************************************************************/


$string = file_get_contents("dictionnaire.txt", FILE_USE_INCLUDE_PATH);
$dico = explode("\n", $string);
$tailleTab = count($dico);//compte les element du tableaux

echo 'Il y a '. $tailleTab .' mots dans le dico.txt</br>';//affiche le resultat;

$motsTab15 = array();//on declare trois variable avec un tableaux vide
$motsEnW = array();
$motsEnQ = array();

foreach($dico as $mots) {//on parcourt le tableaux
    if (strlen($mots) == 15) {//compte le nombre de caractere
        array_push($motsTab15, $mots);//envoi les mots avec 15 caractere dans la variablea avec un tableaux vide declarer //plus haut
    }

    if(strpos($mots, "w") !== false){//trouve le nombre de mots avec w
        array_push($motsEnW, $mots);
    }

    if(substr($mots, -1) == "q") { //trouve le nombre de mots qui fini par q
        array_push($motsEnQ, $mots);
    }
}

echo 'Il y a '. count($motsTab15) .' mots de 15 caractere<br/>'; //compte nos trois tableaux
echo 'Il y a ' .count($motsEnW) .' mots qui contienent la lettre W<br/>';
echo 'Il y a ' .count($motsEnQ) .' mots qui finissent par la lettre Q<br/>';


/*************************************************************************************************************************
**************************************************************************************************************************
******************************************* FILM *************************************************************************
**************************************************************************************************************************
*************************************************************************************************************************/


$string = file_get_contents("films.json", FILE_USE_INCLUDE_PATH);
$brut = json_decode($string, true);
$top = $brut["feed"]["entry"];

echo '<pre>';
print_r($top[0]); //affiche 1 element du tableaux
echo'</pre>';

/***************************************************************************
************************** FILM SOUS FORME DE LISTE ************************
***************************************************************************/

$film2 = array();
echo '<h1>Tout les films</h1>';
foreach($top as $film){ //parcours le tableaux
    print_r('<ul><li>'. $film["im:name"]["label"]. '</li></ul>');//affiche tout les nom de film sous forme de liste
    array_push($film2, $film["im:name"]["label"]);//envoi les titre dans un nvx tableaux
}

/***************************************************************************
************************** 10 PREMIERS FILM ********************************
***************************************************************************/

echo '<h1>Les 10 premiers films</h1>';
for($i=1;$i<=10; $i++){ //parcourt les 10 premiers element du tableaux
    echo $i. ' ' .$top[$i-1]["im:name"]["label"]. '<br/>'; //affiche les 10 premiers film
}

/***************************************************************************
************************** FILM GRAVITY ************************************
***************************************************************************/

echo '<h1>Classement Gravity</h1>Le film Gravity est à la ';
print_r(array_search("Gravity",$film2)+1); //affiche la position de gravity +1 pour avoir son classemnt
echo '<br/>';

/***************************************************************************
************************** FILM LEGO ***************************************
***************************************************************************/

for ($i = 0; $i < count($top); $i++) {
    if ($top[$i]["im:name"]["label"] === "The LEGO Movie") {
        echo "<br/>The LEGO Movie a été réalisé par " . $top[$i]["im:artist"]["label"] .'<br/>';
    }
}


/***************************************************************************
************************** FILM 2000 ***************************************
***************************************************************************/

$date = array();
foreach($top as $film){
    array_push($date, $film["im:releaseDate"]["label"]);//envoi la date du film en dans un nvx tableaux
}
echo'<br/>';
print_r(count(array_search(2000,$date)));//cherche et compte le nombre de film en 2000
echo ' film sortie en 2000';


/***************************************************************************
************************** FILM ANCIENS/RECENTS ****************************
***************************************************************************/

for($i = 0; $i < count($top); $i++){
    $titreDate[$top[$i]["im:name"]["label"]] = $top[$i]["im:releaseDate"]["label"] ;//creations tableaux associatif
}

echo '<br/><br/>'. array_search(min($titreDate), $titreDate). ' est le film le plus anciens : '. min($titreDate);//date min
echo '<br/><br/>'. array_search(max($titreDate), $titreDate). ' est le film le plus recent : '. max($titreDate);//date max

/***************************************************************************
***************************** CATEGORIES ***********************************
***************************************************************************/

$categories = [];//nvx tableaux
for ($i = 0; $i <count($top); $i++) {
    array_push($categories, $top[$i]["category"]["attributes"]["label"]);//on met la categorie dans le nvx tableaux
}    
$plusRepresente = array_count_values($categories); //compte les valeur 

echo '<br/><br/>La categorie la plus presente est '. array_search(max($plusRepresente), $plusRepresente);

/***************************************************************************
***************************** REALISATEUR **********************************
***************************************************************************/

$realisateur = [];
for ($i = 0; $i <count($top); $i++) {
    array_push($realisateur, $top[$i]["im:artist"]["label"]);
}
$plusRepresente = array_count_values($realisateur); 

echo '<br/><br/>Le realisateur la plus present est '. array_search(max($plusRepresente), $plusRepresente) .'<br/>';

/***************************************************************************
********************************* PRIX *************************************
***************************************************************************/

$prix = array ();
for ($i = 0; $i < 10; $i++) {
    array_push($prix, $top[$i]["im:price"]["attributes"]["amount"]);
}
echo '<br/>Le prix total des 10 premiers film est de '. array_sum($prix) .'$';

/***************************************************************************
****************************** MOIS DE SORTIE ******************************
***************************************************************************/

$date = array();
for ($i=0; $i <count($top); $i++){
    array_push($date, strtok($top[$i]["im:releaseDate"]["attributes"]["label"], " "));
    //strtok = coupe la valeur aux premier espace pour sortir juste le mois de la date
}
/*echo '<pre>';
print_r($date);
echo '</pre>';*/

$plusDeMois = array_count_values($date);
echo '<br/><br/>Le mois de '. array_search(max($plusDeMois), $plusDeMois) .' est le mois ou il y a eu le plus de sortie de film <br/>';

/***************************************************************************
***************************** PETIT BUDGET *********************************
***************************************************************************/
$petitPrix =  array();

for ($i=0; $i <count($top); $i++){
    if($top[$i]["im:price"]["attributes"]["amount"]<8){
        array_push($petitPrix,$top[$i]["im:name"]["label"]);
    }
}
echo '<br/>Top 10 films les moins chers : <br/><br/>';
for($i=1;$i<=10; $i++){
    echo $i .' '. $petitPrix[$i-1] .'<br/>';
}

?>