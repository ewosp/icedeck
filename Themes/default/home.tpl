	<div id=Content>
		<h2>Last cards</h2>
		<p>&nbsp;</p>
		<p>
{foreach from=$Cards item=Card}
		&nbsp;&nbsp;<a href='/{$Card->id}'>{$Card->title}</a><br />
{/foreach}
		</p>
	</div>

	<div id=MetaArea>
		<div class=logo><img src='_pict/IceDeck.jpg' alt='IceDeck logo' /></div>
                <div class="metaBloc metaInfos"><span class="metaBlocTitle">Alpha version</span>
		<br />
		&nbsp;<strong>Main Developer:</strong><br />&nbsp;&nbsp;Dereckson<br />&nbsp;<br />
		<b>&nbsp;Links:</b><br />
		&nbsp;&nbsp;<a href='http://www.espace-win.org/EWOSP/IceDeck/'>EWOSP</a> (fr)<br />
		&nbsp;&nbsp;<a href='http://www.dereckson.be/Blog/?q=icedeck'>Dereckson's blog</a> (en)
		
		<br />&nbsp;
		</div>

		<div class="metaBloc metaInfos">
			<span class="metaBlocTitle">About this IceDeck</span>
			<p>This is a sandbox, a development testing but will be the official Espace Win IceDeck, so most of the cards are real cards.</p>
		</div>

		<div class="metaBloc metaInfos">
			<span class="metaBlocTitle">À propos de cet IceDeck</span>
			<p>La majorité des cartes (toutes celles cohérentes) sont des cartes réelles répondant à la question <b>"que pouvez-vous faire 
pour aider Espace Win ?</b>.</p>
			<p>Il s'agit également d'un lieu de développement.</p>
		</div>
	</div>


