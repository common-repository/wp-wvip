<?php


class WVIP_Users{
	
	
	
	public function __construct(){	
		
		
	}
	
	public $err = '';
	public $code = 0;
	
	public $pages = '';
	
	
	public function stats(){
		global $wpdb;		
		$prefix = $wpdb->get_blog_prefix();
		$wp_user = $prefix.'users';
		$user_table = $prefix.WVIP_Common::$tb_user;
		$log_table = $prefix.WVIP_Common::$tb_buylog;
		
		$sql = "select from_unixtime(created,'%Y-%m') smonth, count(1) num,sum(money) money from $log_table group by smonth";
		//echo $sql;
		return $wpdb->get_results($sql,ARRAY_A);
	}
	public function vipInfo($user_id){
		global $wpdb;		
		$prefix = $wpdb->get_blog_prefix();
		$wp_user = $prefix.'users';
		$user_table = $prefix.WVIP_Common::$tb_user;
		$log_table = $prefix.WVIP_Common::$tb_buylog;
		
		$sql = "SELECT * FROM $user_table WHERE uid='$user_id' AND expired>UNIX_TIMESTAMP() AND status=1";
		$row = $wpdb->get_row($sql,ARRAY_A);
		return $row;
	}
	
	public function userVip($user_id){
		global $wpdb;		
		$prefix = $wpdb->get_blog_prefix();
		$wp_user = $prefix.'users';
		$user_table = $prefix.WVIP_Common::$tb_user;
		$log_table = $prefix.WVIP_Common::$tb_buylog;
		
		$sql = "SELECT id FROM $user_table WHERE uid='$user_id' AND expired>UNIX_TIMESTAMP() AND status=1";
		$id = $wpdb->get_var($sql);
		return $id;
	}
	
	public function vipExpire($user_id){
		global $wpdb;		
		$prefix = $wpdb->get_blog_prefix();
		$wp_user = $prefix.'users';
		$user_table = $prefix.WVIP_Common::$tb_user;
		$log_table = $prefix.WVIP_Common::$tb_buylog;
		
		$sql = "SELECT expired FROM $user_table WHERE uid='$user_id' AND expired>UNIX_TIMESTAMP() AND status=1";
		$id = $wpdb->get_var($sql);
		return $id;
	}
	
	public function userBatchVip($post){
		global $wpdb;		
		$prefix = $wpdb->get_blog_prefix();
		$wp_user = $prefix.'users';
		$user_table = $prefix.WVIP_Common::$tb_user;
		$log_table = $prefix.WVIP_Common::$tb_buylog;
		
		do{
			if(!isset($post['id']) || empty($post['id'])){
				$this->err = '请选择记录';
				break;
			}
			$post['id'] = array_map('intval',$post['id']);
			$post['id'] = array_filter($post['id']);
			/*$ids = implode(',',$post['id']);
			if(!$ids){
				$this->err = '请选择记录';
				break;
			}*/
			
			if($post['id'])foreach($post['id'] as $id){
				$this->addBuylog($id);
			}
			
			
			return true;
			
		}while(false);
		return false;
	}
	public function queryWpUsers($param){
		global $wpdb;		
		$prefix = $wpdb->get_blog_prefix();
		$wp_user = $prefix.'users';
		$user_table = $prefix.WVIP_Common::$tb_user;
		$log_table = $prefix.WVIP_Common::$tb_buylog;
		
		$list = array();
		
		
		$param['paged'] = $param['paged']?$param['paged']:1;
		
		$pagesize = $param['pagesize']?$param['pagesize']:10;
		
		$offset = ($param['paged'] -1 ) * $pagesize;
		
		
		
		$sql = "SELECT id,user_login,user_email,user_nicename,user_registered,display_name FROM $wp_user a WHERE not exists( SELECT 1 FROM $user_table b WHERE a.id=b.uid AND b.expired>UNIX_TIMESTAMP()) ";
			
		
		
		
		
		if(isset($_GET['q'])&& $_GET['q']){
			$_GET['q'] = trim($_GET['q']);
			$sql .= $wpdb->prepare(" AND concat_ws('',a.user_login,a.user_email) like %s",'%'.$_GET['q'].'%');
		}
		
		$param['total'] = $wpdb->get_var("select count(1) from ($sql) as tmp  ");
		
		$this->pages($param);
		
		
		$sql .= " ORDER BY  a.id DESC LIMIT $offset,$pagesize";
		
		return $wpdb->get_results($sql,ARRAY_A);
	}
	
