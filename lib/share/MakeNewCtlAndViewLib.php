<?php
/**
 * 表示用のControllerとViewを生成する
 * 既存のクラスファイルがある場合は上書きしない
 * パラメータ：
 * -m モデル名 （必須）
 * -p ページ名  （オプション。省略時はモデル名）
 */
class MakeNewCtlAndViewLib extends AppCtl {
	
	public function __construct()
	{
		parent::__construct();
		CmdLibs::bannerBig( "make List Controller and View.");
	}
	
	function main()
	{
		$_ = $this;
		$_->APP_ROOT = CmdLibs::getAppRoot(1);
		$_->initView();
		
		// パラメータからモデル名とページ名を決定。
		$modelName = cmdLibs::getParam('-m');
		$pageName = cmdLibs::getParam('-p');
		$overWrite = cmdLibs::getParam('-o');
		if (empty($modelName) && empty($pageName)) {
			die("usage: ".cmdLibs::scriptName()." -m modelName -p pageName [-o yes]\n At least you should spec one parameter -m or -p.\n");
		}
		
		if ($modelName) {
			echo "use model name  $modelName.\n";

			// テーブル名を取得
			//$MODEL = $_->getModel($modelName);
			$MODEL = new $modelName;
			$tableName = $MODEL->TABLE->TABLE;
			if(empty($tableName)) {
				die("\nmodel $modelName is not correct.\n\n");
			}
			echo "table name is '$tableName'.\n";

			// テーブルのカラム一覧を取得
			$DB = $MODEL->DB;
			$DbOperator = new DbOperatorContext($DB::$SYSTEM);
			$DbOperator->setDb($DB);
			$columns = $DbOperator->getColumns($tableName);

			// モデル関連テンプレート変数の設定
			$_->view->assign('modelName', $modelName);
			$_->view->assign('tableName', $tableName);
			$_->view->assign('columns', $columns);
		}


		// ページ名を決定
		if (!$pageName) {
			if (preg_match("/(.*)List/", $modelName, $m)) {
				$pageName = lcfirst($m[1]);
			} elseif (preg_match("/(.*)Record/", $modelName, $m)) {
				$pageName = lcfirst($m[1]).'/record';
			} else {
				$pageName = $tableName;
			}
		}
		echo "page name is '$pageName'.\n";


		// 上書きモードを設定
		if ($overWrite=='yes') {
			$_->overWrite = true;
		} else {
			$_->overWrite = false;
		}


		// ページ名からクラス名を生成
		if (strpos($pageName, '/')!==false) {
			$pageNameAry = explode('/', $pageName);
			$pageNameAry = array_map("ucfirst", $pageNameAry);
			$className = implode('', $pageNameAry);
		} else {
			$className = ucfirst($pageName);
		}
		echo "class name is '$className'.\n\n";

		// ページ関係テンプレート変数の設定
		$_->view->assign('pageName', $pageName);
		$_->view->assign('className', $className);


		// 各ファイルの保存
		if (isset($MODEL)) {
			switch($MODEL::MODEL_TYPE) {
				case 'List':
					$_->genListCtl($pageName, $className);
					$_->genListHtml($pageName);
					$_->genAutoList($pageName);
					$_->genScrollAndSearch($pageName, $className);
					$_->genLangResource($pageName);
					break;
				case 'Record':
					$_->genRecordCtl($pageName, $className);
					$_->genRecordHtml($pageName);
					$_->genAddHtml($pageName);
					$_->genEditHtml($pageName);
					$_->genAutoRecord($pageName);
					$_->genAutoAddForm($pageName);
					$_->genAutoEditForm($pageName);
					$_->genPost($pageName, $className);
					$_->genLangResource($pageName);
					break;
			}
		} else {
			$_->genBlankCtl($pageName, $className);
			$_->genBlankHtml($pageName);
			$_->genLangResource($pageName);
		}
	}
	
	/**
	 * ファイルの保存
	 * @param boolean $altDelimiter
	 */
	public function saveFile($altDelimiter=false)
	{
		$_ = $this;
		if(file_exists($_->filePath) && !$_->overWrite) {
			echo "Warning: '".$_->filePath."' is exists. You should set parameter '-o yes'.\n";
			return false;
		}
		
		if(!file_exists($_->dirPath)) {
			mkdir($_->dirPath, 0777, true);
		}
		
		if($altDelimiter==true) {
			$_->view->left_delimiter = '<!--{';
			$_->view->right_delimiter = '}-->';
			$code = $_->view->fetch($_->templateFile);
			$_->view->left_delimiter = '{';
			$_->view->right_delimiter = '}';
		} else {
			$code = $_->view->fetch($_->templateFile);
		}
		
		file_put_contents($_->filePath, $code);
		CmdLibs::bannerBig($_->message);
		CmdLibs::bannerMid($code);
	}
	
