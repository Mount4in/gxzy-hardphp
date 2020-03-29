<?php 
class LogDriver extends Model
{
    function __construct()
    {
        $this->{'log'} = new Log();
    }
    function save($arg0, $arg1)
    {
        $v0 = [$this->{'log'}->{'db'}, $arg1['data']];
        return $this->{'log'}->{'create'}(['time' => $arg0, 'type' => $arg1['type'], 'data' => mysqli_escape_string($this->{'log'}->{'db'}, $arg1['data'])]);
    }
}