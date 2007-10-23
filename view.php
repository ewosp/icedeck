<?php

	//Load card
	if (!defined('CARD_ALREADY_INITIALIZED')) {
		if (!$_GET['id']) message_die(GENERAL_ERROR, 'id was expected', 'URL error');
		if (!is_numeric($_GET['id']) && $_GET['id'] != 'rnd') message_die(GENERAL_ERROR, 'bad id: ' + $_GET['id'], 'URL error');
		$card = new Card($_GET[id]);
	}

	//
	// SAJAX CODE
	//

	//Sajax functions
	function sajax_update_completed ($completed) {
		global $card, $Utilisateur;
		$card->completed = $completed;
		$card->updated_date = time();
		$card->updated_by = $Utilisateur['user_id'];
		$card->save();
	}

	//$sajax_debug_mode = 1;
	sajax_init();
	sajax_export("sajax_update_completed");
	sajax_handle_client_request();

	//
	// HTML output
	//
	
	//Header
	$smarty->assign('PAGE_TITLE', $card->title);
	$smarty->assign('PAGE_CSS', 'Themes/' . THEME . '/view.css');
	$smarty->assign('SAJAX_JAVASCRIPT_CODE', sajax_get_javascript());
	$smarty->assign('PAGE_JS', array('common.js', 'view.js'));
	$smarty->assign('ACCENT', $card->accent);

	//View variables
	$smarty->assign('card_id', $card->id);
	$smarty->assign('card_title', $card->title);
	$smarty->assign('card_text', nl2br($card->text));
	$smarty->assign('card_keywords_URL', $card->keywords_URL);
	if ($card->logo) $smarty->assign('card_logo', URL_LOGOS . $card->logo);
	$smarty->assign('card_logo_alt', "Card $card->title logo");
	$smarty->assign('card_completed', $card->completed);
	$smarty->assign('card_points', $card->points);
	$smarty->assign('card_created_date', $card->created_date);
	$smarty->assign('card_created_by', "<a href='/who/$card->created_by'>" . getnom($card->created_by) . '</a>');
	$smarty->assign('card_updated_date', $card->updated_date);
	$smarty->assign('card_updated_by', "<a href='/who/$card->updated_by'>" . getnom($card->updated_by) . '</a>');

	$smarty->assign('URL_edit', "/edit/$card->id");
	$smarty->assign('URL_new', '/new');

	$smarty->display('header.tpl');
	$smarty->display('view.tpl');
	$smarty->display('footer.tpl');
?>