	//查询用户
	public function queryUsers($param){
		global $wpdb;		
		$prefix = $wpdb->get_blog_prefix();
		$wp_user = $prefix.'users';
		$user_table = $prefix.WVIP_Common::$tb_user;
		$log_table = $prefix.WVIP_Common::$tb_buylog;
		
		$list = array();
		
		
		$param['paged'] = $param['paged']?$param['paged']:1;
		
		$pagesize = $param['pagesize']?$param['pagesize']:10;
		
		$offset = ($param['paged'] -1 ) * $pagesize;
		
		
		
		$sql = "SELECT b.id,b.user_login,user_email,user_nicename,user_registered,display_name,
		a.`uid`, a.`rid`, a.`expired`, a.`month`, a.`status`,
		IF(a.expired>0 and a.expired>UNIX_TIMESTAMP(),0,1) AS isout,
		IF(a.expired>0,FROM_UNIXTIME(a.expired,'%Y-%m-%d %H:%i:%s'),'-') AS expiretime 
		FROM $wp_user b left join $user_table a on a.uid=b.id WHERE 1=1 ";
		if(isset($_GET['type'])){
			if($_GET['type'] == '1'){
				$sql .= ' AND a.uid is not null';//$wpdb->prepare();
			}else if($_GET['type'] == '2'){
				$sql .= ' AND a.uid is null';//$wpdb->prepare();
			}
		
		}
		if(isset($_GET['q'])&& $_GET['q']){
			$_GET['q'] = trim($_GET['q']);
			$sql .= $wpdb->prepare(" AND concat_ws('',b.user_login,user_email) like %s",'%'.$_GET['q'].'%');
		}
		
		$param['total'] = $wpdb->get_var("select count(1) from ($sql) as tmp  ");
		
		$sql .= " ORDER BY  a.id DESC LIMIT $offset,$pagesize";
		
		
		
		$this->pages($param);
		
		
		return $wpdb->get_results($sql,ARRAY_A);
	}
	
	public function userBatchOp($status){
		global $wpdb;		
		$prefix = $wpdb->get_blog_prefix();
		$wp_user = $prefix.'users';
		$user_table = $prefix.WVIP_Common::$tb_user;
		$log_table = $prefix.WVIP_Common::$tb_buylog;
		
		$post = $_POST;
		
		do{
			
			
			if(!isset($post['id']) || empty($post['id'])){
				$this->err = '请选择记录';
				break;
			}
			$post['id'] = array_map('intval',$post['id']);
			$post['id'] = array_filter($post['id']);
			$ids = implode(',',$post['id']);
			if(!$ids){
				$this->err = '请选择记录';
				break;
			}
			
			$wpdb->query("UPDATE $user_table SET status='$status' WHERE id IN($ids)");
			return true;
		}while(false);
		
		return false;
	}
	
	
	
