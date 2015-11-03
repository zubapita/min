<!-- userauth/includes/autoAddForm.inc -->
<div class="container">
<form id="UserauthForm">
    
<div class="form-group has-feedback">
	<label class="sr-only" for="UserauthUsername">{$l['username']}</label>
    <div>
	    <input type="text" class="validate[required] text-input form-control input-lg" id="UserauthUsername" name="UserauthUsername" placeholder="{$l['username']}" value="" />
        <span class="form-control-feedback" aria-hidden="true"></span>
    </div>
</div>

<div class="form-group has-feedback">
	<label class="sr-only" for="UserauthPassword">{$l['password']}</label>
    <div>
	    <input type="password" class="fvalidate[required] text-input form-control input-lg" id="UserauthPassword" name="UserauthPassword" placeholder="{$l['password']}" value="" />
        <span class="form-control-feedback" aria-hidden="true"></span>
    </div>
</div>

<div class="button">
<button type="button" id="UserauthSaveButton" class="btn btn-primary btn-block">{$l['save_button']}</button>
<input type="hidden" id="UserauthId" value="" />
<input type="hidden" id="Session-token" value="{$token}" />
</div>

</form>
{*<span>token:{$ss.MIN_AUTH_SESSION.token}</span>*}
</div>
<!-- end of userauth/includes/autoAddForm.inc -->
