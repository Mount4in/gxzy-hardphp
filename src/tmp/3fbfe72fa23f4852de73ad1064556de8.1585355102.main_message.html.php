<?php if(!class_exists("View", false)) exit("no direct access allowed");?><div class="navbar navbar-inverse navbar-fixed-top">
        <div class="navbar-inner">
          <div class="container-fluid">
            <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <a class="brand" href="#">HardPHP</a>
            <div class="nav-collapse collapse">
              <p class="navbar-text pull-right">
                <span>Now: <?php echo htmlspecialchars($now, ENT_QUOTES, "UTF-8"); ?></span>
                <a href="<?php echo url(array('c'=>"user", 'a'=>"LoginOut", ));?>" class="navbar-link">Loginout</a>
              </p>
              <ul class="nav">
                	<li><a href="<?php echo url(array('c'=>"main", 'a'=>"index", ));?>">Home</a></li>
                <li class="active"><a href="<?php echo url(array('c'=>"main", 'a'=>"Message", ));?>">Message</a></li>
                <li><a href="<?php echo url(array('c'=>"main", 'a'=>"Post", ));?>">Post</a></li>
              </ul>
            </div><!--/.nav-collapse -->
          </div>
        </div>
</div>
<div class="content">
    <?php if(!empty($messages)){ $_foreach_v_counter = 0; $_foreach_v_total = count($messages);?><?php foreach( $messages as $k => $v ) : ?><?php $_foreach_v_index = $_foreach_v_counter;$_foreach_v_iteration = $_foreach_v_counter + 1;$_foreach_v_first = ($_foreach_v_counter == 0);$_foreach_v_last = ($_foreach_v_counter == $_foreach_v_total - 1);$_foreach_v_counter++;?>
        <ul class="breadcrumb row">
            <div class="span10">
                <h5> username: <?php echo htmlspecialchars($v['username'], ENT_QUOTES, "UTF-8"); ?> message: <?php echo htmlspecialchars($v['message'], ENT_QUOTES, "UTF-8"); ?></h5>
            </div>
        </ul>
    <?php endforeach; }?>
</div>