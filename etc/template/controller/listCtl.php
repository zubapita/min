<?php
/**
 * テーブル {$tableName} のリスト表示コントローラーのテンプレート
 *
 *
 * @copyright	Tomoyuki Negishi and ZubaPitaTech Y.K.
 * @author		Tomoyuki Negishi <tomoyu-n@zubapita.jp>
 * @license	http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @package	Min - Minimam INter framework for PHP
 * @version	0.1
 */
class {$ctlClassName} extends AjaxCtl
{
	/**
	 * 表示するmodelを格納する変数
	 */
	private $MODEL;

	/**
	 * リスト表示するmodelとviewの初期化
	 *
	 * @return void
	 */
	public function __construct() {
		$_ = $this;
		parent::__construct();

		// modelの初期化
		$_->MODEL = $_->getModel('{$modelName}');
		// viewの初期化
		$_->initView();
		$_->view->escape_html = true;
	}

	/**
	 * index アクション
	 */
	function index() {
		$_ = $this;
		$currentPage = 1;

		// modelから出力を得る
		$conditions = array();
		${$modelName} = $_->MODEL->get($conditions, $currentPage);
		$pager = $_->MODEL->getPager();

		// modelの出力をviewに接続
		$_->view->assign('{$modelName}', ${$modelName});
		$_->view->assign('pager', $pager);

		// viewの表示
		$_->view->display($_->view_template);

	}

	/**
	 * Ajax による次ページ表示と検索結果表示
	 */
	function getList() {
		$_ = $this;

		// viewから通知を取得
		$_->initAjax();
		$v = $_->ajax->getPostedData();
		$searchKeyword = $v['searchKeyword'];
		$currentPage = $v['pageNum'];

		// modelから出力を得る
		$conditions = array();

		if(!empty($searchKeyword)) {
			$conditions['{$tableName}./*検索対象カラムをここに入れる*/'] =
				array('opr'=>'like', 'val'=>"%$searchKeyword%");
		}

		${$modelName} =$_->MODEL->get($conditions, $currentPage);
		$pager = $_->MODEL->getPager();

		// modelの出力をviewに接続
		$_->view->assign('{$modelName}', ${$modelName});
		$_->view->assign('pager', $pager);

		// 新しいviewの実体（HTML）を生成
		$listHtml = $_->view->fetch('{$pageName}/includes/autoList.inc');

		// viewへの送信（表示）
		$data = array();
		$data['listHtml'] = $listHtml;
		$data['pager'] = $pager;
		$_->ajax->sendData($data);
	}

}

