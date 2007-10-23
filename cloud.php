<?php
    require_once('_includes/cloud.php');

    //Ensures data integrity
    Keywords::Clean();
    Keywords::SyncCount();    

    //Color cloud
    $table = TABLE_CARDS;
    $field = 'card_accent';
    $qt = 'count(card_id)';
    $nullString = 'incolor';
    $colorCloud = new Cloud($table, $field, $qt);
    $colorCloud->nullString = $nullString;
    $colorCloud->Compute();
    
    //Regular cloud
    //Color cloud
    $table = TABLE_KEYWORDS;
    $field = 'keyword_word';
    $qt = 'keyword_count';
    $nullString = '(blank keyword)';
    $Cloud = new Cloud($table, $field, $qt);
    $Cloud->nullString = $nullString;
    $Cloud->Compute();
    
    //
    // HTML output
    //
    
    //Header
    $smarty->assign('PAGE_TITLE', "Cloud");
    $smarty->assign('PAGE_CSS', 'Themes/' . THEME . '/cloud.css');
   
    //Cloud variables
    $smarty->assign('KeywordsCloud', $Cloud->cloud);
    $smarty->assign('ColorCloud', $colorCloud->cloud);
    
    $smarty->display('header.tpl');
    $smarty->display('cloud.tpl');
    $smarty->display('footer.tpl');
?>
