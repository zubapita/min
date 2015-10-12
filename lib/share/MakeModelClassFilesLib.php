<?php
/**
 * ModelクラスファイルをDBから自動生成する
 * 既存のクラスファイルがある場合は上書きしない
 * パラメータ：
 * -d データベース名 （必須）
 * -t テーブル名  （オプション）
 */
class MakeModelClassFilesLib extends AppCtl {
	
	public function __construct()
	{
		parent::__construct();
		$_ = $this;
		$_->APP_ROOT = CmdLibs::getAppRoot(1);
		$_->initView();
		echo "\nmake Model Class file.\n\n";
	}
	
	function main()
	{
		$_ = $this;
		
		if($pageName = CmdLibs::getParam('-b')) {
			$_->makeBlankModel($pageName);
			exit;
		}
		
		if(!$dbname = CmdLibs::getParam('-d')) {
			die("usage: ".CmdLibs::scriptName()." [-d dbname -t tableName] OR [-b modelName for blank model]\n");
		} else {
			echo "use database $dbname.\n\n";
		}
		
		if($targetTable = CmdLibs::getParam('-t')) {
			echo "target table is '$targetTable'.\n\n";
		} else {
			echo "all tables in DB are target.\n\n";
		}

		$_->makeTableModels($dbname, $targetTable);
		
	}
	
	function makeTableModels($dbname, $targetTable)
	{
		$_ = $this;

		if(!$DB = $_->getDB($dbname)) {
			die("error: '$dbname' class file is not exists in ../model/db/\n");
		}
		
		$DbOperator = new DbOperatorContext($DB::$SYSTEM);

		$DbOperator->setDb($DB);
		$tables = $DbOperator->getTables();
		
		foreach($tables as $table) {
			
			if(!empty($targetTable) && $table!=$targetTable) {
				continue;
			}

			$dirPath = $_->APP_ROOT."/model/$table";
			if(!file_exists($dirPath)) {
				mkdir($dirPath, 0777, true);
			}

			$testDirPath = $_->APP_ROOT."/test/model/$table";
			if(!file_exists($testDirPath)) {
				mkdir($testDirPath, 0777, true);
			}

			//
			// List Model
			$columns = $DbOperator->getColumns($table);
			$_->view->assign('db', $dbname);
			$_->view->assign('table', $table);
			$_->view->assign('columns', $columns);
			
			$className = ucfirst($table).'List';
			$_->view->assign('className', $className);
			
			$filePath = $dirPath.'/'.$className.".php";
			if(file_exists($filePath)) {
				echo "error: $filePath is exists. can not save file.\n\n";
			} else {
				$templateFile = $_->APP_ROOT.'/etc/template/model/dataList.php';
				$classCode = $_->view->fetch($templateFile);
			
				file_put_contents($filePath, $classCode);
			
				echo "------------------------------\n";
				echo "save class file $className.php\n";
				echo "\n";
				echo $classCode;
				echo "\n";
			}
		
			// List Model Test
			$filePath = $testDirPath.'/'.$className."Test.php";
			if(file_exists($filePath)) {
				echo "error: $filePath is exists. can not save file.\n\n";
			} else {
				$templateFile = $_->APP_ROOT.'/etc/template/test/model/dataListTest.php';
				$classCode = $_->view->fetch($templateFile);
			
				file_put_contents($filePath, $classCode);
			
				echo "------------------------------\n";
				echo "save class file $className"."Test.php\n";
				echo "\n";
				echo $classCode;
				echo "\n";
			}
			
		
			// Record Model
			$className = ucfirst($table).'Record';
			$_->view->assign('className', $className);
			
			$filePath = $dirPath.'/'.$className.".php";
			if(file_exists($filePath)) {
				echo "error: $filePath is exists. can not save file.\n\n";
			} else {
				$templateFile = $_->APP_ROOT.'/etc/template/model/dataRecord.php';
				$classCode = $_->view->fetch($templateFile);
			
				file_put_contents($filePath, $classCode);
			
				echo "------------------------------\n";
				echo "save class file $className.php\n";
				echo "\n";
				echo $classCode;
				echo "\n";
			}
			
			// Record Model Test
			$filePath = $testDirPath.'/'.$className."Test.php";
			if(file_exists($filePath)) {
				echo "error: $filePath is exists. can not save file.\n\n";
			} else {
				$templateFile = $_->APP_ROOT.'/etc/template/test/model/dataRecordTest.php';
				$classCode = $_->view->fetch($templateFile);
			
				file_put_contents($filePath, $classCode);
			
				echo "------------------------------\n";
				echo "save class file $className"."Test.php\n";
				echo "\n";
				echo $classCode;
				echo "\n";
			}
		
		}
		
	}
	
	
	
	function makeBlankModel($pageName)
	{

		$_ = $this;
		$_->APP_ROOT = CmdLibs::getAppRoot(1);
		$_->initView();
		
		$dirPath = $_->APP_ROOT."/model/$pageName";
		if(!file_exists($dirPath)) {
			mkdir($dirPath, 0777, true);
		}

		$className = ucfirst($pageName).'Model';
		$_->view->assign('pageName', $pageName);
		$_->view->assign('className', $className);
		
		$filePath = $dirPath.'/'.$className.".php";
		
		// model file
		if(file_exists($filePath)) {
			echo "error: $filePath is exists. can not save file.\n\n";
		} else {
			$templateFile = $_->APP_ROOT.'/etc/template/model/blankModel.php';
			$classCode = $_->view->fetch($templateFile);
		
			file_put_contents($filePath, $classCode);
		
			echo "------------------------------\n";
			echo "save class file $className.php\n";
			echo "\n";
			echo $classCode;
			echo "\n";
		}
		// Blank Model Test


		$testDirPath = $_->APP_ROOT."/test/model/$pageName";
		if(!file_exists($testDirPath)) {
			mkdir($testDirPath, 0777, true);
		}

		$filePath = $testDirPath.'/'.$className."Test.php";
		if(file_exists($filePath)) {
			echo "error: $filePath is exists. can not save file.\n\n";
		} else {
			$templateFile = $_->APP_ROOT.'/etc/template/test/model/blankModelTest.php';
			$classCode = $_->view->fetch($templateFile);
		
			file_put_contents($filePath, $classCode);
		
			echo "------------------------------\n";
			echo "save class file $className"."Test.php\n";
			echo "\n";
			echo $classCode;
			echo "\n";
		}
		
		
	}
	
}
