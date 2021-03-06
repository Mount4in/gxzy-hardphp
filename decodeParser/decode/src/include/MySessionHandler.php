<?php 
class MySessionHandler implements SessionHandlerInterface
{
    private $savepath;
    private $dbsession;
    public function open($arg0, $arg1)
    {
        $this->{'dbsession'} = new DbSession();
        $this->{'gc'}(ini_get('session.gc_maxlifetime'));
        return \true;
    }
    public function close()
    {
        return \true;
    }
    public function read($arg2)
    {
        $v2 = $this->{'dbsession'}->{'query'}("{'SELECT * FROM `'}{$this->{'dbsession'}->{'table_name'}}{'` where `sessionid` = \\''}{$arg2}{'\\''}");
        if (empty($v2)) {
            $v3 = [\null];
            return serialize(\null);
        } else {
            return (string) @$v2[0]['data'];
        }
    }
    public function write($arg2, $arg3)
    {
        $arg3 = str_replace(' ', '\\0', $arg3);
        $v7 = [];
        $v9 = time();
        $v2 = $this->{'dbsession'}->{'query'}("{'SELECT * FROM `'}{$this->{'dbsession'}->{'table_name'}}{'` where `sessionid` = \\''}{$arg2}{'\\' '}");
        if ($v2) {
            $this->{'dbsession'}->{'execute'}("{'UPDATE `'}{$this->{'dbsession'}->{'table_name'}}{'` SET `data` = \\''}{$arg3}{'\\',`lastvisit` = \\''}{$v9}{'\\' where `sessionid` = \\''}{$arg2}{'\\''}");
        } else {
            $v2 = $this->{'dbsession'}->{'create'}(['data' => $arg3, 'sessionid' => $arg2, 'lastvisit' => $v9]);
        }
        return \true;
    }
    public function destroy($arg2)
    {
        $v2 = $this->{'dbsession'}->{'execute'}("{'DELETE FROM `'}{$this->{'dbsession'}->{'table_name'}}{'` where `sessionid`=\\''}{$arg2}{'\\''}");
        return $v2;
    }
    public function gc($arg4)
    {
        $v10 = [];
        $v12 = time();
        $v2 = $this->{'dbsession'}->{'execute'}("{'DELETE FROM `'}{$this->{'dbsession'}->{'table_name'}}{'` where ('}{$arg4}{'+`lastvisit`)<'}{$v12}");
        if ($v2) {
            return \true;
        }
        return \false;
    }
}