<div class="wrap">
	<h2>购买统计</h2>
     
	<form action="" method="post">
		<table class="widefat fixed" cellspacing="0">
			<thead>
				<tr>
					<th scope="col" class="manage-column column-name" width="20%">日期</th>
					<th scope="col" class="manage-column column-name" width="20%">数量</th>
					<th scope="col" class="manage-column column-name" width="20%">金额</th>
				</tr>
			</thead>

			<tbody>
			<?php
				
				if(isset($list) && count($list ) > 0) {
				$num = 0;
				$money = 0;
					foreach($list as $v) {
						$num += $v['num'];
						$money += $v['money'];
						?>
						<tr valign="middle" id="link-2">
							<td class="username column-username"><?php echo $v['smonth'];?></td>
							<td class="column-name"><?php echo $v['num'];?></td>
							<td class="column-name"><?php echo $v['money'];?></td>
						</tr>
						<?php
					}
					?>
                    <tr valign="middle" id="link-2">
							<td class="username column-username">合计：&nbsp;</td>
							<td class="column-name"><?php echo $num;?></td>
							<td class="column-name"><?php echo $money;?></td>
						</tr>
                        <?php 
				} else { ?>
						<tr>
							<td colspan="3">暂无记录</td>
						</tr>
				<?php } ?>
			</tbody>

			
		</table>

		
	</form>

</div>