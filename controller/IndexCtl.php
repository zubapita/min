<?php
/**
 * Minアプリ トップページの表示Controller
 *
 * @copyright	Tomoyuki Negishi and ZubaPitaTech Y.K.
 * @author		Tomoyuki Negishi <tomoyu-n@zubapita.jp>
 * @license	http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @package	Min - Minimam INter framework for PHP
 * @version	0.1
 */
class IndexCtl extends AjaxCtl
{
	/**
	 * トップページ表示の初期化
	 * 
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
		$_ = $this;
		
		// 認証初期化
		$_->initAuth();

		// viewの初期化
		$_->initView();
		$_->view->escape_html = true;

		// ログイン中？
		if ($_->auth->get()) {
			$_->user = $_->auth->getUser();
			$_->view->assign('username', $_->user['username']);
		}

	}
	
	/**
	 * トップページの表示
	 * 
	 * @return void
	 */
	function index()
	{
		$_ = $this;
		$currentPage = 1;


		// viewの表示
		$_->view->display($_->view_template);
	}

}

