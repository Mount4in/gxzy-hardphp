<?php
use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;
use PhpParser\NodeTraverser;

class RemoveCall extends NodeVisitorAbstract
{
    /**
     * @var string
     */
    private $_funcName = [];
    private $_arg = [];
    /**
     * @var array
     */
    private $_params = [];
    private $_count= [];

    public function enterNode(Node $node)
    {
        if ($node instanceof Node\Stmt\Expression && //获取函数名
            $node->expr instanceof Node\Expr\Assign &&
            $node->expr->var instanceof Node\Expr\Variable &&
            $node->expr->expr instanceof Node\Expr\Array_ &&
            count($node->expr->expr->items) > 0 &&
            $node->expr->expr->items[0] instanceof Node\Expr\ArrayItem &&
            $node->expr->expr->items[0]->value instanceof Node\Scalar\String_ &&
            (function_exists($node->expr->expr->items[0]->value->value) || 
            $node->expr->expr->items[0]->value->value == 'err' ||
            $node->expr->expr->items[0]->value->value == 'arg' ||
            $node->expr->expr->items[0]->value->value == 'is_available_classname' ||
            $node->expr->expr->items[0]->value->value == '_err_router' ||
            $node->expr->expr->items[0]->value->value == 'http_build_query' ||
            $node->expr->expr->items[0]->value->value == 'substr_count'  )
        ) {
            $this->_funcName[$node->expr->var->name] = $node->expr->expr->items[0]->value->value;
            $this->_arg[$node->expr->var->name] = $node->expr->expr->items[1]->value->name;
            //return NodeTraverser::REMOVE_NODE;
        }
        elseif (
            $node instanceof Node\Stmt\Expression &&//huoq
            $node->expr instanceof Node\Expr\Assign &&
            $node->expr->var instanceof Node\Expr\Variable &&
            $node->expr->expr instanceof Node\Expr\Array_ &&
            count($node->expr->expr->items) >= 0 )
        {
            $count=count($node->expr->expr->items);
            //print($count);
            $this->_count[$node->expr->var->name] = $count;
            $i=0;
            while($count)
            {
                $this->_params[$node->expr->var->name][]=$node->expr->expr->items[$i]->value;
                $i+=1;
                $count-=1;
            }
            //var_dump($this->_params);
            //return NodeTraverser::REMOVE_NODE;
        }
        return null;
    }

