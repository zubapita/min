<?php
/**
 * Excelのxlsxファイルを読み込むModel
 *
 * PHPExcelが必要
 *
 */
class ExcelReader extends AppCtl
{
    /**
     * ExcelワークブックObject
     */
    public $ExcelObj;

    /**
     * Excelの現在のワークシート
     */
    public $SheetObj;

    /**
     * 初期化
     *
     */
    public function __construct()
    {
        ini_set('memory_limit', '256M');
        parent::__construct();
    }

    /**
     * Excelの.xlsxファイルを読み込む
     *
     * @param string $filePath
     * @return object 自分自身のインスタンス
     */
    public function load($filePath)
    {
        $_ = $this;
        $_->ExcelObj = PHPExcel_IOFactory::load($filePath);

        // 日時のシリアル値をPHP型に指定
/*
        $PHP_num = PHPExcel_Calculation_Functions::RETURNDATE_PHP_NUMERIC;
        PHPExcel_Calculation_Functions::getReturnDateType($PHP_num);
*/

        $_->ExcelObj->setActiveSheetIndex(0);
        $_->SheetObj = $_->ExcelObj->getActiveSheet();
        return $this;
    }

    /**
     * 操作対象のシートNoを指定する
     *
     * @param integer $sheetNo
     * @return object 自分自身のインスタンス
     */
    public function setSheetNo($sheetNo)
    {
        $_ = $this;
        $_->ExcelObj->setActiveSheetIndex($sheetNo);
        $_->SheetObj = $_->ExcelObj->getActiveSheet();
        return $this;
    }

    /**
     * 操作対象のシートを名前で指定する
     *
     * @param string $sheetName
     * @return object 自分自身のインスタンス
     */
    public function setSheetName($sheetName)
    {
        $_ = $this;
        $_->SheetObj = $_->ExcelObj->getSheetByName($sheetName);
        return $this;
    }

    /**
     * 操作対象シートを名前を取得する
     *
     * @return string シート名
     */
    public function getSheetName()
    {
        $_ = $this;
        $name = $_->SheetObj->getTitle();
        return $name;
    }

    /**
     * ワークブック内のシートの数を取得する
     *
     * @return integer シート数
     */
    public function getSheetCount()
    {
        $_ = $this;
        $count = $_->ExcelObj->getSheetCount();
        return $count;
    }


    /**
     * 各カラムの名前。 array(カラムNo.=>'カラム名',..)
     */
    public $columnsName = array();

    /**
     * カラム名を設定する
     *
     * @param array $columns カラムNo.=>'カラム名' 左端は0
     * @return object 自分自身のインスタンス
     */
    public function setColumnsName($columns)
    {
        $_ = $this;
        $_->columnsName = $columns;
        return $this;
    }

    /**
     * 各行の名前。 array(行No.=>'行名',..)
     */
    public $rowsName = array();

    /**
     * 行名を設定する
     *
     * @param array $rows 行No.=>'行名' 上端は1
     * @return object 自分自身のインスタンス
     */
    public function setRowsName($rows)
    {
        $_ = $this;
        $_->rowsName = $rows;
        return $this;
    }

    /**
     * セルの値を返す
     *
     * @param integer $column 1から始まるカラムNo.
     * @param integer $row 0から始まる行No.
     * @return string カラムの値
     */
    public function getCellValue($column, $row)
    {
        $_ = $this;
        $value = $_->SheetObj->getCellByColumnAndRow($column, $row)->getValue();
        return $value;
    }

    /**
     * セルの計算結果を返す（
     *
     * @param integer $column 1から始まるカラムNo.
     * @param integer $row 0から始まる行No.
     * @return string カラムの値
     */
    public function getCalculatedValue($column, $row)
    {
        $_ = $this;
        $value = $_->SheetObj->getCellByColumnAndRow($column, $row)->getCalculatedValue();
        return $value;
    }

    /**
     * 取得する最大行数。0のとき、リミットなし
     */
    public $limit = 0;

    /**
     * 取得する最大行数を設定
     *
     * @param integer $limit
     * @return object 自分自身のインスタンス
     */
    public function setLimit($limit)
    {
        if (is_int($limit)) {
            $this->limit = $limit;
        }
        return $this;
    }

    /**
     * 検索開始する行
     */
    public $startRow = 1;

    /**
     * 検索開始する行を設定
     *
     * @param integer $startRow
     * @return object 自分自身のインスタンス
     */
    public function setStartRow($startRow)
    {
        if (is_int($startRow)) {
            $this->startRow = $startRow;
        }
        return $this;
    }

    /**
     * 検索終了する行。0のときは空白行まで
     */
    public $endRow = 0;

    /**
     * 検索終了する行を設定
     *
     * @param integer $endRow
     * @return object 自分自身のインスタンス
     */
    public function setEndRow($endRow)
    {
        if (is_int($endRow)) {
            $this->endRow = $endRow;
        }
        return $this;
    }

    /**
     * 取得開始するカラム
     */
    public $startColumn = 0;

    /**
     * 取得開始するカラムを設定
     *
     * @param integer $startColumn
     * @return object 自分自身のインスタンス
     */
    public function setStartColumn($startColumn)
    {
        if (is_int($startColumn)) {
            $this->startColumn = $startColumn;
        }
        return $this;
    }

    /**
     * 取得終了するカラム。0のときは空白カラムまで
     */
    public $endColumn = 0;

