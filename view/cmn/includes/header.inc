<header>
<div class="navbar navbar-default navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <a href="/" class="navbar-brand">Bootswatch</a>
            <button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#navbar-main">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            </button>
        </div>

    <div class="navbar-collapse collapse" id="navbar-main">
    <!-- Global menu -->
    <ul class="nav navbar-nav">
        <!-- Spots -->
        <li class="dropdown">
        <a class="dropdown-toggle" data-toggle="dropdown" href="#" id="spots-menu">{$l['spots']}<span class="caret"></span></a>
        <ul class="dropdown-menu" aria-labelledby="spots">
            <li><a href="{$lang}/spots/">{$l['spots']}{$l['list']}</a></li>
            <li class="divider"></li>
            <li><a href="{$lang}/spots/record/add">{$l['spots']}{$l['add']}</a></li>
        </ul>
        </li>
        <!-- /Spots -->
        
        <!-- Books -->
        <li class="dropdown">
        <a class="dropdown-toggle" data-toggle="dropdown" href="#" id="books-menu">{$l['books']}<span class="caret"></span></a>
        <ul class="dropdown-menu" aria-labelledby="books">
            <li><a href="{$lang}/books/">{$l['books']}{$l['list']}</a></li>
            <li class="divider"></li>
            <li><a href="{$lang}/books/record/add">{$l['books']}{$l['add']}</a></li>
        </ul>
        </li>
        <!-- /Books -->
        
        <!-- Others -->
        <li class="dropdown">
        <a class="dropdown-toggle" data-toggle="dropdown" href="#" id="others-menu">{$l['others']}<span class="caret"></span></a>
        <ul class="dropdown-menu" aria-labelledby="others">
            <li><a href="{$lang}/uploadImg/">{$l['uploadImg']}</a></li>
            <li><a href="{$lang}/gmap/">{$l['gmap']}</a></li>
        </ul>
        </li>
        <!-- /Others -->

        <li><a href="http://bootswatch.com/cerulean/" target="_blank">Theme</a></li>
    </ul>
    <!-- /Global menu -->

    <!-- Login menu -->
    <ul class="nav navbar-nav navbar-right">
        {if isset($username)}
        <li><a href="#">{$l['welcome']}{$username}{$l['jp-san']}</a></li>
        <li class="dropdown">
        <a class="dropdown-toggle" data-toggle="dropdown" href="#" id="mymenu">{$l['mymenu']}<span class="caret"></span></a>
        <ul class="dropdown-menu" aria-labelledby="mymenu">
        <li><a href="#" onclick="AJAX.logout('{$lang}');">{$l['logout']}</a></li>
            <li class="divider"></li>
            <li><a href="{$lang}/userauth/connect">{$l['connectToSns']}</a></li>
        </ul>
        </li>
        {else}
        <li><a href="#">{$l['welcome']}{$l['guest']}{$l['jp-san']}</a></li>
        <li class="dropdown">
        <a class="dropdown-toggle" data-toggle="dropdown" href="#" id="login-menu">{$l['login']}<span class="caret"></span></a>
        <ul class="dropdown-menu" aria-labelledby="login">
            <li><a href="/oauth/twitter">Twitter</a></li>
            <li><a href="/oauth/facebook">Facebook</a></li>
            <li><a href="/oauth/google">Google</a></li>
            <li class="divider"></li>
            <li><a href="{$lang}/userauth/login">{$l['loginWithRegisterdAccount']}</a></li>
            <li><a href="{$lang}/userauth/add">{$l['add']}</a></li>
        </ul>
        </li>
        {/if}
    </ul>
    <!-- /Login menu -->

    </div>
    </div>
</div>
</header>