	/**
	 * List controllerの保存
	 * 
	 */
	public function genListCtl($pageName, $className)
	{
		$_ = $this;
		$ctlClassName = $className.'Ctl';
		$_->view->assign('ctlClassName', $ctlClassName);
		$_->dirPath = $_->APP_ROOT.'/controller/'.$pageName;
		$_->filePath = $_->dirPath.'/'.$ctlClassName.'.php';
		$_->templateFile = $_->APP_ROOT.'/etc/template/controller/listCtl.php';
		$_->message = "save class file {$_->dirPath}/$className.php\n";
		$_->saveFile($altDelimiter=false);
	}
	

	/**
	 * Record controllerの保存
	 * 
	 */
	public function genRecordCtl($pageName, $className)
	{
		$_ = $this;
		$ctlClassName = $className.'Ctl';
		$_->view->assign('ctlClassName', $ctlClassName);
		$_->dirPath = $_->APP_ROOT.'/controller/'.$pageName;
		$_->filePath = $_->dirPath.'/'.$ctlClassName.'.php';
		$_->templateFile = $_->APP_ROOT.'/etc/template/controller/recordCtl.php';
		$_->message = "save class file {$_->dirPath}/$className.php\n";
		$_->saveFile($altDelimiter=false);
	}

	/**
	 * Blank controllerの保存
	 * 
	 */
	public function genBlankCtl($pageName, $className)
	{
		$_ = $this;
		$ctlClassName = $className.'Ctl';
		$_->view->assign('ctlClassName', $ctlClassName);
		$_->dirPath = $_->APP_ROOT.'/controller/'.$pageName;
		$_->filePath = $_->dirPath.'/'.$ctlClassName.'.php';
		$_->templateFile = $_->APP_ROOT.'/etc/template/controller/blankCtl.php';
		$_->message = "save class file {$_->dirPath}/$className.php\n";
		$_->saveFile($altDelimiter=false);
	}

	/**
	 * List表示htmlの保存
	 * 
	 */
	public function genListHtml($pageName)
	{
		$_ = $this;
		$_->dirPath = $_->APP_ROOT.'/view/'.$pageName;
		$_->filePath = $_->dirPath.'/index.html';
		$_->templateFile = $_->APP_ROOT.'/etc/template/view/list.html';
		$_->message = "save html file {$_->dirPath}/index.html\n";
		$_->saveFile($altDelimiter=true);
	}

	/**
	 * Record表示index.htmlの保存
	 * 
	 */
	public function genRecordHtml($pageName)
	{
		$_ = $this;
		$_->dirPath = $_->APP_ROOT.'/view/'.$pageName;
		$_->filePath = $_->dirPath.'/index.html';
		$_->templateFile = $_->APP_ROOT.'/etc/template/view/record.html';
		$_->message = "save html file {$_->dirPath}/index.html\n";
		$_->saveFile($altDelimiter=true);
	}

	/**
	 * Record追加add.htmlの保存
	 * 
	 */
	public function genAddHtml($pageName)
	{
		$_ = $this;
		$_->dirPath = $_->APP_ROOT.'/view/'.$pageName;
		$_->filePath = $_->dirPath.'/add.html';
		$_->templateFile = $_->APP_ROOT.'/etc/template/view/add.html';
		$_->message = "save html file {$_->dirPath}/add.html\n";
		$_->saveFile($altDelimiter=true);
	}

	/**
	 * Record更新edit.htmlの保存
	 * 
	 */
	public function genEditHtml($pageName)
	{
		$_ = $this;
		$_->dirPath = $_->APP_ROOT.'/view/'.$pageName;
		$_->filePath = $_->dirPath.'/edit.html';
		$_->templateFile = $_->APP_ROOT.'/etc/template/view/edit.html';
		$_->message = "save html file {$_->dirPath}/edit.html\n";
		$_->saveFile($altDelimiter=true);
	}

