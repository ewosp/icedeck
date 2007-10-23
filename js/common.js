/*
 * IceDeck Javascript bits
 * by Dereckson
 * (c) 2006 Espace Win Open Source Project, all rights reserved.
 * 
 * Released under BSD License
 * http://www.espace-win.info/EWOSP/IceDeck
 *
 */

var IE = document.all ? true : false;
if (!IE) document.captureEvents(Event.MOUSEMOVE);
document.onmousemove = GetMousePosition

function GetMousePosition (e) {
	if (IE) { // grab the x-y pos.s if browser is IE
		tempX = event.clientX + document.body.scrollLeft
		tempY = event.clientY + document.body.scrollTop
	} else {  // grab the x-y pos.s if browser is NS
		tempX = e.pageX
		tempY = e.pageY
	}

	// catch possible negative values in NS4
	if (tempX < 0){tempX = 0}
	if (tempY < 0){tempY = 0}  

	// copy to global vars
	xMousePos = tempX;
	yMousePos = tempY;

	return true;
}