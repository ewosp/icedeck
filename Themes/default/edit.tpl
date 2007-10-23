	<form name=CardEditor method=post enctype='multipart/form-data'>
	<input type=hidden name=id value='{$card_id}' />
        <div id=Content>
	  <p><strong>{#Title_t#}</strong> <input class=title name=title value='{$card_title}' /></p>
	  <textarea rows=30 cols=80 name=text>{$card_text}</textarea>
	  <h3>{#logo#|capitalize}</h3>
          <p>
		{#new#|capitalize} {#logo#} ({#max#}{#_t#} {$upload_max_filesize}){#_t#} <INPUT type='file' name='NewLogo' />
	  	<br /><INPUT type='checkbox' value=1 name='DeleteLogo' /> {#delete#|capitalize} {#logo#}
	  </p>
	  <h3>{#properties#|capitalize}</h3>
	  <p>
		% {#completed#}{#_t#} <input type=text size=4 maxlen=3 name=completed value="{$card_completed}" />
		<br />{#points#|capitalize}{#_t#} <input type=text size=8 maxlen=12 name=points value="{$card_points}" />
          </p>
	  <p><input type=submit name=CardEditor value="{#save#|capitalize}" class=title /></p>
        </div>
	<div id=Keywords>
		<strong>{#Keywords_t#}</strong>
		<input type=text size=90  class=keywordsField name=keywords value="{$card_keywords_string}" />
	</div>

        <div id=MetaArea>
{if ($card_logo)}
		<div class=logo>
			<img src="{$card_logo}" alt="{$card_logo_alt}" />
		</div>
{/if}
                <div class="metaBloc metaInfos">
{if $smarty.get.page == "edit"}
			<span class="metaBlocTitle">{#card#|capitalize} #{$card_id}</span>
                        <p>{#CreatedBy#} {$card_created_by}
                        <br />{$card_created_date|date_format:"%Y-%m-%d %H:%M"}</p>
{if $card_updated_date > 0}
                        <p>{#LastUpdatedBy#} {$card_updated_by}
                        <br />{$card_updated_date|date_format:"%Y-%m-%d %H:%M"}</p>
{/if}
{else}
			<span class="metaBlocTitle">{#new#|capitalize} {#card#}</span>
{/if}
		</div>
	</div>
	</form>
