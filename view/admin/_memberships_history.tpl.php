<?php
  /**
   * Membership Manager
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');
?>
<div class="row">
  <div class="column">
    <h3><?php echo Lang::$word->META_T7;?>
      <small>// <?php echo $this->data->{'title' . Lang::$lang};?></small></h3>
  </div>
  <div class="column shrink"><a href="<?php echo ADMINVIEW . '/helper.php?exportMembershipPayments&amp;id=' . $this->data->id;?>" class="yoyo secondary button"><i class="icon wysiwyg table"></i><?php echo Lang::$word->EXPORT;?></a>
  </div>
</div>
<div class="yoyo segment">
  <div id="legend" class="yoyo small horizontal list align-right"></div>
  <div id="payment_chart" style="height:300px;"></div>
</div>
<?php if($this->plist):?>
<div class="yoyo segment">
  <table class="yoyo sorting basic table">
    <thead>
      <tr>
        <th data-sort="string"><?php echo Lang::$word->USER;?></th>
        <th data-sort="int"><?php echo Lang::$word->TRX_AMOUNT;?></th>
        <th data-sort="int"><?php echo Lang::$word->TRX_TAX;?></th>
        <th data-sort="int"><?php echo Lang::$word->TRX_COUPON;?></th>
        <th data-sort="int"><?php echo Lang::$word->TRX_TOTAMT;?></th>
        <th data-sort="int"><?php echo Lang::$word->CREATED;?></th>
      </tr>
    </thead>
    <?php foreach ($this->plist as $row):?>
    <tr>
      <td><a class="inverted" href="<?php echo Url::url("admin/users/edit", $row->user_id);?>"><?php echo $row->name;?></a></td>
      <td><?php echo $row->rate_amount;?></td>
      <td><?php echo $row->tax;?></td>
      <td><?php echo $row->coupon;?></td>
      <td><?php echo $row->total;?></td>
      <td data-sort-value="<?php echo strtotime($row->created);?>"><?php echo Date::doDate("short_date", $row->created);?></td>
    </tr>
    <?php endforeach;?>
  </table>
  <div class="yoyo double divider"></div>
  <div class="yoyo small passive primary button"><?php echo Lang::$word->TRX_TOTAMT;?>
    <?php echo Utility::formatMoney(Stats::doArraySum($this->plist, "total"));?></div>
</div>
<div class="row half-gutters-mobile half-gutters-phone align-middle">
  <div class="columns shrink mobile-100 phone-100">
    <div class="yoyo small thick text"><?php echo Lang::$word->TOTAL.': '.$this->pager->items_total;?> / <?php echo Lang::$word->CURPAGE.': '.$this->pager->current_page.' '.Lang::$word->OF.' '.$this->pager->num_pages;?></div>
  </div>
  <div class="columns mobile-100 phone-100 content-right mobile-content-left"><?php echo $this->pager->display_pages('small');?></div>
</div>
<?php endif;?>
<script type="text/javascript" src="<?php echo SITEURL;?>/assets/morris.min.js"></script>
<script type="text/javascript" src="<?php echo SITEURL;?>/assets/raphael.min.js"></script>
<script type="text/javascript"> 
// <![CDATA[
$(document).ready(function() {	
	$("#payment_chart").parent().addClass('loading');
	$.ajax({
		type: 'GET',
		url: "<?php echo ADMINVIEW . '/helper.php?getMembershipPaymentsChart=1&id=' . $this->data->id;?>&timerange=all",
		dataType: 'json'
	}).done(function(json) {
		var legend = '';
		json.legend.map(function(val) {
		   legend += val;
		});
		$("#legend").html(legend);
		Morris.Line({
			element: 'payment_chart',
			data: json.data,
			xkey: 'm',
			ykeys: json.label,
			labels: json.label,
			parseTime: false,
			lineWidth: 4,
			pointSize: 6,
			lineColors: json.color,
			gridTextFamily: "Roboto",
			gridTextColor: "rgba(0,0,0,0.6)",
			gridTextSize: 12,
			fillOpacity: '.75',
			hideHover: 'auto',
			preUnits: json.preUnits,
			smooth: true,
			resize: true,
		});
		$("#payment_chart").parent().removeClass('loading');
	});
});
// ]]>
</script>