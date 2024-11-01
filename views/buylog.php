<div class="wrap">
	<h2>VIP日志</h2>
     <form name="from1" action="<?php echo $current_url;?>" method="get">
    <input type="hidden" name="page" value="<?php echo esc_html($_GET['page']);?>"/>
    <div class="tablenav">
			<div class="alignleft actions">
				
               <input type="text" name="q" id="q" value="<?php echo isset($_GET['q'])?esc_html($_GET['q']):'';?>" placeholder="输入用户名或邮箱"/>
				<select name="year">
					<option value="">年</option>
                    <?php $cyear = date('Y');for($i=2016;$i<=$cyear;$i++){?>
                    <option value="<?php echo $i;?>"<?php echo isset($_GET['year']) && $_GET['year'] == $i?' selected':'';?>><?php echo $i;?></option>
                    <?php } ?>
				</select>
                <select name="month">
					<option value="">月</option>
                    <?php for($i=1;$i<=12;$i++){?>
                    <option value="<?php echo $i;?>"<?php echo isset($_GET['month']) && $_GET['month'] == $i?' selected':'';?>><?php echo $i;?></option>
                    <?php } ?>
				</select>
                
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
					<th scope="col" class="manage-column column-name" width="20%">会员</th>
					<th scope="col" class="manage-column column-name" width="20%">邮箱</th>
					<th scope="col" class="manage-column column-name" width="20%">添加日期</th>
					<th scope="col" class="manage-column column-name" width="20%">支付金额</th>
					<th scope="col" class="manage-column column-name" width="20%">支付方式</th>
					<th scope="col" class="manage-column column-name" width="10%">数量</th>
					<th scope="col" class="manage-column column-name" width="10%">备注</th>
				</tr>
			</thead>

			<tbody>
			<?php
				
				if(isset($list) && count($list ) > 0) {
				
					foreach($list as $v) {
						?>
						<tr valign="middle" id="link-2">
							<th class="check-column" scope="row"><input type="checkbox" name="id[]" value="<?php echo $v['id'] ; ?>" /></th>
							<td class="username column-username"><?php echo $v['user_login'];?></td>
							<td class="column-name"><?php echo $v['user_email'];?></td>
							<td class="column-name"><?php echo $v['creatime'];?></td>
							<td class="column-name"><?php echo $v['money'];?></td>
							<td class="column-name"><?php echo $v['paytype'];?></td>
							<td class="column-name"><?php echo $v['month']>36?'不限':$v['month'].'个月';?></td>
							<td class="column-name"><?php echo $v['memo'];?></td>
						</tr>
						<?php
					}
				} else { ?>
						<tr>
							<td colspan="8">暂无记录</td>
						</tr>
				<?php } ?>
			</tbody>

			
		</table>

		<div class="tablenav">
			<div class="alignleft actions">
				<select name="op">
					<option selected="selected">批量操作</option>
					<option value="delete">删除</option>
				</select>
				<input value="应用" name="batch" id="batch" class="button-secondary action" type="submit"/>
			</div>
            <?php echo $pages;?>
			<br class="clear">
		</div>
	</form>
<form action="" method="post">
	<table>
			<tr><td colspan="2"><h3>添加购买记录</h4></td></tr>
			<tr>
				<td><label for="username" class="label_td">会员</label></td>
				<td>
					<input type="text" placeholder="请输入用户名或邮箱" name="username" id="username"/>
				</td>
			</tr>
			<tr>
				<td><label class="label_td" for="money">支付金额</label></td>
				<td>
					<input type="text" name="money" id="money"/>
				</td>
			</tr>
			<tr>
				<td><label class="label_td" for="paytype">支付方式</label></td>
				<td>
				  
                    <select name="paytype" id="paytype">
                    <option value="gift">赠送</option>
                      <option value="alipay">支付宝</option>
                      <option value="wechat">微信</option>
                      <option value="baidupay">百度钱包</option>
                      <option value="paypal">PayPal</option>
              </select></td>
			</tr>
<tr>
				<td><label class="label_td" for="month">购买数量</label></td>
				<td>
				  <select name="month" id="month">
                      <option value="1">1个月</option>
                      <option value="3">3个月</option>
                      <option value="6">半年</option>
                      <option value="12">一年</option>
                      <option value="24">二年</option>
                      <option value="36">三年</option>
                      <option value="240">不限</option>
              </select>
                    </td>
			</tr>
            <tr>
				<td><label class="label_td" for="memo">备注</label></td>
				<td>
				  <input type="text" name="memo" id="memo"/>
				</td>
			</tr>
			<tr>
				<td colspan="2"><input type="submit" class="button-primary" name="save" value="保存" /></td>
			</tr>
		</table>
  </form>
</div>