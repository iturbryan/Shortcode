<div class="login_container">
  <input type="hidden" id="error" value="<?php echo $this->session->flashdata('error'); ?>" />
  <input type="hidden" id="success" value="<?php echo $this->session->flashdata('success'); ?>" />
<img src="<?php echo base_url();?>media/images/logo.png" class="centered"/>
<p>&nbsp;</p>
	<form class="form-horizontal" role="form" action="" method="POST">
	  <div class="form-group">
	    <label for="inputEmail3" class="col-sm-2 control-label">Username:</label>
	    <div class="col-sm-10 input-append">
	      <input type="text" class="form-control input-sm" id="inputEmail3" name="username" placeholder="Username">
	    </div>
	  </div>
	  <div class="form-group">
	    <label for="inputPassword3" class="col-sm-2 control-label">Password:</label>
	    <div class="col-sm-10">
	      <input type="password" class="form-control input-sm" id="inputPassword3" name="password" placeholder="Password">
	    </div>
	  </div>
	    <div class="col-sm-8">
	    </div>
	    <div class="col-sm-4">
	     <div class="form-group">
	      <button type="submit" class="btn btn-primary btn-long btn-block">Login</button>
	    </div>
	  </div>
	</form>
</div>
