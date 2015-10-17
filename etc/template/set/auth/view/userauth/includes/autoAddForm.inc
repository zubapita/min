<!-- userauth/includes/autoAddForm.inc -->
<div class="table">
<form id="UserauthForm">
<table>
<tr>
    <th><label>username</label></th>
    <td><input type="text" id="Userauth-username" value="" /></td>
</tr>
<tr>
    <th><label>password</label></th>
    <td><input type="text" id="Userauth-password" value="" /></td>
</tr>
</table>
<div class="button">
<input type="button" id="UserauthSaveButton" value="SAVE" />
<input type="hidden" id="Userauth-id" value="" />
<input type="hidden" id="Session-token" value="{$token}" />
</div>
</form>
<span>token:{$ss.MIN_AUTH_SESSION.token}</span>
</div>
<!-- end of userauth/includes/autoAddForm.inc -->
