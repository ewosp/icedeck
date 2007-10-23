        <div id=Content>{$card_text}</div>
	<div id=Keywords>
		<b>{#Keywords_t#}</b>
		{$card_keywords_URL}
	</div>
        <div id=MetaArea>
{if $card_logo}
		<div class=logo><img src='{$card_logo}' alt='{$card_logo_alt}' /></div>
{/if}
		<div class="metaBloc metaInfos"><span class="metaBlocTitle">{#card#|capitalize} #{$card_id}</span>
			<p>{#CreatedBy#} {$card_created_by}
			<br />{$card_created_date|date_format:"%Y-%m-%d %H:%M"}</p>
{if $card_updated_date > 0}
			<p>{#LastUpdatedBy#} {$card_updated_by}
			<br />{$card_updated_date|date_format:"%Y-%m-%d %H:%M"}</p>
{/if}
			<p class=metaInfosMenu>[ <a href='{$URL_edit}'>{#edit#|capitalize} {#card#}</a> | <a href='{$URL_new}'>{#new#|capitalize} {#card#}</a> ]</p>
		</div>
{if $card_completed > -1}
		<div class="metaBloc progressBar">
			<img id=ProgressBar src='/_includes/ProgressBar/ProgressBar.php/osx/{#PROGRESS_BAR_LENGTH#}/{$card_completed}/100' alt='{$card_completed} % completed' onClick="UpdateProgressBar()"; />
			<br /><span id=updatingProgressBar>[{#updating#|capitalize}]</span><span id=completedValue>{$card_completed}</span>% {#completed#}
		</div>
{/if}
{if $card_points > 0}
		<div class="metaBloc prime">{$card_points} {if $card_points > 1}{#points#}{else}{#point#}{/if}</div>
{/if}
{if $card_keywordsRepresentation_count > 0}
		<div class="metaBloc keywordsRepresentation">
{foreach from=$card_keywordsRepresentation item=meta}
			{$meta}<br />
{/foreach}
		</div>
{/if}
        </div>
