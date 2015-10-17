<?php
return [
<!--{if !empty($columns)}-->
<!--{foreach $columns as $column}-->
    "<!--{$column['name']}-->" => "<!--{$column['name']}-->",
<!--{/foreach}-->
<!--{/if}-->
    "save_button" => "保存",
    "cancel_button" => "キャンセル",
    "edit_button" => "編集",
    "return_button" => "戻る",
];
