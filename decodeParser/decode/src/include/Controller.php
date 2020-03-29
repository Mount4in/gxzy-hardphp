<?php 
class Controller
{
    public $layout;
    public $_auto_display = true;
    protected $_v;
    protected $_data = array();
    public function init()
    {
    }
    public function __construct()
    {
        $this->{'init'}();
    }
    public function &__get($arg0)
    {
        return $this->{'_data'}[$arg0];
    }
    public function __set($arg0, $arg1)
    {
        $this->{'_data'}[$arg0] = $arg1;
    }
    public function display($arg2, $arg3 = false)
    {
        if (!$this->{'_v'}) {
            $v0 = isset($GLOBALS['view']['compile_dir']) ? $GLOBALS['view']['compile_dir'] : APP_DIR . DS . 'tmp';
            $this->{'_v'} = new View(APP_DIR . DS . 'view', $v0);
        }
        $v1 = [$this];
        $this->{'_v'}->{'assign'}(get_object_vars($this));
        $this->{'_v'}->{'assign'}($this->{'_data'});
        if ($this->{'layout'}) {
            $this->{'_v'}->{'assign'}('__template_file', $arg2);
            $arg2 = $this->{'layout'};
        }
        $this->{'_auto_display'} = \false;
        if ($arg3) {
            return $this->{'_v'}->{'render'}($arg2);
        } else {
            echo $this->{'_v'}->{'render'}($arg2);
            return \null;
        }
    }
}