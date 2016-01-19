<?php
// Gestionnaire d'erreur
//
// SQL_ERROR  : Erreur de requ�tes SQL
// HACK_ERROR : Appel d'une page o� l'utilisateur n'a pas acc�s

//Constantes
define ("SQL_ERROR",  65);
define ("HACK_ERROR", 99);
define ("GENERAL_ERROR", 117);

function message_die($msg_code, $msg_text = '', $msg_title = '', $err_line = '', $err_file = '', $sql = '')
{
	global $db, $Utilisateur;
	$sql_store = $sql;
	
	if ($msg_code == HACK_ERROR && $Utilisateur[user_id] < 1000) {
		global $LoginResult;
		foreach ($_POST as $name => $value) {
			$champs .= "<input type=hidden name=$name value=\"$value\" />";
		}
		$titre = "Qui �tes-vous ?";
		$debug_text = "Vous devez �tre authentifi� pour acc�der � cette page.";
		$debug_text .= "
		<FORM method='post'>
		$champs
		<table border='0'>
		  <tr>
			<td><STRONG>Login</STRONG></td>
			<td><input name='Login' type='text' id='Login'  value='$_POST[Login]' size='10' /></td>
			<td><STRONG>Mot de passe</STRONG></td>
			<td>
				<input name='MotDePasse' type='password' id='MotDePasse' size='10' />
				<input type='submit' name='LoginBox' value='Connexion' />
			</td>
		  </tr>
		  <tr> 
			<td align=center COLSPAN=4><a href='/?Topic=My&Article=Enregistrer'>Je d&eacute;sire ouvrir un compte</a></td>
		  </tr>
		</TABLE><span class=error>$LoginResult</span>
		</FORM>
		";
	} elseif ($msg_code == HACK_ERROR) {
		$titre = "Acc�s non autoris�";
		$debug_text = $msg_text;
	} elseif ($msg_code == SQL_ERROR) {
		$titre = "Erreur dans la requ�te SQL";
		$sql_error = $db->sql_error();
		$debug_text = $msg_text;
		if ( $err_line != '' && $err_file != '') $debug_text .= ' dans ' . $err_file. ', ligne ' . $err_line ;
		if ( $sql_error['message'] != '' ) $debug_text .= '<br />Erreur n� ' . $sql_error['code'] . ' : ' . $sql_error['message'];
		if ( $sql_store != '' ) $debug_text .= "<br /><strong>$sql_store</strong>";
	} elseif ($msg_code == GENERAL_ERROR) {
	    $titre = $msg_title;
		$debug_text = $msg_text;
		if ($err_line && $err_file) {
			$debug_text .= "<BR />$err_file, ligne $err_line";
		}
	}	
	
	echo "
	<TABLE height='100%' cellSpacing=0 cellPadding=0 width='100%' border=0>
  <TBODY>
  <TR>
    <TD vAlign=top align=middle>
      <TABLE cellSpacing=0 cellPadding=0 border=0>
        <TBODY> 
        <TR>
          <TD vAlign=top rowSpan=5><IMG height=177 alt='' 
            src='/_pict/error/notfound.jpg' width=163 border=0></TD>
          <TD colSpan=4><IMG height=2 alt='' src='/_pict/error/mrblue.gif' 
            width=500 border=0></TD>
          <TD><IMG height=2 alt='' src='/_pict/error/undercover.gif' width=1 
            border=0></TD></TR>
        <TR>
          <TD vAlign=bottom rowSpan=4 bgcolor='#FFFFFF'><IMG height=43 alt='' 
            src='/_pict/error/ecke.gif' width=14 border=0></TD>
          <TD vAlign=center align=middle rowSpan=2 bgcolor='#FFFFFF'> 
            <TABLE cellSpacing=1 cellPadding=0 width=470 border=0>
              <TBODY>
              <TR>
                <TD><FONT face='Verdana, Helvetica, sans-serif' color=red 
                  size=4><B>$titre</B></FONT><BR>
                  <IMG height=5 alt='' 
                  src='/_pict/error/undercover.gif' width=14 border=0><BR></TD></TR>
              <TR>
                <TD><FONT face='Verdana, Helvetica, sans-serif' color=black 
                  size=2>$debug_text</FONT></TD></TR></TBODY></TABLE></TD>
          <TD align=right width=2 rowSpan=2 bgcolor='#FFFFFF'><IMG height=146 alt='' 
            src='/_pict/error/mrblue.gif' width=2 border=0></TD>
          <TD bgcolor='#FFFFFF'><IMG height=132 alt='' src='/_pict/error/undercover.gif' width=1 
            border=0></TD>
        </TR>
        <TR>
          <TD><IMG height=14 alt='' src='/_pict/error/undercover.gif' width=1 
            border=0></TD></TR>
        <TR>
          <TD colSpan=2><IMG height=2 alt='' src='/_pict/error/mrblue.gif' 
            width=486 border=0></TD>
          <TD><IMG height=2 alt='' src='/_pict/error/undercover.gif' width=1 
            border=0></TD></TR>
        <TR>
          <TD colSpan=2><IMG height=27 alt='' src='/_pict/error/undercover.gif' 
            width=486 border=0></TD>
          <TD><IMG height=27 alt='' src='/_pict/error/undercover.gif' width=1 
            border=0></TD></TR></TBODY></TABLE>
      <P>&nbsp;</P>
      </TD></TR></TBODY></TABLE>
	";
	
	exit;
}
?>
