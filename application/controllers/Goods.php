<?php

/**
 * **********************************************************************
 * サブシステム名  ： Task
 * 機能名         ：文件
 * 作成者        ： Gary
 * **********************************************************************
 */
class Goods extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!isset($_SESSION['user_name'])) {
            header("Location:" . RUN . '/login/logout');
        }
        $this->load->model('Goods_model', 'goods');
        $this->load->model('Task_model', 'task');
		$this->load->model('Label_model', 'label');
        $this->load->model('Taskclass_model', 'taskclass');
        header("Content-type:text/html;charset=utf-8");
    }
    /**
     * 文件列表页
     */
    public function goods_list()
    {
		if (empty($_SESSION['user_name'])) {
			echo json_encode(array('error' => false, 'msg' => "无法加载数据"));
			return;
		}
		$start = isset($_GET['start']) ? $_GET['start'] : '';
		$end = isset($_GET['end']) ? $_GET['end'] : '';
		$fname = isset($_GET['fname']) ? $_GET['fname'] : '';
		$page = isset($_GET["page"]) ? $_GET["page"] : 1;

		if ($_SESSION['user_name'] == 'admin'){
			$data["ftid_op"] = 1;
            $allpage = $this->goods->getgoodsAllPage($fname,$start,$end);
            $page = $allpage > $page ? $page : $allpage;
            $list = $this->goods->getgoodsAllNew($page,$fname,$start,$end);
        }else{
            $member_info = $this->goods->getgoodsByname123($_SESSION['user_name']);
            $rid = $member_info['rid'];
            $ftidlist = $this->goods->gettidlist123($rid);
            if (empty($ftidlist)){
                $start = '2030-12-12';
                $ftid = array();
                $data["ftid_op"] = 0;
            }else{
                $data["ftid_op"] = 1;
                foreach ($ftidlist as $k=>$v){
                    $ftid[] = $v['ftid'];
                }
            }
            $allpage = $this->goods->getgoodsAllPage($fname,$start,$end,$ftid);
            $page = $allpage > $page ? $page : $allpage;
            $list = $this->goods->getgoodsAllNew($page,$fname,$start,$end,$ftid);
        }

        $data["pagehtml"] = $this->getpage($page, $allpage, $_GET);
        $data["page"] = $page;
        $data["allpage"] = $allpage;
        $data["fname"] = $fname;
        if (!empty($list)){
			foreach ($list as $k=>$v){
				$tidone = $this->goods->gettaskclassById($v['ftid']);
				$list[$k]['ftname'] = $tidone['fname'];
			}
		}

        $data["list"] = $list;
		$data["start"] = $start == '2030-12-12' ? '' : $start;
		$data["end"] = $end;
        $this->display("goods/goods_list", $data);
    }
    /**
     * 文件添加页
     */
    public function goods_add()
    {
		if (empty($_SESSION['user_name'])) {
			echo json_encode(array('error' => false, 'msg' => "无法加载数据"));
			return;
		}
		$member_info = $this->goods->getgoodsByname123($_SESSION['user_name']);
		$rid = $member_info['rid'];
		$ftidlist = $this->goods->gettidlist123($rid);
		if (empty($ftidlist)){
			$ftid = array();
		}else{
			foreach ($ftidlist as $k=>$v){
				$ftid[] = $v['ftid'];
			}
		}
		$typelist = $this->goods->gettidlist($ftid);
        $data['typelist'] = $typelist;
        $this->display("goods/goods_add",$data);
    }
    /**
     * 文件添加提交
     */
    public function goods_save()
    {
        if (empty($_SESSION['user_name'])) {
            echo json_encode(array('error' => false, 'msg' => "无法添加数据"));
            return;
        }
        $fname = isset($_POST["fname"]) ? $_POST["fname"] : '';
        $ftid = isset($_POST["ftid"]) ? $_POST["ftid"] : '';
        $furl = isset($_POST["pdfurl"]) ? $_POST["pdfurl"] : '';
        $hash = isset($_POST["pdfhash"]) ? $_POST["pdfhash"] : '';
        $fsize = isset($_POST["fsize"]) ? $_POST["fsize"] : '';
		$is_open = isset($_POST["is_open"]) ? $_POST["is_open"] : '';
        $addtime = time();
		$username = $_SESSION['user_name'];
        $goods_info = $this->goods->getgoodsByname($fname);
        if (!empty($goods_info)) {
            echo json_encode(array('error' => true, 'msg' => "该文件名称已经存在。"));
            return;
        }
        $gid = $this->goods->goods_save($fname,$ftid,$furl,$hash,$fsize,$addtime,$username,$is_open);
		$nowtime = date('Y-m-d H:i:s',time());
		$log_news = "账号：".$_SESSION['user_name'].",在".$nowtime."上传一个文件。文件名称：".$fname;
		$this->label->log_save($_SESSION['user_name'],$log_news);
        if ($gid) {
            echo json_encode(array('success' => true, 'msg' => "操作成功。"));
            return;
        } else {
            echo json_encode(array('error' => false, 'msg' => "操作失败"));
            return;
        }
    }
    /**
     * 文件删除
     */
    public function goods_delete()
    {
		$fid = isset($_POST['fid']) ? $_POST['fid'] : 0;
		$stb = isset($_POST['stb']) ? $_POST['stb'] : 0;
		$goods_info = $this->goods->getgoodsById($fid);
		if (empty($goods_info)) {
			echo json_encode(array('error' => true, 'msg' => "数据错误"));
			return;
		}
		if ($stb == 1){
			if ($this->goods->goods_delete($fid)) {
				$nowtime = date('Y-m-d H:i:s',time());
				$log_news = "账号：".$_SESSION['user_name'].",在".$nowtime."删除一个文件。文件名称：".$goods_info['fname'];
				$this->label->log_save($_SESSION['user_name'],$log_news);
				echo json_encode(array('success' => true, 'msg' => "删除成功"));
				return;
			} else {
				echo json_encode(array('success' => false, 'msg' => "删除失败"));
				return;
			}
		}else{
			$nowtime = date('Y-m-d H:i:s',time());
			$log_news = "账号：".$_SESSION['user_name'].",在".$nowtime."下载一个文件。文件名称：".$goods_info['fname'];
			$this->label->log_save($_SESSION['user_name'],$log_news);
		}

    }
    /**
     * 文件修改页
     */
    public function goods_edit()
    {
        $fid = isset($_GET['fid']) ? $_GET['fid'] : 0;
        $goods_info = $this->goods->getgoodsById($fid);
        if (empty($goods_info)) {
            echo json_encode(array('error' => true, 'msg' => "数据错误"));
            return;
        }
		if (empty($_SESSION['user_name'])) {
			echo json_encode(array('error' => false, 'msg' => "无法加载数据"));
			return;
		}
		$member_info = $this->goods->getgoodsByname123($_SESSION['user_name']);
		$rid = $member_info['rid'];
		$ftidlist = $this->goods->gettidlist123($rid);
		if (empty($ftidlist)){
			$ftid = array();
		}else{
			foreach ($ftidlist as $k=>$v){
				$ftid[] = $v['ftid'];
			}
		}
		$typelist = $this->goods->gettidlist($ftid);
		$data['typelist'] = $typelist;
        $data['fname'] = $goods_info['fname'];
        $data['fsize'] = $goods_info['fsize'];
        $data['furl'] = $goods_info['furl'];
        $data['ftid'] = $goods_info['ftid'];
        $data['username'] = $goods_info['username'];
        $data['hash'] = $goods_info['hash'];
		$data['is_open'] = $goods_info['is_open'];
        $data['fid'] = $fid;
        $this->display("goods/goods_edit", $data);
    }
    /**
     * 文件修改提交
     */
    public function goods_save_edit()
    {
        if (empty($_SESSION['user_name'])) {
            echo json_encode(array('error' => false, 'msg' => "无法修改数据"));
            return;
        }
        $fid = isset($_POST["fid"]) ? $_POST["fid"] : '';
		$fname = isset($_POST["fname"]) ? $_POST["fname"] : '';
		$ftid = isset($_POST["ftid"]) ? $_POST["ftid"] : '';
		$furl = isset($_POST["pdfurl"]) ? $_POST["pdfurl"] : '';
		$hash = isset($_POST["pdfhash"]) ? $_POST["pdfhash"] : '';
		$is_open = isset($_POST["is_open"]) ? $_POST["is_open"] : '';
		$fsize = isset($_POST["fsize"]) ? $_POST["fsize"] : '';
		$addtime = time();
        $goods_info = $this->goods->getgoodsById2($fname,$fid);
        if (!empty($goods_info)) {
            echo json_encode(array('error' => true, 'msg' => "该文件名称已经存在。"));
            return;
        }

        $result = $this->goods->goods_save_edit($fid,$fname,$ftid,$furl,$hash,$fsize,$addtime,$is_open);
        if ($result) {
			$nowtime = date('Y-m-d H:i:s',time());
			$log_news = "账号：".$_SESSION['user_name'].",在".$nowtime."修改一个文件。文件名称：".$fname;
			$this->label->log_save($_SESSION['user_name'],$log_news);
            echo json_encode(array('success' => true, 'msg' => "操作成功。"));
            return;
        } else {
            echo json_encode(array('error' => false, 'msg' => "操作失败"));
            return;
        }
    }

}
