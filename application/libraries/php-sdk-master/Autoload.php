<?php
use Qiniu\Auth;
use Qiniu\Storage\BucketManager;
use Qiniu\Storage\UploadManager;


class Autoload {
    public $akey = '34G2D4N5i4P_8KKJV2yacR0xDeZ-2XRRccmigVQW'; // gE6p8OeM08gS4k95ijg4G8gihptWFVm5uwIpjKml-_xaH2
    public $skey = 'kDnLlN_C32_SM2dZLfJ5jp8IODCXKyquAlFbbImW'; // O51qHpVS9yeeEzDViY28q_Dw3Q_b_f69Ql-hJK8G
    public function __construct()
    {
//        spl_autoload_register('classLoader');
//        require_once  __DIR__ . '/src/Qiniu/functions.php';

        require_once  __DIR__ . '/src/Qiniu/Auth.php';
		require_once  __DIR__ . '/src/Qiniu/Storage/UploadManager.php';
        require_once  __DIR__ . '/src/Qiniu/Config.php';
        require_once  __DIR__ . '/src/Qiniu/Etag.php';
        require_once  __DIR__ . '/src/Qiniu/Zone.php';
        require_once  __DIR__ . '/src/Qiniu/Http/Client.php';
        require_once  __DIR__ . '/src/Qiniu/Http/Error.php';
        require_once  __DIR__ . '/src/Qiniu/Http/Request.php';
        require_once  __DIR__ . '/src/Qiniu/Http/Response.php';
        require_once  __DIR__ . '/src/Qiniu/functions.php';
		require_once  __DIR__ . '/src/Qiniu/functions.php';
    }

    public function get_token()
    {
        $accessKey = '34G2D4N5i4P_8KKJV2yacR0xDeZ-2XRRccmigVQW';
        $secretKey = 'kDnLlN_C32_SM2dZLfJ5jp8IODCXKyquAlFbbImW';
        $auth = new Auth($accessKey, $secretKey);
        $bucket = 'dmsys';
        return $auth->uploadToken($bucket);
    }

    public function drop_once_oss($key)
    {
        $accessKey = '34G2D4N5i4P_8KKJV2yacR0xDeZ-2XRRccmigVQW';
        $secretKey = 'kDnLlN_C32_SM2dZLfJ5jp8IODCXKyquAlFbbImW';
        $auth = new Auth($accessKey, $secretKey);
        $bucket = 'dmsys';
        $bucketMgr = new BucketManager($auth);
        $err = $bucketMgr->delete($bucket, $key);
        if ($err !== null)
            return true;
        return false;
    }
	public function putFileNew($token, $fileName, $filePath)
	{
		$uploadMgr = new UploadManager();
		return $uploadMgr->putFile($token, $fileName, $filePath);
	}

    public function authorization($url, $body=null, $contentType=null)
    {
        $accessKey = '34G2D4N5i4P_8KKJV2yacR0xDeZ-2XRRccmigVQW';
        $secretKey = 'kDnLlN_C32_SM2dZLfJ5jp8IODCXKyquAlFbbImW';
        $auth = new Auth($accessKey, $secretKey);
        return $auth->authorization($url, $body, $contentType);
    }
}

//function classLoader($class)
//{
//    $path = str_replace('\\', DIRECTORY_SEPARATOR, $class);
//    $file = __DIR__ . '/src/' . $path . '.php';
//
//    if (file_exists($file)) {
//        require_once $file;
//    }
//}

