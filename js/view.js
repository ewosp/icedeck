/*
 * IceDeck Javascript bits
 * by Dereckson
 * (c) 2006-2007, Espace Win Open Source Project, all rights reserved.
 * 
 * Released under BSD License
 * http://www.espace-win.info/EWOSP/IceDeck
 *
 */

function UpdateProgressBar_cb () {
	//We've updated
	document.getElementById("updatingProgressBar").style.display = 'none';
}	

function UpdateProgressBar () {
	//We're updating ...
	document.getElementById("updatingProgressBar").style.display = 'inline';
	//Measures new %
	var bar = document.getElementById('ProgressBar');
	x = bar.offsetLeft;
	obj = bar;
	while (obj = obj.offsetParent) { //while there isn't obj parent anymore
		x += obj.offsetLeft;
	}
	card_completed = Math.round((xMousePos - x) / bar.width * 100);
	if (card_completed > 100) card_completed = 100; //as we can reach 101%
	//Updates through Sajax call
	x_sajax_update_completed(card_completed, UpdateProgressBar_cb);
	//Updates card view
	bar.src = "_includes/ProgressBar/ProgressBar.php/osx/" + bar.width + "/"
		+ card_completed + "/100";
	bar.alt = card_completed + " % completed";
	document.getElementById('completedValue').innerHTML = card_completed;
}
