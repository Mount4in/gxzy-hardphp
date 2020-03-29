（1）PhpParser\Node\Stmt是语句节点，不带任何返回信息（return）的结构，如赋值语句”$a = $b” ;

（2）PhpParser\Node\Expr是表达式节点，可以返回一个值的语言结构，如$var和func()。

（3）PhpParser\Node\Scalar是常量节点，可以用来表示任何常量值。如’string’,0,以及常量表达式。

（4）还有一些节点没有包括进去，如参数节点(PhpParser\Node\Arg)

assign 