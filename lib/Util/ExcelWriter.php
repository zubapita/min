<?php
/**
 * Excelのxlsxファイルを作成するModel
 * 
 * PHPExcelが必要
 * 
 */
class ExcelWriter extends ExcelReader
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
        parent::__construct();
        ini_set('memory_limit', '256M');
    }
    
    /**
     * Excelの.xlsxファイルを読み込む
     * 
     * @return object 自分自身のインスタンス
     */
    public function create()
    {
        $_ = $this;
        $_->ExcelObj = new PHPExcel;
        $_->ExcelObj->setActiveSheetIndex(0);
        $_->SheetObj = $_->ExcelObj->getActiveSheet();
        return $this;
    }

    /**
     * Excelの.xlsxファイルを保存する
     * 
     */
    public function save($filePath)
    {
        $_ = $this;
        $WriterObj = PHPExcel_IOFactory::createWriter($_->ExcelObj, 'Excel2007');
        $WriterObj->save($filePath);
    }


    /**
     * セルの値を設定する
     * 
     * @param integer|string $value セルにセットする値
     * @param integer $column 1から始まるカラムNo.
     * @param integer $row 0から始まる行No.
     * @return object 自分自身のインスタンス
     */
    public function setCellValue($value, $column, $row)
    {
        $_ = $this;
        $_->SheetObj->setCellValueByColumnAndRow($column, $row, $value);
        return $this;
    }



    /**
     * デフォルトフォントとサイズを設定する
     * 
     * @param string $fontName フォント名
     * @param integer $fontSize フォントサイズ
     * @return object 自分自身のインスタンス
     */
    public function setDefalutFont($fontName, $size)
    {
        $_ = $this;
        $_->SheetObj->getDefaultStyle()->getFont()->setName($fontName);
        $_->SheetObj->getDefaultStyle()->getFont()->setSize($size);
        return $this;
    }

    /**
     * 指定範囲のフォントとサイズを設定する
     * 
     * @param string $fontName フォント名
     * @param integer $fontSize フォントサイズ
     * @param integer $startCol
     * @param integer $startRow
     * @param integer $endCol オプション
     * @param integer $endRow オプション
     * @return object 自分自身のインスタンス
     */
    public function setFont($fontName, $size, $startCol, $startRow, $endCol=0, $endRow=0)
    {
        $_ = $this;
        $range = $_->getRange($startCol, $startRow, $endCol, $endRow);
        
        $_->SheetObj->getStyle($range)->getFont()->setName($fontName);
        $_->SheetObj->getStyle($range)->getFont()->setSize($size);
        return $this;
    }

    /**
     * プリント範囲を設定する
     * 
     * @param integer $startCol
     * @param integer $startRow
     * @param integer $endCol
     * @param integer $endRow
     * @return object 自分自身のインスタンス
     */
    public function setPrintArea($startCol, $startRow, $endCol, $endRow)
    {
        $_ = $this;
        $range = $_->getRange($startCol, $startRow, $endCol, $endRow);
        
        $_->SheetObj
                ->getPageSetup()
                ->setFitToPage(true)
                ->setFitToWidth(1)
                ->setFitToHeight(0)
                ->setPrintArea($range);
        
        return $this;
    }


    /**
     * 指定範囲に罫線グリッドを設定する
     * 
     * @param string $lineType 
     * @param integer $startCol
     * @param integer $startRow
     * @param integer $endCol オプション
     * @param integer $endRow オプション
     * @return object 自分自身のインスタンス
     */
    public function setBorderGrid($lineType, $startCol, $startRow, $endCol=0, $endRow=0)
    {
        $_ = $this;
        $range = $_->getRange($startCol, $startRow, $endCol, $endRow);
        
        $_->SheetObj->getStyle($range)->getBorders()->getAllBorders()->setBorderStyle($lineType);
        
        return $this;
    }

    /**
     * 文字カラーの設定
     * 
     * @param string $color ARGB形式の色表現（'FFFFFFFF'）か色定数
     * @param integer $startCol
     * @param integer $startRow
     * @param integer $endCol オプション
     * @param integer $endRow オプション
     * @return object 自分自身のインスタンス
     */
    public function setFontColor($color, $startCol, $startRow, $endCol=0, $endRow=0)
    {
        $_ = $this;
        $range = $_->getRange($startCol, $startRow, $endCol, $endRow);
        
        $_->SheetObj->getStyle($range)->getFont()->getColor()->setARGB($color);
        return $this;
    }

    /**
     * 背景色の設定
     * 
     * @param string $color ARGB形式の色表現（'FFFFFFFF'）か色定数
     * @param integer $startCol
     * @param integer $startRow
     * @param integer $endCol オプション
     * @param integer $endRow オプション
     * @return object 自分自身のインスタンス
     */
    public function setBGColor($color, $startCol, $startRow, $endCol=0, $endRow=0)
    {
        $_ = $this;
        $range = $_->getRange($startCol, $startRow, $endCol, $endRow);
        // setFillTypeが必ず必要
        $_->SheetObj->getStyle($range)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
        $_->SheetObj->getStyle($range)->getFill()->getStartColor()->setARGB($color);
        return $this;
    }

    /**
     * 水平方向のの文字アライメントの設定
     * 
     * @param string $alignment 
     * @param integer $startCol
     * @param integer $startRow
     * @param integer $endCol オプション
     * @param integer $endRow オプション
     * @return object 自分自身のインスタンス
     */
    public function setH($alignment, $startCol, $startRow, $endCol=0, $endRow=0)
    {
        $_ = $this;
        $range = $_->getRange($startCol, $startRow, $endCol, $endRow);
        
        $_->SheetObj->getStyle($range)->getAlignment()->setHorizontal($alignment);
        return $this;
    }

    /**
     * 垂直方向の文字アライメントの設定
     * 
     * @param string $alignment 
     * @param integer $startCol
     * @param integer $startRow
     * @param integer $endCol オプション
     * @param integer $endRow オプション
     * @return object 自分自身のインスタンス
     */
    public function setV($alignment, $startCol, $startRow, $endCol=0, $endRow=0)
    {
        $_ = $this;
        $range = $_->getRange($startCol, $startRow, $endCol, $endRow);
        
        $_->SheetObj->getStyle($range)->getAlignment()->setVertical($alignment);
        return $this;
    }
    


    /**
     * 色定数
     */
    const BLACK = PHPExcel_Style_Color::COLOR_BLACK;
    const WHITE = PHPExcel_Style_Color::COLOR_WHITE;
    const RED = PHPExcel_Style_Color::COLOR_RED;
    const DARKRED = PHPExcel_Style_Color::COLOR_DARKRED;
    const BLUE = PHPExcel_Style_Color::COLOR_BLUE;
    const DARKBLUE = PHPExcel_Style_Color::COLOR_DARKBLUE;
    const GREEN = PHPExcel_Style_Color::COLOR_GREEN;
    const DARKGREEN = PHPExcel_Style_Color::COLOR_DARKGREEN;
    const YELLOW = PHPExcel_Style_Color::COLOR_YELLOW;
    const DARKYELLOW = PHPExcel_Style_Color::COLOR_DARKYELLOW;
    
    
    
