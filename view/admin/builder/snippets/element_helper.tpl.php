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
?>
<div id="element-helper" class="transition hidden">
  <div class="header">
    <i class="icon white note"></i>
    <h3 class="handle"> Element Editor</h3>
    <a class="close-editor"><i class="icon white delete"></i></a>
  </div>
  <div class="yoyo form wrapper" id="eleditor">
    <a class="yoyo small circular icon button elementRestore" data-tooltip="Restore" data-position="left center"><i class="icon undo"></i></a>
    <div data-field="elementButton" class="eltype">
      <h4>Edit Button</h4>
      <div class="yoyo block half fields">
        <div class="field">
          <label>Button text</label>
          <div class="yoyo small fluid icon input">
            <i class="icon wysiwyg type"></i>
            <input id="buttonText"  placeholder="Type something…" value="" type="text">
          </div>
        </div>
        <div class="field">
          <label>Button Size</label>
          <div class="content-center">
            <button name="buttonSize" type="button" class="yoyo small basic button" data-value="mini">Mini</button>
            <button name="buttonSize" type="button" class="yoyo small basic button" data-value="small">Small</button>
            <button name="buttonSize" type="button" class="yoyo small basic button" data-value="">Default</button>
            <button name="buttonSize" type="button" class="yoyo small basic button" data-value="big">Big</button>
          </div>
        </div>
        <div class="field">
          <label>Button Color</label>
          <div class="content-center">
            <button name="buttonColor" type="button" class="yoyo mini basic button" data-value="">default</button>
            <button name="buttonColor" type="button" class="yoyo mini basic button" data-value="primary">primary</button>
            <button name="buttonColor" type="button" class="yoyo mini basic button" data-value="secondary">secondary</button>
            <button name="buttonColor" type="button" class="yoyo mini basic button" data-value="positive">positive</button>
            <button name="buttonColor" type="button" class="yoyo mini basic button" data-value="negative">negative</button>
            <button name="buttonColor" type="button" class="yoyo mini basic button" data-value="white">white</button>
            <button name="buttonColor" type="button" class="yoyo mini basic button" data-value="basic">basic</button>
          </div>
        </div>
      </div>
      <div class="yoyo half fields">
        <div class="field">
          <label>Button Style</label>
          <button name="buttonStyle" type="button" class="yoyo small basic button" data-value="">&nbsp;</button>
          <button name="buttonStyle" type="button" class="yoyo small basic button rounded" data-value="rounded">&nbsp;</button>
          <button name="buttonStyle" type="button" class="yoyo basic icon button circular" data-value="circular"></button>
        </div>
        <div class="field">
          <label>Button Width</label>
          <button name="buttonWidth" type="button" class="yoyo small basic button" data-tooltip="Auto" data-value="">A</button>
          <button name="buttonWidth" type="button" class="yoyo small basic button" data-tooltip="Fluid" data-value="fluid">F</button>
        </div>
      </div>
      <div class="yoyo half fields">
        <div class="field">
          <label>Icon Spin</label>
          <button name="buttonSpin" type="button" class="yoyo small basic icon button" data-value="1"><i class="icon check"></i></button>
          <button name="buttonSpin" type="button" class="yoyo small basic icon button" data-value="0"><i class="icon close"></i></button>
        </div>
        <div class="field">
          <label>Icon Position</label>
          <button name="buttonIcon" type="button" class="yoyo small basic icon button" data-value="">
          <i class="icon chevron left"></i>
          </button>
          <button name="buttonIcon" type="button" class="yoyo small basic icon button" data-value="right">
          <i class="icon chevron right"></i></button>
        </div>
      </div>
      <div class="yoyo block half fields">
        <div class="field">
          <label>Button Type</label>
          <button name="buttonType" type="button" class="yoyo small basic button" data-value="a">Link</button>
          <button name="buttonType" type="button" class="yoyo small basic button active" data-value="button">Button</button>
        </div>
      </div>
    </div>
    <div data-field="elementLink" class="eltype">
      <h4>Edit Link</h4>
      <div class="yoyo block half fields">
        <div class="field">
          <label>Link</label>
          <div class="yoyo small fluid action right input">
            <input id="elementLink"  placeholder="http://" value="" type="text">
            <div class="yoyo white icon button" data-dropdown="#linkDrop">
              <i class="icon ellipsis vertical"></i></div>
            <div class="yoyo dropdown small menu pointing top-right" id="linkDrop">
              <a class="item" data-value="">none</a>
              <div class="basic divider"></div>
              <div class="scrolling"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div data-field="elementIconAssets" class="eltype">
      <div class="yoyo block half fields">
        <div class="field">
          <label>Icon Color</label>
          <div class="content-center">
            <button name="iconColor" type="button" class="yoyo circular icon button" data-value=""></button>
            <button name="iconColor" type="button" class="yoyo circular icon button primary" data-value="primary"></button>
            <button name="iconColor" type="button" class="yoyo circular icon button secondary" data-value="secondary"></button>
            <button name="iconColor" type="button" class="yoyo circular icon button positive" data-value="positive"></button>
            <button name="iconColor" type="button" class="yoyo circular icon button negative" data-value="negative"></button>
          </div>
        </div>
        <div class="field">
          <label>Icon Style</label>
          <div class="content-center">
            <button name="iconStyle" type="button" class="yoyo simple small icon button" data-value="circular"><i class="icon user circular"></i></button>
            <button name="iconStyle" type="button" class="yoyo simple small icon button" data-value="rounded"><i class="icon user rounded"></i></button>
            <button name="iconType" type="button" class="yoyo simple small icon button" data-value="inverted"><i class="icon user inverted"></i></button>
            <button name="iconType" type="button" class="yoyo simple small icon button" data-value="normal"><i class="icon user"></i></button>
          </div>
        </div>
        <div class="field">
          <label>Icon Size</label>
          <div class="content-center">
            <input class="rangers" type="text" value="0" name="iconSize" data-ranger='{"step":5,"from":-10, "to":20, "format":"size", "tip": false, "range":false}'>
          </div>
        </div>
      </div>
    </div>
    <div data-field="elementIcon" class="eltype">
      <h4>Icon</h4>
      <div class="yoyo block half fields">
        <div class="field">
          <label>Icons</label>
          <div id="elementIcon" style="height:400px" class="optiscroll">
            <?php include("icons.tpl.php");?>
          </div>
        </div>
      </div>
    </div>
    <div data-field="elementMap" class="eltype">
      <h4>Maps</h4>
      <div class="yoyo block half fields">
        <div class="field">
          <label>Google Map Url</label>
          <textarea name="elementMapUrl"></textarea>
        </div>
        <div class="field">
          <button name="elementMap" type="button" class="yoyo primary button">Update</button>
        </div>
        <div class="field">
          <p class="yoyo small secondary icon text middle"><i class="icon question sign"></i>Google map embed html code.</p>
        </div>
      </div>
    </div>
    <div data-field="elementVideo" class="eltype">
      <h4>Video</h4>
      <div class="yoyo block half fields">
        <div class="field">
          <label>Vimeo/Youtube/Dailymotion Url</label>
          <input type="text" name="elementVideoUrl" value="">
        </div>
        <div class="field">
          <button name="elementVideo" type="button" class="yoyo primary button">Update</button>
        </div>
        <div class="field">
          <p class="yoyo small secondary icon text middle"><i class="icon question sign"></i>Youtube, dailymotion or vimeo url.</p>
        </div>
      </div>
    </div>
    <div data-field="elementSound" class="eltype">
      <h4>Soundcloud</h4>
      <div class="yoyo block half fields">
        <div class="field">
          <label>Soundcloud Url</label>
          <input type="text" name="elementSoundUrl" value="">
        </div>
        <div class="field">
          <button name="elementSound" type="button" class="yoyo primary button">Update</button>
        </div>
        <div class="field">
          <p class="yoyo small secondary icon text middle"><i class="icon question sign"></i>Soundcloud share url.</p>
        </div>
      </div>
    </div>
    <div data-field="elementImage" class="eltype">
      <h4>Image</h4>
      <div class="yoyo block half fields">
        <div class="field">
          <label>Image Title</label>
          <input type="text" name="elementImageTitle" value="">
        </div>
        <div class="field">
          <label>Image Description</label>
          <input type="text" name="elementImageDesc" value="">
        </div>
        <div class="field">
          <label>Open in lightbox</label>
          <div class="yoyo checkbox toggle fitted inline">
            <input name="imageLightbox" type="checkbox" value="1" id="imageLightbox">
            <label for="imageLightbox"><?php echo Lang::$word->YES;?></label>
          </div>
        </div>
        <div class="yoyo divider"></div>
        <div class="field">
          <label>Image Style</label>
          <div class="row half-horizontal-gutters" id="imageStyles">
            <div class="columns"><span data-value="basic"><img src="<?php echo ADMINVIEW;?>/builder/images/blank_image.png" class="yoyo basic image"></span>
            </div>
            <div class="columns"><span data-value="rounded"><img src="<?php echo ADMINVIEW;?>/builder/images/blank_image.png" class="yoyo rounded image"></span>
            </div>
            <div class="columns"><span data-value="circular"><img src="<?php echo ADMINVIEW;?>/builder/images/blank_image.png" class="yoyo circular image"></span>
            </div>
            <div class="columns"><span data-value="shadow"><img src="<?php echo ADMINVIEW;?>/builder/images/blank_image.png" class="yoyo shadow image"></span>
            </div>
          </div>
        </div>
        <div class="yoyo divider"></div>
        <div class="field">
          <label>Image Filter</label>
          <div class="row half-gutters align-middle">
            <div class="columns screen-30"><span class="yoyo tiny semi text">Grayscale</span></div>
            <div class="columns screen-70">
              <input class="rangers" type="text" value="0" name="image_gs" data-ranger='{"step":1,"from":0, "to":100, "format":"%", "tip": false,"skin": "grayscale", "range":false}'>
            </div>
          </div>
          <div class="row half-gutters align-middle">
            <div class="columns screen-30"><span class="yoyo tiny semi text">Blur</span></div>
            <div class="columns screen-70">
              <input class="rangers" type="text" value="0" name="image_blur" data-ranger='{"step":1,"from":0, "to":10, "format":"px", "tip": false, "range":false}'>
            </div>
          </div>
          <div class="row half-gutters align-middle">
            <div class="columns screen-30"><span class="yoyo tiny semi text">Brightness</span></div>
            <div class="columns screen-70">
              <input class="rangers" type="text" value="100" name="image_br" data-ranger='{"step":1,"from":0, "to":200, "format":"%", "tip": false,"skin": "brightness", "range":false}'>
            </div>
          </div>
          <div class="row half-gutters align-middle">
            <div class="columns screen-30"><span class="yoyo tiny semi text">Contrast</span></div>
            <div class="columns screen-70">
              <input class="rangers" type="text" value="100" name="image_ct" data-ranger='{"step":1,"from":0, "to":200, "format":"%", "tip": false,"skin":"contrast", "range":false}'>
            </div>
          </div>
          <div class="row half-gutters align-middle">
            <div class="columns screen-30"><span class="yoyo tiny semi text">Hue</span></div>
            <div class="columns screen-70">
              <input class="rangers" type="text" value="0" name="image_hue" data-ranger='{"step":1,"from":0, "to":360, "format":"&deg;", "tip": false, "skin": "hue","range":false}'>
            </div>
          </div>
          <div class="row half-gutters align-middle">
            <div class="columns screen-30"><span class="yoyo tiny semi text">Opacity</span></div>
            <div class="columns screen-70">
              <input class="rangers" type="text" value="100" name="image_opacity" data-ranger='{"step":1,"from":0, "to":100, "format":"%", "tip": false, "skin": "opacity","range":false}'>
            </div>
          </div>
          <div class="row half-gutters align-middle">
            <div class="columns screen-30"><span class="yoyo tiny semi text">Invert</span></div>
            <div class="columns screen-70">
              <input class="rangers" type="text" value="0" name="image_invert" data-ranger='{"step":1,"from":0, "to":100, "format":"%", "tip": false, "skin":"invert","range":false}'>
            </div>
          </div>
          <div class="row half-gutters align-middle">
            <div class="columns screen-30"><span class="yoyo tiny semi text">Saturate</span></div>
            <div class="columns screen-70">
              <input class="rangers" type="text" value="100" name="image_saturate" data-ranger='{"step":1,"from":0, "to":500, "format":"%", "tip": false, "skin": "saturate", "range":false}'>
            </div>
          </div>
          <div class="row half-horizontal-gutters align-middle">
            <div class="columns screen-30"><span class="yoyo tiny semi text">Sepia</span></div>
            <div class="columns screen-70">
              <input class="rangers" type="text" value="0" name="image_sepia" data-ranger='{"step":1,"from":0, "to":100, "format":"%", "tip": false, "skin":"sepia","range":false}'>
            </div>
          </div>
          <div class="yoyo space divider"></div>
          <div class="row half-horizontal-gutters align-middle">
            <div class="columns screen-30"><span class="yoyo tiny semi text">Reset Filter</span></div>
            <div class="columns screen-70 content-right">
              <button name="imageFilterReset" type="button" class="yoyo small primary circular icon button"><i class="icon refresh"></i></button>
            </div>
          </div>
        </div>
        <div class="yoyo divider"></div>
        <div class="field">
          <label>Replace Image</label>
          <div id="imageWrap"><a>NO IMAGE</a>
          </div>
        </div>
      </div>
    </div>
    <div data-field="elementUrl" class="eltype">
      <h4>Link</h4>
      <div class="yoyo block half fields">
        <div class="field">
          <label>Url Title</label>
          <input type="text" name="elementUrlTitle" value="">
        </div>
        <div class="field">
          <label>Url</label>
          <input id="elementUrl" placeholder="http://" value="" type="url">
        </div>
        <div class="field">
          <label>Open in new tab</label>
          <div class="yoyo checkbox toggle fitted inline">
            <input name="urlTrget" type="checkbox" value="1" id="urlTrget">
            <label for="urlTrget"><?php echo Lang::$word->YES;?></label>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>