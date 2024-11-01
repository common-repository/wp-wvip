<style>
    .form-table th, .form-wrap label{font-size:12px;color:#333;}
    .form-table th{width:120px;}
    .locale-zh-cn p.description{font-size:12px;}
    .adside{display:block; width:350px; padding-top:30px; margin-left:20px;}
    .adside a{display:block; text-decoration:none; background-color:#ccc; margin:0 5px 10px 5px; text-align:center;color:#fff;}
    .adside .ad1,.adside .ad2{width:150px; line-height:150px; float:left;}
    .adside .ad4{width:260px; line-height:20px;}
    .adside .ad3{width:310px; line-height:60px; clear:both;}
    .admain{overflow:hidden; }
    .js .postbox .hndle{ cursor:default;}
</style>
<div class="wrap" id="poststuff">
    <h1>设置</h1>
    <form method="post" action="options.php">
        <?php
        settings_fields( $setting_field );
        ?>
        <!-- 基本设置 S-->
        <div class="postbox-container">
            <div class="postbox">
                <h3 class="hndle">
                    <span>基本设置</span>
                </h3>
                <div class="inside">
                    <table class="form-table">
                        <tr valign="top">
                            <th>
                                <label for="<?php echo $option_name;?>_vipurl">
                                    VIP购买页面
                                </label>
                            </th>
                            <td>
                                <input type="text"
                                       id="<?php echo $option_name;?>_vipurl"
                                       name="<?php echo $option_name;?>[vipurl]"
                                       class="regular-text"
                                       value="<?php echo isset($op_sets['vipurl'])?$op_sets['vipurl']:'';?>" />
                              
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <!-- 基本设置 E-->

     
        <!-- 高级设置 S-->
        <div class="postbox-container">
            <div class="meta-box-sortables">
                <div class="postbox">
                    
                    <h2 class="hndle">
                        <span>高级设置</span>
                    </h2>
                    <div class="inside">
                        <table class="form-table">
                            
                            <tr valign="top">
                                <th>
                                    <label for="<?php echo $option_name;?>_tpl">
                                        自定义文章页下载信息版块无权限下载html模板
                                    </label>
                                </th>
                                <td>
                                    <textarea name="<?php echo $option_name;?>[tpl]" rows="5" cols="50" id="<?php echo $option_name;?>_tpl" class="large-text code"><?php echo isset($op_sets['tpl'])?$op_sets['tpl']:'';?></textarea>
                                    <p class="description">高级用户可以通过编写或者修改的形式，替换现有的文章页面的下载信息区块的HTML结构。</p>
                                </td>
                            </tr>
                            
                             <tr valign="top">
                                <th>
                                    <label for="<?php echo $option_name;?>_viptpl">
                                        自定义文章页下载信息版块VIP下载html模板
                                    </label>
                                </th>
                                <td>
                                    <textarea name="<?php echo $option_name;?>[viptpl]" rows="5" cols="50" id="<?php echo $option_name;?>_viptpl" class="large-text code"><?php echo isset($op_sets['viptpl'])?$op_sets['viptpl']:'';?></textarea>
                                    <p class="description">高级用户可以通过编写或者修改的形式，替换现有的文章页面的下载信息区块的HTML结构。</p>
                                </td>
                            </tr>

                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- 高级设置 E-->

        <div class="postbox-container">
            <p class="submit">
                <input type="submit"
                       name="Submit"
                       class="button-primary"
                       value="保存" />
            </p>
        </div>
    </form>
</div>


