<?php  
    //
    // HTML output
    //
    //Header
    $smarty->assign('PAGE_TITLE', 'Fatal error');
   
    //Cloud variables
    $smarty->assign('Title', $Title);
    $smarty->assign('Description', $Description);
    
    $smarty->display('header.tpl');
    $smarty->display('error.tpl');
    $smarty->display('footer.tpl');

?>