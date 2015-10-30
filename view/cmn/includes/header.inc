<header>
<div class="navbar navbar-default navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <a href="../" class="navbar-brand">Bootswatch</a>
            <button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#navbar-main">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            </button>
        </div>
    <div class="navbar-collapse collapse" id="navbar-main">
    <ul class="nav navbar-nav">
        <li class="dropdown">
        <a class="dropdown-toggle" data-toggle="dropdown" href="#" id="themes">Spot <span class="caret"></span></a>
        <ul class="dropdown-menu" aria-labelledby="themes">
            <li><a href="{$lang}/spot/">List</a></li>
            <li class="divider"></li>
            <li><a href="{$lang}/spot/record/add">Add</a></li>
        </ul>
        </li>
        <li><a href="/uploadImg/">Upload Image</a></li>
        <li><a href="/gmap/">Gmap</a></li>
        <li><a href="http://bootswatch.com/cerulean/" target="_blank">Theme</a></li>
    </ul>

    <ul class="nav navbar-nav navbar-right">
        {if isset($username)}
        <li><a href="#">{$l['welcome']}{$username}さん</a></li>
        <li><a href="{$lang}/userauth/logout">ログアウト</a></li>
        {else}
        <li><a href="#">{$l['welcome']}ゲストさん</a></li>
        <li class="dropdown">
        <a class="dropdown-toggle" data-toggle="dropdown" href="#" id="download">ログイン <span class="caret"></span></a>
        <ul class="dropdown-menu" aria-labelledby="download">
            <li><a href="/oauth/twitter">Twitter</a></li>
            <li><a href="/oauth/facebook">Facebook</a></li>
            <li><a href="/oauth/google">Google</a></li>
            <li class="divider"></li>
            <li><a href="{$lang}/userauth/login">登録アカウントでログイン</a></li>
            <li><a href="{$lang}/userauth/add">新規登録</a></li>
        </ul>
        </li>
        {/if}
    </ul>

    </div>
    </div>
</div>
</header>