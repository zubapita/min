<!-- includes/common/var_check.inc -->
{if isset($var)}{assign var="table_view" value=$var}{/if}

{foreach from=$table_view item="row" key="row_key"}
	{if is_array($row)}
		[row={$row_key}]<br />
		<ul>
		{foreach from=$row item="cell" key="cell_key"}
			{if is_array($cell)}
				<li>[{$cell_key}]</li>
				<ul>
				{foreach from=$cell item="attr" key="attr_key"}
					{if is_array($attr)}
						<li>{$attr_key}</li>
						<ul>
						{foreach from=$attr item="sub_attr" key="sub_attr_key"}
							{if is_array($sub_attr)}
								<li>{$sub_attr_key}</li>
								<ul>
								{foreach from=$sub_attr item="subsub_attr" key="subsub_key"}
									<li>{$subsub_key}={$subsub_attr}</li>
								{/foreach}
								</ul>
							{else}
								<li>{$sub_attr_key}={$sub_attr}</li>
							{/if}
						{/foreach}
						</ul>
					{else}
						<li>{$attr_key}={$attr}</li>
					{/if}
				{/foreach}
				</ul>
			{else}
				<li>{$cell_key}={$cell}</li>
			{/if}
		{/foreach}
		</ul>
	{else}
		[{$row_key}={$row}]<br />
	{/if}
{/foreach}
<!-- /includes/common/var_check.inc -->