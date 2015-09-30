
<header>
<div class="navbar navbar-default navbar-fixed-top">
	<div class="container">
		<div class="navbar-header">
			<a href="/" class="navbar-brand">Min Sample</a>
		</div><!-- /navbar-header -->
		<div class="navbar-collapse collapse" id="navbar-main">
			<ul class="nav navbar-nav">
				<li{if $dispatch_path=='{$lang}/'} class="active"{/if}><a href="{$lang}/">Home</a></li>
				{*<li{if $dispatch_path=='{$lang}/spot/'} class="active"{/if}><a href="{$lang}/spot/">Spot</a></li>*}
				<li class="dropdown">
					<a class="dropdown-toggle" data-toggle="dropdown" href="#" id="themes">Spot <span class="caret"></span></a>
					<ul class="dropdown-menu" aria-labelledby="themes">
						<li><a href="{$lang}/spot/">List</a></li>
						<li class="divider"></li>
						<li><a href="{$lang}/spot/record/add">Add</a></li>
					</ul><!-- /dropdown-menu -->
				</li><!-- /dropdown -->
				<li><a href="/mimage/">Mimage</a></li>
				<li><a href="/oauth/twitter">Twitter</a></li>
				{if $dispatch_path=='/func1/detail/'}<li class="active">func1 detail</li>{/if}
				{if $dispatch_path=='/func2/detail/'}<li class="active">func2 detail</li>{/if}
			</ul><!-- /nav navbar-nav -->

			<ul class="nav navbar-nav navbar-right">
				{if isset($username)}
				<li><a href="#">{$l['welcome']}{$username}さん</a></li>
				<li><a href="{$lang}/userauth/logout">ログアウト</a></li>
				{else}
				<li><a href="#">{$l['welcome']}ゲストさん</a></li>
				<li><a href="{$lang}/userauth/login">ログイン</a></li>
				<li><a href="{$lang}/userauth/add">新規登録</a></li>
				{/if}
			</ul><!-- /nav navbar-nav navbar-right -->

		</div><!-- /id="navbar-main" -->
	</div><!-- /container -->
</div><!-- /navbar navbar-default navbar-fixed-top -->
</header>

