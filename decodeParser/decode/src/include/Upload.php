<?php 
class Upload
{
    protected $upfile;
    protected $controller;
    protected $userId;
    protected $user;
    protected $savePath = "img" . DS . "upload" . DS;
    function __construct($arg0, $arg1)
    {
        $this->{'upfile'} = $arg0;
        $this->{'controller'} = $arg1;
        $this->{'user'} = new User();
        $v0 = [$_SESSION['data'], ['allowed_classes' => ['Session']]];
        $v2 = unserialize($_SESSION['data'], ['allowed_classes' => ['Session']]);
        $this->{'userId'} = $v2->{'getUserInfo'}()[0];
    }
    public function write($arg2, $arg3)
    {
        if ($this->{'waf'}($arg2)) {
            $v3 = [$arg3, $arg2];
            return file_put_contents($arg3, $arg2) !== \null;
        }
        return \false;
    }
    public function waf($arg2)
    {
        $v5 = [$arg2, '<?php'];
        return strpos($arg2, '<?php') === \false;
    }
    public function upload()
    {
        if ($this->{'upfile'}['error'] != UPLOAD_ERR_OK) {
            echo '<script>alert(\\\\\"upload file error!\\\\\")</script>';
            $this->{'controller'}->{'jump'}('/main/index');
            return;
        }
        if ($this->{'upfile'}['size'] > 102400) {
            echo '<script>alert(\\\\\"upload file too big!\\\\\")</script>';
            $this->{'controller'}->{'jump'}('/main/index');
            return;
        }
        $v7 = $this->{'upfile'}['name'];
        $v8 = [$v7];
        $v9 = [$v7];
        $v12 = isset(pathinfo($v7)['extension']) ? pathinfo($v7)['extension'] : 'png';
        $v13 = [$v12, ['jpg', 'png', 'bmp']];
        if (!in_array($v12, ['jpg', 'png', 'bmp'])) {
            $v12 = 'png';
        }
        $v15 = [$v12];
        $v12 = addslashes($v12);
        $v17 = $this->{'randomStr'}() . '.' . $v12;
        $this->{'save'}($this->{'upfile'}['tmp_name'], $v17);
    }
    public function save($arg4, $v17)
    {
        $v18 = APP_DIR . DS . $this->{'savePath'} . $v17;
        $v19 = [$arg4];
        $arg2 = file_get_contents($arg4);
        if ($this->{'write'}($arg2, $v18)) {
            $v21 = DS . $this->{'savePath'} . $v17;
            $v22 = $this->{'user'}->{'execute'}("{'UPDATE `'}{$this->{'user'}->{'table_name'}}{'` set `picture`=\\''}{$v21}{'\\' where `id`=\\''}{$this->{'userId'}}{'\\''}");
            if ($v22) {
                echo '<script>alert(\\\\\"Upload file success!\\\\\")</script>';
            } else {
                echo '<script>alert(\\\\\"Upload file error!\\\\\")</script>';
            }
            $this->{'controller'}->{'jump'}('/main/index');
            return;
        } else {
            echo '<script>alert(\\\\\"Upload file Error!\\\\\")</script>';
            $this->{'controller'}->{'jump'}('/main/index');
            return;
        }
    }
    private function randomStr($arg5 = 32)
    {
        $v23 = 'abcdefghijklmnopqrstuvwxyz0123456789';
        $v24 = '';
        for ($v25 = 0; $v25 < $arg5; $v25++) {
            $v26 = [$v23];
            $v28 = [0, strlen($v23) - 1];
            $v24 .= $v23[mt_rand(0 + (0 - 0), strlen($v23) - 1)];
        }
        return $v24;
    }
    public function __destruct()
    {
        $v30 = [$this->{'upfile'}['tmp_name']];
        if (is_file($this->{'upfile'}['tmp_name'])) {
            $v32 = [$this->{'upfile'}['tmp_name']];
            unlink($this->{'upfile'}['tmp_name']);
        }
    }
}