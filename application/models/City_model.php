<?php


class City_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->date = time();
        $this->load->database();
    }
	public function getmemberById($id)
	{
		$id = $this->db->escape($id);
		$sql = "SELECT * FROM `admin_user` where did=$id ";
		return $this->db->query($sql)->row_array();
	}
    public function getcityAllPage($dname)
    {
        $sqlw = " where 1=1 ";
        if (!empty($dname)) {
            $sqlw .= " and ( dname like '%" . $dname . "%' ) ";
        }
        $sql = "SELECT count(1) as number FROM `f_department` " . $sqlw;

        $number = $this->db->query($sql)->row()->number;
        return ceil($number / 10) == 0 ? 1 : ceil($number / 10);
    }

    public function getcityAll($pg,$dname)
    {
		$sqlw = " where 1=1 ";
		if (!empty($dname)) {
			$sqlw .= " and ( dname like '%" . $dname . "%' ) ";
		}
        $start = ($pg - 1) * 10;
        $stop = 10;

        $sql = "SELECT * FROM `f_department` " . $sqlw . " order by addtime desc LIMIT $start, $stop";
        return $this->db->query($sql)->result_array();
    }

    public function city_save($dname,$add_time)
    {
		$dname = $this->db->escape($dname);
        $add_time = $this->db->escape($add_time);

        $sql = "INSERT INTO `f_department` (dname,addtime) VALUES ($dname,$add_time)";
        return $this->db->query($sql);
    }

    public function city_delete($id)
    {
        $id = $this->db->escape($id);
        $sql = "DELETE FROM f_department WHERE did = $id";
        return $this->db->query($sql);
    }

    public function getcityById($id)
    {
        $id = $this->db->escape($id);
        $sql = "SELECT * FROM `f_department` where did=$id ";
        return $this->db->query($sql)->row_array();
    }

    public function getcityByname($dname)
    {
		$dname = $this->db->escape($dname);
        $sql = "SELECT * FROM `f_department` where dname=$dname ";
        return $this->db->query($sql)->row_array();
    }

    public function getcityById2($dname, $did)
    {
		$dname = $this->db->escape($dname);
		$did = $this->db->escape($did);
        $sql = "SELECT * FROM `f_department` where dname=$dname and did != $did";
        return $this->db->query($sql)->row_array();
    }

    public function city_save_edit($did, $dname)
    {
		$did = $this->db->escape($did);
		$dname = $this->db->escape($dname);
        $sql = "UPDATE `f_department` SET dname=$dname WHERE did = $did";
        return $this->db->query($sql);
    }




	public function getcityAllPage1($fname)
	{
		$sqlw = " where 1=1 ";
		if (!empty($fname)) {
			$sqlw .= " and ( fname like '%" . $fname . "%' ) ";
		}
		$sql = "SELECT count(1) as number FROM `f_file_type` " . $sqlw;

		$number = $this->db->query($sql)->row()->number;
		return ceil($number / 10) == 0 ? 1 : ceil($number / 10);
	}

	public function getcityAll1($pg,$fname)
	{
		$sqlw = " where 1=1 ";
		if (!empty($fname)) {
			$sqlw .= " and ( fname like '%" . $fname . "%' ) ";
		}
		$start = ($pg - 1) * 10;
		$stop = 10;

		$sql = "SELECT * FROM `f_file_type` " . $sqlw . " order by addtime desc LIMIT $start, $stop";
		return $this->db->query($sql)->result_array();
	}

	public function city_save1($fname,$add_time)
	{
		$fname = $this->db->escape($fname);
		$add_time = $this->db->escape($add_time);

		$sql = "INSERT INTO `f_file_type` (fname,addtime) VALUES ($fname,$add_time)";
		return $this->db->query($sql);
	}

	public function city_delete1($id)
	{
		$id = $this->db->escape($id);
		$sql = "DELETE FROM f_file_type WHERE ftid = $id";
		return $this->db->query($sql);
	}

	public function getcityById1($id)
	{
		$id = $this->db->escape($id);
		$sql = "SELECT * FROM `f_file_type` where ftid=$id ";
		return $this->db->query($sql)->row_array();
	}

	public function getcityByname1($fname)
	{
		$fname = $this->db->escape($fname);
		$sql = "SELECT * FROM `f_file_type` where fname=$fname ";
		return $this->db->query($sql)->row_array();
	}

	public function getcityById21($fname, $ftid)
	{
		$fname = $this->db->escape($fname);
		$ftid = $this->db->escape($ftid);
		$sql = "SELECT * FROM `f_file_type` where fname=$fname and ftid != $ftid";
		return $this->db->query($sql)->row_array();
	}

	public function city_save_edit1($ftid, $fname)
	{
		$ftid = $this->db->escape($ftid);
		$fname = $this->db->escape($fname);
		$sql = "UPDATE `f_file_type` SET fname=$fname WHERE ftid = $ftid";
		return $this->db->query($sql);
	}
}
