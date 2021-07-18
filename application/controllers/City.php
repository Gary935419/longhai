<?php

/**
 * **********************************************************************
 * サブシステム名  ： Task
 * 機能名         ：部门
 * 作成者        ： Gary
 * **********************************************************************
 */
class City extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!isset($_SESSION['user_name'])) {
            header("Location:" . RUN . '/login/logout');
        }
        $this->load->model('City_model', 'city');
        header("Content-type:text/html;charset=utf-8");
    }
    /**
     * 部门列表页
     */
    public function city_list()
    {
        $dname = isset($_GET['dname']) ? $_GET['dname'] : '';
        $page = isset($_GET["page"]) ? $_GET["page"] : 1;
        $allpage = $this->city->getcityAllPage($dname);
        $page = $allpage > $page ? $page : $allpage;
        $data["pagehtml"] = $this->getpage($page, $allpage, $_GET);
        $data["page"] = $page;
        $data["allpage"] = $allpage;
        $data["list"] = $this->city->getcityAll($page, $dname);
        $data["dname"] = $dname;
        $this->display("city/city_list", $data);
    }
    /**
     * 部门添加页
     */
    public function city_add()
    {
        $this->display("city/city_add");
    }
    /**
     * 部门添加提交
     */
    public function city_save()
    {
        if (empty($_SESSION['user_name'])) {
            echo json_encode(array('error' => false, 'msg' => "无法添加数据"));
            return;
        }

        $dname = isset($_POST["dname"]) ? $_POST["dname"] : '';
        $add_time = time();

        $city_info = $this->city->getcityByname($dname);
        if (!empty($city_info)) {
            echo json_encode(array('error' => true, 'msg' => "该部门已经存在。"));
            return;
        }
        $result = $this->city->city_save($dname,$add_time);
        if ($result) {
            echo json_encode(array('success' => true, 'msg' => "操作成功。"));
            return;
        } else {
            echo json_encode(array('error' => false, 'msg' => "操作失败"));
            return;
        }
    }
    /**
     * 部门删除
     */
    public function city_delete()
    {
        $did = isset($_POST['did']) ? $_POST['did'] : 0;
		$member_info = $this->city->getmemberById($did);
		if (!empty($member_info)) {
			echo json_encode(array('error' => true, 'msg' => "当前部门已有账户设定中。"));
			return;
		}
        if ($this->city->city_delete($did)) {
            echo json_encode(array('success' => true, 'msg' => "删除成功"));
            return;
        } else {
            echo json_encode(array('success' => false, 'msg' => "删除失败"));
            return;
        }
    }
    /**
     * 部门修改页
     */
    public function city_edit()
    {
        $did = isset($_GET['did']) ? $_GET['did'] : 0;
        $city_info = $this->city->getcityById($did);
        if (empty($city_info)) {
            echo json_encode(array('error' => true, 'msg' => "数据错误"));
            return;
        }
        $data = array();
        $data['dname'] = $city_info['dname'];
        $data['did'] = $did;
        $this->display("city/city_edit", $data);
    }
    /**
     * 部门修改提交
     */
    public function city_save_edit()
    {
        if (empty($_SESSION['user_name'])) {
            echo json_encode(array('error' => false, 'msg' => "无法修改数据"));
            return;
        }

        $dname = isset($_POST["dname"]) ? $_POST["dname"] : '';
        $did = isset($_POST["did"]) ? $_POST["did"] : '';

        $city_info = $this->city->getcityById2($dname, $did);
        if (!empty($city_info)){
            echo json_encode(array('error' => false, 'msg' => "该部门已经存在"));
            return;
        }

        $result = $this->city->city_save_edit($did,$dname);
        if ($result) {
            echo json_encode(array('success' => true, 'msg' => "操作成功。"));
            return;
        } else {
            echo json_encode(array('error' => false, 'msg' => "操作失败"));
            return;
        }
    }




	/**
	 * 类型列表页
	 */
	public function city_list1()
	{
		$fname = isset($_GET['fname']) ? $_GET['fname'] : '';
		$page = isset($_GET["page"]) ? $_GET["page"] : 1;
		$allpage = $this->city->getcityAllPage1($fname);
		$page = $allpage > $page ? $page : $allpage;
		$data["pagehtml"] = $this->getpage($page, $allpage, $_GET);
		$data["page"] = $page;
		$data["allpage"] = $allpage;
		$data["list"] = $this->city->getcityAll1($page, $fname);
		$data["fname"] = $fname;
		$this->display("city/city_list1", $data);
	}
	/**
	 * 类型添加页
	 */
	public function city_add1()
	{
		$this->display("city/city_add1");
	}
	/**
	 * 类型添加提交
	 */
	public function city_save1()
	{
		if (empty($_SESSION['user_name'])) {
			echo json_encode(array('error' => false, 'msg' => "无法添加数据"));
			return;
		}

		$fname = isset($_POST["fname"]) ? $_POST["fname"] : '';
		$add_time = time();

		$city_info = $this->city->getcityByname1($fname);
		if (!empty($city_info)) {
			echo json_encode(array('error' => true, 'msg' => "该类型已经存在。"));
			return;
		}
		$result = $this->city->city_save1($fname,$add_time);
		if ($result) {
			echo json_encode(array('success' => true, 'msg' => "操作成功。"));
			return;
		} else {
			echo json_encode(array('error' => false, 'msg' => "操作失败"));
			return;
		}
	}
	/**
	 * 类型删除
	 */
	public function city_delete1()
	{
		$ftid = isset($_POST['ftid']) ? $_POST['ftid'] : 0;
		if ($this->city->city_delete1($ftid)) {
			echo json_encode(array('success' => true, 'msg' => "删除成功"));
			return;
		} else {
			echo json_encode(array('success' => false, 'msg' => "删除失败"));
			return;
		}
	}
	/**
	 * 类型修改页
	 */
	public function city_edit1()
	{
		$ftid = isset($_GET['ftid']) ? $_GET['ftid'] : 0;
		$city_info = $this->city->getcityById1($ftid);
		if (empty($city_info)) {
			echo json_encode(array('error' => true, 'msg' => "数据错误"));
			return;
		}
		$data = array();
		$data['fname'] = $city_info['fname'];
		$data['ftid'] = $ftid;
		$this->display("city/city_edit1", $data);
	}
	/**
	 * 类型修改提交
	 */
	public function city_save_edit1()
	{
		if (empty($_SESSION['user_name'])) {
			echo json_encode(array('error' => false, 'msg' => "无法修改数据"));
			return;
		}

		$fname = isset($_POST["fname"]) ? $_POST["fname"] : '';
		$ftid = isset($_POST["ftid"]) ? $_POST["ftid"] : '';

		$city_info = $this->city->getcityById21($fname, $ftid);
		if (!empty($city_info)){
			echo json_encode(array('error' => false, 'msg' => "该类型已经存在"));
			return;
		}

		$result = $this->city->city_save_edit1($ftid,$fname);
		if ($result) {
			echo json_encode(array('success' => true, 'msg' => "操作成功。"));
			return;
		} else {
			echo json_encode(array('error' => false, 'msg' => "操作失败"));
			return;
		}
	}
}
