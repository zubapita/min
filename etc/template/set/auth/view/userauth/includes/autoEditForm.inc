<!-- userauth/includes/autoEditForm.inc -->
<div class="container">
<form id="UserauthForm">
{$v = $UserauthRecord}

<div class="form-group">
	<label class="sr-only" for="Userauth-username">username</label>
	<input type="text" class="form-control input-lg" id="Userauth-username" name="Userauth-username" placeholder="username" value="{$v['username']}" />
</div>

<div class="form-group">
	<label class="sr-only" for="Userauth-password">password</label>
	<input type="password" class="form-control input-lg" id="Userauth-password" name="Userauth-password" placeholder="password" value="" />
</div>

<div class="form-group">
	<label class="sr-only" for="Userauth-entryAt">entryAt</label>
	<input type="text" class="form-control input-lg" id="Userauth-entryAt" name="Userauth-entryAt" placeholder="date time" value="{$v['entryAt']}" />
</div>

<div class="form-group">
	<label class="sr-only" for="Userauth-updateAt">updateAt</label>
	<input type="text" class="form-control input-lg" id="Userauth-updateAt" name="Userauth-updateAt" placeholder="date time" value="{$v['updateAt']}" />
</div>

<div class="button">
<button type="button" id="UserauthSaveButton" class="btn btn-primary btn-block">{$l['save_button']}</button>
<input type="hidden" id="Userauth-id" name="Userauth-id" value="{$v['id']}" />
<input type="hidden" id="Session-token" value="{$token}" />
</div>

</form>
{*<span>token:{$ss.MIN_AUTH_SESSION.token}</span>*}
</div>
<!-- end of userauth/includes/autoEditForm.inc -->
