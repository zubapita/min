<!-- userauth/includes/autoEditForm.inc -->
<div class="container">
<form id="UserauthForm">
{$v = $UserauthRecord}

<div class="form-group has-feedback">
	<label class="sr-only" for="UserauthUsername">{$l['username']}</label>
    <div>
	    <input type="text" class="validate[required,minSize[4],custom[onlyLetterNumber]] text-input form-control input-lg" id="UserauthUsername" name="UserauthUsername" placeholder="{$l['username']}" value="" />
        <span class="form-control-feedback" aria-hidden="true"></span>
    </div>
</div>

<div class="form-group has-feedback">
	<label class="sr-only" for="UserauthEmail">{$l['email']}</label>
    <div>
	    <input type="text" class="validate[required,custom[email]] text-input form-control input-lg" id="UserauthEmail" name="UserauthEmail" placeholder="{$l['email']}" value="" />
        <span class="form-control-feedback" aria-hidden="true"></span>
    </div>
</div>


<div class="form-group has-feedback">
	<label class="sr-only" for="UserauthPassword">{$l['password']}</label>
    <div>
	    <input type="password" class="fvalidate[required,minSize[8]] text-input form-control input-lg" id="UserauthPassword" name="UserauthPassword" placeholder="{$l['password']}" value="" />
        <span class="form-control-feedback" aria-hidden="true"></span>
    </div>
</div>


<div class="form-group has-feedback">
	<label class="sr-only" for="UserauthPasswordConfirm">{$l['password_confirm']}</label>
    <div>
	    <input type="password" class="fvalidate[required,equals[UserauthPassword]] text-input form-control input-lg" id="UserauthPasswordConfirm" name="UserauthPasswordConfirm" placeholder="{$l['password_confirm']}" value="" />
        <span class="form-control-feedback" aria-hidden="true"></span>
    </div>
</div>

<div class="button">
<button type="button" id="UserauthSaveButton" class="btn btn-primary btn-block">{$l['save_button']}</button>
<input type="hidden" id="UserauthId" name="UserauthId" value="{$v['id']}" />
<input type="hidden" id="Session-token" value="{$token}" />
</div>

</form>
{*<span>token:{$ss.MIN_AUTH_SESSION.token}</span>*}
</div>
<!-- end of userauth/includes/autoEditForm.inc -->
