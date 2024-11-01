<?php


class WVIP_Common{
	
	
	public static $name = 'wvip_pack';
	public static $mataPrefix = '_wvip_';
	public static $optionName = 'wvip_option';
	public static $langPack = 'wvip_textdomain';
	public static $settingField = 'wvip_options';
	
	
	public static $tb_user = 'wvip_users';
	public static $tb_buylog = 'wvip_buylogs';
	
	public static function payType($key = null){
		$types = array(
			'alipay' => '支付宝',
			'wechat' => '微信',
			'baidupay' => '百度钱包',
			'paypal' => 'PayPal',
		);
		return $key?$types[$key]:$types;		
	}
	
	function getUserCredit($user_id) {

		global $wpdb, $table_prefix;

		$get_user = $wpdb->get_row("SELECT * FROM {$table_prefix}wvip_users WHERE `username_ID` = '".$user_id."'");

		$get_role = $wpdb->get_row("SELECT * FROM {$table_prefix}wvip_roles WHERE `ID` = '".$get_user->user_role."'");
		
		return $get_role->credit_required;
	}

	function getUserPayments($user_id) {

		global $wpdb, $table_prefix;

		$get_payment = $wpdb->get_results("SELECT * FROM {$table_prefix}wvip_payments WHERE `username_ID` = '".$user_id."'");
		foreach($get_payment as $payments)
			$total += $payments->payment_price;

		return $total;
	}

	function getUser($column) {

		global $wpdb, $table_prefix, $current_user;

		$get_users = $wpdb->get_row("SELECT * FROM {$table_prefix}wvip_users WHERE `username_ID` = '".$current_user->ID."'");
		$get_roles = $wpdb->get_row("SELECT * FROM {$table_prefix}wvip_roles WHERE `ID` = '".$get_users->user_role."'");

		return $get_roles->$column;
	}

}
 
