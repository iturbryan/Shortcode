
  <input type="hidden" id="error" value="<?php echo $this->session->flashdata('error'); ?>" />
  <input type="hidden" id="success" value="<?php echo $this->session->flashdata('success'); ?>" />
<div class="row row_header">
  <div class="col-xs-2">#</div>
  <div class="col-xs-4">To (Phone)</div>
  <div class="col-xs-4">Datetime</div>
  <div class="col-xs-2">Status</div>
</div>
<?php $i = 1; foreach($outbox->result() as $row){?>
<div class="row row_item">
  <div class="col-xs-2"><?php echo $i ++; ?></div>
  <div class="col-xs-4"><?php echo $row->telephone; ?></div>
  <div class="col-xs-4"><?php echo date("d-m-Y H:i:s", strtotime($row->datetime)); ?></div>
  <div class="col-xs-2"><?php echo $row->status; ?></div>
</div>
<?php } ?>

<div class="row">
<?php echo $pages; ?>
</div>
