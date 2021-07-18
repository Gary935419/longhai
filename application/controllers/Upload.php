<?php

/**
 * **********************************************************************
 * サブシステム名  ： TASK
 * 機能名         ：上传
 * 作成者        ： Gary
 * **********************************************************************
 */
class Upload extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!isset($_SESSION['user_name'])) {
            header("Location:" . RUN . '/login/logout');
        }
		$this->load->library("php-sdk-master/autoload");
        header("Content-type:text/html;charset=utf-8");
    }
    function GetRandStr($length){
        $str='abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $len=strlen($str)-1;
        $randstr='';
        for($i=0;$i<$length;$i++){
            $num=mt_rand(0,$len);
            $randstr .= $str[$num];
        }
        return $randstr;
    }

	function getsize($size, $format) {
		$p = 0;
		if ($format == 'kb') {
			$p = 1;
		} elseif ($format == 'mb') {
			$p = 2;
		} elseif ($format == 'gb') {
			$p = 3;
		}
		$size /= pow(1024, $p);
		return number_format($size, 3);
	}
	/**
	 * 文件上传
	 */
	public function pushFIleNew(){
		$token = $this->autoload->get_token();
		$strname = $_FILES['file']['name'];
		$str_arr = explode('.',$strname);
		$filenamwnow = $str_arr[0];
		$_swap = time();
		$number=$this->GetRandStr(2);
		$_swap = $_swap.$number;
		$fileName = $_swap.".".substr(strrchr($_FILES['file']['name'], '.'), 1);
		// 要上传文件的本地路径
		$filePath = $_FILES['file']["tmp_name"];
		$size = filesize($_FILES['file']["tmp_name"]);
		$size = $this->getsize($size, 'kb'); //进行单位转换
        // 调用 UploadManager 的 putFile 方法进行文件的上传。
		list($ret, $err) = $this->autoload->putFileNew($token, $fileName, $filePath);

		$maxsize = 30720;
		if ($size > $maxsize){
			$code = 203;
			$src = '';
			$hash = '';
			$msg = "上传失败,已超过最大文件大小上传限制。";
		}else{
			if ($err !== null) {
				$code = 203;
				$src = 'http://longhai.jiuyuangroup.cn/';
				$hash = '';
				$msg = "上传失败";
			} else {
				$src = 'http://longhai.jiuyuangroup.cn/'.$ret['key'];
				$hash = $ret['hash'];
				$msg = "上传成功";
				$code = 200;
			}
		}
		echo json_encode(array('code' => $code,'src' => $src,'hash' => $hash,'size' => $size.'kb','filename' => $filenamwnow,'msg' => $msg));
		return;
	}
    /**
     * 单图片上传
     */
    public function pushFIle(){
        $src="";
        $_swap = time();
        $number=$this->GetRandStr(2);
        $_swap = $_swap.$number;
        $fileName = $_swap.".".substr(strrchr($_FILES['file']['name'], '.'), 1);
        move_uploaded_file($_FILES['file']["tmp_name"], "./static/uploads/".$fileName);
        if (file_exists("./static/uploads/".$fileName)) {
            $src="/static/uploads/".$fileName;
        }
        echo json_encode(array('code' => 200,'src' => "http://ryksht.ychlkj.cn".$src, 'msg' => "上传成功"));
        return;
    }
    /**
     * 富文本单图片上传
     */
    public function pushFIletextarea(){
        $src="";
        $_swap = time();
        $fileName = $_swap.".".substr(strrchr($_FILES['file']['name'], '.'), 1);
        move_uploaded_file($_FILES['file']["tmp_name"], "./static/uploads/".$fileName);
        if (file_exists("./static/uploads/".$fileName)) {
            $src="/static/uploads/".$fileName;
        }
        $data = array();
        $data['src'] = "http://www.task.com".$src;
        echo json_encode(array('code' => 0,'msg' => "上传成功", 'data' => $data));
        return;
    }
    /**
     * 多图片上传
     */
    public function pushFIles(){

        $count=sizeof($_FILES['file']['name']);
        $src=array();
        for ($i=0;$i<$count;$i++) {
            $_swap = time()."_".$i;
            $fileName = $_swap.".".substr(strrchr($_FILES['file']['name'][$i], '.'), 1);
            move_uploaded_file($_FILES['file']["tmp_name"][$i], "./static/upload/".$fileName);
            if (file_exists("./static/upload/".$fileName)) {
                $src[]="http://www.task.com"."/static/upload/".$fileName;
            }
        }
        $data = array();
        $data['src'] = $src;
        echo json_encode(array('code' => 0,'msg' => "上传成功", 'data' => $data));
        return;
    }
}
