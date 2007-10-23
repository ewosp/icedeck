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
		&nbsp;&nbsp;<a href='http://www.espace-win.info/EWOSP/IceDeck/'>EWOSP</a> (fr)<br />
		&nbsp;&nbsp;<a href='http://www.dereckson.be/Blog/?q=icedeck'>Dereckson's blog</a> (en)
		
		<br />&nbsp;
		</div>

		<div class="metaBloc metaInfos">
			<span class="metaBlocTitle">À propos de cet IceDeck</span>
			<p>J'utilise mon IceDeck perso comme base de données contenant <a href='/search/category:project'>mes projets logiciels en cours</a>.</p>
		</div>
	</div>


