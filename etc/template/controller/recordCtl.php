<?php
/**
 * テーブル {$tableName} のRecord表示・操作コントローラーのテンプレート
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
	 *
	 */
	public function index() {
		$_ = $this;

		// viewから通知を取得
		$recordId = $_->getGETNumValue('id', false);
		${$modelName} = array();

		// modelから出力を得る
		if($recordId) {
			$conditions = array('id'=>$recordId);
			${$modelName} = $_->MODEL->get($conditions);
			if (empty(${$modelName})) $_->redirect('/');
		} else {
			$_->redirect('/');
		}

		// modelの出力をviewに接続
		$_->view->assign('{$modelName}', ${$modelName});

		// viewへの送信（表示）
		$_->view->display($_->view_template);
	}

	/**
	 * テーブルに新規レコードを追加するための空のフォームを表示する
	 *
	 */
	public function add()
	{
		$_ = $this;
		
		// セキュリティ用トークンの取得
		$_->initAuth();
		$token = $_->auth->getToken();
		if (empty($token)) {
			$token = $_->auth->setToken();
		}

		// viewへの送信（表示）
		$_->view->assign('token', $token);
		$_->view->display($_->view_template);
	}


	/**
	 * edit アクション
	 *
	 */
	public function edit()
	{
		$_ = $this;

		// セキュリティ用トークンの取得
		$_->initAuth();
		if (!$token = $_->auth->getToken()) {
			$token = $_->auth->setToken();
		}

		// viewから通知を取得
		$recordId = $_->getGETNumValue('id', false);
		${$modelName} = array();

		// modelから出力を得る
		if($recordId) {
			$conditions = array('id'=>$recordId);
			${$modelName} = $_->MODEL->get($conditions);

			// modelの出力をviewに接続
			$_->view->assign('{$modelName}', ${$modelName});

		} else {
			Console::log('Error! : record id is missing!');
		}


		// viewへの送信（表示）
		$_->view->assign('token', $token);
		$_->view->display($_->view_template);
	}

	/**
	 * save アクション
	 *
	 */
	public function save()
	{
		$_ = $this;

		// viewからデータを取得
		$_->initAjax();
		$data = $_->ajax->getPostedData();
		
		// セッショントークンを検証
		$_->initAuth();
		if ($_->auth->validateToken($data['token'])) {
			unset($data['token']);
		} else {
			$data['result'] = false;
			$data['message'] = "Illegal session.";
			$_->ajax->sendData($data);
			exit;
		}
		
		// modelにデータを渡して更新
		if ($_->dispatch_trace) {
			Console::log('save:');
			Console::log($data);
			$result = $_->MODEL->set($data);
			Console::log('Save to DB result:');
			Console::log($result);
		} else {
			$result = $_->MODEL->set($data);
		}
		
		// viewへmodelの更新結果を送信
		if($result!==false) {
			$data['result'] = true;
			$data['id'] = $result;
		} else {
			$data['result'] = false;
			$data['message'] = "Can't save to DB.";
			if (!empty($_->MODEL->$errors)) {
				$data['errors'] = $_->MODEL->$errors;
			}
		}
		$_->ajax->sendData($data);
		
	}

}

