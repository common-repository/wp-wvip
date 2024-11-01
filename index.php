<?php
/*
Plugin Name: WordPress WVIP
Plugin URI: http://www.wbolt.com/
Description: WordPress Wvip 是一款在wordpress的会员基础上增加VIP会员功能,结合下载插件(Download Info Page),实现VIP下载。
Author: wbolt
Version: 0.1.5
Author URI:http://www.wbolt.com/
*/

define('WVIP_PATH',dirname(__FILE__));
define('WVIP_BASE_FILE',__FILE__);

require_once WVIP_PATH.'/classes/common.class.php';
require_once WVIP_PATH.'/classes/admin.class.php';
require_once WVIP_PATH.'/classes/front.class.php';
new WVIP_Admin();
new WVIP_Front();