	//购买记录
	public function queryBuylogs($param){
		global $wpdb;		
		$prefix = $wpdb->get_blog_prefix();
		$wp_user = $prefix.'users';
		$user_table = $prefix.WVIP_Common::$tb_user;
		$log_table = $prefix.WVIP_Common::$tb_buylog;
		
		
		$param['paged'] = $param['paged']?$param['paged']:1;
		
		$pagesize = $param['pagesize']?$param['pagesize']:10;
		
		$offset = ($param['paged'] -1 ) * $pagesize;
		
		
		
		$sql = "SELECT a.*,FROM_UNIXTIME(created,'%Y-%m-%d %H:%i') AS creatime,b.user_login,user_email,user_nicename FROM $log_table a,$wp_user b WHERE a.uid=b.id ";		
		
		if(isset($_GET['q'])&& $_GET['q']){
			$_GET['q'] = trim($_GET['q']);
			$sql .= $wpdb->prepare(" AND concat_ws('',b.user_login,b.user_email) like %s",'%'.$_GET['q'].'%');
		}
		
		$year = $month = $dformat = '';
		if(isset($_GET['year'])&& $_GET['year']){
			$year = intval($_GET['year']);	
			$dformat = '%Y';
		}
		if(isset($_GET['month'])&& $_GET['month']){
			$month = intval($_GET['month']);
			
			if(!$year)$year = date('Y');	
			if($month){
				$year = $year.($month>9?$month:'0'.$month);		
				$dformat = '%Y%m';
			}
		}
		if($year)$sql .= " AND FROM_UNIXTIME(created,'$dformat') = $year";
		
		$param['total'] = $wpdb->get_var("select count(1) from ($sql) as tmp  ");
		$this->pages($param);
		
		$sql .= " ORDER BY  a.id DESC LIMIT $offset,$pagesize";
		//echo $sql;
		return $wpdb->get_results($sql,ARRAY_A);
		
		
	}
	
	//添加购买记录
	public function addBuylog($user_id = 0){
		global $wpdb;		
		$prefix = $wpdb->get_blog_prefix();
		$wp_user = $prefix.'users';
		$user_table = $prefix.WVIP_Common::$tb_user;
		$log_table = $prefix.WVIP_Common::$tb_buylog;
		
		$post = $_POST;
		
		do{
			
			$sql = "SELECT id FROM $wp_user a WHERE ";
			if($user_id){
				$sql .= $wpdb->prepare("id = %d",$user_id);
			}else{
				$username = $post['username'];
				
				if(strpos($username,'@')){
					$sql .= $wpdb->prepare("user_email = %s",$username);
				}else{
					$sql .= $wpdb->prepare("user_login = %s",$username);
				}
				
			}
			$user_id = $wpdb->get_var($sql);			
			if(!$user_id){
				$this->err = '用户不存';
				break;
			}
			
			//购买记录
			$d = array();
			$d['uid'] = $user_id;
			$d['created'] = time();
			$d['money'] = floatval($post['money']);
			$d['paytype'] = trim($post['paytype']);
			$d['month'] = intval($post['month']);
			$d['memo'] = trim($post['memo']);
			if(!$d['month'])$d['month'] = 1;
			
			$wpdb->insert($log_table,$d);
			
			$month = $d['month'];
			
			//vip会员
			$vip_row = $wpdb->get_row("SELECT id,expired,month FROM $user_table WHERE uid='$user_id'",ARRAY_A);
			//print_r($vip_row);exit();
			$smonth = '+'.$month.' month';
			$umonth = $month;
			if($vip_row['id']){
				//更新时长		
				$umonth = $vip_row['month'] + $month;		
				if($month>36){
					$expired = time() + 315360000 * 2;
					$umonth = $month;
				}else{
					$expired = strtotime($smonth,max(time(),$vip_row['expired']));
				}
				
				$wpdb->update($user_table,array('expired'=>$expired,'month'=>$umonth),array('id'=>$vip_row['id']));
			}else{
				//添加
				if($month>36){
					$expired = time() + 315360000 * 2;					
				}else{
					$expired = strtotime($smonth,time());
				}
				$d = array(
					'uid' => $user_id,
					'rid' => 0,
					'expired' => $expired,
					'month' => $umonth,
					'status' => 1
				);
								
				$wpdb->insert($user_table,$d);
			}
			return true;
		}while(false);
		
		return false;		
	}
	
