<?php
use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;

class HTMLToEcho extends NodeVisitorAbstract
{
    private $_inStatic = false;
    public function enterNode(Node $node)
    {
        if($node instanceof Node\Param ||$node instanceof Node\Stmt\Static_)
        {
            $this->_inStatic = true;
        }
    }
    
    public function leaveNode(Node $node)
    {
        if ($node instanceof Node\Param || $node instanceof Node\Stmt\Static_)
        {
            $this->_inStatic = false;
        }
        if ($this->_inStatic)
        {
            return;
        }
        if ($node instanceof Node\Scalar\String_) {
            $name = $node->value;
            return new Node\Expr\FuncCall(
                new Node\Name("str_rot13"),
                [new Node\Arg(new Node\Scalar\String_(str_rot13($name)))]
            );
        }
        // 当当前节点的类型是 InlineHTML
        /*if ($node instanceof Node\Stmt\InlineHTML) {
            // 将其替换成 echo 'value';
            return new Node\Stmt\Echo_([
                new Node\Scalar\String_($node->value)
            ]);
        }*/
    }

}