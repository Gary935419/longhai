<?php


class Goods_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->date = time();
        $this->load->database();
    }
    //商品count
    public function getgoodsAllPage($fname,$starttime,$end,$ftid=array())
    {
    	if (empty($ftid)){
			$sqlw = " where 1=1 ";
		}else{
			$str = implode(",", $ftid);
			$sqlw = " where 1=1 and ftid in ($str) ";
		}

        if (!empty($fname)) {
            $sqlw .= " and ( fname like '%" . $fname . "%' ) ";
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
        $sql = "SELECT count(1) as number FROM `f_file`" . $sqlw;

        $number = $this->db->query($sql)->row()->number;
        return ceil($number / 10) == 0 ? 1 : ceil($number / 10);
    }
    //商品list
    public function getgoodsAllNew($pg,$fname,$starttime,$end,$ftid=array())
    {
		if (empty($ftid)){
			$sqlw = " where 1=1 ";
		}else{
			$str = implode(",", $ftid);
			$sqlw = " where 1=1 and ftid in ($str) ";
		}
		if (!empty($fname)) {
			$sqlw .= " and ( fname like '%" . $fname . "%' ) ";
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

        $sql = "SELECT * FROM `f_file` " . $sqlw . " order by addtime desc LIMIT $start, $stop";
        return $this->db->query($sql)->result_array();
    }
	public function gettidlist($ftid = array())
	{
		if (empty($ftid)){
			$sqlw = " where 1=1 ";
		}else{
			$str = implode(",", $ftid);
			$sqlw = " where 1=1 and ftid in ($str) ";
		}
		$sql = "SELECT * FROM `f_file_type`" . $sqlw;
		return $this->db->query($sql)->result_array();
	}
	public function gettidlist123($rid)
	{
		$rid = $this->db->escape($rid);
		$sql = "SELECT ftid FROM `f_type` where rid = $rid ";
		return $this->db->query($sql)->result_array();
	}
	public function gettaskclassById($id)
	{
		$id = $this->db->escape($id);
		$sql = "SELECT * FROM `f_file_type` where ftid=$id ";
		return $this->db->query($sql)->row_array();
	}
    //商品图片list
    public function getgoodsimgsAllNew($gid)
    {
        $gid = $this->db->escape($gid);
        $sqlw = " where 1=1 and gid = $gid";
        $sql = "SELECT * FROM `gimgs` " . $sqlw;
        return $this->db->query($sql)->result_array();
    }
    public function getgoodsByname($fname)
    {
		$fname = $this->db->escape($fname);
        $sql = "SELECT * FROM `f_file` where fname=$fname ";
        return $this->db->query($sql)->row_array();
    }
	public function getgoodsByname123($username)
	{
		$username = $this->db->escape($username);
		$sql = "SELECT * FROM `admin_user` where username=$username ";
		return $this->db->query($sql)->row_array();
	}
    public function goods_save($fname,$ftid,$furl,$hash,$fsize,$addtime,$username,$is_open)
    {
		$fname = $this->db->escape($fname);
		$ftid = $this->db->escape($ftid);
		$furl = $this->db->escape($furl);
		$hash = $this->db->escape($hash);
		$fsize = $this->db->escape($fsize);
		$addtime = $this->db->escape($addtime);
		$username = $this->db->escape($username);
		$is_open = $this->db->escape($is_open);
        $sql = "INSERT INTO `f_file` (fname,ftid,furl,hash,fsize,addtime,username,is_open) VALUES ($fname,$ftid,$furl,$hash,$fsize,$addtime,$username,$is_open)";
        $this->db->query($sql);
        $gid=$this->db->insert_id();
        return $gid;
    }
    //商品bannersave
    public function goodsimg_save($gid,$imgs)
    {
        $gid = $this->db->escape($gid);
        $imgs = $this->db->escape($imgs);
        $sql = "INSERT INTO `gimgs` (gid,imgs) VALUES ($gid,$imgs)";
        return $this->db->query($sql);;
    }
    public function goods_delete($fid)
    {
		$fid = $this->db->escape($fid);
        $sql = "DELETE FROM f_file WHERE fid = $fid";
        return $this->db->query($sql);
    }

    public function getgoodsById($fid)
    {
		$fid = $this->db->escape($fid);
        $sql = "SELECT * FROM `f_file` where fid=$fid ";
        return $this->db->query($sql)->row_array();
    }

    public function getgoodsById2($fname,$fid)
    {
		$fname = $this->db->escape($fname);
		$fid = $this->db->escape($fid);
        $sql = "SELECT * FROM `f_file` where fname=$fname and fid!=$fid ";
        return $this->db->query($sql)->row_array();
    }
    public function goods_save_edit($fid,$fname,$ftid,$furl,$hash,$fsize,$addtime,$is_open)
    {
		$fid = $this->db->escape($fid);
		$fname = $this->db->escape($fname);
		$ftid = $this->db->escape($ftid);
		$furl = $this->db->escape($furl);
		$hash = $this->db->escape($hash);
		$fsize = $this->db->escape($fsize);
		$addtime = $this->db->escape($addtime);
		$is_open = $this->db->escape($is_open);
        $sql = "UPDATE `f_file` SET is_open=$is_open,fname=$fname,ftid=$ftid,furl=$furl,hash=$hash,fsize=$fsize,addtime=$addtime WHERE fid = $fid";
        return $this->db->query($sql);
    }
}
