<?php

	//Have we a query ?
	if (!isset($_GET['q'])) {
		$Title = "Unable to perform search";
		$Description = "Your query is empty.";
		include('errorbox.php');
		die();
	}

	//
	// SAJAX CODE
	//
/*
	//Sajax functions
	function sajax_update_completed ($completed) {
		global $card, $Utilisateur;
		$card->completed = $completed;
		$card->updated_date = time();
		$card->updated_by = $Utilisateur['user_id'];
		$card->save();
	}

	sajax_init();
	sajax_export("sajax_update_completed");
	sajax_handle_client_request();
*/

	//
	// Perfoms the search
	//

	//We need a query
	$query = explode(' ', $_GET['q']);

	require_once('_includes/IceDeck/Keywords.php');
	if (count($cards_id = Keywords::GetResources('C', $query))) {
		foreach ($cards_id as $card_id) {
			$Cards[] = new Card($card_id);
		}
	}
	
	//
	// HTML output
	//
	//$value = '<td>'.$i.'</td><td><a href="index.php?guisx=show&pseudo>'.$rows['pseudo'].'</td><td>'.$rows['date_creation'].'</td><td>'.$n_niveau[$rows['niveau']].'</td></a>';
	
	//Header
	$smarty->assign('PAGE_TITLE', "Search results - " . $_GET['q']);
	$smarty->assign('PAGE_CSS', 'Themes/' . THEME . '/search.css');
	//$smarty->assign('SAJAX_JAVASCRIPT_CODE', sajax_get_javascript());
	//$smarty->assign('PAGE_JS', array('common.js', 'view.js'));
	//$smarty->assign('ACCENT', $card->accent);

	//Search variables
	$smarty->assign('Cards', $Cards);
	$smarty->assign('URL_new', '/new');

	$smarty->display('header.tpl');
	$smarty->display('search.tpl');
	$smarty->display('footer.tpl');
?>
