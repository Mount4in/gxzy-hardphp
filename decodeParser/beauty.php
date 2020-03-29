<?php
use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;


class BeautifyNodeVisitor extends NodeVisitorAbstract
{
    public $varMap = [];
    public $argCount = 0;
    public $localVarCount = 0;

    public static function isUnreadable($string)
    {
        return 1 === preg_match('/\W/', $string);
    }

    public function generateArgName()
    {
        return 'arg' . ($this->argCount++);
    }

    public function generateLocalVarName()
    {
        return 'v' . ($this->localVarCount++);
    }
    
    public function enterNode(Node $node)
    {
        if ($node instanceof Node\Expr\FuncCall
            && $node->name instanceof Node\Scalar\String_) {
            $node->name = new Node\Name($node->name->value);
        }//修改函数名 'chr' ->chr ...
        if ($node instanceof Node\FunctionLike) {
            foreach ($node->params as $param) {
                $name = $param->var->name;
                if (array_key_exists($name, $this->varMap)) {
                    $param->var->name = $this->varMap[$name];
                } elseif (self::isUnreadable($name)) {
                    $this->varMap[$name] = $this->generateArgName();
                    $param->var->name = $this->varMap[$name];
                }
            }
        }
        ///重命名变量名
        return null;
    }

    public function leaveNode(Node $node)
    {
        if ($node instanceof Node\Expr\FuncCall && // 去掉chr
            $node->name instanceof Node\Name &&
            $node->name->parts[0] == 'chr' &&
            $node->args[0]->value instanceof Node\Expr\BinaryOp\Plus && 
            $node->args[0]->value->left instanceof Node\Scalar\LNumber  
         ) {
            //print_r($node->args[0]->value->left->value);
            //print_r($node->args[0]->value->right->left->value);
            //print_r($node->args[0]->value->right->right->value);
            $value = $node->args[0]->value->left->value + ($node->args[0]->value->right->left->value - $node->args[0]->value->right->right->value);
            return new Node\Scalar\String_(chr($value));
        }
        if ($node instanceof Node\Expr\FuncCall &&
            $node->name instanceof Node\Name &&
            $node->name->parts[0] == 'chr' &&
            $node->args[0]->value instanceof Node\Expr\BinaryOp\Plus && 
            $node->args[0]->value->left instanceof  Node\Expr\UnaryMinus
         ) {
            //print_r($node->args[0]->value->left->expr->value);
            //print_r($node->args[0]->value->right->left->value);
            //print_r($node->args[0]->value->right->right->value);
            $value = 0 - $node->args[0]->value->left->expr->value + ( $node->args[0]->value->right->left->value - $node->args[0]->value->right->right->value );
            return new Node\Scalar\String_(chr($value));
        }

        if ($node instanceof Node\Expr\FuncCall &&
            $node->name instanceof Node\Name &&
            $node->name->parts[0] == 'str_rot13' &&
            $node->args[0] instanceof Node\Arg  
        ) {//去掉 str_rot13
            $val="";
            $param=$node->args[0]->value;
            if ($param instanceof Node\Scalar\String_)
                return new Node\Scalar\String_(str_rot13($param->value));
            while ($param instanceof Node\Expr\BinaryOp\Concat){
                $val.=$param->left->value;
                $param=$param->right;
            }
            $val.=$param->value;
            return new Node\Scalar\String_(str_rot13($val));
        }

        if ($node instanceof Node\Expr\Variable) {
            $name = $node->name;
            if (array_key_exists($name, $this->varMap)) {
                $node->name = $this->varMap[$name];
            } elseif (self::isUnreadable($name)) {
                $this->varMap[$name] = $this->generateLocalVarName();
                $node->name = $this->varMap[$name];
            }
        }

        if ($node instanceof Node\Expr\FuncCall
            && $node->name instanceof Node\Scalar\String_) {//修改函数名 call_user_array()
            $node->name = new Node\Name($node->name->value);
        }

    }
}