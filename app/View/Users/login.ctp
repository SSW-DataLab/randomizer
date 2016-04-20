<h1><?= $settings['project_label']; ?></h1>
<?php echo $this->Session->flash('auth'); ?>
<form role="form" action="<?= $baseURL?>/users/login" id="UserLoginForm" method="post" accept-charset="utf-8">
 <input type="hidden" name="_method" value="POST"/>
 <div class="form-group">
     <label for="UserUsername">Username</label>
     <input class="form-control" name="data[User][username]" maxlength="32" type="text" id="UserUsername"/>
 </div>
 <div class="form-group">
     <label for="UserPassword">Password</label>
     <input class="form-control" name="data[User][password]" type="password" id="UserPassword"/>
 </div>
 <button class='btn btn-default' type="submit" value="Login">Login</button>
</form>