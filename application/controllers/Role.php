<?php

/**
 * **********************************************************************
 * サブシステム名  ： Task
 * 機能名         ：角色
 * 作成者        ： Gary
 * **********************************************************************
 */
class Role extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		if (!isset($_SESSION['user_name'])) {
			header("Location:" . RUN . '/login/logout');
		}
		$this->load->model('Users_model', 'users');
		$this->load->model('Role_model', 'role');
		header("Content-type:text/html;charset=utf-8");
	}
	/**
	 * 角色列表页
	 */
	public function role_list()
	{

		$page = isset($_GET["page"]) ? $_GET["page"] : 1;
		$allpage = $this->role->getroleAllPage();
		$page = $allpage > $page ? $page : $allpage;
		$data["pagehtml"] = $this->getpage($page, $allpage, $_GET);
		$data["page"] = $page;
		$data["allpage"] = $allpage;
		$data["list"] = $this->role->getroleAllNew($page);

		$this->display("role/role_list", $data);
	}
	/**
	 * 角色添加页
	 */
	public function role_add()
	{
		$data = array();
		$typelist = $this->role->gettypelist();
		$data['typelist'] = $typelist;
		$this->display("role/role_add",$data);
	}
	/**
	 * 角色添加提交
	 */
	public function role_save()
	{
		if (empty($_SESSION['user_name'])) {
			echo json_encode(array('error' => false, 'msg' => "无法添加数据"));
			return;
		}

		$rname = isset($_POST["rname"]) ? $_POST["rname"] : '';
		$rdetails = isset($_POST["rdetails"]) ? $_POST["rdetails"] : '';
		$menu = isset($_POST["menu"]) ? $_POST["menu"] : '';
		$menu1 = isset($_POST["menu1"]) ? $_POST["menu1"] : '';
		if (in_array(3,$menu)){
			if (empty($menu1)){
				echo json_encode(array('error' => false, 'msg' => "请先设定文件类型权限。"));
				return;
			}
		}
		$add_time = time();
		if (empty($rname) || empty($rdetails)) {
			echo json_encode(array('error' => false, 'msg' => "操作失败"));
			return;
		}
		$role_info = $this->role->getroleByname($rname);
		if (!empty($role_info)) {
			echo json_encode(array('error' => true, 'msg' => "该角色已经存在。"));
			return;
		}
		$rid = $this->role->role_save($rname, $rdetails, $add_time);
		if ($rid) {
			if (is_array($menu)){
				foreach ($menu as $k=>$v){
					$this->role->rtom_save($rid,$v);
				}
			}
			if (is_array($menu1)){
				foreach ($menu1 as $k=>$v){
					$this->role->rtom_save1($rid,$v);
				}
			}
			echo json_encode(array('success' => true, 'msg' => "操作成功。"));
			return;
		} else {
			echo json_encode(array('error' => false, 'msg' => "操作失败"));
			return;
		}
	}
	/**
	 * 角色删除
	 */
	public function role_delete()
	{
		$id = isset($_POST['id']) ? $_POST['id'] : 0;
		$member_info = $this->role->getmemberById($id);
		if (!empty($member_info)) {
			echo json_encode(array('error' => true, 'msg' => "当前角色已有账户设定中。"));
			return;
		}
		if ($this->role->role_delete($id)) {
			$this->role->role_delete_rtom($id);
			echo json_encode(array('success' => true, 'msg' => "删除成功"));
			return;
		} else {
			echo json_encode(array('success' => false, 'msg' => "删除失败"));
			return;
		}
	}
	/**
	 * 角色修改页
	 */
	public function role_edit()
	{
		$rid = isset($_GET['rid']) ? $_GET['rid'] : 0;
		$role_info = $this->role->getroleById($rid);
		if (empty($role_info)) {
			echo json_encode(array('error' => true, 'msg' => "数据错误"));
			return;
		}
		$data = array();
		$data['rname'] = $role_info['rname'];
		$data['rdetails'] = $role_info['rdetails'];
		$data['rid'] = $rid;
		/*$data['role_status1'] = empty($this->role->getroleByIdRtom($rid,1))?0:1;
		$data['role_status2'] = empty($this->role->getroleByIdRtom($rid,2))?0:1;
		$data['role_status3'] = empty($this->role->getroleByIdRtom($rid,3))?0:1;
		$typelistme = $this->role->gettypelistme($rid);*/

		$rid_result1 = $this->role->getroleByIdRtom($rid,1);
		$data['role_status1'] = empty($rid_result1)?0:1;
		$rid_result2 = $this->role->getroleByIdRtom($rid,2);
		$data['role_status2'] = empty($rid_result2)?0:1;
		$rid_result3 = $this->role->getroleByIdRtom($rid,3);
		$data['role_status3'] = empty($rid_result3)?0:1;
		$typelistme = $this->role->gettypelistme($rid);

		$data['typelistme'] = $typelistme;
		$typelist = $this->role->gettypelist();
		if (!empty($typelistme)){
			foreach ($typelist as $k=>$v){
				foreach ($typelistme as $kk=>$vv){
					if ($v['ftid'] === $vv['ftid']){
						$typelist[$k]['open'] = 1;
					}
				}
			}
		}
		$data['typelist'] = $typelist;
		$this->display("role/role_edit", $data);
	}
	/**
	 * 角色修改提交
	 */
	public function role_save_edit()
	{
		if (empty($_SESSION['user_name'])) {
			echo json_encode(array('error' => false, 'msg' => "无法添加数据"));
			return;
		}
		$rid = isset($_POST["rid"]) ? $_POST["rid"] : '';
		$rname = isset($_POST["rname"]) ? $_POST["rname"] : '';
		$rdetails = isset($_POST["rdetails"]) ? $_POST["rdetails"] : '';
		$menu = isset($_POST["menu"]) ? $_POST["menu"] : '';
		$menu1 = isset($_POST["menu1"]) ? $_POST["menu1"] : '';
		if (in_array(3,$menu)){
			if (empty($menu1)){
				echo json_encode(array('error' => false, 'msg' => "请先设定文件类型权限。"));
				return;
			}
		}
		if (empty($rname) || empty($rdetails)) {
			echo json_encode(array('error' => false, 'msg' => "操作失败"));
			return;
		}
		$this->role->role_delete_rtom($rid);
		$this->role->role_delete_rtom1($rid);
		if ($rid) {
			$this->role->role_save_edit($rid, $rname, $rdetails);
			if (is_array($menu)){
				foreach ($menu as $k=>$v){
					$this->role->rtom_save($rid,$v);
				}
			}
			if (is_array($menu1)){
				foreach ($menu1 as $k=>$v){
					$this->role->rtom_save1($rid,$v);
				}
			}
			echo json_encode(array('success' => true, 'msg' => "操作成功。"));
			return;
		} else {
			echo json_encode(array('error' => false, 'msg' => "操作失败"));
			return;
		}

	}

}
