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
        $hybridauthSession = $_->getHASessionJson();

        $conditions = ['provider'=>$provider, 'providerId'=>$providerId];
        $userId = $_->getUserId($conditions);
        
        if ($userId) {
            $_->setAuth($userId, $provider, $providerId, $hybridauthSession);
            
            $_->redirect($_->auth->getReferer());
        } else {
            // ユーザーIDがなければ、ユーザー登録へリダイレクト
            $_SESSION['Oauth']['provider'] = $provider;
            $_SESSION['Oauth']['providerId'] = $providerId;
            $_SESSION['Oauth']['hybridauthSession'] = $hybridauthSession;
            $_SESSION['Oauth']['userProfile'] = $_->userProfile;
            
            $_->redirect('/userauth/regist');
            // ユーザー登録が完了すると、$_->continueRegist() に戻ってくる
        }
    }


    /**
     * ユーザー登録後、認証を続行
     *
     */
    public function continueRegist()
    {
        $_ = $this;
        $_->initAuth();
        $userId = $_->getGETNumValue('id');

        $provider = $_SESSION['Oauth']['provider'];
        $providerId = $_SESSION['Oauth']['providerId'];
        $hybridauthSession = $_SESSION['Oauth']['hybridauthSession'];
        unset($_SESSION['Oauth']);
        
        $_->setAuth($userId, $provider, $providerId, $hybridauthSession);
        
        $_->redirect($_->auth->getReferer());
    }


    /*
     * 認証状態を保存
     * 
     * @param integer $userId
     * @param string $userId
     * @param string $providerId
     * @param string $$hybridauthSession (json)
     * @return void
     */
    private function setAuth($userId, $provider, $providerId, $hybridauthSession)
    {
        $_ = $this;
        
        // authProviderに保存
        $_->saveToAuthProvider($userId, $provider, $providerId);

        // OAuth セッションの保存
        $_->saveToAuthConnection($userId, $provider, $hybridauthSession);

        // ユーザー認証状態にする
        if (!$_->setUserAuth($userId)) {
            Console::log('OauthCtl::execOauth:usrauth auth failed.');
            Console::log($hybridauthSession);
        }
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
            $userId = 0;
        }
        return $userId;
    }
    
    /*
     * ログインプロバイダとローカルアカウントの接続を保存
     * 
     * @param array $data ex. ['userId'=>$userId, 'provider'=>'twitter', 'providerId'=>$twitterId];
     * @return integer authProvider id
     */
    private function saveToAuthProvider($userId, $provider, $providerId)
    {
        $_ = $this;
        $data['userId'] = $userId;
        $data['provider'] = $provider;
        $data['providerId'] = $providerId;
        
        Console::log('Save to AuthProvider:');
        Console::log($data);
        $result = $_->AuthProviderRecord->set($data);
        Console::log('Save to AuthProvider result:');
        Console::log($result);
        
        return $result;
    }

    /*
     * 　OAuthの接続情報をJSON形式で返す
     * 
     * @return string
     */
    private function getHASessionJson()
    {
        $_ = $this;
        
        $sessionData = unserialize($_->HybridAuth->getSessionData());
        $tmpArray = array();
        foreach ($sessionData as $key=>$sValue) {
            $value = unserialize($sValue);
            $tmpArray[$key] = $value;
        }
        
        return json_encode($tmpArray);
    }
    
    /*
     * 　OAuthの接続情報を保存
     * 
     * @param integer $userId
     * @param string $provider ex. 'twitter' or 'facebook'
     * @return void
     */
    private function saveToAuthConnection($userId, $provider, $hybridauthSession)
    {
        $_ = $this;
        
        Console::log('saveToAuthConnection: userId='.$userId.' provider='.$provider);
        
        Console::log('save to AuthConnection:');
        $data = array(
            'userId' => $userId,
            'provider' => $provider,
            'hybridauthSession' => $hybridauthSession,
        );
        Console::log($data);
        $AuthConnectionRecord = new AuthConnectionRecord();
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
    private function setUserAuth($userId)
    {
        $_ = $this;
        
        $conditions = ['id'=>$userId];
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

