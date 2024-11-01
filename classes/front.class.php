<?php


class WVIP_Front{	
	
	public function __construct(){	
		
		$this->init();
	}
	public function init(){
		//add_filter('the_content',array($this,'the_content'),30);
		//add_action('wp_footer', array($this,'downStyle'),30);
		
		add_filter('dlip_mata_fields',array($this,'setDownMataFields'));
		add_filter('dlip_down_tpl',array($this,'setVipTpl'));
		add_action('dlip_down_page',array($this,'vipDown'));
		//add_filter('dlip_render_mata_field_checkbox',array($this,'renderCheckBox'),10,2);
		add_action( 'admin_bar_menu', array($this,'adminVipBar'), 8 );
		add_action('wp_footer', array($this,'vipStyle'));
	}
	
	function adminVipBar($wp_admin_bar){
		$user_id      = get_current_user_id();
		$current_user = wp_get_current_user();
	
		if ( ! $user_id )
			return;
		//if(is_admin())return;
		require_once WVIP_PATH.'/classes/users.class.php';
		$oUser = new WVIP_Users();
		//配置
		$op_sets = get_option( WVIP_Common::$optionName );
		$vipurl = '#';
		if(isset($op_sets['vipurl']) && $op_sets['vipurl'])$vipurl = $op_sets['vipurl'];
		
		$html = '<i class="icon-vip-none"></i><span class="vip-info">[普通会员]</span><a class="link-vip" href="'.$vipurl.'">[去充值]</a>';
		$vip = $oUser->vipInfo($user_id);
		if($vip){
			if($vip['month']>200)$vipname = '永久会员';
			else $vipname = '有效期至：'.date('Y-m-d',$vip['expired']);
			$html = '<i class="icon-vip"></i><span class="vip-info-none">['.$vipname.']</span><a class="link-vip" href="'.$vipurl.'">[去充值]</a>';	
		}
	/*<li class="wp-admin-bar-vip">
            <i class="icon-vip"></i>
            <span class="vip-info">[有效期至：2016.07.12]</span>
            <a class="link-vip" href="#">[去充值]</a>
        </li>*/
		//<i class="icon-vip-none"></i><span class="vip-info-none">[普通会员]</span><a class="link-vip" href="#">[去充值]</a>
		//
		$wp_admin_bar->add_menu( array(
			'id'        => 'wvip',
			'parent'    => 'top-secondary',
			'title'     => '',
			'href'      => '#',
			'meta'      => array(
				'class'     => 'wp-admin-bar-vip',//$class,
				'html' => $html,
			),
		) );
		
	}
	
	function tourl(){
		global $postId;
		if($postId){
			$url = get_permalink($postId);
		}else{
			$url = get_option('siteurl');
		}
		wp_redirect($url);
	}
	function vipDown($data){
		global $current_user, $display_name , $user_email;
		 
		if(!$data['isvip'])return true;
		$user_id = $current_user->ID;
		if(!$user_id)$this->tourl();
		require_once WVIP_PATH.'/classes/users.class.php';
		$oUser = new WVIP_Users();
		if(!$oUser->userVip($user_id))$this->tourl();
	}
	function defaultTpl(){
		$html = '<div class="wbolt-box">
					<h3 class="wb-title">下载信息</h3>
					<div class="txtc">
						<p class="hl">-------［仅限 <span class="gary">VIP</span> 用户下载］-------</p>
						<a class="wbolt-btn" href="{vipurl}">成为VIP</a>
					</div>
				</div>';
		
		$html .= '<link href="'.plugins_url("css/wbolt_vip_download.css", WVIP_BASE_FILE).'" rel="stylesheet">';
		return $html;
	}
	
	
	function setVipTpl($tpl){		
		global $current_user, $display_name , $user_email;  
	
		
		$user_id = $current_user->ID;
		$postId = get_the_ID();	
		$isvip = get_post_meta( $postId,'_mddp_down_isvip' , true );
		//非vip资源
		//if(!$isvip)return $tpl;//下载
		
		//配置
		$op_sets = get_option( WVIP_Common::$optionName );
		
		//已登录
		if($user_id){
			require_once WVIP_PATH.'/classes/users.class.php';
			$oUser = new WVIP_Users();
			if($oUser->userVip($user_id)){
				if(isset($op_sets['viptpl']) && $op_sets['viptpl'])return $op_sets['viptpl'];
				return $tpl;//默认下载
			}
		}
		//未登录样式
		//非vip资源
		if(!$isvip)return $tpl;//默认样式
		//无权限模板
		$vip_tpl = $this->defaultTpl();
		if(isset($op_sets['tpl']) && $op_sets['tpl'])$vip_tpl = $op_sets['tpl'];
		$replace = '#';
		if(isset($op_sets['vipurl']) && $op_sets['vipurl'])$replace = $op_sets['vipurl'];
		
		$vip_tpl = str_replace('{vipurl}',$replace,$vip_tpl);
		
		return $vip_tpl;//无权限模板
	}
	
	function renderCheckBox($html,$setting){
		
		return $html;
	}
	function setDownMataFields($fields){
		$fields['isvip'] = array('text'=>'是否VIP资源','type'=>'checkbox','prop'=>array('1'=>'是'));
		return $fields;
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