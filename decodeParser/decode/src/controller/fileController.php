<?php 
class FileController extends BaseController
{
    function actionIndex()
    {
        $this->{'jump'}('/main/index');
    }
    public function actionUpload()
    {
        if (!isset($_SESSION['data'])) {
            $this->{'jump'}('/user/login');
            return;
        }
        $v0 = [$_SESSION['data'], ['allowed_classes' => ['Session']]];
        $v2 = unserialize($_SESSION['data'], ['allowed_classes' => ['Session']]);
        $v7 = arg('HTTP_X_FORWARDED_FOR', arg('REMOTE_ADDR'));
        $v10 = arg('HTTP_USER_AGENT');
        if (!$v2->{'isAccountSec'}($v7, $v10)) {
            echo '<script>alert(\'your cookie my be stealed by hacker!\');</script>';
            $v11 = [];
            session_destroy();
            $this->{'jump'}('/user/login');
            return;
        }
        if (empty($_FILES['upfile'])) {
            echo '<script>alert(\\\\\"Upload file empty!\\\\\")</script>';
            $this->{'jump'}('/main/index');
            return;
        }
        $v13 = new Upload($_FILES['upfile'], $this);
        $v13->{'upload'}();
    }
}