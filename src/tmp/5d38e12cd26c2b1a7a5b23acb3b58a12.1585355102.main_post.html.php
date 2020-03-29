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
                <li><a href="<?php echo url(array('c'=>"main", 'a'=>"Message", ));?>">Message</a></li>
                <li class="active"><a href="<?php echo url(array('c'=>"main", 'a'=>"Post", ));?>">Post</a></li>
              </ul>
            </div><!--/.nav-collapse -->
          </div>
        </div>
</div>
<div class="content">
    <form action="#" method="post" >
        <textarea name="msg" rows="10" class="text" placeholder="Message"></textarea>
        <button class="btn btn-large btn-primary" type="submit">submit</button>
    </form>
</div>