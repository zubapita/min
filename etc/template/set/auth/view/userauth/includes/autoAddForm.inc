<!-- userauth/includes/autoAddForm.inc -->
<div class="container">
<form id="UserauthForm">
<div class="form-group">
	<label class="sr-only" for="Userauth-username">username</label>
	<input type="text" class="form-control input-lg" id="Userauth-username" name="Userauth-username" placeholder="username" value="" />
</div>

<div class="form-group">
	<label class="sr-only" for="Userauth-password">password</label>
	<input type="password" class="form-control input-lg" id="Userauth-password" name="Userauth-password" placeholder="password" value="" />
</div>

<div class="button">
<button type="button" id="UserauthSaveButton" class="btn btn-primary btn-block">{$l['save_button']}</button>
<input type="hidden" id="Userauth-id" value="" />
<input type="hidden" id="Session-token" value="{$token}" />
</div>

</form>
{*<span>token:{$ss.MIN_AUTH_SESSION.token}</span>*}
</div>
<!-- end of userauth/includes/autoAddForm.inc -->
