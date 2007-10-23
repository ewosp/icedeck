
<?php
	//  Theme, Accent & CSS must be defined and refer an existing accent file
	if (!$_GET['Accent'] || !$_GET['Theme']) die("Usage: cssloader.php?Theme=&lt;theme name>&Accent=&lt;accent name>&CSS=&lt;css .tpl file>");

	$accent_conf = "Themes/$_GET[Theme]/css/Accents/$_GET[Accent].conf";
	if (!file_exists($accent_conf)) {
		die("File not found : $accent_conf");
 	}

	//Smarty
	require('_includes/Smarty/Smarty.class.php');
	$smarty = new Smarty();

	//TODO : trouver solution plus élégante et compatible Windows
        $current_dir = dirname(__FILE__);
	$smarty->template_dir = $current_dir . '/Themes/' . $_GET['Theme'] . '/css'; 
	$smarty->compile_dir = $current_dir . '/cache/compiled';
	$smarty->cache_dir = $current_dir . '/cache';
	$smarty->config_dir = $current_dir;

	$smarty->config_load($accent_conf);
	$smarty->display($_GET['CSS'] . '.tpl');
?>
