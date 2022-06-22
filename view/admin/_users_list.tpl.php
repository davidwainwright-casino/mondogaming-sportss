<?php
  /**
   * User Manager
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');
?>
<div class="row horizontal-gutters align-bottom">
  <div class="columns">
    <h3><?php echo Lang::$word->META_T2;?></h3>
    <a href="<?php echo Url::url(Router::$path, "new/");?>" class="yoyo secondary button"><i class="icon plus alt"></i><?php echo Lang::$word->M_TITLE5;?></a>
  </div>
  <div class="columns shrink">
    <a class="yoyo basic disabled icon button"><i class="icon unordered list"></i></a>
    <a href="<?php echo Url::url(Router::$path, "grid");?>" class="yoyo primary icon button"><i class="icon grid"></i></a>
    <a href="<?php echo ADMINVIEW . '/helper.php?exportUsers';?>" data-content="<?php echo Lang::$word->EXPORT;?>" class="yoyo white circular icon button"><i class="icon wysiwyg table"></i></a>
  </div>
</div>
<div class="yoyo segment form">
  <div class="yoyo divided horizontal link list align-center">
    <div class="disabled item yoyo bold text">
      <?php echo Lang::$word->SORTING_O;?>
    </div>
    <a href="<?php echo Url::url(Router::$path);?>" class="item<?php echo Url::setActive("order", false);?>">
    <?php echo Lang::$word->RESET;?></a>
    <a href="<?php echo Url::url(Router::$path, "?order=membership_id|DESC");?>" class="item<?php echo Url::setActive("order", "membership_id");?>">
    <?php echo Lang::$word->MEMBERSHIP;?></a>
    <a href="<?php echo Url::url(Router::$path, "?order=email|DESC");?>" class="item<?php echo Url::setActive("order", "email");?>">
    <?php echo Lang::$word->M_EMAIL1;?></a>
    <a href="<?php echo Url::url(Router::$path, "?order=fname|DESC");?>" class="item<?php echo Url::setActive("order", "fname");?>">
    <?php echo Lang::$word->NAME;?></a>
    <div class="item"><a href="<?php echo Url::sortItems(Url::url(Router::$path), "order");?>" data-content="ASC/DESC"><i class="icon triangle unfold more link"></i></a>
    </div>
  </div>
  <div class="content-center"><?php echo Validator::alphaBits(Url::url(Router::$path), "letter", "yoyo small demi text horizontal link divided list align-center");?></div>
</div>
<?php if(!$this->data):?>
<div class="content-center"><img src="<?php echo ADMINVIEW;?>/images/notfound.png" alt="">
  <p class="yoyo small bold caps text"><?php echo Lang::$word->M_INFO6;?></p>
</div>
<?php else:?>
<div class="row screen-block-2 tablet-block-1 mobile-block-1 phone-block-1 half-gutters">
  <?php foreach($this->data as $row):?>
  <div class="column" id="item_<?php echo $row->id;?>">
    <div class="yoyo card">
      <div class="grey header">
        <div class="row horizontal-gutters align-middle">
          <div class="column shrink"><img src="<?php echo UPLOADURL;?>/avatars/<?php echo $row->avatar ? $row->avatar : "blank.png" ;?>" alt="" class="yoyo avatar image"></div>
          <div class="column">
            <h4>
              <?php if(Auth::hasPrivileges('edit_user')):?>
              <a class="white" href="<?php echo Url::url(Router::$path, "edit/" . $row->id);?>"><?php echo $row->fullname;?></a>
              <?php else:?>
              <?php echo $row->fullname;?>
              <?php endif;?>
            </h4>
          </div>
          <div class="column shrink">
            <a class="yoyo icon circular white button" data-dropdown="#userDrop_<?php echo $row->id;?>">
            <i class="icon horizontal ellipsis"></i>
            </a>
            <div class="yoyo dropdown small menu pointing top-right" id="userDrop_<?php echo $row->id;?>">
              <?php if(Auth::hasPrivileges('edit_user')):?>
              <a class="item" href="<?php echo Url::url(Router::$path, "edit/" . $row->id);?>"><i class="icon pencil"></i>
              <span class="padding-left"><?php echo Lang::$word->EDIT;?></span></a>
              <?php endif;?>
              <a class="item" href="<?php echo Url::url(Router::$path, "history/" . $row->id);?>"><i class="icon history"></i>
              <span class="padding-left"><?php echo Lang::$word->HISTORY;?></span></a>
              <?php if(Auth::hasPrivileges('delete_user')):?>
              <div class="yoyo basic divider"></div>
              <a data-set='{"option":[{"trash": "trashUser","title": "<?php echo Validator::sanitize($row->fullname, "chars");?>","id":<?php echo $row->id;?>}],"action":"trash","parent":"#item_<?php echo $row->id;?>"}' class="item action">
              <i class="icon trash"></i>
              <span class="padding-left"><?php echo Lang::$word->TRASH;?></span></a>
              <?php endif;?>
            </div>
          </div>
        </div>
      </div>
      <div class="footer">
        <div class="row align-middle">
          <div class="column">
            <div class="yoyo small divided horizontal list">
              <div class="item">
                <div class="header">
                  <span class="yoyo caps text"><?php echo Lang::$word->M_EMAIL1;?></span>
                  <span class="yoyo caps text"><a href="<?php echo Url::url("/admin/mailer", "?email=" . urlencode($row->email));?>"><?php echo $row->email;?></a>
                  </span>
                </div>
              </div>
              <div class="item">
                <div class="header">
                  <span class="yoyo caps text"><?php echo Lang::$word->MEMBERSHIP;?></span>
                  <span class="yoyo caps text"><?php echo ($row->membership_id) ? '<a href="' . Url::url("/admin/memberships/edit/" . $row->membership_id) . '">' . $row->mtitle . '</a>' : '-/-';?></span>
                </div>
              </div>
              <div class="item">
                <div class="header">
                  <span class="yoyo caps text"><?php echo Lang::$word->CREATED;?></span>
                  <span class="yoyo caps text"><?php echo Date::doDate("short_date", $row->created);?></span>
                </div>
              </div>
            </div>
          </div>
          <div class="column shrink">
            <?php echo Utility::status($row->active, $row->id);?>
            <?php echo Utility::userType($row->type);?>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php endforeach;?>
</div>
<?php endif;?>
<div class="row half-gutters-mobile half-gutters-phone align-middle">
  <div class="columns shrink mobile-100 phone-100">
    <div class="yoyo small semi text"><?php echo Lang::$word->TOTAL.': '.$this->pager->items_total;?> / <?php echo Lang::$word->CURPAGE.': '.$this->pager->current_page.' '.Lang::$word->OF.' '.$this->pager->num_pages;?></div>
  </div>
  <div class="columns mobile-100 phone-100 content-right mobile-content-left"><?php echo $this->pager->display_pages('small');?></div>
</div>