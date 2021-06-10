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
}
?>