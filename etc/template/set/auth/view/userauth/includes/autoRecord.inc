<!-- userauth/includes/autoRecord.inc -->
<div class="table-responsive">
{$v = $UserauthRecord}
<table class="table table-striped table-hover">
<tbody>
<tr>
    <th>username</th>
    <td>{$v['username']}</td>
</tr>
<tr>
    <th>password</th>
    <td>{$v['password']}</td>
</tr>
<tr>
    <th>entry_datetime</th>
    <td>{$v['entry_datetime']}</td>
</tr>
<tr>
    <th>update_datetime</th>
    <td>{$v['update_datetime']}</td>
</tr>
</tbody>
</table>
<form id="UserauthEdit">
<div class="button">
<button type="button" id="UserauthEditButton" class="btn btn-primary btn-block">{$l['edit_button']}</button>
<button type="button" id="UserauthReturnButton" class="btn btn-default btn-block">{$l['return_button']}</button>
<input type="hidden" id="Userauth-id" name="Userauth-id" value="{$v['id']}" />
</form>
</div>
</form>
</div>
<!-- end of userauth/includes/autoRecord.inc -->
