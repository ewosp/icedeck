<?php
	//TMP (debug purpose)
	//include('dBug.php');

	//Pluton library
	include('_includes/core.php');

	//Sajax
	include('_includes/Sajax.php');
        //Sets the true URL and not the rewrited one as Sajax remote URL
	$sajax_remote_uri = '/?' . $_SERVER["QUERY_STRING"];

	//IceDeck library
        include('_includes/IceDeck/Card.php');
	include('_includes/IceDeck/Keywords.php');

	//Session
        $IP = encode_ip($_SERVER["REMOTE_ADDR"]);
        session_start();
        $_session[ID] = session_id();
        SessionUpdate(); //updates or creates the session
        include("_includes/login.php"); //login/logout
        $Utilisateur = GetUtilisateurInfos($IP); //Gets current user infos

	//Preferences
	define('LANG', 'english');
	define('THEME', 'default');

	//Smarty
	require('_includes/Smarty/Smarty.class.php');
	$smarty = new Smarty();

	//TODO : trouver solution plus élégante et compatible Windows
	
	$current_dir = dirname(__FILE__);
	$smarty->template_dir = $current_dir . '/Themes/' . THEME;
	$smarty->compile_dir = $current_dir . '/cache/compiled';
	$smarty->cache_dir = $current_dir . '/cache';
	$smarty->config_dir = $current_dir;

	//Theme preferences
	$smarty->config_load("Themes/" . THEME . "/theme.conf");
	//Language preferences
	$smarty->config_load("Lang/" . LANG . "/core.conf");
	$smarty->config_load("Lang/" . LANG . "/personalize.conf");

	//Now, we include correct script
        switch ($_GET['action']) {
		case 'new':
		case 'clone':
		case 'edit':
		include('edit.php');
		break;

		case 'view':
		include('view.php');
		break;
        
                case 'cloud':
                include('cloud.php');
                break;
        
                case 'search':
		include('search.php');
		break;                
			
		case '':
		include('home.php');
		break;

		case 'pdf':
		case 'print':
		//TODO
		$Description = "Action $_GET[action] not yet implemented.";
                $Title = 'Invalid URL';
                include('errorbox.php');
		break;
        
                default:
                $Description = 'Action ' . $_GET['action'] . ' doesn\'t exist.';
                $Title = 'Invalid URL';
                include('errorbox.php');
	}
?>
