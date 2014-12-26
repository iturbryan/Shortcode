
  <input type="hidden" id="error" value="<?php echo $this->session->flashdata('error'); ?>" />
  <input type="hidden" id="success" value="<?php echo $this->session->flashdata('success'); ?>" />
<div class="row row_header">
  <div class="col-xs-1">#</div>
  <div class="col-xs-2">From (Phone)</div>
  <div class="col-xs-1">To</div>
  <div class="col-xs-2">Datetime</div>
  <div class="col-xs-6">Message</div>
</div>
<?php $i = 1; foreach($inbox->result() as $row){?>
<div class="row row_item">
  <div class="col-xs-1"><?php echo $i ++; ?></div>
  <div class="col-xs-2"><?php echo $row->from; ?></div>
  <div class="col-xs-1"><?php echo $row->to; ?></div>
  <div class="col-xs-2"><?php echo date("d-m-Y H:i:s", strtotime($row->datetime)); ?></div>
  <div class="col-xs-6"><?php echo $row->message; ?></div>
</div>
<?php } ?>

<div class="row">
<?php echo $pages; ?>
</div>
