    <script id="spotDetailTemplate" type="text/jarty">
    {literal}
        <div class="spotDetail">
            <div class="ttl cf">
            {if $icon}<img src="{$icon}" alt=""/>{/if}
            {if $name}<b>{$name}</b>{else}不明{/if}
            </div>
            {if $formatted_phone_number}<p>Tel:{$formatted_phone_number}</p>{/if}
            {if $website}<p><a href="{$website}" target="_blank">HomePage</a></p>{/if}
            {if $vicinity}<p>住所：{$vicinity}</p>{/if}
            {if $openPeriods}<p>営業時間：{$openPeriods}</p>{/if}
        </div>
    {/literal}
    </script>
