<!doctype html>
<html class="x-admin-sm">
<head>
    <meta charset="UTF-8">
    <title>龙海后台系统</title>
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport"
          content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8,target-densitydpi=low-dpi"/>
    <meta http-equiv="Cache-Control" content="no-siteapp"/>
    <link rel="stylesheet" href="<?= STA ?>/css/font.css">
    <link rel="stylesheet" href="<?= STA ?>/css/xadmin.css">
    <script src="<?= STA ?>/lib/layui/layui.js" charset="utf-8"></script>
    <script type="text/javascript" src="<?= STA ?>/js/jquery.mini.js"></script>
    <script type="text/javascript" src="<?= STA ?>/js/xadmin.js"></script>
</head>
<body>
<div class="x-nav">
          <span class="layui-breadcrumb">
            <a>
              <cite>文件一览</cite></a>
          </span>
</div>
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-body ">
                    <form class="layui-form layui-col-space5" method="get" action="<?= RUN, '/goods/goods_list' ?>">
                        <div class="layui-inline layui-show-xs-block">
                            <input type="text" name="fname" id="fname" value="<?php echo $fname ?>"
                                   placeholder="文件名称" autocomplete="off" class="layui-input">
                        </div>
						<div class="layui-input-inline layui-show-xs-block">
							<input class="layui-input" placeholder="开始日期" value="<?php echo $start ?>" name="start" id="start"></div>
						<div class="layui-input-inline layui-show-xs-block">
							<input class="layui-input" placeholder="截止日期" value="<?php echo $end ?>" name="end" id="end"></div>
                        <div class="layui-inline layui-show-xs-block">
                            <button class="layui-btn" lay-submit="" lay-filter="sreach"><i
                                        class="layui-icon">&#xe615;</i></button>
                        </div>
                    </form>
                </div>
				<?php if ($_SESSION['user_name'] === 'admin'){ ?>
					<button class="layui-btn layui-card-header" style="float: right;margin-top: -40px;margin-right: 20px;"
							onclick="xadmin.open('上传文件','<?= RUN . '/goods/goods_add' ?>',1000,600)"><i
								class="layui-icon"></i>上传文件
					</button>
				<?php }else{ ?>
					<?php if ($ftid_op >= 1){ ?>
						<button class="layui-btn layui-card-header" style="float: right;margin-top: -40px;margin-right: 20px;"
								onclick="xadmin.open('上传文件','<?= RUN . '/goods/goods_add' ?>',1000,600)"><i
									class="layui-icon"></i>上传文件
						</button>
					<?php } ?>
				<?php } ?>
                <div class="layui-card-body ">
                    <table class="layui-table layui-form">
                        <thead>
                        <tr>
                            <th>序号</th>
							<th>上传账户</th>
                            <th>文件名称</th>
                            <th>文件大小</th>
                            <th>文件类型</th>
							<th>文件权限</th>
                            <th>更新时间</th>
                            <th>操作</th>
                        </thead>
                        <tbody>
                        <?php if (isset($list) && !empty($list)) { ?>
                            <?php foreach ($list as $num => $once): ?>
                                <tr id="p<?= $once['fid'] ?>" sid="<?= $once['fid'] ?>">
                                    <td><?= $num + 1 ?></td>
                                    <td><?= $once['username'] ?></td>
                                    <td><?= $once['fname'] ?></td>
									<td><?= $once['fsize'] ?></td>
									<td><?= $once['ftname'] ?></td>
									<?php if ($once['is_open'] == 1){ ?>
										<?php if ($_SESSION['user_name'] === 'admin'){ ?>
											<td style="color: green;">上传账号可编辑、删除。</td>
										<?php }else{ ?>
											<?php if ($once['username'] != $_SESSION['user_name']){ ?>
												<td style="color: red;">上传账号可编辑、删除。</td>
											<?php }else{ ?>
												<td style="color: green;">上传账号可编辑、删除。</td>
											<?php } ?>
										<?php } ?>
									<?php }else{ ?>
										<td style="color: green;">全员账号可编辑、删除。</td>
									<?php } ?>
                                    <td><?= date('Y-m-d H:i:s', $once['addtime']) ?></td>
                                    <td class="td-manage">
										<?php if ($once['is_open'] == 1 && $once['username'] != $_SESSION['user_name']){ ?>
											<?php if ($_SESSION['user_name'] === 'admin'){ ?>
												<button class="layui-btn layui-btn-normal"
														onclick="xadmin.open('编辑','<?= RUN . '/goods/goods_edit?fid=' ?>'+'<?= $once['fid'] ?>',1000,600)">
													<i class="layui-icon">&#xe642;</i>编辑
												</button>
												<button class="layui-btn layui-btn-danger"
														onclick="goods_delete('<?= $once['fid'] ?>',1)"><i class="layui-icon">&#xe640;</i>删除
												</button>
											<?php }else{ ?>
												<button class="layui-btn layui-btn-danger">文件权限未开放</button>
											<?php } ?>
								        <?php }else{ ?>
											<button class="layui-btn layui-btn-normal"
													onclick="xadmin.open('编辑','<?= RUN . '/goods/goods_edit?fid=' ?>'+'<?= $once['fid'] ?>',1000,600)">
												<i class="layui-icon">&#xe642;</i>编辑
											</button>
											<button class="layui-btn layui-btn-danger"
													onclick="goods_delete('<?= $once['fid'] ?>',1)"><i class="layui-icon">&#xe640;</i>删除
											</button>
								        <?php } ?>
										<a href="<?= $once['furl'] ?>" onclick="goods_delete('<?= $once['fid'] ?>',0)" style="margin-left: 10px;" target="_blank">
											<button class="layui-btn layui-btn-warm"><i class="layui-icon">&#xe714;</i>下载</button>
										</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php } else { ?>
                            <tr>
                                <td colspan="11" style="text-align: center;">暂无数据</td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
                <div class="layui-card-body ">
                    <div class="page">
                        <?= $pagehtml ?>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
</body>
<script>layui.use(['laydate', 'form'],
        function() {
            var laydate = layui.laydate;
            //执行一个laydate实例
            laydate.render({
                elem: '#start' //指定元素
            });
            //执行一个laydate实例
            laydate.render({
                elem: '#end' //指定元素
            });
        });
</script>
<script>
    function goods_delete(id,stb) {
        if(stb == 1){
			layer.confirm('您是否确认删除？', {
					title: '温馨提示',
					btn: ['确认', '取消']
					// 按钮
				},
				function (index) {
					$.ajax({
						type: "post",
						data: {"fid":id,"stb":1},
						dataType: "json",
						url: "<?= RUN . '/goods/goods_delete' ?>",
						success: function (data) {
							if (data.success) {
								$("#p" + id).remove();
								layer.alert(data.msg, {
										title: '温馨提示',
										icon: 6,
										btn: ['确认']
									},
								);
							} else {
								layer.alert(data.msg, {
										title: '温馨提示',
										icon: 5,
										btn: ['确认']
									},
								);
							}
						},
					});
				});
        }else{
          $.ajax({
                    type: "post",
                    data: {"fid":id,"stb":0},
                    dataType: "json",
                    url: "<?= RUN . '/goods/goods_delete' ?>",
                    success: function (data) {
                        if (data.success) {
                            $("#p" + id).remove();
                            layer.alert(data.msg, {
                                    title: '温馨提示',
                                    icon: 6,
                                    btn: ['确认']
                                },
                            );
                        } else {
                            layer.alert(data.msg, {
                                    title: '温馨提示',
                                    icon: 5,
                                    btn: ['确认']
                                },
                            );
                        }
                    },
                });
        }
    }
</script>
</html>