/*
    水平方向位置
    HORIZONTAL_GENERAL			= 'general';
    HORIZONTAL_LEFT				= 'left';
    HORIZONTAL_RIGHT			= 'right';
    HORIZONTAL_CENTER			= 'center';
    HORIZONTAL_JUSTIFY			= 'justify';

    縦方向位置
    VERTICAL_BOTTOM				= 'bottom';
    VERTICAL_TOP				= 'top';
    VERTICAL_CENTER				= 'center';
    VERTICAL_JUSTIFY			= 'justify';

    罫線
    BORDER_NONE					= 'none';
    BORDER_DASHDOT				= 'dashDot';
    BORDER_DASHDOTDOT			= 'dashDotDot';
    BORDER_DASHED				= 'dashed';
    BORDER_DOTTED				= 'dotted';
    BORDER_DOUBLE				= 'double';
    BORDER_HAIR					= 'hair';
    BORDER_MEDIUM				= 'medium';
    BORDER_MEDIUMDASHDOT		= 'mediumDashDot';
    BORDER_MEDIUMDASHDOTDOT		= 'mediumDashDotDot';
    BORDER_MEDIUMDASHED			= 'mediumDashed';
    BORDER_SLANTDASHDOT			= 'slantDashDot';
    BORDER_THICK				= 'thick';
    BORDER_THIN					= 'thin';

    パターン
    FILL_NONE					= 'none';
    FILL_SOLID					= 'solid';
    FILL_GRADIENT_LINEAR		= 'linear';
    FILL_GRADIENT_PATH			= 'path';
    FILL_PATTERN_DARKDOWN		= 'darkDown';
    FILL_PATTERN_DARKGRAY		= 'darkGray';
    FILL_PATTERN_DARKGRID		= 'darkGrid';
    FILL_PATTERN_DARKHORIZONTAL	= 'darkHorizontal';
    FILL_PATTERN_DARKTRELLIS	= 'darkTrellis';
    FILL_PATTERN_DARKUP			= 'darkUp';
    FILL_PATTERN_DARKVERTICAL	= 'darkVertical';
    FILL_PATTERN_GRAY0625		= 'gray0625';
    FILL_PATTERN_GRAY125		= 'gray125';
    FILL_PATTERN_LIGHTDOWN		= 'lightDown';
    FILL_PATTERN_LIGHTGRAY		= 'lightGray';
    FILL_PATTERN_LIGHTGRID		= 'lightGrid';
    FILL_PATTERN_LIGHTHORIZONTAL= 'lightHorizontal';
    FILL_PATTERN_LIGHTTRELLIS	= 'lightTrellis';
    FILL_PATTERN_LIGHTUP		= 'lightUp';
    FILL_PATTERN_LIGHTVERTICAL	= 'lightVertical';
    FILL_PATTERN_MEDIUMGRAY		= 'mediumGray';
*/
}
