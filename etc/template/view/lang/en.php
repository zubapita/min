<?php
return [
<!--{if !empty($columns)}-->
<!--{foreach $columns as $column}-->
    "<!--{$column['name']}-->" => "<!--{$column['name']}-->",
<!--{/foreach}-->
<!--{/if}-->
    "save_button" => "Save",
    "cancel_button" => "Cancel",
    "edit_button" => "Edit",
    "return_button" => "Return",
];
