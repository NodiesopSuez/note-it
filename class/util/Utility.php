<?php
class SaftyUtil{
	//ワンタイムトークン発生
	public static function generateToken(string $token_name='token'):string{
		$token = bin2hex(openssl_random_pseudo_bytes(32));
		$_SESSION[$token_name] = $token;
		return $token;
	}
	
	//送信されてきたトークンが正しいか判断する
	public static function validToken(string $token,string $token_name='token'):bool{
		if(!isset($_SESSION[$token_name])||$_SESSION[$token_name]!==$token){
			return false;
		}
		return true;
	}

	//サニタイズする $db_or_html 1=>データベース格納時 2=>html出力時
	public static function sanitize(int $db_or_html, $array){
		if($db_or_html === 1){
			foreach ($array as $key => $val) {
				$sanitized[$key] = pg_escape_string($val);
			}
		}elseif($db_or_html === 2){
			foreach ($array as $key => $val) {
				$sanitized[$key] = htmlspecialchars($val, ENT_QUOTES, 'UTF-8');
			}
		}elseif($db_or_html === 3){
			$sanitized = pg_escape_string($array);
		}elseif($db_or_html === 4){
			$sanitized = htmlspecialchars($array, ENT_QUOTES, 'UTF-8');
		}
		return $sanitized;
	}
}
?>