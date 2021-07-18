<?php


class swapFile
{
    public $fileInfo =array();

    public function init()
    {
        $_SESSION['files'] = array();
    }

    public function push()
    {
        $dir = SCDIR.'/static/private/img/';
        $src = STA.'/private/img/';
        $_swap = time();
        $fileName = $_swap.substr(strrchr($_FILES['file']['name'], '.'), 1);
        move_uploaded_file($_FILES['file']["tmp_name"], $dir.$fileName);
        if (file_exists($dir.$fileName)) {
            return $src.$fileName;
        }
    }

    public function pop()
    {
        array_pop($this->fileInfo);
    }

    public function finish()
    {
        unset($this->fileInfo);
        unset($_SESSION['files']);
    }
}