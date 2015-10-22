<?php
/**
 * テーブル {$table} のリスト表示model
 * 
 *
 * @copyright    Tomoyuki Negishi and ZubaPitaTech Y.K.
 * @author        Tomoyuki Negishi <tomoyu-n@zubapita.jp>
 * @license    http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @package    Min - Minimam INter framework for PHP
 * @version    0.1
 */
class {$className} extends DataList
{
    /**
     * TABLE {$db}.{$table} の初期化
     * 
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $_ = $this;

        $_->DB = new {$db};
        $_->{$table|ucfirst} = $_->getTable($_->DB, '{$table}');
        $_->TABLE = $_->{$table|ucfirst}; // 外部から参照用
    }    

    /**
     * TABELを検索してデータの一覧を返す
     * 
     * @param (array|string) $conditions 検索条件
     * @param integer $currentPage
     * @return array 検索結果
     */
    public function get($conditions, $currentPage)
    {
        $_ = $this;
        
        // SELECT 条件の設定
        $_->{$table|ucfirst}->reset();
        $columns = [
            {foreach $columns as $column}
            '{$column['name']}',
            {/foreach}
        ];
        $_->{$table|ucfirst}->select($columns);
        $_->setLimit($currentPage, $_->maxItemsInPage);
        
        // SELECTの結果取得
        $list = $_->{$table|ucfirst}->find($conditions)->fetchAll();
        if ($_->dispatch_trace) {
            Console::log('{$className}::get');
            Console::log($_->{$table|ucfirst}->SQL);
        }

        // ページャを設定
        $_->setPager($conditions, $currentPage);

        return $list;
    }


    /**
     * ページャを設定
     * 
     * @param (array|string) $conditions 検索条件
     * @param integer $currentPage
     */
    public function setPager($conditions, $currentPage)
    {
        $_ = $this;
        
        $pagerParams['currentPage'] = $currentPage;
        $pagerParams['allItemsNum'] = $_->count($conditions);
        $pagerParams['maxItemsInPage'] = $_->maxItemsInPage;
        $_->pager = PagerCtl::get($pagerParams);
        if ($_->dispatch_trace) {
            Console::log('{$className}::PagerParams');
            Console::log($pagerParams);
        }
    }
    
    
    /**
     * 条件にマッチするデータが何行あるかカウントする
     * 
     * @param (array|string) $conditions テーブルの検索条件
     * @return integer マッチするデータの行数
     */
    public function count($conditions)
    {
        $_ = $this;
        
        // SELECT 条件の設定
        $_->{$table|ucfirst}->reset();
        $_->{$table|ucfirst}->select(array('count(*)'));

        // SELECTの結果取得
        $results = $_->{$table|ucfirst}->find($conditions)->fetchColumn();


        return $results;
    }


    /**
     * LimitとOffsetの設定
     * 
     * @param integer $pageNum ページ位置
     * @param integer $limit 最大取得行数
     */
    protected function setLimit($pageNum, $limit)
    {
        $_ = $this;
        
        $offset = ($pageNum - 1) * $limit;
        $_->{$table|ucfirst}->Limit($limit)->Offset($offset);
    }
    

}

