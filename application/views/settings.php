<form method="POST" action="" id="settings">
<div class="row">
  <input type="hidden" id="error" value="<?php echo $this->session->flashdata('error'); ?>" />
  <input type="hidden" id="success" value="<?php echo $this->session->flashdata('success'); ?>" />
  <h3>AT Account Settings</h3>
    <div class="input-group col-xs-12">
      <label>Username:</label>
      <input type="text" class="form-control input-sm" placeholder="Your AT Username" name="username" value="<?php foreach($configs->result() as $config){ if($config->key == 'username'){ echo $config->value; break; } } ?>"/>
    </div>
    <div class="input-group col-xs-12">
      <label>API Key:</label>
      <input type="text" class="form-control input-sm" placeholder="Your AT API Key" name="api_key" value="<?php foreach($configs->result() as $config){ if($config->key == 'api_key'){ echo $config->value; break; } } ?>"/>
    </div>
    <div class="input-group col-xs-12">
      <label>Send SMS As:</label>
      <input type="text" class="form-control input-sm" placeholder="Your AT Shortcode or Alphanumeric" name="from" value="<?php foreach($configs->result() as $config){ if($config->key == 'from'){ echo $config->value; break; } } ?>"/>
    </div>
    <div class="form-button">
      <button type="submit" class="btn btn-primary">SAVE SETTINGS</button>
     </div>
</div>
</form>
