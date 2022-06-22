<?php
  /**
   * Invoice
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo Lang::$word->M_INVOICE;?></title>
<style type="text/css">
body {
  background-color: #fff;
  color: #333;
  font-family: DejaVu Serif, Helvetica, Times-Roman;
  font-size: 1em;
  margin: 0;
  padding: 0
}
table {
  font-size: 75%;
  width: 100%;
  border-collapse: separate;
  border-spacing: 2px
}
th,
td {
  position: relative;
  text-align: left;
  border-radius: .25em;
  border-style: solid;
  border-width: 1px;
  padding: .5em
}
th {
  background: #EEE;
  border-color: #BBB
}
td {
  border-color: #DDD
}
h1 {
  font: bold 100% sans-serif;
  letter-spacing: .5em;
  text-align: center;
  text-transform: uppercase
}
table.inventory {
  clear: both;
  width: 100%
}
table.inventory th,
table.payments th {
  font-weight: 700;
  text-align: center
}
table.inventory td:nth-child(1) {
  width: 52%
}
table.payments {
  padding-top: 20px
}
table.balance th,
table.balance td {
  width: 50%
}
.green {
  background-color: #D5EEBE;
  color: #689340
}
.blue {
  background-color: #D0EBFB;
  color: #4995B1
}
.red {
  background-color: #FAD0D0;
  color: #AF4C4C
}
.yellow {
  background-color: #FFC;
  color: #BBB840
}
#aside {
  padding-top: 30px;
  font-size: 65%
}
small {
  font-size: 75%;
  line-height: 1.5em
}
table.inventory td.right {
  text-align: right;
  width: 12%
}
table.payments td.right,
table.balance td {
  text-align: right
}
#footer {
  position: fixed;
  bottom: 0px;
  left: 0px;
  right: 0px;
  height: 100px;
  text-align: center;
  border-top: 2px solid #eee;
  font-size: 85%;
  padding-top: 5px
}
@page {
  margin: 30px;
  margin-footer: 5mm;
  footer: html_footer;
}
</style>
</head>
<body>
<table border="0">
  <tr>
    <td style="width: 60%;" valign="top"><?php if ($this->core->plogo):?>
      <img alt="" src="<?php echo UPLOADS . '/' . $this->core->plogo;?>">
      <?php else:?>
      <?php echo $this->core->company;?>
      <?php endif;?></td>
    <td valign="top" style="width:40%;text-align: right"><h4 style="margin:0px;padding:0px;font-size: 12px;">Invoice: #<?php echo $this->row->invid;?></h4>
      <h4 style="margin:0px;padding:0px;font-size: 12px;"><?php echo Date::doDate("short_date", $this->row->created);?></h4></td>
  </tr>
</table>
<div style="background-color:#ddd;height:1px">&nbsp;</div>
<table style="padding-top:25px">
  <tr>
    <td valign="top" style="width:60%">Payment To</td>
    <td colspan="2" valign="top" style="width:40%">Bill To</td>
  </tr>
  <tr>
    <td valign="top" style="width:60%"><p><?php echo Validator::cleanOut($this->core->inv_info);?></p></td>
    <td colspan="2" valign="top" style="width:40%"><p><?php echo $this->user->fname;?> <?php echo $this->user->lname;?><br />
        <?php echo $this->user->address;?><br />
        <?php echo $this->user->city.', '.$this->user->state.' '.$this->user->zip;?><br />
        <?php echo $this->user->country;?></p></td>
  </tr>
  <tr>
    <td valign="top" style="width:60%"><br /></td>
    <td valign="top" style="width:20%">Amount Due:<br />
      Due Date:</td>
    <td valign="top" style="width:20%"><?php echo Utility::formatMoney($this->row->total);?><br />
      <?php echo Date::dodate("short_date", $this->row->created);?></td>
  </tr>
</table>
<div style="height:20px"></div>
<table class="inventory">
  <thead>
    <tr>
      <th><span>Invoice Items</span></th>
      <th><span>Total</span></th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td><span><?php echo $this->row->title;?> <small>(<?php echo $this->row->description;?>)</small></span></td>
      <td class="right"><span><?php echo $this->row->rate_amount;?></span></td>
    </tr>
    <tr>
      <td align="right">Discount/Coupon:</td>
      <td align="right">- <?php echo number_format($this->row->coupon, 2);?></td>
    </tr>
  </tbody>
</table>
<table class="balance">
  <tr>
    <th><span>Subtotal</span></th>
    <td><span><?php echo number_format($this->row->total - $this->row->coupon - $this->row->tax, 2);?></span></td>
  </tr>
  <tr>
    <th><span>Taxes</span></th>
    <td><span><?php echo $this->row->tax;?></span></td>
  </tr>
  <tr>
    <th><span>Grand Total</span></th>
    <td><span><?php echo $this->row->total;?> <?php echo $this->row->currency;?></span></td>
  </tr>
  <tr>
    <th>Status</th>
    <td class="green">Paid</td>
  </tr>
</table>
<htmlpagefooter name="footer">
  <table width="100%" style="vertical-align: bottom;font-size: 8pt; border-top:1px solid #BBB; font-weight: bold; font-style: italic;">
    <tr>
      <td colspan="3" style="border:0"><?php if($this->core->inv_note):?>
        <?php echo Validator::cleanOut($this->core->inv_note);?>
        <?php endif;?></td>
    </tr>
    <tr>
      <td width="33%" style="border:0"><span style="font-weight: bold; font-style: italic;">{DATE j-m-Y}</span></td>
      <td width="33%" align="center" style="font-weight: bold; font-style: italic;border:0">{PAGENO}/{nbpg}</td>
      <td width="33%" style="text-align: right;border:0 "><?php echo $this->core->company;?></td>
    </tr>
  </table>
</htmlpagefooter>
</body>
</html>