	/**
	 * autoList.incの保存
	 * 
	 */
	public function genAutoList($pageName)
	{
		$_ = $this;
		$_->dirPath = $_->APP_ROOT.'/view/'.$pageName.'/includes';
		$_->filePath = $_->dirPath.'/autoList.inc';
		$_->templateFile = $_->APP_ROOT.'/etc/template/view/includes/autoList.inc';
		$_->message = "save autoList file {$_->dirPath}/autoList.inc\n";
		$_->saveFile($altDelimiter=true);
	}

	/**
	 * autoRecord.incの保存
	 * 
	 */
	public function genAutoRecord($pageName)
	{
		$_ = $this;
		$_->dirPath = $_->APP_ROOT.'/view/'.$pageName.'/includes';
		$_->filePath = $_->dirPath.'/autoRecord.inc';
		$_->templateFile = $_->APP_ROOT.'/etc/template/view/includes/autoRecord.inc';
		$_->message = "save autoRecord file {$_->dirPath}/autoRecord.inc\n";
		$_->saveFile($altDelimiter=true);
	}
	
	/**
	 * autAddoForm.incの保存
	 * 
	 */
	public function genAutoAddForm($pageName)
	{
		$_ = $this;
		$_->dirPath = $_->APP_ROOT.'/view/'.$pageName.'/includes';
		$_->filePath = $_->dirPath.'/autoAddForm.inc';
		$_->templateFile = $_->APP_ROOT.'/etc/template/view/includes/autoAddForm.inc';
		$_->message = "save autoForm file {$_->dirPath}/autoAddForm.inc\n";
		$_->saveFile($altDelimiter=true);
	}

	/**
	 * autoEditForm.incの保存
	 * 
	 */
	public function genAutoEditForm($pageName)
	{
		$_ = $this;
		$_->dirPath = $_->APP_ROOT.'/view/'.$pageName.'/includes';
		$_->filePath = $_->dirPath.'/autoEditForm.inc';
		$_->templateFile = $_->APP_ROOT.'/etc/template/view/includes/autoEditForm.inc';
		$_->message = "save autoForm file {$_->dirPath}/autoEditForm.inc\n";
		$_->saveFile($altDelimiter=true);
	}

	/**
	 * ScrollAndSearch.jsの保存
	 * 
	 */
	public function genScrollAndSearch($pageName, $className)
	{
		$_ = $this;
		$_->dirPath = $_->APP_ROOT.'/view/'.$pageName.'/js';
		$_->filePath = $_->dirPath.'/'.$className.'ScrollAndSearch.js';
		$_->templateFile = $_->APP_ROOT.'/etc/template/view/js/ScrollAndSearch.js';
		$_->message = "save js file {$_->dirPath}/{$className}ScrollAndSearch.js\n";
		$_->saveFile($altDelimiter=true);
	}

	/**
	 * Post.jsの保存
	 * 
	 */
	public function genPost($pageName, $className)
	{
		$_ = $this;
		$_->dirPath = $_->APP_ROOT.'/view/'.$pageName.'/js';
		$_->filePath = $_->dirPath.'/'.$className.'Post.js';
		$_->templateFile = $_->APP_ROOT.'/etc/template/view/js/Post.js';
		$_->message = "save js file {$_->dirPath}/{$className}Post.js\n";
		$_->saveFile($altDelimiter=true);
	}

	/**
	 * Blank表示index.htmlの保存
	 * 
	 */
	public function genBlankHtml($pageName)
	{
		$_ = $this;
		$_->dirPath = $_->APP_ROOT.'/view/'.$pageName;
		$_->filePath = $_->dirPath.'/index.html';
		$_->templateFile = $_->APP_ROOT.'/etc/template/view/blank.html';
		$_->message = "save html file {$_->dirPath}/index.html\n";
		$_->saveFile($altDelimiter=true);
	}

	/**
	 * 言語リソースjp.phpの保存
	 * 
	 */
	public function genLangResource($pageName)
	{
		$_ = $this;
		$_->dirPath = $_->APP_ROOT.'/view/'.$pageName.'/lang';
		$_->filePath = $_->dirPath.'/jp.php';
		$_->templateFile = $_->APP_ROOT.'/etc/template/view/lang/jp.php';
		$_->message = "save lang respurce file {$_->dirPath}/lang/jp.php\n";
		$_->saveFile($altDelimiter=true);
	}

}
