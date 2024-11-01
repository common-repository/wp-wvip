<?php


class WVIP_Admin
{
	public function __construct(){	
		
		$this->init();
		
	}
	
	
	public function init(){
		
		if(is_admin()){
			
			register_activation_hook(WVIP_BASE_FILE, array($this,'plugin_activate'));	
			register_deactivation_hook(WVIP_BASE_FILE, array($this,'plugin_deactivate'));
			
			//注册管理菜单动作			
			add_action( 'admin_menu', array($this,'admin_menu') );
			
			add_action( 'admin_init', array($this,'admin_init') );
			//插件设置连接
			add_filter( 'plugin_action_links', array($this,'actionLinks'), 10, 2 );
		}
		add_action('admin_head', array($this,'vipStyle'));
		
		//注册插件初始化
		add_action('init',array($this,'plugin_init'));
		
		
	}
	//设置
	function actionLinks( $links, $file ) {
		
		if ( $file != plugin_basename(WVIP_BASE_FILE) )
			return $links;
	
		$settings_link = '<a href="'.menu_page_url( WVIP_Common::$name, false ).'">设置</a>';
	
		array_unshift( $links, $settings_link );
	
		return $links;
	}
	
	function admin_init(){
		register_setting(  WVIP_Common::$settingField,WVIP_Common::$optionName );
	}
	
	//后台菜单
	function admin_menu(){
		add_menu_page('VIP设置', 'WVIP设置', 'manage_options', WVIP_Common::$name, array($this,'settingPage'));
		add_submenu_page(WVIP_Common::$name, 'VIP会员','VIP会员', 'manage_options', 'wvip_users',array($this,'usersPage'));
		add_submenu_page(WVIP_Common::$name, '会员日志','会员日志', 'manage_options', 'wvip_buylog', array($this,'userBuyPage'));
		add_submenu_page(WVIP_Common::$name, '非VIP会员','非VIP会员', 'manage_options', 'wvip_wpusers', array($this,'wpusersPage'));
		add_submenu_page(WVIP_Common::$name, '购买统计','购买统计', 'manage_options', 'wvip_wpstats', array($this,'wpstatsPage'));
	}
	
	//设置页面
	function settingPage() {
		if (!current_user_can('manage_options')) {
			wp_die(__('You do not have sufficient permissions to access this page.'));
		}
		
		//$user_list_url = menu_page_url('wvip_users',false);
		
		$setting_field = WVIP_Common::$settingField;
		$option_name = WVIP_Common::$optionName;
		$op_sets = get_option( $option_name );
		
		include_once WVIP_PATH.'/views/setting.php';
	}
	function wpstatsPage(){
		if (!current_user_can('manage_options')) {
			wp_die(__('You do not have sufficient permissions to access this page.'));
		}
		require_once WVIP_PATH.'/classes/users.class.php';
		$oUser = new WVIP_Users();
		$list = $oUser->stats();
		include_once WVIP_PATH.'/views/stats.php';
	}
	//vip会员
	function wpusersPage() {

		if (!current_user_can('manage_options')) {
			wp_die(__('You do not have sufficient permissions to access this page.'));
		}
		require_once WVIP_PATH.'/classes/users.class.php';
		$oUser = new WVIP_Users();
		
		//批量操作
		if(isset($_POST['batch']) && $_POST['batch']){
			if(isset($_POST['op'])){
				switch($_POST['op']){
					case 'vip':
						if(!$oUser->userBatchVip($_POST)){
							$this->error($oUser->err);
						}
					break;
					
				}
			}
		}
		$current_url = $oUser->urls();
		$paged = isset($_GET['paged'])?absint($_GET['paged']):1;
		//列表
		$list = $oUser->queryWpUsers(array('pagesize'=>10,'paged'=>$paged));
		$pages = $oUser->pages;
		include_once WVIP_PATH.'/views/wpusers.php';
	}
	