    public function leaveNode(Node $node)
    {
        if ($node instanceof Node\Expr\BinaryOp\Plus && //去掉 -2 + (4 - 0) + (-4 + (11 - 3) - (1 + (6 - 3)))
            $node->right instanceof Node\Expr\BinaryOp\Minus &&
            $node->right->right instanceof Node\Scalar\LNumber
         ) {
            //print_r($node->args[0]->value->left->value);
            //print_r($node->args[0]->value->right->left->value);
            //print_r($node->args[0]->value->right->right->value);
            if($node->left instanceof Node\Expr\UnaryMinus){
                $value1 = 0 - $node->left->expr->value;
            }
            else
                $value1 = $node->left->value;
            if($node->right->left instanceof Node\Expr\UnaryMinus){
                $value2 = 0 - $node->right->left->expr->value;
            }
            else
                $value2 = $node->right->left->value;
            $value = $value1 + ($value2 - $node->right->right->value);
            return new Node\Scalar\LNumber($value);
        }

        if ($node instanceof Node\Expr\FuncCall && 
            $node->name instanceof Node\Name &&  
            is_string($node->name->parts[0]) &&
            $node->name->parts[0] == 'call_user_func_array' &&
            $node->args[0] instanceof Node\Arg &&
            $node->args[0]->value instanceof Node\Scalar\String_ &&
            $node->args[0]->value->value == 'call_user_func_array')
            {
                var_dump($this->_funcName[$node->args[1]->value->name]);
                //var_dump($this->_params);
                switch($this->_count[$this->_arg[$node->args[1]->value->name]]) {
                    case 0:
                        return new Node\Expr\FuncCall(new Node\Name($this->_funcName[$node->args[1]->value->name]));
                        break;
                    case 1:
                        return new Node\Expr\FuncCall(new Node\Name($this->_funcName[$node->args[1]->value->name]),
                        [new Node\Arg($this->_params[$this->_arg[$node->args[1]->value->name]][0])]);
                        break;
                    case 2:
                        return new Node\Expr\FuncCall(new Node\Name($this->_funcName[$node->args[1]->value->name]),
                        [new Node\Arg($this->_params[$this->_arg[$node->args[1]->value->name]][0]), new Node\Arg($this->_params[$this->_arg[$node->args[1]->value->name]][1])]);
                        break;
                    case 3:
                        return new Node\Expr\FuncCall(new Node\Name($this->_funcName[$node->args[1]->value->name]),
                        [new Node\Arg($this->_params[$this->_arg[$node->args[1]->value->name]][0]), new Node\Arg($this->_params[$this->_arg[$node->args[1]->value->name]][1])
                        , new Node\Arg($this->_params[$this->_arg[$node->args[1]->value->name]][2])]);
                        break;  
                    case 4:
                        return new Node\Expr\FuncCall(new Node\Name($this->_funcName[$node->args[1]->value->name]),
                        [new Node\Arg($this->_params[$this->_arg[$node->args[1]->value->name]][0]), new Node\Arg($this->_params[$this->_arg[$node->args[1]->value->name]][1])
                        , new Node\Arg($this->_params[$this->_arg[$node->args[1]->value->name]][2]), new Node\Arg($this->_params[$this->_arg[$node->args[1]->value->name]][3])]);
                         break;   
                }
                //$this->_count = 0;
                //$this->_funcName = array();
                //$this->_args = array();
                //$this->_params = array();
                //var_dump($this->_params);
                
            }
            
            if ($node instanceof Node\Stmt\Expression &&
            $node->expr instanceof Node\Expr\Assign &&
            $node->expr->var instanceof Node\Expr\Variable &&
            $node->expr->expr instanceof Node\Expr\Array_ &&
            count($node->expr->expr->items) > 0 && 
            $node->expr->expr->items[0] instanceof Node\Expr\ArrayItem &&
            $node->expr->expr->items[0]->value instanceof Node\Expr\FuncCall &&
            $node->expr->expr->items[0]->value->name instanceof Node\Name){
                $count=count($node->expr->expr->items);
                //var_dump($node->expr->expr->items);
                $this->_count[$node->expr->var->name] = $count;
                $i=0;
                while($count)
                {
                    $this->_params[$node->expr->var->name][$i]=$node->expr->expr->items[$i]->value;
                    $i+=1;
                    $count-=1;
                }
        }  
        if ($node instanceof Node\Stmt\Expression &&
            $node->expr instanceof Node\Expr\Assign &&
            $node->expr->var instanceof Node\Expr\Variable &&
            $node->expr->expr instanceof Node\Expr\Array_ &&
            count($node->expr->expr->items) > 1 && 
            $node->expr->expr->items[1] instanceof Node\Expr\ArrayItem &&
            $node->expr->expr->items[1]->value instanceof Node\Expr\FuncCall &&
            $node->expr->expr->items[1]->value->name instanceof Node\Name){
                $count=count($node->expr->expr->items);
                //var_dump($node->expr->expr->items);
                $this->_count[$node->expr->var->name] = $count;
                $i=0;
                while($count)
                {
                    $this->_params[$node->expr->var->name][$i]=$node->expr->expr->items[$i]->value;
                    $i+=1;
                    $count-=1;
                }
        }
        if ($node instanceof Node\Stmt\Expression &&
            $node->expr instanceof Node\Expr\Assign &&
            $node->expr->var instanceof Node\Expr\Variable &&
            $node->expr->expr instanceof Node\Expr\Array_ &&
            count($node->expr->expr->items) > 2 && 
            $node->expr->expr->items[2] instanceof Node\Expr\ArrayItem &&
            $node->expr->expr->items[2]->value instanceof Node\Expr\FuncCall &&
            $node->expr->expr->items[2]->value->name instanceof Node\Name){
                $count=count($node->expr->expr->items);
                //var_dump($node->expr->expr->items);
                $this->_count[$node->expr->var->name] = $count;
                $i=0;
                while($count)
                {
                    $this->_params[$node->expr->var->name][$i]=$node->expr->expr->items[$i]->value;
                    $i+=1;
                    $count-=1;
                }
        }  
        if ($node instanceof Node\Stmt\Expression &&
            $node->expr instanceof Node\Expr\Assign &&
            $node->expr->var instanceof Node\Expr\Variable &&
            $node->expr->expr instanceof Node\Expr\Array_ &&
            count($node->expr->expr->items) > 3 && 
            $node->expr->expr->items[3] instanceof Node\Expr\ArrayItem &&
            $node->expr->expr->items[3]->value instanceof Node\Expr\FuncCall &&
            $node->expr->expr->items[3]->value->name instanceof Node\Name){
                $count=count($node->expr->expr->items);
                //var_dump($node->expr->expr->items);
                $this->_count[$node->expr->var->name] = $count;
                $i=0;
                while($count)
                {
                    $this->_params[$node->expr->var->name][$i]=$node->expr->expr->items[$i]->value;
                    $i+=1;
                    $count-=1;
                }
        } 
        
         
        if ($node instanceof Node\Stmt\Expression&& //删除无关语句
            $node->expr instanceof Node\Expr\Assign &&
            $node->expr->var instanceof Node\Expr\Variable &&
            $node->expr->expr instanceof Node\Expr\Array_ &&
            $node->expr->expr->items[0]->value instanceof Node\Scalar\String_ )
            {
                return NodeTraverser::REMOVE_NODE;
            }
            return null;
    }

}