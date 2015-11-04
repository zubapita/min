<?php
/**
 * Google Maps表示コントローラー
 *
 *
 * @copyright    Tomoyuki Negishi and ZubaPitaTech Y.K.
 * @author        Tomoyuki Negishi <tomoyu-n@zubapita.jp>
 * @license    http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @package    Min - Minimam INter framework for PHP
 * @version    0.1
 */
class  <!--{$className}-->Ctl extends IndexCtl
{

    /**
     * viewの初期化
     *
     * @return void
     */
    public function __construct()
    {
        $_ = $this;
        parent::__construct();

    }

    /**
     * index アクション
     */
    public function index()
    {
        $_ = $this;


        // viewの表示
        $_->view->display($_->view_template);

    }


}

