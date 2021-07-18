<!DOCTYPE html>
<html class="x-admin-sm">
<head>
    <meta charset="UTF-8">
    <title>龙海后台系统</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport"
          content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8,target-densitydpi=low-dpi"/>
    <link rel="stylesheet" href="<?= STA ?>/css/font.css">
    <link rel="stylesheet" href="<?= STA ?>/css/xadmin.css">
    <script type="text/javascript" src="<?= STA ?>/lib/layui/layui.js" charset="utf-8"></script>
    <script type="text/javascript" src="<?= STA ?>/js/xadmin.js"></script>
    <script type="text/javascript" src="<?= STA ?>/js/jquery-1.11.2.min.js"></script>
    <script type="text/javascript" src="<?= STA ?>/js/jquery.validate.js"></script>
    <script type="text/javascript" src="<?= STA ?>/js/upload/jquery_form.js"></script>
</head>
<body>
<div class="layui-fluid" style="padding-top: 66px;">
    <div class="layui-row">
        <form method="post" class="layui-form" action="" name="basic_validate" id="tab">

			<div class="layui-form-item">
				<label for="L_pass" class="layui-form-label" style="width: 30%;">
					<span class="x-red">*</span>名称
				</label>
				<div class="layui-input-inline" style="width: 300px;">
					<input type="text" value="<?php echo $fname ?>" id="fname" name="fname" lay-verify="fname"
						   autocomplete="off" class="layui-input">
				</div>
			</div>
			<div class="layui-form-item">
				<label for="L_pass" class="layui-form-label" style="width: 30%;">
					<span class="x-red">*</span>类型
				</label>
				<div class="layui-input-inline layui-show-xs-block">
					<div style="width: 300px" class="layui-input-inline layui-show-xs-block">
						<select name="ftid" id="ftid" lay-verify="ftid">
							<?php if (isset($typelist) && !empty($typelist)) { ?>
								<option value="">请选择</option>
								<?php foreach ($typelist as $k => $v) : ?>
									<option <?php echo $ftid == $v['ftid'] ? 'selected' : '' ?> value="<?= $v['ftid'] ?>"><?= $v['fname'] ?></option>
								<?php endforeach; ?>
							<?php } ?>
						</select>
					</div>
				</div>
			</div>
			<div class="layui-form-item">
				<label for="L_pass" class="layui-form-label" style="width: 30%;">

				</label>
				<div class="layui-input-inline" style="width: 300px;">
					<button type="button" class="layui-btn" id="upload1">上传文件</button>
				</div>
			</div>
			<div class="layui-form-item">
				<label for="L_pass" class="layui-form-label" style="width: 30%;">

				</label>
				<div class="layui-input-inline" style="width: 300px;">
					<input type="text" readonly id="pdfurl" name="pdfurl"
						   autocomplete="off" value="<?php echo $furl ?>" class="layui-input">
					<input type="hidden" readonly id="pdfhash" name="pdfhash"
						   autocomplete="off" value="<?php echo $hash ?>" class="layui-input">
				</div>
			</div>
			<div class="layui-form-item">
				<label for="L_pass" class="layui-form-label" style="width: 30%;">

				</label>
				<div class="layui-input-inline" style="width: 300px;">
					<input type="text" readonly id="fsize" name="fsize"
						   autocomplete="off" value="<?php echo $fsize ?>" class="layui-input">
				</div>
			</div>
			<?php if ($username != $_SESSION['user_name']){ ?>

			<?php }else{ ?>
				<div class="layui-form-item">
					<label for="L_pass" class="layui-form-label" style="width: 30%;">
						<span class="x-red">*</span>权限
					</label>
					<div class="layui-input-inline" style="width: 500px;">
						<input type="radio" name="is_open" lay-skin="primary" <?php echo $is_open == 1 ? 'checked' : '' ?> title="上传账号可编辑、删除。" value="1" checked>
						<input type="radio" name="is_open" lay-skin="primary" <?php echo $is_open != 1  ? 'checked' : '' ?> title="全员账号可编辑、删除。" value="0">
					</div>
				</div>
			<?php } ?>
            <div class="layui-form-item">
                <label class="layui-form-label" style="width: 30%;">
                </label>
                <div class="layui-input-inline" style="width: 300px;">
                    <span class="x-red">※</span>温馨提示：允许的最大上传文件大小为30M。
                </div>
            </div>
            <input type="hidden" id="fid" name="fid" value="<?php echo $fid ?>">
            <div class="layui-form-item">
                <label for="L_repass" class="layui-form-label" style="width: 30%;">
                </label>
                <button class="layui-btn" lay-filter="add" lay-submit="">
                    确认提交
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    layui.use('upload', function(){
        var $ = layui.jquery
            ,upload = layui.upload;
        upload.render({ //允许上传的文件后缀
            elem: '#upload1'
            ,url: '<?= RUN . '/upload/pushFIleNew' ?>'
            ,accept: 'file' //普通文件
            ,before: function(obj){ //obj参数包含的信息，跟 choose回调完全一致，可参见上文。
                layer.load(); //上传loading
            }
            ,done: function(res){
            layer.closeAll('loading'); //关闭loading
                console.log(res)
                if(res.code == 200){
                    console.log(res.src)
                    $('#pdfurl').val(res.src);
                    $('#pdfhash').val(res.hash);
                    $('#fsize').val(res.size);
                    if(!$('#fname').val()){
                       $('#fname').val(res.filename);
                    }
                    return layer.msg('上传成功');
                }else {
                    return layer.msg('上传失败');
                }
            }
        });
    });
</script>
<script>
    layui.use(['form','layedit', 'layer'],
        function () {
            var form = layui.form,
                layer = layui.layer;
            var layedit = layui.layedit;
            layedit.set({
                uploadImage: {
                    url: '<?= RUN . '/upload/pushFIletextarea' ?>',
                    type: 'post',
                }
            });
            var editIndex1 = layedit.build('gcontent', {
                height: 300,
            });
            //自定义验证规则
            form.verify({
                fname: function (value) {
                    if ($('#fname').val() == "") {
                        return '请输入文件名称。';
                    }
                },
                ftid: function (value) {
                    if ($("#ftid option:selected").val() == "") {
                        return '请选择文件类型。';
                    }
                },
                pdfurl: function (value) {
                    if ($('#pdfurl').val() == "") {
                        return '请上传文件信息。';
                    }
                },
            });
            $("#tab").validate({
                submitHandler: function (form) {
                    $.ajax({
                        cache: true,
                        type: "POST",
                        url: "<?= RUN . '/goods/goods_save_edit' ?>",
                        data: $('#tab').serialize(),
                        async: false,
                        error: function (request) {
                            alert("error");
                        },
                        success: function (data) {
                            var data = eval("(" + data + ")");
                            if (data.success) {
                                layer.msg(data.msg);
                                setTimeout(function () {
                                    cancel();
                                }, 2000);
                            } else {
                                layer.msg(data.msg);
                            }
                        }
                    });
                }
            });
        });

    function cancel() {
        //关闭当前frame
        xadmin.close();
    }
</script>
</body>
</html>
