<?php

	//Loads the card we wish edit
	$id = $_POST['id'] ? $_POST['id'] : $_GET['id'];
	$card = new Card($id);
	
	
	if (!$id) {
		//New card
		$smarty->assign('PAGE_TITLE', 'New card');
	} else {
		//Edit card
		$smarty->assign('PAGE_TITLE', 'Edit ' . $card->title);
	}

	//If we've got a form, let's save it
	if ($_POST['CardEditor'] == 'Save') {
		//1. Sets card data
		$card->title = $_POST['title'];
		$card->text = $_POST['text'];
		if ($id) {
			$card->updated_date = time();
			$card->updated_by = $Utilisateur['user_id'];
		} else {
			//New card
			$card->created_date = time();
                        $card->created_by = $Utilisateur['user_id'];
			$card->updated_date = 0;
			$card->updated_by = 0;
		}

		//2. Logo management
		if ($_POST['DeleteLogo'] && !$_FILES['NewLogo']['error']) {
			$errors[] = $Lang['Errors']['delete_logo_and_upload_one_error'];
			$_POST['DeleteLogo'] = false;
		}

		if ($_POST['DeleteLogo']) {
			$card->logo = '';
		} elseif ( $_FILES['NewLogo']['error'] != 4) {
			#We've a file !

			#We ignore $_FILES[NewLogo][error] 4, this means no file has been uploaded
			#(so user doesn't want upload a new file)
			#See http:/www.php.net/features.file-upload and http://www.php.net/manual/en/features.file-upload.errors.php about common errors
			#Not valid before PHP 4.2.0
			switch ($_FILES['NewLogo']['error']) {
				case 0:
				#There is no error, the file uploaded with success.

				#Gets filename				
				$base = get_name_from_file($_FILES['NewLogo']['name']);
				$ext =  get_extension_from_file($_FILES['NewLogo']['name']);

				$filename = $base . '.' . $ext; 
				$i = 1; //counter, for file name appending purpose
				
				while (file_exists(DIR_LOGOS . $filename)) {
					#This filename already exists, so we append it with 3-digits numeric value (e.g. 001, 002, ...).
					#When 999 is overtaken, filename's appended by 1000, 1001, ..., 9999, 10000, ...
					$filename = $base . zerofill($i, 3) . '.' . $ext;
					$i++;
				}

				if (!move_uploaded_file($_FILES['NewLogo']['tmp_name'], DIR_LOGOS . $filename)) {
					$errors[] = $Lang['Errors']['move_upload_error'];
				} else {
					//Sets logo filename in card data
					$card->logo = $filename;
				}
				break;

				#TODO : more explicit error messages

				default:
				$errors[] = $Lang['Errors']['other_upload_error'];
			}
		}

		//3. Saves the card
		$card->save();
		$id = $card->id;
			
		//4. Keywords
		//TODO : more clever code
		Keywords::Truncate('C', $card->id);
		$keywords = explode(', ', $_POST['keywords']);
		foreach ($keywords as $keyword) {
			Keywords::Add('C', $card->id, $keyword);
		}
		//Yes, it's absolutely not optimized and very stupid to all delete and add again !
		$action = 'view';	
	} elseif ($_GET['action'] == 'clone') {
		//User's cloning a card, so erase base card info
		unset($id, $card->id);
	}


if ($action == 'view') {
	//Come back to view mode after sucessfully posted form
	//Let's warn that our object $card is already there :)
	define('CARD_ALREADY_INITIALIZED', true);
	include('view.php');
} else {
	//
	// HTML OUTPUT
	//

	//Header variables
	$smarty->assign('PAGE_CSS', "/cssloader.php?Theme=default&Accent=$card->accent&CSS=editor");
	$smarty->assign('ACCENT', $card->accent);

	//Edit variables
	$smarty->assign('card_id', $id);
	$smarty->assign('card_title', $card->title);
	$smarty->assign('card_text', $card->text);
	$smarty->assign('card_keywords_string', $card->keywords_string);
	if ($card->logo) $smarty->assign('card_logo', URL_LOGOS . $card->logo);
	$smarty->assign('card_logo_alt', "Card $card->title logo");
	$smarty->assign('card_completed', $card->completed);
        $smarty->assign('card_points', $card->points);
        $smarty->assign('card_created_date', $card->created_date);
        $smarty->assign('card_created_by', "<a href='/who/$card->created_by'>" . getnom($card->created_by) . '</a>');
        $smarty->assign('card_updated_date', $card->updated_date);
        $smarty->assign('card_updated_by', "<a href='/who/$card->updated_by'>" . getnom($card->updated_by) . '</a>');

	//TODO - keywords representation
	//$smarty->assign('card_keywordsRepresentation_count', count($keywordsRepresentation));
	//$smarty->assign('card_keywordsRepresentation, $keywordsRepresentation);

	$smarty->assign('upload_max_filesize', ini_get('upload_max_filesize'));

	//Loads template
	$smarty->display('header.tpl');
	$smarty->display('edit.tpl');
	$smarty->display('footer.tpl');		
}

?>
