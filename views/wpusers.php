<div class="wrap">
	<h2>非VIP会员</h2>
     <form name="from1" action="<?php echo $current_url;?>" method="get">
    <input type="hidden" name="page" value="<?php echo esc_html($_GET['page']);?>"/>
    <div class="tablenav">
			<div class="alignleft actions">
				
               <input type="text" name="q" id="q" value="<?php echo isset($_GET['q'])?esc_html($_GET['q']):'';?>" placeholder="输入用户名或邮箱"/>
				<input value="搜索" name="search" id="search" class="button-secondary action" type="submit"/>
			</div>
			<br class="clear">
		</div>
        </form>
        
	<form action="" method="post">
		<table class="widefat fixed" cellspacing="0">
			<thead>
				<tr>
					<th id="cb" scope="col" class="manage-column column-cb check-column"><input type="checkbox" name="checkAll" value=""/></th>
					<th scope="col" class="manage-column column-name" width="20%">用户名</th>
					<th scope="col" class="manage-column column-name" width="15%">用户邮箱</th>
					<th scope="col" class="manage-column column-name" width="15%">注册时间</th>
					<th scope="col" class="manage-column column-name" width="10%">显示名</th>
				</tr>
			</thead>

			<tbody>
			<?php
				
				if(isset($list) && count($list ) > 0) {
					
					if(is_array($list))foreach($list as $v) {
						 ?>
						<tr valign="middle" id="link-2">
							<th class="check-column" scope="row"><input type="checkbox" name="id[]" value="<?php echo $v['id'] ; ?>" /></th>
							<td class="username column-username"><?php echo $v['user_login']; ?></td>
							<td class="column-name"><?php echo $v['user_email']; ?></td>
							<td class="column-name"><?php echo $v['user_registered'];?></td>
							<td class="column-name"><?php echo $v['display_name']; ?></td>
						</tr>
						<?php
					}
				} else { ?>
						<tr>
							<td colspan="5">暂无记录</td>
						</tr>
				<?php } ?>
			</tbody>

			
		</table>

		<div class="tablenav">
			<div class="alignleft actions">
				<select name="op">
					<option value="" selected="selected">批量操作</option>
					<option value="vip">设置VIP</option>
				</select>
                <select name="month" id="month">
                      <option value="1">1个月</option>
                      <option value="3">3个月</option>
                      <option value="6">半年</option>
                      <option value="12">一年</option>
                      <option value="24">二年</option>
                      <option value="36">三年</option>
                      <option value="240">不限</option>
              </select>
              <label class="label_td" for="memo">备注</label><input type="text" name="memo" id="memo"/>
              <label class="label_td" for="money">支付金额</label><input type="text" name="money" id="money"/>
             
               <select name="paytype" id="paytype">
               <option value="gift">赠送</option>
                      <option value="alipay">支付宝</option>
                      <option value="wechat">微信</option>
                      <option value="baidupay">百度钱包</option>
                      <option value="paypal">PayPal</option>
              </select>
				<input value="应用" name="batch" id="batch" class="button-secondary action" type="submit"/>
			</div>
            <?php echo $pages;?>
			<br class="clear">
		</div>
	</form>

</div>