    /**
     * 検索終了する行を設定
     *
     * @param integer $endColumn
     * @return object 自分自身のインスタンス
     */
    public function setEndColumn($endColumn)
    {
        if (is_int($endColumn)) {
            $this->endColumn = $endColumn;
        }
        return $this;
    }

    /**
     * 正規表現検索で行を括り出す
     *
     * @param integer $searchColumn 検索対象カラムNo.
     * @param string $searchWord 検索語。正規表現が使える
     * @return array 検索結果
     */
    public function grep($searchColumn, $searchWord)
    {
        $_ = $this;
        $result = array();
        $row = $_->startRow;
        $count = 0;
        do {
            $value = $_->getCellValue($searchColumn, $row);
            if (preg_match("/$searchWord/", $value)) {
                $result[] = $_->getRow($row);
            }
            $row++;
            $count++;

            if ($_->limit && $count >= $_->limit) {
                break;
            } elseif ($_->endRow && $row>$_->endRow) {
                break;
            } elseif (empty($value)) {
                break;
            }
        } while (true);

        return $result;
    }


    /**
     * 指定された行のデータを返す
     *
     * @param integer $row 指定行No.
     * @param boolean $emptyCellIsOk セルがからでも警告しない
     * @return array 行データ
     */
    public function getRow($row, $emptyCellIsOk=false)
    {
        $_ = $this;
        $result = array();
        $column = $_->startColumn;
        do {
            $value = $_->getCellValue($column, $row);

            // 名前付きのカラムのセルが空なら警告
            if (is_null($value) && !$emptyCellIsOk) {
                if (isset($_->columnsName[$column])) {
                    echo "Warning: Column ".$_->columnsName[$column]." is Null!\n";
                } else {
                    //echo "Warning: Column ".$column." is Null!\n";
                }
            }

            // カラム名を付けて配列に保存
            if (isset($_->columnsName[$column])) {
                $name = $_->columnsName[$column];
                $result[$name] = $value;
            } else {
                $result[$column] = $value;
            }
            $column++;

            // 最終カラムか判断
            if ($_->endColumn && $column>$_->endColumn) {
                break;
            // 2014/09/16 空セルの判断をemptyからis_nullに
            } elseif (is_null($value)) {
                $dmy = array_pop($result);
                //break;
            }
        } while (true);

        return $result;
    }

    /**
     * 指定されたカラムのデータを返す
     *
     * @param integer $column 指定カラムNo.
     * @return array 行データ
     */
    public function getColumn($column)
    {
        $_ = $this;
        $result = array();
        $row = $_->startRow;
        do {
            $value = $_->getCellValue($column, $row);

            if (isset($_->rowsName[$row])) {
                $name = $_->rowsName[$row];
                $result[$name] = $value;
            } else {
                $result[$column] = $value;
            }
            $column++;

            if ($_->endRow && $row>$_->endRow) {
                break;
            } elseif (empty($value) && $_->endRow==0) {
                $dmy = array_pop($result);
                break;
            }
        } while (true);

        return $result;
    }


    /**
     * リセット
     */
    public function reset()
    {
        $_ = $this;
        $_->columnsName = array();
        $_limit = 0;
        $_->startRow = 1;
        $_->endRow = 0;
        $_->startColumn = 0;
        $_->endColumn =0;
    }


    /**
     * Excelの日付/時間データのシリアルを文字表現に変換して返す
     *
     * @param integer $serial Excelから取得した日時シリアル値
     * @param string $format 日時の文字表現フォーマット（デフォルト="Y/m/d"）
     * @return string 文字表現に変換した日時
     */
    public function dateTimeToString($serial, $format="Y/m/d")
    {
        return gmdate($format, ($serial - 25569) * 60 * 60 * 24);
    }

    /**
     * Excelの日付/時間データのシリアルをPHPの日時シリアル（Unixタイム）に変換
     *
     * @param integer $serial Excelから取得した日時シリアル値
     * @return integer PHPの日時シリアル
     */
    public function getUnixTime($serial, $format="Y/m/d")
    {
        return ($serial - 25569) * 60 * 60 * 24;
    }


    /**
     * (0,1)形式のセルアドレスをA1形式に変換する
     *
     */
    public function getCoord($column, $row)
    {
        $_ = $this;
/*
        $numToChar = array(
            'A', 'B' ,'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z',
        );

        if ($column <= 26) {
            $columnStr = $numToChar[$column-1];
        } else {
            $firstNum = floor($column / 26);
            $firstChar = $numToChar[$firstNum-1];
            $lastNum = $column % 26;
            $lastChar = $numToChar[$lastNum-1];
            $columnStr = $firstChar.$lastChar;
        }

        $coordinate = $columnStr.$row;
*/

        $coordinate = $_->SheetObj->getCellByColumnAndRow($column, $row)->getCoordinate();

        return $coordinate;
    }

    /**
     * A1:A1形式の範囲アドレスに変換する。
     * $endCol、$endRowが省略されると、A1形式で返す
     *
     * @param integer $startCol
     * @param integer $startRow
     * @param integer $endCol オプション
     * @param integer $endRow オプション
     * @return string A1:A1 もしくは A1 形式のセルアドレス
     */
    public function getRange($startCol, $startRow, $endCol=0, $endRow=0)
    {
        $_ = $this;
        $startAddr = $_->getCoord($startCol, $startRow);
        if ($endRow) {
            $endAddr = $_->getCoord($endCol, $endRow);
            $range = "$startAddr:$endAddr";
        } else {
            $range = $startAddr;
        }

        return $range;
    }
}
