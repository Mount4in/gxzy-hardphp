<?php 
class Logger
{
    protected $err = [];
    protected $handle;
    public function __construct()
    {
        $this->{'handle'} = new LogDriver();
    }
    public function add($arg0, $arg1 = null)
    {
        $v0 = [];
        $this->{'err'}[time()] = ['data' => $arg0, 'type' => $arg1];
    }
    public function __destruct()
    {
        $v2 = [$this->{'err'}];
        if (count($this->{'err'})) {
            foreach ($this->{'err'} as $v4 => $v5) {
                $this->{'handle'}->{'save'}($v4, $v5);
            }
        }
    }
}