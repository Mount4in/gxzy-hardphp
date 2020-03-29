<?php
error_reporting(0);
#var_dump(gettype(get_defined_functions()));
#var_dump(count(get_defined_functions()[internal]));
$i_need_func=array();
$j=0;
for ($i=0; $i < count(get_defined_functions()[internal]) ; $i++) {
    if (!preg_match('/et|na|nt|strlen|info|path|rand|dec|bin|hex|oct|pi|exp|log|xdebug|prvd|_/i', get_defined_functions()[internal][$i])) {
        $i_need_func[$j]=get_defined_functions()[internal][$i];
        $j++;
    }
}
try {
    for ($i=0; $i < count($i_need_func); $i++) {
        if($i_need_func[$i]=="mhash")
            continue;
        if(!is_null($i_need_func[$i]())){
            echo $i_need_func[$i];
            var_dump($i_need_func[$i]());
        }
    }
} catch (\Throwable $th) {
}
