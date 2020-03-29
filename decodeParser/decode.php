<?php 
class Model
{
    public $page;
    public $table_name;
    public $db;
    protected $sql = array();
    public function __construct($arg0 = null)
    {
        if ($arg0) {
            $this->{'table_name'} = $arg0;
        }
        $this->{'db'} = $this->{'dbInstance'}($GLOBALS['mysql'], 'master');
    }
    public function dumpSql()
    {
        return $this->{'sql'};
    }
    public function pager($arg1, $arg2 = 10, $arg3 = 10, $arg4)
    {
        $this->{'page'} = \null;
        if ($arg4 > $arg2) {
            $v0 = [$arg4 / $arg2];
            $v2 = ceil($arg4 / $arg2);
            $v3 = [$arg1, 1];
            $v5 = [max($arg1, -1 + (4 - 2))];
            $v7 = [intval(max($arg1, -1 + (4 - 2))), $arg4];
            $arg1 = min(intval(max($arg1, -1 + (4 - 2))), $arg4);
            $this->{'page'} = array('total_count' => $arg4, 'page_size' => $arg2, 'total_page' => $v2, 'first_page' => 1, 'prev_page' => 1 == $arg1 ? 1 : $arg1 - 1, 'next_page' => $arg1 == $v2 ? $v2 : $arg1 + 1, 'last_page' => $v2, 'current_page' => $arg1, 'all_pages' => array(), 'offset' => ($arg1 - 1) * $arg2, 'limit' => $arg2);
            $arg3 = (int) $arg3;
            if ($v2 <= $arg3) {
                $v9 = [1, $v2];
                $this->{'page'}['all_pages'] = range(-1 + (3 - 1), $v2);
            } elseif ($arg1 <= $arg3 / 2) {
                $v11 = [1, $arg3];
                $this->{'page'}['all_pages'] = range(-1 + (3 - 1), $arg3);
            } elseif ($arg1 <= $v2 - $arg3 / 2) {
                $v13 = $arg1 + (int) ($arg3 / 2);
                $v14 = [$v13 - $arg3 + 1, $v13];
                $this->{'page'}['all_pages'] = range($v13 - $arg3 + 1, $v13);
            } else {
                $v16 = [$v2 - $arg3 + 1, $v2];
                $this->{'page'}['all_pages'] = range($v2 - $arg3 + 1, $v2);
            }
        }
        return $this->{'page'};
    }
    public function query($arg5)
    {
        return $this->{'execute'}($arg5, \true);
    }
    public function execute($arg5, $arg6 = false)
    {
        $this->{'sql'}[] = $arg5;
        $v18 = $this->{'db'}->{'query'}($arg5);
        if (!$v18) {
            $v19 = $this->{'db'}->{'error'};
            $v20 = ['Database SQL: \\\"' . $arg5 . '\\\", ErrorInfo: ' . $v19];
            err('Database SQL: \\\"' . $arg5 . '\\\", ErrorInfo: ' . $v19);
        }
        if ($arg6) {
            return $v18->{'fetch_all'}(MYSQLI_ASSOC);
        } else {
            return $v18;
        }
    }
    public function dbInstance($arg7, $arg8, $arg9 = false)
    {
        if ($arg9 || empty($GLOBALS['mysql_instances'][$arg8])) {
            $v22 = new mysqli($arg7['MYSQL_HOST'], $arg7['MYSQL_USER'], $arg7['MYSQL_PASS'], $arg7['MYSQL_DB'], $arg7['MYSQL_PORT']);
            $v22->{'set_charset'}($arg7['MYSQL_CHARSET']);
            if ($v22->{'connect_error'}) {
                $v23 = ['Connect Error (' . $v22->{'connect_errno'} . ') ' . $v22->{'connect_error'}];
                err('Connect Error (' . $v22->{'connect_errno'} . ') ' . $v22->{'connect_error'});
            }
            $GLOBALS['mysql_instances'][$arg8] = $v22;
        }
        return $GLOBALS['mysql_instances'][$arg8];
    }
    public function lastInsertId()
    {
        return $this->{'dbInstance'}($GLOBALS['mysql'], 'master')->{'insert_id'};
    }
    public function create($arg10)
    {
        $v25 = [];
        $v26 = [];
        foreach ($arg10 as $v27 => $v28) {
            $v25[] = "{'`'}{$v27}{'`'}";
            $v26[] = "{'\\''}{$v28}{'\\''}";
        }
        $arg5 = sprintf('INSERT INTO `%s`( %s ) VALUES ( %s )', $this->{'table_name'}, implode(',', $v25), implode(',', $v26));
        return $this->{'execute'}($arg5);
    }
}