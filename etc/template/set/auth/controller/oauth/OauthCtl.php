<?php
/**
 * ブランク表示コントローラーのテンプレート
 *
 *
 * @copyright    Tomoyuki Negishi and ZubaPitaTech Y.K.
 * @author        Tomoyuki Negishi <tomoyu-n@zubapita.jp>
 * @license        http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @package        Min - Minimam INter framework for PHP
 * @version        0.1
 */
class OauthCtl extends AjaxCtl
{

    /**
     * 各認証情報を保存するテーブル用モデルのインスタンスを格納する
     */
    private $UserauthRecord;
    private $AuthProviderRecord;

    /**
     * HybridAuth の設定情報を格納する
     */
    private $config = [];


    /**
     * 初期化
     *
     * @return void
     */
    public function __construct()
    {
        $_ = $this;
        parent::__construct();

        // modelの初期化
        $_->UserauthRecord = new UserauthRecord();
        $_->AuthProviderRecord = new AuthProviderRecord();
        
        $_->config['base_url'] = "http://".$_SERVER['SERVER_NAME']."/authCallBack.php";
        $_->config['debug_mode'] = "info"; // info or false
        $_->config['debug_file'] = $_->APP_ROOT."/var/log/hybridauth.log";
        
    }


    /**
     * Twitterで認証する
     * 
     * model/_def/api/TwitterApiKey.php にAPIのキーを設定して使ってください
     * 
     * http://hybridauth.sourceforge.net/userguide/IDProvider_info_Twitter.html
     */
    public function twitter()
    {
        $_ = $this;
        
        $provider = 'Twitter';
        $_->config['providers'][$provider] = TwitterApiKey::getParams();
        $_->execOauth($provider, $_->config);
    }

    /**
     * Facebookで認証する
     * 
     * model/_def/api/FacebookApiKey.php にAPIのキーを設定して使ってください
     * 
     * http://hybridauth.sourceforge.net/userguide/IDProvider_info_Facebook.html
     */
    public function facebook()
    {
        $_ = $this;
        
        $provider = 'Facebook';
        $_->config['providers'][$provider] = FacebookApiKey::getParams();
        $_->execOauth($provider, $_->config);

    }

    /**
     * Googleで認証する
     * 
     * model/_def/api/GoogleApiKey.php にAPIのキーを設定して使ってください
     * 
     * http://hybridauth.sourceforge.net/userguide/IDProvider_info_Google.html
     */
    public function google()
    {
        $_ = $this;
        
        $provider = 'Google';
        $_->config['providers'][$provider] = GoogleApiKey::getParams();
        $_->execOauth($provider, $_->config);

    }

    /**
     * Oauth認証を実行する
     *
     * @param string $provider ex. 'Twitter' or 'Facebook'
     * @param array $config Hybrid_Auth config array
     * @return void
     */
    private function execOauth($provider, $config)
    {
        $_ = $this;
        
        $_->initAuth();
        $_->auth->setReferer();

        // 認証の実行（未認証の場合はここでリダイレクトされ、認証済みの場合はスルーされる）
        try {
            $_->HybridAuth = new Hybrid_Auth($config); 
            $adapter = $_->HybridAuth->authenticate($provider); 
        } catch (Exception $e) {
            Console::log('fail to connect '.$provider.':'.$e->getMessage().'  code:'.$e->getCode());
            
            $_->redirect($_->auth->getReferer());
        }

        // ↓ ここから認証済みの場合の処理
        // 接続先SNSのユーザー情報を取得
        $_->userProfile = $adapter->getUserProfile();
        $providerId = $_->userProfile->identifier;
        $conditions = ['provider'=>$provider, 'providerId'=>$providerId];
        $userId = $_->getUserId($conditions);
        
        // OAuth セッションの保存
        $_->saveToAuthConnection($userId, $provider);

        // ユーザー認証状態にして元のURLに戻る
        $conditions = ['id'=>$userId];
        if (!$_->setUserAuth($conditions)) {
            // ユーザー認証失敗時のデバッグ用
            $sessionData = unserialize($_->HybridAuth->getSessionData());
            Console::log('OauthCtl::execOauth:usrauth auth failed.');
            Console::log($sessionData);
        }
        $_->redirect($_->auth->getReferer());

    }




