<?php


class Images
{
    public function base64ToJpeg($imgsrc)
    {
        $imgsrc = explode(',', $imgsrc)[1];
        $img = base64_decode($imgsrc);  // 解码
        $fname = microtime();
        if (file_exists('static/private/img/'. $fname. '.jpg')) {
            $fname = $fname. '_'. rand(0, 20);
        }
        file_put_contents('static/private/img/'. $fname. '.jpg', $img);
        return STA. '/private/img/'. $fname. '.jpg';
    }
}