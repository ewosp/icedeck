<html>
    <head>
        <title>{#TitleStartsWith#}{$PAGE_TITLE}</title>
	<link rel="stylesheet" href="{#THEME_DIR#}theme.css" />
        <link rel="stylesheet" href="{#THEME_DIR#}tabs.css" />
        <link rel="stylesheet" href="{#THEME_DIR#}lightbox.css" />
	<link rel="stylesheet" href="/cssloader.php?Theme=default&Accent={$ACCENT}&CSS=accent" />
{foreach from=$PAGE_CSS item=css}
	<link rel="stylesheet" href="{$css}" />
{/foreach}
	{$EXTRA_HEAD_CODE}
{foreach from=$PAGE_JS item=js}
	<script language=javascript type="text/javascript" src="/js/{$js}"></script>
{/foreach}
	<script language=javascript type="text/javascript" src="/js/prototype.js"></script>
        <script language=javascript type="text/javascript" src="/js/lightbox.js"></script>
	<script language=javascript>
		{$SAJAX_JAVASCRIPT_CODE}

		{$ICEDECK_JAVASCRIPT_CODE}
	</script>
    </head>
    <body>
      <div class=wrapper>
        <div id=NavArea>
            <ul class="two">
                <li><a href="/"><span>Home</span></a></li>
                <li><a href="/new"><span>New</span></a></li>
                <li><a href="/rnd"><span>Random</span></a></li>
                <li><a href="/cloud"><span>Cloud</span></a></li>
                <li><a href="#lightboxsearch" rel="lightboxsearch" class="lbOn"><span>Search</span></a></li>
                
                <li><a href="#"><span>Help</span></a></li>
                <li><a href="#"><span>About</span></a></li>
            </ul>
        </div>
        <div id=Header>{#HeaderStartsWith#}{$PAGE_TITLE}</div>
