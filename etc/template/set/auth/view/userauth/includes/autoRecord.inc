<!-- userauth/includes/autoRecord.inc -->
<div class="table">
<table>
{$v = $UserauthRecord}
<tr>
    <th>id</th>
    <td>{$v['id']}</td>
</tr>
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
</table>
<form id="UserauthEdit">
<div class="button">
<input type="button" id="UserauthEditButton" value="EDIT" />
</div>
</form>
</div>
<!-- end of userauth/includes/autoRecord.inc -->
