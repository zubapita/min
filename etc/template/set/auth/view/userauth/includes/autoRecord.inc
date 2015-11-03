<!-- userauth/includes/autoRecord.inc -->
<div class="table-responsive">
{$v = $UserauthRecord}
<table class="table table-striped table-hover">
<tbody>
<tr>
    <th>{$v['username']}</th>
    <td>{$v['username']}</td>
</tr>
<tr>
    <th>{$v['password']}</th>
    <td>{$v['password']}</td>
</tr>
<tr>
    <th>{$v['entryAt']}</th>
    <td>{$v['entryAt']}</td>
</tr>
<tr>
    <th>{$v['updateAt']}</th>
    <td>{$v['updateAt']}</td>
</tr>
</tbody>
</table>
<form id="UserauthEdit">
<div class="button">
<button type="button" id="UserauthEditButton" class="btn btn-primary btn-block">{$l['edit_button']}</button>
<button type="button" id="UserauthReturnButton" class="btn btn-default btn-block">{$l['return_button']}</button>
<input type="hidden" id="UserauthId" name="UserauthId" value="{$v['id']}" />
</form>
</div>
</form>
</div>
<!-- end of userauth/includes/autoRecord.inc -->
