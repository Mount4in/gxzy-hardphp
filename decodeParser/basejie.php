<?php
use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;
use PhpParser\NodeTraverser;

class ArrayToConstant extends NodeVisitorAbstract
{
    /**
     * @var string
     */
    private $_variableName = '';
    /**
     * @var array
     */
    private $_constants = [];
    private $_constants2 = [];

    public function enterNode(Node $node)
    {
        if ($node instanceof Node\Expr\Assign &&
            $node->expr instanceof Node\Expr\FuncCall &&
            $node->expr->name instanceof Node\Name &&
            is_string($node->expr->name->parts[0]) &&
            $node->expr->name->parts[0] == 'unserialize' &&
            count($node->expr->args) === 1 &&
            $node->expr->args[0] instanceof Node\Arg &&
            $node->expr->args[0]->value instanceof Node\Expr\FuncCall &&
            $node->expr->args[0]->value->name instanceof Node\Name &&
            is_string($node->expr->args[0]->value->name->parts[0]) &&
            $node->expr->args[0]->value->name->parts[0] == 'base64_decode'
        ) {
            $string = $node->expr->args[0]->value->args[0]->value->value;
            $array = unserialize(base64_decode($string));
            $this->_variableName = $node->var->dim->value;
            $this->_constants = $array;
            $this->_constants2 = unserialize(base64_decode($array[1]));
            var_dump($this->_constants);
            var_dump($this->_constants2);
            //var_dump($node->var->dim->value);
            return new Node\Expr\Assign($node->var, Node\Scalar\LNumber::fromString("0"));
        }
        return null;
    }

    public function leaveNode(Node $node)
    {
        if ($this->_variableName === '') return;
        //if ($this->)
        if ($node instanceof Node\Expr\ArrayDimFetch && $node->var instanceof Node\Expr\ArrayDimFetch &&  $node->dim instanceof PhpParser\Node\Expr\BinaryOp\Plus)
            //$node->var->dim->dim->value === 0) //$this->_variableName
            {
                //$val = $this->_constants2[$node->var->dim->dim->value];
                $val1 = $this->_constants[$node->dim->left->dim->value] + (($this->_constants[$node->dim->right->left->dim->value]) - ($this->_constants[$node->dim->right->right->dim->value]));
                //var_dump($val1);
                $val = $this->_constants2[$val1];
                //var_dump($val);
                if (is_string($val)) {
                    return new Node\Scalar\String_($val);
                } elseif (is_double($val)) {
                    return new Node\Scalar\DNumber($val);
                } elseif (is_int($val)) {
                    return new Node\Scalar\LNumber($val);
                } else {
                    return new Node\Expr\ConstFetch(new Node\Name\FullyQualified(json_encode($val)));
                    //return new Node\Expr\ConstFetch(new Node\Name($val));
                }
            }
        if ($node instanceof Node\Stmt\Expression&&
            $node->expr instanceof Node\Expr\Assign &&
            $node->expr->var instanceof Node\Expr\ArrayDimFetch&&
            $node->expr->var->var instanceof Node\Expr\Variable &&
            $node->expr->var->var->name == 'GLOBALS'  &&
            ($node->expr->var->dim instanceof Node\Expr\ArrayDimFetch ||
            ($node->expr->var->dim instanceof Node\Scalar\String_ &&
            $node->expr->var->dim->value != 'http_scheme')))
            {
                return NodeTraverser::REMOVE_NODE;
            }
            return null;
    }

}

//$GLOBALS[$GLOBALS['�����'][0]] [$GLOBALS['�����'][4]+($GLOBALS['�����'][5]-$GLOBALS['�����'][6])]