<!-- userauth/includes/autoEditForm.inc -->
<div class="table">
<form id="UserauthForm">
<table>
{$v = $UserauthRecord}
<tr>
	<th><label>id</label></th>
	<td><input type="text" id="Userauth-id" value="{$v['id']}" /></td>
</tr>
<tr>
	<th><label>username</label></th>
	<td><input type="text" id="Userauth-username" value="{$v['username']}" /></td>
</tr>
<tr>
	<th><label>password</label></th>
	<td><input type="text" id="Userauth-password" value="{$v['password']}" /></td>
</tr>
<tr>
	<th><label>entry_datetime</label></th>
	<td><input type="text" id="Userauth-entry_datetime" value="{$v['entry_datetime']}" /></td>
</tr>
<tr>
	<th><label>update_datetime</label></th>
	<td><input type="text" id="Userauth-update_datetime" value="{$v['update_datetime']}" /></td>
</tr>
</table>
<div class="button">
<input type="button" id="UserauthSaveButton" value="SAVE" />
<input type="hidden" id="Session-token" value="{$token}" />
</div>
</form>
</div>
<!-- end of userauth/includes/autoEditForm.inc -->