	//vip会员
	function usersPage() {

		if (!current_user_can('manage_options')) {
			wp_die(__('You do not have sufficient permissions to access this page.'));
		}
		require_once WVIP_PATH.'/classes/users.class.php';
		$oUser = new WVIP_Users();
		
		//批量操作
		if(isset($_POST['batch']) && $_POST['batch']){
			if(isset($_POST['op'])){
				switch($_POST['op']){
					case 'invalid':
						if(!$oUser->userBatchOp(0)){
							$this->error($oUser->err);
						}
					break;
					case 'valid':
						if(!$oUser->userBatchOp(1)){
							$this->error($oUser->err);
						}
					break;
				}
			}
		}
		$current_url = $oUser->urls();
		
		$paged = isset($_GET['paged'])?absint($_GET['paged']):1;
		//列表
		$list = $oUser->queryUsers(array('pagesize'=>10,'paged'=>$paged));
		$pages = $oUser->pages;
		include_once WVIP_PATH.'/views/users.php';
	}

	//错误提示
	function error($err){
		echo "<div class='updated'><p>" . $err . "</div></p>";		 
	}
	
	//购买记录
	function userBuyPage() {

		if (!current_user_can('manage_options')) {
			wp_die(__('You do not have sufficient permissions to access this page.'));
		}
		require_once WVIP_PATH.'/classes/users.class.php';
		$oUser = new WVIP_Users();
		
		//批量操作
		if(isset($_POST['batch']) && $_POST['batch']){
			if(isset($_POST['op'])){
				switch($_POST['op']){
					case 'delete':
						if(!$oUser->delBuylog()){
							$this->error($oUser->err);
						}
					break;
				}
			}
		}
		if(isset($_POST['save']) && $_POST['save']){
			if(!$oUser->addBuylog()){
				$this->error($oUser->err);
			}
		}
		
		$paged = isset($_GET['paged'])?absint($_GET['paged']):1;
		$current_url = $oUser->urls();
		//列表
		$list = $oUser->queryBuylogs(array('pagesize'=>10,'paged'=>$paged));
		$pages = $oUser->pages;
		//print_r($list);
		include_once WVIP_PATH.'/views/buylog.php';
	}
	
	
	function plugin_activate()
	{
		global $wpdb,$table_prefix;
		
		$user_ddl = "CREATE TABLE IF NOT EXISTS ".$table_prefix."wvip_users (
			  `id` int(10) NOT NULL AUTO_INCREMENT,
			  `uid` int(10) NOT NULL,
			  `rid` int(10) NOT NULL,
			  `expired` int(10) unsigned NOT NULL DEFAULT '0',
			  `month` int(10) unsigned NOT NULL DEFAULT '0',
			  `status` tinyint(3) unsigned NOT NULL DEFAULT '1',
			  PRIMARY KEY (`id`)			  
			) DEFAULT CHARSET=utf8";
		$wpdb->query($user_ddl);
		
		$log_ddl = "CREATE TABLE IF NOT EXISTS ".$table_prefix."wvip_buylogs (
			 `id` int(10) NOT NULL AUTO_INCREMENT,
			  `uid` int(10) NOT NULL,
			  `created` int(10) unsigned NOT NULL DEFAULT '0',
			  `money` decimal(10,2) NOT NULL DEFAULT '0.00',
			  `paytype` varchar(20) DEFAULT NULL,
			  `month` int(10) unsigned NOT NULL DEFAULT '0',
			  `memo` varchar(200) DEFAULT NULL,
			  PRIMARY KEY (`id`),
			  KEY `uid` (`uid`)
			) DEFAULT CHARSET=utf8";
		
		$wpdb->query($log_ddl);
	}
	
	function plugin_deactivate()
	{
		
	}
	
	function plugin_init(){
		
		
	}
	
	function vipStyle(){
		?>
        <style>	
		#wp-admin-bar-wvip .ab-item{display:none !important;}
		 #wpadminbar .vip-info {
				color:#fbeb58;
			}
			#wpadminbar .icon-vip{
				background:url(<?php echo plugins_url("images/vip.gif", WVIP_BASE_FILE)?>); width:22px; height:19px; vertical-align:middle; display:inline-block; background-size:22px 19px;
			}
			#wpadminbar .icon-vip-none{
				background:url(<?php echo plugins_url("images/vip-none.gif", WVIP_BASE_FILE)?>); width:22px; height:19px; vertical-align:middle; display:inline-block; background-size:22px 19px;
			}
			#wpadminbar a.link-vip{
				display:inline-block !important;
				color:#fff;
			}
			#wpadminbar a:hover{
				color:#00b9eb;
			}</style>
            <?php
	}
	
}