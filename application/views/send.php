<div class="row">
<form action="" method="POST" id="send" >
  <input type="hidden" id="error" value="<?php echo $this->session->flashdata('error'); ?>" />
  <input type="hidden" id="success" value="<?php echo $this->session->flashdata('success'); ?>" />
  <h3 class="centered">SEND MESSAGE</h3>
	<div class="form-group">
	<label>Select Keyword</label>
		<select name="keyword" class="form-control input-sm">
		<?php foreach($keywords->result() as $keyword){?>
			<option value="<?php echo $keyword->name; ?>"><?php echo $keyword->name; ?></option>
		<?php } ?>
		</select>
	</div>
	<div class="form-group">
	<label>Message</label>
		<textarea name="message" class="form-control input-sm" placeholder="Type your message here"></textarea>
	</div>
    <div class="form-group centered">
	<input type="submit" class="btn btn-primary btn-lg btn-block" value="SEND MESSAGE"/>
	</div>
</form>
</div>
