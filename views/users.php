<div class="wrap">
	<h2>VIP会员</h2>
    <form name="from1" action="<?php echo $current_url;?>" method="get">
    <input type="hidden" name="page" value="<?php echo esc_html($_GET['page']);?>"/>
    <div class="tablenav">
			<div class="alignleft actions">
				<select name="type">
					<option value="" selected="selected">所有</option>
					<option value="1">VIP</option>
                    <option value="2">非VIP</option>
				</select>
                <script>document.from1.type.value = '<?php echo isset($_GET['type'])?esc_html($_GET['type']):'';?>';</script>
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
					<th scope="col" class="manage-column column-name" width="5%">VIP</th>
					<th scope="col" class="manage-column column-name" width="20%">用户名</th>
					<th scope="col" class="manage-column column-name" width="15%">用户邮箱</th>
					<th scope="col" class="manage-column column-name" width="15%">注册时间</th>
					<th scope="col" class="manage-column column-name" width="15%">过期时间</th>
					<th scope="col" class="manage-column column-name" width="10%">月份</th>
					<th scope="col" class="manage-column column-name" width="10%">状态</th>
				</tr>
			</thead>

			<tbody>
			<?php
				
				if(isset($list) && count($list ) > 0) {
					
					if(is_array($list))foreach($list as $v) {
						 ?>
						<tr valign="middle" id="link-2">
							<th class="check-column" scope="row"><input type="checkbox" name="id[]" value="<?php echo $v['id'] ; ?>" /></th>
							<td class="column-username"><?php echo $v['uid']?'VIP':'非VIP';?></td>
							<td class="username column-username"><?php echo $v['user_login']; ?></td>
							<td class="column-name"><?php echo $v['user_email']; ?></td>
							<td class="column-name"><?php echo $v['user_registered'];?></td>
							<td class="column-name"><?php echo $v['expiretime']; echo $v['uid'] && $v['isout']?'(过期)':''?></td>
							<td class="column-name"><?php echo $v['month'];?></td>
							<td class="column-name"><?php echo $v['uid']?($v['status']?'正常':'禁用'):''; ?></td>
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
					<option value="" selected="selected">批量操作</option>
					<option value="invalid">禁用</option>
                    <option value="valid">启用</option>
				</select>
				<input value="应用" name="batch" id="batch" class="button-secondary action" type="submit"/>
			</div>
            <?php echo $pages;?>
			<br class="clear">
		</div>
	</form>

</div>