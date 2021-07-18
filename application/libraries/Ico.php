<?php


class Ico
{
    public $icoPath = "/ico";
    public function get()
    {
        $list = glob('static/ico/*');
        return $list;
    }
}