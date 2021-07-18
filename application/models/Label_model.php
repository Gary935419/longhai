<?php


class Label_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->date = time();
        $this->load->database();
    }

    public function getlabelAllPage($username,$starttime,$end)
    {
        $sqlw = " where 1=1 ";
        if (!empty($username)) {
            $sqlw .= " and ( username like '%" . $username . "%' ) ";
        }
		if (!empty($starttime) && !empty($end)) {
			$starttime = strtotime($starttime);
			$end = strtotime($end)+86400;
			$sqlw .= " and addtime >= $starttime and addtime <= $end ";
		} elseif (!empty($starttime) && empty($end)) {
			$starttime = strtotime($starttime);
			$sqlw .= " and addtime >= $starttime ";
		} elseif (empty($starttime) && !empty($end)) {
			$end = strtotime($end)+86400;
			$sqlw .= " and addtime <= $end ";
		}
        $sql = "SELECT count(1) as number FROM `f_log` " . $sqlw;

        $number = $this->db->query($sql)->row()->number;
        return ceil($number / 10) == 0 ? 1 : ceil($number / 10);
    }

    public function getlabelAll($pg,$username,$starttime,$end)
    {
		$sqlw = " where 1=1 ";
		if (!empty($username)) {
			$sqlw .= " and ( username like '%" . $username . "%' ) ";
		}
		if (!empty($starttime) && !empty($end)) {
			$starttime = strtotime($starttime);
			$end = strtotime($end)+86400;
			$sqlw .= " and addtime >= $starttime and addtime <= $end ";
		} elseif (!empty($starttime) && empty($end)) {
			$starttime = strtotime($starttime);
			$sqlw .= " and addtime >= $starttime ";
		} elseif (empty($starttime) && !empty($end)) {
			$end = strtotime($end)+86400;
			$sqlw .= " and addtime <= $end ";
		}
        $start = ($pg - 1) * 10;
        $stop = 10;

        $sql = "SELECT * FROM `f_log` " . $sqlw . " order by addtime desc LIMIT $start, $stop";
        return $this->db->query($sql)->result_array();
    }

    public function log_save($username,$log_news)
    {
		$username = $this->db->escape($username);
		$log_news = $this->db->escape($log_news);
		$addtime = time();
        $sql = "INSERT INTO `f_log` (username,log_news,addtime) VALUES ($username,$log_news,$addtime)";
        return $this->db->query($sql);
    }
    //标签delete
    public function label_delete($id)
    {
        $id = $this->db->escape($id);
        $sql = "DELETE FROM label WHERE lid = $id";
        return $this->db->query($sql);
    }
    //标签byid
    public function getlabelById($id)
    {
        $id = $this->db->escape($id);
        $sql = "SELECT * FROM `label` where lid=$id ";
        return $this->db->query($sql)->row_array();
    }
    //标签byname
    public function getlabelByname($lname)
    {
        $lname = $this->db->escape($lname);
        $sql = "SELECT * FROM `label` where lname=$lname ";
        return $this->db->query($sql)->row_array();
    }
    //标签byname id
    public function getlabelById2($lname, $lid)
    {
        $lname = $this->db->escape($lname);
        $lid = $this->db->escape($lid);
        $sql = "SELECT * FROM `label` where lname=$lname and lid != $lid";
        return $this->db->query($sql)->row_array();
    }
    //标签save_edit
    public function label_save_edit($lid, $lname)
    {
        $lid = $this->db->escape($lid);
        $lname = $this->db->escape($lname);

        $sql = "UPDATE `label` SET lname=$lname WHERE lid = $lid";
        return $this->db->query($sql);
    }
}
