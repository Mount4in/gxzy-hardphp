<?php
use PhpParser\Lexer;
use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\NodeVisitorAbstract;

class ConstantToArray extends NodeVisitorAbstract
{
    /**
     * @var string
     */
    private $_variableName = '';
    /**
     * @var array
     */
    private $_constants = [];

    private $_parser;

    private $_inStatic = false;

    public function __construct($_parser)
    {
        // 生成一个用于存储数据的变量名，比如AAAAA
        $this->_variableName = "AAAAA";
        $this->_parser = $_parser;
    }

    public function afterTraverse(array $nodes)
    {
        $keys = [];
        foreach ($this->_constants as $key => $value) {
            $keys[] = unserialize($key);
        }
        $items = base64_encode(serialize($keys));
        // 懒得写一大串了。。。
        $nodes = array_merge($this->_parser->parse(
            "<?php \${$this->_variableName}=unserialize(base64_decode('$items'));"
        ), $nodes);
        return $nodes;
    }

    public function enterNode(Node $node)
    {
        // 在每个函数头部插入global $AAAAA
        if ($node instanceof Node\Stmt\Function_) {
            $global = new Node\Stmt\Global_([new Expr\Variable($this->_variableName)]);
            array_unshift($node->stmts, $global);
        }
        if ($node instanceof Node\Param || $node instanceof Node\Stmt\Static_) {
            $this->_inStatic = true;
        }
    }

    public function leaveNode(Node $node)
    {
        if ($node instanceof Node\Param || $node instanceof Node\Stmt\Static_) {
            $this->_inStatic = false;
        }
        if ($this->_inStatic) {
            return;
        }

                // 处理字符串、数字等类型
        if ($node instanceof Node\Scalar
            && (!$node instanceof Node\Scalar\MagicConst)) {
            // 使用serialize是为了解决类型问题，PHP是个神奇的弱类型语言
            $name = serialize($node->value);
            // _constants是个Map，这样做性能会高一些
            if (!isset($this->_constants[$name])) {
                // 这里最好事先扫描一遍并编制索引以提升随机性
                // count仅供测试用，比较好看
                $this->_constants[$name] = count($this->_constants);
            }
            return new Expr\ArrayDimFetch(
                new Expr\Variable($this->_variableName),
                Node\Scalar\LNumber::fromString($this->_constants[$name])
            );
        }

          // 处理true, false等类型
        if ($node instanceof Node\Expr\ConstFetch && $node->name instanceof Node\Name && count($node->name->parts) === 1) {
            $name = $node->name->parts[0];
            switch (strtolower($name)) {
                case 'true':
                    $name = true;
                    break;
                case 'false':
                    $name = false;
                    break;
                case 'null':
                    $name = null;
                    break;
                default:
                    return;
            }
            $name = serialize($name);
            if (!isset($this->_constants[$name])) {
                $this->_constants[$name] = count($this->_constants);
            }
            return new Expr\ArrayDimFetch(
                new Expr\Variable($this->_variableName),
                Node\Scalar\LNumber::fromString($this->_constants[$name])
            );
    }
}
}