<?php 
class Session
{
    protected $ip;
    protected $userAgent;
    protected $userId;
    protected $loginTime;
    public static $timeFormat = "H:i:s";
    function __construct($arg0, $arg1, $arg2 = "0.0.0.0", $arg3 = "")
    {
        $this->{'userId'} = $arg0;
        $this->{'ip'} = $arg2;
        $this->{'loginTime'} = $arg1;
        $v0 = [$arg3];
        $this->{'userAgent'} = md5($arg3);
    }
    public function getUserInfo()
    {
        $v2 = [$this->{'userId'}];
        $v3 = [self::$timeFormat, $this->{'loginTime'}];
        return array(intval($this->{'userId'}), date(self::$timeFormat, $this->{'loginTime'}));
    }
    public function isAccountSec($arg2 = "0.0.0.0", $arg3 = "")
    {
        $v6 = [$arg3];
        return $this->{'ip'} === $arg2 && $this->{'userAgent'} === md5($arg3);
    }
    public function __toString()
    {
        return $this->{'userId'};
    }
    static function getTime($arg4)
    {
        $v8 = [self::$timeFormat, $arg4];
        return date(self::$timeFormat, $arg4);
    }
}