    /*
     * ローカルアカウントのユーザーIDを取得。存在しなければ、ローカルアカウントを新規作成して紐付ける
     * 
     * @param array $conditions ex. ['userId'=>$userId, 'provider'=>'twitter', 'providerId'=>$twitterId];
     * @return integer userauth id
     */
    private function getUserId($conditions)
    {
        $_ = $this;
        
        if($user = $_->AuthProviderRecord->get($conditions)) {
            $userId = $user['userId'];
        } else {
            // userauthに保存
            Console::log('save to userauth:');
            
            $_->now = date("Y-m-d H:i:s");
            $data = [];
            $data['username'] = $_->userProfile->displayName;
            //仮パスワードを生成 パスワードログインさせる場合は、改めてパスワードを入れさせる必要がある
            $data['password'] = substr(str_shuffle('1234567890abcdefghijklmnopqrstuvwxyz'), 0, 16);
            $data['entryAt'] = $_->now;
            $data['updateAt'] = $_->now;
            Console::log($data);
            $result = $_->UserauthRecord->set($data);
            Console::log('Save to userauth result:');
            Console::log($result);
            if($result!==false) {
                $userId = $result;
            } else {
                $message = "Can't save to userauth table.";
                Console::log($message);
                return 0;
            }
            
            // authProviderに保存
            $conditions['userId'] = $userId;
            $conditions['updateAt'] = $_->now;
            $_->saveToAuthProvider($conditions);
        }
        return $userId;
    }
    
    /*
     * ログインプロバイダとローカルアカウントの接続を保存
     * 
     * @param array $data ex. ['userId'=>$userId, 'provider'=>'twitter', 'providerId'=>$twitterId];
     * @return integer authProvider id
     */
    private function saveToAuthProvider($data)
    {
        $_ = $this;
        
        Console::log('Save to AuthProvider:');
        Console::log($data);
        $result = $_->AuthProviderRecord->set($data);
        Console::log('Save to AuthProvider result:');
        Console::log($result);
        
        return $result;
    }

    /*
     * 　OAuthの接続情報を保存
     * 
     * @param integer $userId
     * @param string $provider ex. 'twitter' or 'facebook'
     * @return void
     */
    private function saveToAuthConnection($userId, $provider)
    {
        $_ = $this;
        
        Console::log('saveToAuthConnection: userId='.$userId.' provider='.$provider);
        
        $AuthConnectionRecord = new AuthConnectionRecord();
        $sessionData = unserialize($_->HybridAuth->getSessionData());
        $tmpArray = array();
        foreach ($sessionData as $key=>$sValue) {
            $value = unserialize($sValue);
            $tmpArray[$key] = $value;
        }
        $jsonData = json_encode($tmpArray);
        
        Console::log('save to AuthConnection:');
        $data = array(
            'userId' => $userId,
            'provider' => $provider,
            'hybridauthSession' => $jsonData,
            'updateAt' => $_->now,
        );
        Console::log($data);
        $result = $AuthConnectionRecord->set($data);
        Console::log('Save to AuthConnection result:');
        Console::log($result);
    }
    
    /*
     * ユーザーを認証された状態にセット
     * 
     * @param array $conditions ['id'=>$userId]
     * @return boolean ユーザー認証状態を設定できたらtrue
     */
    private function setUserAuth($conditions)
    {
        $_ = $this;
        
        $userauthRecord = $_->UserauthRecord->get($conditions);
        
        // ログイン成功
        if($userauthRecord) {
            $_->auth->set($userauthRecord); // 認証状態を開始
            
            return true;
        // ログイン失敗
        } else {
            $_->auth->clear();
            
            return false;
        }
    }
    
}

