<?
setcookie('PlutonLogin', '', time() - 3600);
//Gestion du login/logout
	$IP = encode_ip($_SERVER["REMOTE_ADDR"]);
	if ($_POST["LoginBox"]=="Connexion") {
		//GESTION LOGIN
		$Login = $_POST["Login"];
		$sql = "SELECT user_password,isRealUser,user_active,isSurfBoardian,user_id FROM Utilisateurs WHERE username = '$Login'";
		if ( !($result = $db->sql_query($sql)) ) message_die(SQL_ERROR, "Impossible d'interroger le listing des utilisateurs", '', __LINE__, __FILE__, $sql);
		if ($row = $db->sql_fetchrow($result)) {
			//Login existe
			if ($row["isRealUser"] == 0) {
				//DUMMY - HACKING ATTEMPT
				$LoginResult = "Ce compte n'est pas r�el mais est destin� � l'usage exclusif de divers robots.";
			} elseif ($row["user_active"] == 0) {
				$LoginResult = "Votre compte n'a pas �t� activ�.";
				if ($row["isSurfBoardian"]) {
					//ANCIEN COMPTE SURFBOARD
					$LoginResult .= "<br />En tant qu'ancien (?) membre de #Win, consultez un administrateur.";
				} else {
					//NO MAIL REPLY
					$LoginResult .= "<br />Avez-vous confirm� votre inscription en validant l'e-mail que vous avez re�u ? En cas de probl�me, <a href='?Topic=AProposDe&Article=ContactezNous&To=Tech&Objet=%5BCompte%20universel%5D%20Probl%E8me%20premi%E8re%20activation'>contacter notre support technique</a>.";
				}
			} elseif ($row["user_password"] != md5($_POST["MotDePasse"])) {
				//PASS NOT OK
				$LoginResult = "Mot de passe incorrect. En cas d'oubli, <a href='?Topic=My&Article=MotDePassePerdu'>contacter notre support technique</a>.";
			} else {
				$sql = "UPDATE Sessions SET user_id = '$row[user_id]' WHERE session_id LIKE '$_SESSION[ID]'";
				if ( !($result = $db->sql_query($sql)) ) message_die(SQL_ERROR, "Impossible de proc�der � la connexion", '', __LINE__, __FILE__, $sql);
				$data[user_id] = $row[ID];
				$data[CleDeVerification] = md5($row[Password]);
				setcookie(
					'PlutonLogin',
					serialize($data),
					time() + 31 * 24 * 3600,
					'/',
					".espace-win.info",
					0
				);
			}				
		} else {
			//Login n'existe pas
			$LoginResult = "Le login que vous avez entr�, $Login, n'est pas correct.";
		}
	} elseif ($_POST["LoginBox"]=="D�connexion") {
		//GESTION LOGOUT
		$sql = "UPDATE Sessions SET user_id = '-1' WHERE session_id LIKE '$_SESSION[ID]'";
		if ( !($result = $db->sql_query($sql)) ) message_die(SQL_ERROR, "Impossible de proc�der � la d�connexion", '', __LINE__, __FILE__, $sql);
		$_GET['Topic'] = '';
		$_GET['Article'] = '';
		setcookie(
			'PlutonLogin',
			'DECO',
			time() - 60,
			'/',
			".espace-win.info",
			0
		);
	} elseif ($_COOKIE[PlutonLogin]) {
		//GESTION COOKIE
		$dat = unserialize(stripslashes($_COOKIE[PlutonLogin]));
		if (!is_numeric($dat[user_id])) {
			//Cookie sans user_id, on destroy
			setcookie(
				'PlutonLogin',
				'DECO',
				time() - 60,
				'/',
				".espace-win.info",
				0
			);
		} else {
			$sql = "SELECT user_password FROM Utilisateurs WHERE user_id = $dat[user_id]";
			if ($result = $db->sql_query($sql)) {
					$row = $db->sql_fetchrow($result);
					if ($dat[CleDeVerification] == $row[0]) {
						//OK, Login
						$sql = "UPDATE Sessions SET user_id = $dat[user_id] WHERE session_id LIKE '$_SESSION[ID]'";
						if ( !($result = $db->sql_query($sql)) ) message_die(SQL_ERROR, "Impossible de proc�der � la connexion", '', __LINE__, __FILE__, $sql);
					} else {
						//On efface ce cookie erron�
						setcookie(
							'PlutonLogin',
							'DECO',
							time() - 60,
							'/',
							".espace-win.info",
							0
						);
					}
			}
		}
	}
?>
