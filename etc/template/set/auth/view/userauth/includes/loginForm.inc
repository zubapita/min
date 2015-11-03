<!-- userauth/includes/loginForm.inc -->
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
        <input type="password" class="validate[required] text-input form-control input-lg" id="UserauthPassword" name="UserauthPassword" placeholder="{$l['username']}" value="" />
        <span class="form-control-feedback" aria-hidden="true"></span>
    </div>
</div>

<div class="button">
    <button type="button" class="btn btn-primary btn-block" id="UserauthLoginButton">{$l['login']}</button>
</div>
</form>
</div>
<!-- end of userauth/includes/loginForm.inc -->
