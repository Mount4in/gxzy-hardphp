<?php 
class userController extends BaseController
{
    function actionIndex()
    {
        $this->{'jump'}('/user/login');
    }
    function actionLoginOut()
    {
        $v0 = [];
        session_destroy();
        $this->{'jump'}('/user/login');
    }
    function actionLogin()
    {
        if ($_POST) {
            $v4 = arg('username');
            $v7 = arg('password');
            $v12 = arg('HTTP_X_FORWARDED_FOR', arg('REMOTE_ADDR'));
            $v15 = arg('HTTP_USER_AGENT');
            if (empty($v4) || empty($v7)) {
                echo '<script>alert(\'Username or password is empty.\')</script>';
            } else {
                $v16 = new User();
                $v17 = [$v7];
                $v7 = md5($v7);
                $v19 = $v16->{'query'}("{'SELECT * FROM `'}{$v16->{'table_name'}}{'` where `username`=\\''}{$v4}{'\\' AND `password`=\\''}{$v7}{'\\''}");
                if (empty($v19) || $v19[0]['password'] !== $v7) {
                    echo '<script>alert(\'Username or password is error.\')</script>';
                } else {
                    $v20 = [];
                    $v22 = new Session($v19[0]['id'], time(), $v12, $v15);
                    $v23 = [$v22];
                    $_SESSION['data'] = serialize($v22);
                    $_SESSION['username'] = $v4;
                    $this->{'jump'}('/main/index');
                }
            }
        }
    }
    function actionRegister()
    {
        if ($_POST) {
            $v4 = arg('username');
            $v7 = arg('password');
            if (empty($v4) || empty($v7)) {
                echo '<script>alert(\'Username or password is error.\')</script>';
            } else {
                $v29 = [$v7];
                $v7 = md5($v7);
                $v16 = new User();
                $v19 = $v16->{'query'}("{'SELECT * FROM `'}{$v16->{'table_name'}}{'` WHERE `username` =\\''}{$v4}{'\\''}");
                if (!empty($v19)) {
                    echo '<script>alert(\'Username is registered!.\')</script>';
                } else {
                    $v19 = $v16->{'create'}(['username' => $v4, 'password' => $v7, 'picture' => '/img/pic.jpg']);
                    if (!$v19) {
                        echo '<script>alert(\'something error. register fiaied!\')</script>';
                    } else {
                        $this->{'jump'}('/user/login');
                    }
                }
            }
        }
    }
}