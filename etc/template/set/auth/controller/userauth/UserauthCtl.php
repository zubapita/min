<?php
/**
 * テーブル userauth のRecord表示・操作コントローラーのテンプレート
 *
 *
 * @copyright    Tomoyuki Negishi and ZubaPitaTech Y.K.
 * @author        Tomoyuki Negishi <tomoyu-n@zubapita.jp>
 * @license    http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @package    Min - Minimam INter framework for PHP
 * @version    0.1
 */
class UserauthCtl extends AjaxCtl
{
    /**
     * 表示するmodelを格納する変数
     */
    private $UserauthRecord;

    /**
     * リスト表示するmodelとviewの初期化
     *
     * @return void
     */
    public function __construct() {
        $_ = $this;
        parent::__construct();

        // modelの初期化
        $_->UserauthRecord = $_->getModel('UserauthRecord');
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
        $userauthRecord = array();

        
        // modelにデータを渡して更新
        if($recordId) {
            $conditions = array('id'=>$recordId,);
            $userauthRecord = $_->UserauthRecord->get($conditions);
        }

        // modelの出力をviewに接続
        $_->view->assign('UserauthRecord', $userauthRecord);

        // viewへの送信（表示）
        $_->view->display($_->view_template);
    }

    /**
     * ログインフォームを表示する
     *
     */
    public function login()
    {
        $_ = $this;
        
        $_->initAuth();
        $_->auth->setReferer();

        // viewへの送信（表示）
        $_->view->display($_->view_template);
    }

    /**
     * ログアウトし、結果を表示する
     *
     */
    public function logout()
    {
        $_ = $this;

        $_->initAuth();
        $_->auth->clear();
        $_->auth->setToken();

        // viewへの送信（表示）
        $_->view->display($_->view_template);
    }


    /**
     * login アクション
     *
     */
    public function auth()
    {
        $_ = $this;

        // viewからデータを取得
        $_->initAjax();
        $data = $_->ajax->getPostedData();

        // username から userauth レコードを取得
        $username = $data['username'];
        if($username) {
            $conditions = array('username'=>$username);
            $userauthRecord = $_->UserauthRecord->get($conditions);
        }
        
        // パスワード認証
        $_->initAuth();
        $authResult = $_->auth->verifyPassword($data, $userauthRecord);
        if ($_->dispatch_trace) {
            Console::log('login auth result:');
            Console::log($authResult);
        }

        // ログイン成功
        if($authResult) {
            $referer = $_->auth->getReferer();
            $_->auth->set($userauthRecord);
            
            $data['referer'] = $referer;
            $data['auth'] = true;
        
        // ログイン失敗
        } else {
            $_->auth->clear();
            $data['auth'] = false;
        }

        // viewへmodelの更新結果を送信
        $_->ajax->sendData($data);
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
        $userauthRecord = array();

        // modelから出力を得る
        if($recordId) {
            $conditions = array('id'=>$recordId,);
            $userauthRecord = $_->UserauthRecord->get($conditions);

            // modelの出力をviewに接続
            $_->view->assign('UserauthRecord', $userauthRecord);

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
            $data['message'] = "Illegal session.";
            $_->ajax->sendData($data);
            exit;
        }
        
        $now = date("Y-m-d H:i:s");
        // idが空ならadd。idをautoinclementで入れるため一旦削除
        if (empty($data['id'])) {
            unset($data['id']);
            $data['entryAt'] = $now;
        }

        // Passwordをhash化
        $data['password'] = $_->auth->getHash($data['password']);

        // 更新時刻
        $data['updateAt'] = $now;

        // modelにデータを渡して更新
        if ($_->dispatch_trace) {
            Console::log('save:');
            Console::log($data);
            $result = $_->UserauthRecord->set($data);
            Console::log('Save to DB result:');
            Console::log($result);
        } else {
            $result = $_->UserauthRecord->set($data);
        }
        
        // viewへmodelの更新結果を送信
        if($result!==false) {
            $data['id'] = $result;
        } else {
            $data['id'] = 0;
            $data['message'] = "Can't save to DB.";
        }
        $_->ajax->sendData($data);
        
    }

}

