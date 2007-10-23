<?php
	//
	// Let's print the 20 last cards
        // TODO : replace by most popular, most recent, less popular, ...
	//

	$sql = "SELECT card_id FROM " . TABLE_CARDS . " ORDER BY card_id DESC LIMIT 20";
	$result = $db->sql_query($sql);
	while ($row = $db->sql_fetchrow($result)) {
		$Cards[] = new Card($row[card_id]);
	}
	
	//
	// HTML output
	//

	//Header
	$smarty->assign('PAGE_TITLE', "Welcome");
	$smarty->assign('PAGE_CSS', '/Themes/' . THEME . '/search.css');
	//$smarty->assign('SAJAX_JAVASCRIPT_CODE', sajax_get_javascript());
	//$smarty->assign('PAGE_JS', array('common.js', 'view.js'));
	$smarty->assign('ACCENT', 'lightblue');

	//Search variables
	$smarty->assign('Cards', $Cards);
	$smarty->assign('URL_new', '/new');

	$smarty->display('header.tpl');
	$smarty->display('home.tpl');
	$smarty->display('footer.tpl');
?>
