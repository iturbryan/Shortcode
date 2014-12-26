
  <input type="hidden" id="error" value="<?php echo $this->session->flashdata('error'); ?>" />
  <input type="hidden" id="success" value="<?php echo $this->session->flashdata('success'); ?>" />
<p class="spacer">&nbsp;</p>
<div class="row">

  <div class="row">
	<form action="" method="POST">
	    <div class="col-xs-4">
		    <div class='input-group' >
			<input type='text' class="form-control" name="from" id='from' value="<?php echo $this->session->userdata('from'); ?>"/>
			<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
			</span>
		    </div>
	    </div>
	    <div class="col-xs-4">
		    
		    <div class='input-group' >
			<input type='text' class="form-control" name="to" id="to" value="<?php echo $this->session->userdata('to'); ?>"/>
			<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
			</span>
		    </div>
	    </div>
	    <div class="col-xs-4">&nbsp;<input type="submit" class="btn btn-danger" value="SHOW REPORT" /></div>

<script type="text/javascript">
$(function () {
                $( "#from" ).datepicker({ dateFormat: 'dd-mm-yy' });
                $( "#to" ).datepicker({ dateFormat: 'dd-mm-yy' });
            });

</script>
	</form>
      </div>
<p class="spacer">&nbsp;</p>
  <div class="col-xs-3 smiley green"><img src="<?php echo base_url(); ?>media/images/smile.png" class="img-thumbnail" border="0"/><p class="smiley_block"><?php echo $new_subscriptions; ?></p>New Subscribers</div>
  <div class="col-xs-3 smiley red"><img src="<?php echo base_url(); ?>media/images/sad.png" class="img-thumbnail"/><p class="smiley_block"><?php echo $unsubscriptions; ?></p>Unsubscribers</div>
  <div class="col-xs-3 smiley orange"><img src="<?php echo base_url(); ?>media/images/neutral.png" class="img-thumbnail"/><p class="smiley_block"><?php echo $subscribers; ?></p>Total Subscribers</div>
  <div class="col-xs-3 smiley"><img src="<?php echo base_url(); ?>media/images/delivered.png" class="img-thumbnail"/><p class="smiley_block"><?php echo $delivered; ?></p>Delivered Messages</div>
</div>
<div class="row" id="chart">
  <script type="text/javascript">
$(function () {
    $('#chart').highcharts({
        chart: {
            zoomType: 'xy'
        },
        title: {
            text: 'Premium SMS Subscription Service'
        },
        subtitle: {
            text: 'Between <?php echo date("d-m-Y", strtotime($start_date)); ?> and <?php echo date("d-m-Y", strtotime($end_date)); ?>'
        },
        xAxis: [{
            categories: [<?php $i = 0;  foreach($categories as $key => $value) { if($i > 0) echo ","; echo '"'. $value .'"'; $i ++; } ?>]
        }],
        yAxis: [{ // Primary yAxis
            labels: {
                format: '{value}',
                style: {
                    color: Highcharts.getOptions().colors[1]
                }
            },
            title: {
                text: 'Subscriptions',
                style: {
                    color: Highcharts.getOptions().colors[1]
                }
            }
        }, { // Secondary yAxis
            title: {
                text: 'Sent SMS',
                style: {
                    color: Highcharts.getOptions().colors[0]
                }
            },
            labels: {
                format: '{value}',
                style: {
                    color: Highcharts.getOptions().colors[0]
                }
            },
            opposite: true
        }],
        tooltip: {
            shared: true
        },
        legend: {
            layout: 'vertical',
            align: 'left',
            x: 120,
            verticalAlign: 'top',
            y: 100,
            floating: true,
            backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'
        },
        series: [{
            name: 'Sent SMS',
            type: 'column',
            yAxis: 1,
            data: [<?php $i = 0;  foreach($messages as $key => $value) { if($i > 0) echo ","; echo $value; $i ++; } ?>],
            tooltip: {
                valueSuffix: ''
            }

        }, {
            name: 'Subscriptions',
            type: 'spline',
            data: [<?php $i = 0;  foreach($subscriptions as $key => $value) { if($i > 0) echo ","; echo $value; $i ++; } ?>],
            tooltip: {
                valueSuffix: ''
            }
        }]
    });
});
</script>
</div>