	public function delBuylog(){
		
		global $wpdb;		
		$prefix = $wpdb->get_blog_prefix();
		$wp_user = $prefix.'users';
		$user_table = $prefix.WVIP_Common::$tb_user;
		$log_table = $prefix.WVIP_Common::$tb_buylog;
		
		$post = $_POST;
		
		do{
			
			
			if(!isset($post['id']) || empty($post['id'])){
				$this->err = '请选择记录';
				break;
			}
			$post['id'] = array_map('intval',$post['id']);
			$post['id'] = array_filter($post['id']);
			$ids = implode(',',$post['id']);
			if(!$ids){
				$this->err = '请选择记录';
				break;
			}
			$list =  $wpdb->get_results("SELECT id,uid,month FROM $log_table WHERE id IN($ids)",ARRAY_A);
			//print_r($list);
			foreach($list as $v){
				$user_id = $v['uid'];
				$month = $v['month'];
				if(!$month)continue;
				
				//vip会员
				$vip_row = $wpdb->get_row("SELECT id,expired,month FROM $user_table WHERE uid='$user_id'",ARRAY_A);
				//print_r($vip_row);exit();
				if($vip_row['id']){
					//更新时长
					if($month>34){
						$expired = 0;
					}else{
						$expired = strtotime(' - '.$month.' month',$vip_row['expired']);
					}
					$month = $vip_row['month'] - $month;
					$wpdb->update($user_table,array('expired'=>max(time(),$expired),'month'=>max(0,$month)),array('id'=>$vip_row['id']));
				}
			}
			$wpdb->query("DELETE FROM $log_table WHERE id IN($ids)");
			return true;
		}while(false);
		
		return false;
		
	}
	
	public function urls($param = array()){
		$current_url = set_url_scheme( 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] );
		//$param = array_merge($param,$_POST);
		$current_url = remove_query_arg( array( 'paged','batch', 'op' ), $current_url );
		if(is_array($param))foreach($param as $k=>$v){
			//if(in_array($k,array('op','batch','month','paytype','memo','money')))continue;
			$current_url = add_query_arg( $k, $v, $current_url );
		}
		return esc_url( $current_url );
	}
	
	public function pages($param){
		extract($param);
		$mxpage = ceil($total/$pagesize);
		
		$home = $this->urls();
		$preurl = $this->urls(array('paged'=>$paged-1));
		$nxurl = $this->urls(array('paged'=>$paged+1));
		$lastpage = $this->urls(array('paged'=>$mxpage));
		//$preurl = $this->urls(array('paged'=>$paged-1));
		
		$html = '<div class="tablenav-pages"><span class="displaying-num">'.$total.'项目</span><span class="pagination-links">';
		if($paged ==1){
			$html .= '<span class="tablenav-pages-navspan" aria-hidden="true">«</span>';
			$html .= '<span class="tablenav-pages-navspan" aria-hidden="true">‹</span> ';
		}else if($mxpage>1){
			if($paged == 2){
				$html .= '<span class="tablenav-pages-navspan" aria-hidden="true">«</span>';
			}else{
				$html .= '<a class="first-page" href="'.$home.'"><span class="screen-reader-text">首页</span><span aria-hidden="true">«</span></a>';
			}
		}
		if($paged>1){
			$html .= '<a class="prev-page" href="'.$preurl.'"><span class="screen-reader-text">上一页</span><span aria-hidden="true">‹</span></a>';
		}
		
		$html .= '<span class="screen-reader-text">当前页</span>';
		$html .= '<span id="table-paging" class="paging-input">第'.$paged.'页，共<span class="total-pages">'.$mxpage.'</span>页</span>';

		if($paged<$mxpage){
			$html .= '<a class="next-page" href="'.$nxurl.'"><span class="screen-reader-text">下一页</span><span aria-hidden="true">›</span></a>';
			if($paged+1 == $mxpage){
				$html .= '<span class="tablenav-pages-navspan" aria-hidden="true">»</span>';
			}else{
				$html .= '<a class="last-page" href="'.$lastpage.'"><span class="screen-reader-text">尾页</span><span aria-hidden="true">»</span></a>';
			}
		}
		
		if($mxpage == $paged){
			$html .= '<span class="tablenav-pages-navspan" aria-hidden="true">›</span>';
			$html .= '<span class="tablenav-pages-navspan" aria-hidden="true">»</span>';
			
		}
		
		
		$html .= '</span></div>';
		
		$this->pages = $html;
	}
}

 
