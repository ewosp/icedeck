<ul>
{foreach from=$Cards item=Card}
  <li><a href='/{$Card->id}'>{$Card->title}</a></li>
{/foreach}
</ul>
