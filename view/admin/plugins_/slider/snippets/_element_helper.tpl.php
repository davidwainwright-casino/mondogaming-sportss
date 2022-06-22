<?php
  /**
   * Element Helper
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');
	  
  $images = File::findFiles(UPLOADS, array('fileTypes'=>array('jpg', 'png', 'svg'),'exclude'=>array('/thumbs/','/avatars/','/memberships/'),'returnType'=>'fullPath'));
?>

<div id="element-helper" class="transition hidden">
  <div class="header">
    <i class="icon white note"></i>
    <h3 class="handle"> Element Insert</h3>
    <a class="close-styler"><i class="icon white delete"></i></a>
  </div>
  <div class="sub-header">
    <div class="row">
      <div class="columns">
        <a data-tab="el_button" data-type="button" class="yoyo simple fluid button tbutton active"> Button</a>
      </div>
      <div class="columns">
        <a data-tab="el_icon" data-type="icon" class="yoyo simple fluid button tbutton">Icon</a>
      </div>
      <div class="columns">
        <a data-tab="el_image" data-type="image" class="yoyo simple fluid button tbutton"> Image</a>
      </div>
      <div class="columns">
        <a data-tab="el_text" data-type="text" class="yoyo simple fluid button tbutton"> Text</a>
      </div>
    </div>
  </div>
  <div style="max-height:400px;overflow:auto">
    <div id="el_button" class="yoyo tab active">
      <div class="row">
        <div class="columns half-padding content-center" id="buttons">
          <div class="half-padding"><a class="yoyo button" style="box-shadow:none"><span>Button Text</span></a>
          </div>
          <div class="half-padding"><a class="yoyo rounded button" style="box-shadow:none"><span>Button Text</span></a>
          </div>
          <div class="half-padding"><a class="yoyo button" style="box-shadow:none"><i class="icon check"></i><span>Button Text</span></a>
          </div>
          <div class="half-padding"><a class="yoyo rounded button" style="box-shadow:none"><i class="icon check"></i><span>Button Text</span></a>
          </div>
          <div class="half-padding"><a class="yoyo circular icon button" style="box-shadow:none"><i class="icon check"></i></a>
          </div>
          <div class="half-padding"><a class="yoyo labeled icon button" style="box-shadow:none">
              <i class="icon check"></i>
              <span>Button Text</span>
            </a>
          </div>
          <div class="half-padding"><a class="yoyo right labeled icon button" style="box-shadow:none">
              <span>Button Text</span>
              <i class="icon check"></i>
            </a>
          </div>
        </div>
        <div class="columns shrink half-padding">
          <h4>Colors</h4>
          <div class="yoyo space divider"></div>
          <p class="yoyo small bold text"> Background Color </p>
          <div class="yoyo space divider"></div>
          <div class="yoyo small fluid right action input">
            <input type="text" placeholder="Background Color" readonly>
            <button class="yoyo small basic icon button docolors" data-color="bg"><i class="icon apps"></i></button>
          </div>
          <div class="yoyo space divider"></div>
          <p class="yoyo small bold text"> Text Color </p>
          <div class="yoyo space divider"></div>
          <div class="yoyo small fluid right action input">
            <input type="text" placeholder="Text Color" readonly>
            <button class="yoyo small basic icon button docolors" data-color="text"><i class="icon apps"></i></button>
          </div>
          <div class="yoyo space divider"></div>
          <p class="yoyo small bold text"> Icon Color </p>
          <div class="yoyo space divider"></div>
          <div class="yoyo small fluid right action input">
            <input type="text" placeholder="Icon Color" readonly>
            <button class="yoyo small basic icon button docolors" data-color="icon"><i class="icon apps"></i></button>
          </div>
          <div class="yoyo space divider"></div>
          <p class="yoyo small bold text"> Button Text </p>
          <div class="yoyo space divider"></div>
          <div class="yoyo small fluid input">
            <input type="text" placeholder="Button Text" name="btext">
          </div>
          <div class="yoyo space divider"></div>
          <p class="yoyo small bold text"> Button Link </p>
          <div class="yoyo space divider"></div>
          <div class="yoyo small fluid input">
            <input type="text" placeholder="http://" name="burl">
          </div>
        </div>
      </div>
    </div>
    <div id="el_icon" class="yoyo tab">
      <div class="half-padding">
        <?php include(BASEPATH . '/view/admin/builder/snippets/icons.tpl.php');?>
      </div>
    </div>
    <div id="el_image" class="yoyo tab">
      <div class="half-padding">
        <div class="masonry small">
          <?php foreach ($images as $rows):?>
          <?php $file = str_replace(UPLOADS, UPLOADURL, $rows);?>
          <?php if(substr(strrchr($rows,'.'),1) == "svg"):?>
          <a class="item thumb" data-type="svg" data-src="<?php echo str_replace(BASEPATH, "", $rows);?>"><img src="<?php echo $file;?>"></a>
          <?php else:?>
          <?php if(File::is_File(UPLOADS . '/thumbs/' . basename($file))):?>
          <a class="item thumb" data-type="img"  data-src="<?php echo str_replace(BASEPATH, "", $rows);?>"><img src="<?php echo UPLOADURL . '/thumbs/' . basename($file);?>"></a>
          <?php endif;?>
          <?php endif;?>
          <?php endforeach;?>
        </div>
      </div>
    </div>
    <div id="el_text" class="yoyo tab">
      <div class="half-padding content-center">
        <div class="item">
          <h1>Welcome to Our Company</h1>
        </div>
        <div class="item">
          <h2>Welcome to Our Company</h2>
        </div>
        <div class="item">
          <h3>Welcome to Our Company</h3>
        </div>
        <div class="item">
          <h4>Welcome to Our Company</h4>
        </div>
        <div class="item">
          <p>Demonstrate relevant and engaging content but maximise share of voice. Target key demographics so that we make users into advocates.</p>
        </div>
      </div>
    </div>
  </div>
  <div class="padding">
    <div class="actions content-center">
      <button class="yoyo insert small primary button">insert</button>
    </div>
  </div>
</div>