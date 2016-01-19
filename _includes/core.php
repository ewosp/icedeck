<?php
//No register globals
ini_set('register_globals', 'off');
error_reporting(E_ALL & ~E_NOTICE);

//Appel aux libraires externes
include_once("config.php");               //Configuration
include_once("error.php");               //Le gestionnaire d'erreur principal
include_once("mysql.php");              //Classe SQL + superbe gestion d'erreurs :)
include_once("sessions.php");        //Fonctions de gestion des sessions

function getnom ($id)
//Renvoie le login d'un ID donn�, si il existe
{
	global $db;
	$sql = "SELECT username FROM Utilisateurs WHERE user_id = '$id'";
	if (!$result = $db->sql_query($sql)) message_die(SQL_ERROR, "Impossible d'interroger la table Utilisateurs.", '', __LINE__, __FILE__, $sql);
	$row = $db->sql_fetchrow($result);
	return $row['username'];
}

function s ($nombre) {
	if ($nombre > 1) return "s";
}

function x ($nombre) {
	if ($nombre > 1) return "x";
}

// S�bastien Santoro - cr�ation mi avril 2002
//                     forte amm�lioration 29 mai 2002
//                     ajout de caract�res 13 sep 2002
//                     ajout de caract�res 25 fev 2003

// Transforme les caract�res unicodes en entit�s HTML
// Transforme les espaces du chat+ en vrais espaces
function Unicode2HTML ($texte)
{
	$texte = str_replace ("é", "&eacute;", $texte);
	$texte = str_replace ("ê", "&ecirc;", $texte);
	$texte = str_replace ("è", "&egrave;", $texte);
	$texte = str_replace ("ô", "&ocirc;", $texte);
	$texte = str_replace ("û", "&ucirc;", $texte);
	$texte = str_replace ("î", "&icirc;", $texte);
	$texte = str_replace ("", "&euro;", $texte);
	$texte = str_replace ("ç", "&ccedil;", $texte);
	$texte = str_replace ("»", "�", $texte);
	$texte = str_replace ("«", "�", $texte);
	$texte = str_replace ("â",  "&acirc;", $texte);
	$texte = str_replace ("°", "&deg;", $texte);
	$texte = str_replace ("�",  "&agrave;", $texte);
	$texte = str_replace ("�",  " ", $texte);	
	return $texte;
}

//Idem, mais utilise des caract�res normaux
//2006-08-20 22:31
function clean_unicode ($texte) {
	$texte = str_replace ("é", "�", $texte);
	$texte = str_replace ("ê", "�", $texte);
	$texte = str_replace ("è", "�", $texte);
	$texte = str_replace ("ô", "�", $texte);
	$texte = str_replace ("û", "�", $texte);
	$texte = str_replace ("î", "�", $texte);
	$texte = str_replace ("", "�", $texte);
	$texte = str_replace ("ç", "�", $texte);
	$texte = str_replace ("»", "�", $texte);
	$texte = str_replace ("«", "�", $texte);
	$texte = str_replace ("â",  "�", $texte);
	$texte = str_replace ("°", "0", $texte);
	$texte = str_replace ("�",  "�", $texte);
	$texte = str_replace ("�",  " ", $texte);
	return $texte;
}

function tab_Unicode2HTML (&$item1, $key) {
    $item1 = Unicode2HTML($item1);
}

function Texte2HTML ($texte, $htmlentities = false)
{
	//Unicode
	$texte =  Unicode2HTML ($texte);
	
	//Filtre HTML
	if ($htmlentities) $texte = htmlentities($texte);

	//Rendre les e-mails cliquables
	$texte = preg_replace ("/([_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)+)/i", "<A HREF=\"mailto:\\1\">\\1</A>", $texte);

	//Rendre les URLs cliquables
	//TODO www
	//TODO Traiter diff�remment www.espace-win.net
	$texte = preg_replace ("@([[:alnum:]]+)://([^[:space:]]*)([[:alnum:]#?/&=])@i", "<A HREF=\"\\1://\\2\\3\" TARGET=\"_blank\">\\1://\\2\\3</A>", $texte);
	
	// \n -> <br />
	$texte = nl2br ($texte);

	//Merci c'est utilisable d�sormais :)
	return $texte;
}

function str_range($chaine, $debut = 0, $fin = 'end')
//Retourne une partie de la cha�ne du caract�re <debut> au caract�re <fin>
//(se comporte comme la fonction tcl string range)
{
	if (!$fin || $fin === false) message_die(GENERAL_ERROR, "Dans str_range, la variable fin n'est pas d�finie !", 'Erreur de syntaxe');
	if ($fin == 'end') $fin = strlen($chaine) - 1;
	if ($debut > $fin) message_die(GENERAL_ERROR, "Dans str_range(chaine, $debut, $fin),<br />$debut est sup�rieur � $fin !!!", 'Erreur de syntaxe');
	return substr($chaine, $debut, $fin - $debut + 1);
}

function putdebug ($texte, $user_id = 1148)
//Ecrit une ligne de debug
{
	global $Utilisateur;
	if ($Utilisateur[user_id] == $user_id) echo "<p class=error>$texte</p>";
}

function dprint_r ($mixed) {
	echo "<pre>", print_r($mixed, true), "</pre>";
}

function zerofill ($nombre, $chiffres) {
	while (strlen($nombre) < $chiffres) {
		$nombre = 0 . $nombre;
	}
	return $nombre;
}

#
# File manipulation functions
#

function get_name_from_file ($filename) {
        //removes path
        $pos = strrpos($filename, "/");
        $filename = substr($filename, ++$pos);
	//removes ext
        $pos = strrpos($filename, ".");
        return substr($filename, 0, $pos);
}

function get_extension_from_file ($filename) {
        $pos = strrpos($filename, ".") + 1;
        return substr($filename, $pos);
}

// ------------------------------------------------------------------------- //
// Cha�ne al�atoire                                                          //
// ------------------------------------------------------------------------- //
// Auteur: Pierre Habart                                                     //
// Email:  p.habart@ifrance.com                                              //
// Web:                                                                      //
// ------------------------------------------------------------------------- //

function genereString($format)
{
    mt_srand((double)microtime()*1000000);
    $str_to_return="";

    $t_alphabet=explode(",","A,B,C,D,E,F,G,H,I,J,K,L,M,N,O,P,Q,R,S,T,U,V,W,X,Y,Z");
    $t_number=explode(",","1,2,3,4,5,6,7,8,9,0");

    for ($i=0;$i<strlen($format);$i++)
    {
        if (preg_match("/^[a-zA-Z]/",$format[$i]))
        {
            $add=$t_alphabet[mt_rand() % sizeof($t_alphabet)];
            if (preg_match("/^[a-z]/",$format[$i]))
                $add=strtolower($add);
        }
        elseif(preg_match("/^[0-9]/",$format[$i]))
            $add=$t_number[mt_rand() % sizeof($t_number)];
        else $add="?";

        $str_to_return.=$add;
    }
    return $str_to_return;
}

function generer_hexa($longueur) {
        mt_srand((double)microtime()*1000000);
        $str_to_return="";
        $t_number=explode(",","1,2,3,4,5,6,7,8,9,0,A,B,C,D,E,F");
        for ($i = 0 ; $i < $longueur ; $i++) {
                $str_to_return .= $t_number[mt_rand() % sizeof($t_number)];
        }
    return $str_to_return;
}

?>
