<?php
/**
 * 認証とセッション機能
 *
 *
 * @copyright	Tomoyuki Negishi and ZubaPitaTech Y.K.
 * @author		Tomoyuki Negishi <tomoyu-n@zubapita.jp>
 * @license	http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @package	Min - Minimam INter framework for PHP
 * @version	0.1
 */
class AuthCtl extends AppCtl
{
	
	private $SESSION_NAME = 'MIN_AUTH_SESSION';


	/**
	 * コンストラクタ。
	 *
	 */
	public function __construct()
	{
		$_ = $this;
		parent::__construct();

		if (!isset($_SESSION[$_->SESSION_NAME])) {
			$_->clear();
		}
	}
	
	/**
	 * 認証状態を開始する
	 *
	 * @param array $user
	 * @return boolean
	 **/
	public function set($user) {
		$_ = $this;
		if (isset($_SESSION)) {
			session_regenerate_id(true);
			$_->setToken();
			
			$_SESSION[$_->SESSION_NAME]['auth'] = true;
			$_SESSION[$_->SESSION_NAME]['authedUser'] = $user;
			return true;
		} else {
			return false;
		}
	}

	/**
	 * 認証状態を取得する
	 *
	 * @return boolean
	 **/
	public function get() {
		$_ = $this;
		$auth = $_SESSION[$_->SESSION_NAME]['auth'];
		return $auth;
	}

	/**
	 * 現在認証中のユーザー情報を取得する
	 *
	 * @return boolean
	 **/
	public function getUser() {
		$_ = $this;
		$user = $_SESSION[$_->SESSION_NAME]['authedUser'];
		return $user;
	}

	/**
	 * 認証状態を終了する
	 *
	 * @return boolean
	 **/
	public function clear() {
		$_ = $this;
		if (!isset($_SESSION)) {
			session_start();
		}
		$_SESSION[$_->SESSION_NAME] = array();
		$_SESSION[$_->SESSION_NAME]['auth'] = false;
		return true;
	}

	/**
	 * パスワードハッシュ生成
	 *
	 * @param $rawPassword string
	 * @return string
	 **/
	public function getHash($rawPassword) {
		$passwordHash = password_hash($rawPassword, PASSWORD_DEFAULT);
		return $passwordHash;
	}


	/**
	 * パスワード認証
	 *
	 * @param mix $rawPassword
	 * @param mix $passwordHash
	 * @return string
	 **/
	public function verifyPassword($rawPassword, $passwordHash) {
		if (empty($rawPassword)) return false;
		if (empty($passwordHash)) return false;
		
		if (is_array($rawPassword)) {
			$rawPassword = $rawPassword['password'];
		}
		if (is_array($passwordHash)) {
			$passwordHash = $passwordHash['password'];
		}
		
		$authResult = password_verify($rawPassword, $passwordHash);
		return $authResult;
	}


	/**
	 * ランダムなTokenを返す
	 *
	 * @return string
	 **/
	public function generateToken() {
		$token = rtrim(base64_encode(openssl_random_pseudo_bytes(32)),'=');
		return $token;
	}


	/**
	 * セッショントークンを保存する
	 *
	 * @return boolean
	 **/
	public function setToken() {
		$_ = $this;
		
		if (!isset($_SESSION)) {
			session_start();
		}
		if ($_SESSION[$_->SESSION_NAME]['token'] = $_->generateToken()) {
			if ($_->dispatch_trace) {
				Console::log('SESSION_NAME='.$_->SESSION_NAME);
				Console::log($_SESSION[$_->SESSION_NAME]);
			}
			return $_SESSION[$_->SESSION_NAME]['token'];
		} else {
			return false;
		}
	}

	/**
	 * セッショントークンを返す
	 *
	 * @return string
	 **/
	public function getToken() {
		if (isset($_SESSION[$this->SESSION_NAME]['token'])) {
			$token = $_SESSION[$this->SESSION_NAME]['token'];
		} else {
			$token = false;
		}
		return $token;
	}


	/**
	 * セッショントークンを検証する
	 *
	 * @param string $token
	 * @return boolean
	 **/
	public function validateToken($token) {
		$_ = $this;
		
		if (isset($token) && isset($_SESSION[$_->SESSION_NAME]['token'])) {
			if ($token==$_SESSION[$_->SESSION_NAME]['token']) {
				return true;
			}
		}
		return false;
	}

	/**
	 * リファラを保存する
	 *
	 * @return boolean
	 **/
	public function setReferer() {
		$_ = $this;
		
		if (!isset($_SESSION)) {
			session_start();
		}
		if (isset($_SERVER['HTTP_REFERER']) && $_SESSION[$_->SESSION_NAME]['referer'] = $_SERVER['HTTP_REFERER']) {
			//Console::log('HTTP_REFERER='.$_SESSION[$_->SESSION_NAME]['referer']);
			return $_SESSION[$_->SESSION_NAME]['referer'];
		} else {
			return false;
		}
	}

	/**
	 * リファラを取得する
	 *
	 * @return boolean
	 **/
	public function getReferer() {
		$_ = $this;
		
		if (isset($_SESSION[$_->SESSION_NAME]['referer'])) {
			return $_SESSION[$_->SESSION_NAME]['referer'];
		} else {
			return false;
		}
	}

}

