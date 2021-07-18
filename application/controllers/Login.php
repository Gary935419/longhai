<?php

/**
 * **********************************************************************
 * サブシステム名  ： TASK
 * 機能名         ：登录
 * 作成者        ： Gary
 * **********************************************************************
 */
class Login extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("Users_model", 'user');
		$this->load->model('Label_model', 'label');
        header("Content-type:text/html;charset=utf-8");
    }
    /**
     * 登录页面
     */
    public function index()
    {
        $this->load->view('login');
    }
    /**
     * 登录验证
     */
    public function login()
    {
        $name = $_POST['username'];
        $pwd = $_POST['password'];

        $rest = $this->user->isPass($name, $pwd);
        if (!empty($rest)) {
            if ($rest['user_state'] == 2) {
                header("Location: " . RUN . '/login?err=2');
            } else {
            	$nowtime = date('Y-m-d H:i:s',time());
				$log_news = "账号：".$rest['username'].",在".$nowtime."登录后台管理。";
				$this->label->log_save($rest['username'],$log_news);
                $_SESSION['user_name'] = $rest['username'];
				$_SESSION['rid'] = $rest['rid'];
                header("Location: " . RUN . '/admin');
            }
        } else {
            header("Location: " . RUN . '/login?err=1');
        }
    }
    /**
     * 退出登录
     */
    public function logout()
    {
    	if (empty($_SESSION['user_name'])){
			$nowtime = date('Y-m-d H:i:s',time());
			$log_news = "在".$nowtime."的时候,由于长时间不在管理画面，账户出现强制登出。";
			$this->label->log_save($_SESSION['user_name'],$log_news);
		}else{
			$nowtime = date('Y-m-d H:i:s',time());
			$log_news = "账号：".$_SESSION['user_name'].",在".$nowtime."退出后台管理。";
			$this->label->log_save($_SESSION['user_name'],$log_news);
		}
        unset($_SESSION['user_name']);
		unset($_SESSION['rid']);

        header("Location: " . RUN . '/login');  // 跳转登录页
    }
}
