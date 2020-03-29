<?php 
class MainController extends BaseController
{
    function actionIndex()
    {
        if (isset($_SESSION['data'])) {
            $v0 = [$_SESSION['data'], ['allowed_classes' => ['Session']]];
            $v2 = unserialize($_SESSION['data'], ['allowed_classes' => ['Session']]);
            $v7 = arg('HTTP_X_FORWARDED_FOR', arg('REMOTE_ADDR'));
            $v10 = arg('HTTP_USER_AGENT');
            $v11 = [];
            $this->{'now'} = $v2::getTime(time());
            if ($v2->{'isAccountSec'}($v7, $v10)) {
                $v13 = $v2->{'getUserInfo'}();
                $this->{'username'} = $_SESSION['username'];
                $this->{'loginTime'} = $v13[1];
                $v14 = new User();
                $v15 = $v14->{'query'}("{'SELECT picture FROM `'}{$v14->{'table_name'}}{'` where `id`=\\''}{$v2}{'\\''}");
                if (!empty($v15)) {
                    $this->{'picSrc'} = $v15[0]['picture'];
                } else {
                    $this->{'picSrc'} = '/img/pic.jpg';
                }
            } else {
                echo '<script>alert(\'your cookie my be stealed by hacker!\');</script>';
                $v16 = [];
                session_destroy();
                $this->{'jump'}('/user/login');
            }
        } else {
            $this->{'jump'}('/user/login');
            return;
        }
    }
    public function actionMessage()
    {
        if (!isset($_SESSION['data'])) {
            $this->{'jump'}('/user/login');
            return;
        }
        $v18 = [$_SESSION['data'], ['allowed_classes' => ['Session']]];
        $v2 = unserialize($_SESSION['data'], ['allowed_classes' => ['Session']]);
        $v7 = arg('HTTP_X_FORWARDED_FOR', arg('REMOTE_ADDR'));
        $v10 = arg('HTTP_USER_AGENT');
        $v26 = [];
        $this->{'now'} = $v2::getTime(time());
        if (!$v2->{'isAccountSec'}($v7, $v10)) {
            echo '<script>alert(\'your cookie my be stealed by hacker!\');</script>';
            $v28 = [];
            session_destroy();
            $this->{'jump'}('/user/login');
            return;
        }
        $v30 = array();
        $v31 = new Message();
        $v14 = new User();
        $v15 = $v31->{'query'}("{'SELECT * FROM `'}{$v31->{'table_name'}}{'` order by `id` desc  limit 0,100'}");
        foreach ($v15 as $v32 => $v33) {
            $v34 = $v33['userid'];
            $v13 = $v14->{'query'}("{'SELECT * FROM `'}{$v14->{'table_name'}}{'` WHERE `id`=\\''}{$v34}{'\\''}");
            if (!empty($v13)) {
                $v35 = $v13[0]['username'];
                $v36 = $v13[0]['picture'];
                array_push($v30, array('username' => $v35, 'message' => $v33['content']));
            }
        }
        $this->{'messages'} = $v30;
    }
    public function actionPost()
    {
        if (!isset($_SESSION['data'])) {
            $this->{'jump'}('/user/login');
            return;
        }
        $v37 = [$_SESSION['data'], ['allowed_classes' => ['Session']]];
        $v2 = unserialize($_SESSION['data'], ['allowed_classes' => ['Session']]);
        $v7 = arg('HTTP_X_FORWARDED_FOR', arg('REMOTE_ADDR'));
        $v10 = arg('HTTP_USER_AGENT');
        $v45 = [];
        $this->{'now'} = $v2::getTime(time());
        if (!$v2->{'isAccountSec'}($v7, $v10)) {
            echo '<script>alert(\'your cookie my be stealed by hacker!\');</script>';
            $v47 = [];
            session_destroy();
            $this->{'jump'}('/user/login');
            return;
        }
        if ($_POST) {
            $v51 = arg('msg');
            $v31 = new Message();
            $v15 = $v31->{'create'}(['userid' => (string) $v2, 'content' => $v51]);
            $this->{'jump'}('/main/Message');
        }
    }
}