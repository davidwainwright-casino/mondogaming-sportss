<?php
  /**
   * Style Helper
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');
?>
<div id="style-helper" class="transition hidden">
  <div class="header">
    <i class="icon white note"></i>
    <h3 class="handle"> Section Editor</h3>
    <a class="close-styler"><i class="icon white delete"></i></a>
  </div>
  <div class="yoyo form" id="seditor">
    <article class="yoyo accordion">
      <div class="header"><span>Padding</span>
        <i class="icon angle down"></i></div>
      <div class="content">
        <div class="yoyo block fields">
          <div class="field">
            <label>Padding Top</label>
            <input class="rangers" type="text" value="0" name="paddingTop" data-ranger='{"step":2,"from":0, "to":260, "format":"px", "tip": false, "range":false}'>
          </div>
          <div class="field">
            <label>Padding Bottom</label>
            <input class="rangers" type="text" value="0" name="paddingBottom" data-ranger='{"step":2,"from":0, "to":260, "format":"px", "tip": false, "range":false}'>
          </div>
        </div>
      </div>
    </article>
    <article class="yoyo accordion">
      <div class="header"><span>Margin</span><i class="icon angle down"></i></div>
      <div class="content">
        <div class="yoyo block fields">
          <div class="field">
            <label>Margin Top</label>
            <input class="rangers" type="text" value="0" name="marginTop" data-ranger='{"step":2,"from":0, "to":200, "format":"px", "tip": false, "range":false}'>
          </div>
          <div class="field">
            <label>Margin Bottom</label>
            <input class="rangers" type="text" value="0" name="marginBottom" data-ranger='{"step":2,"from":0, "to":200, "format":"px", "tip": false, "range":false}'>
          </div>
        </div>
      </div>
    </article>
    <article class="yoyo accordion">
      <div class="header"><span>Spacing</span><i class="icon angle down"></i></div>
      <div class="content">
        <div class="yoyo block fields">
          <div class="field">
            <label>Space Size</label>
            <div class="yoyo small checkbox radio fitted inline">
              <input name="space_size" type="radio" value="vertical" id="space_vertical" checked="checked">
              <label for="space_vertical">Vertical</label>
            </div>
            <div class="yoyo small checkbox radio fitted inline">
              <input name="space_size" type="radio" value="horizontal" id="space_horizontal">
              <label for="space_horizontal">Horizontal</label>
            </div>
            <div class="yoyo small checkbox radio fitted inline">
              <input name="space_size" type="radio" value="both" id="space_both">
              <label for="space_both">Both</label>
            </div>
          </div>
          <div class="field">
            <label>Space Width</label>
            <input class="rangers" type="text" value="0" name="spaceWidth" data-ranger='{"step":1,"from":0, "to":3, "format":"wide", "tip": false, "range":false}'>
          </div>
        </div>
      </div>
    </article>
    <article class="yoyo accordion">
      <div class="header"><span>Border</span><i class="icon angle down"></i></div>
      <div class="content">
        <div class="yoyo block fields">
          <div class="field">
            <label>Rounded Corners</label>
            <div id="bRadius">
              <div class="bRadiusWrap">
                <input class="styler small" value="" data-corner="borderTopLeftRadius" placeholder="0px" type="text">
                <input class="styler small" value="" data-corner="borderBottomLeftRadius" placeholder="0px" type="text">
              </div>
              <div class="bRadiusWrap">
                <div id="bRadiusView" class="inner">
                  <button id="bRadiusAll" class="yoyo small grey icon button" type="button"><i class="icon url alt"></i></button>
                </div>
              </div>
              <div class="bRadiusWrap">
                <input class="styler small" value="" data-corner="borderTopRightRadius" placeholder="0px" type="text">
                <input class="styler small" value="" data-corner="borderBottomRightRadius" placeholder="0px" type="text">
              </div>
            </div>
          </div>
          <div class="field">
            <label>Border Styles</label>
            <div id="borderStyle" class="half-padding content-center">
              <button name="borderSolid" data-type="solid" class="yoyo small white icon button"><i class="icon wysiwyg border solid"></i></button>
              <button name="borderDashed" data-type="dashed" class="yoyo small white icon button"><i class="icon wysiwyg border dashed"></i></button>
              <button name="borderDotted" data-type="dotted" class="yoyo small white icon button"><i class="icon wysiwyg border dotted"></i></button>
              <button name="borderDouble" data-type="double" class="yoyo small white icon button"><i class="icon wysiwyg border double"></i></button>
              <button name="borderNone" data-type="none" class="yoyo small white icon button"><i class="icon delete"></i></button>
            </div>
            <div id="bBorder">
              <div id="bBorderView" class="inner"></div>
            </div>
          </div>
          <div class="field">
            <label>Border Width</label>
            <input class="rangers" type="text" value="0" name="borderWidth" data-ranger='{"step":1,"from":0, "to":50, "format":"px", "tip": false, "range":false}'>
          </div>
          <div class="field">
            <label>Border Color</label>
            <div id="borderColor" class="yoyo colorpicker"></div>
          </div>
        </div>
      </div>
    </article>
    <article class="yoyo accordion">
      <div class="header"><span>Box Shadow</span><i class="icon angle down"></i></div>
      <div class="content">
        <div class="yoyo half fields content-center">
          <div class="field"><a class="bShadow preset1"><span><i class="icon ban"></i></span></a>
          </div>
          <div class="field"><a class="bShadow preset2"><span></span></a>
          </div>
          <div class="field"><a class="bShadow preset3"><span></span></a>
          </div>
          <div class="field"><a class="bShadow preset4"><span></span></a>
          </div>
        </div>
        <div class="yoyo half fields content-center">
          <div class="field"><a class="bShadow preset5"><span></span></a>
          </div>
          <div class="field"><a class="bShadow preset6"><span></span></a>
          </div>
          <div class="field"><a class="bShadow preset7"><span></span></a>
          </div>
          <div class="field"><a class="bShadow preset8"><span></span></a>
          </div>
        </div>
        <div class="yoyo block fields">
          <div class="field">
            <label>Box Shadow Horizontal Position</label>
            <input class="rangers" type="text" value="0" name="boxShadowHorizontal" data-ranger='{"step":1,"from":-80, "to":80, "format":"px", "tip": false, "range":false}'>
          </div>
          <div class="field">
            <label>Box Shadow Vertical Position</label>
            <input class="rangers" type="text" value="0" name="boxShadowVertical" data-ranger='{"step":1,"from":-80, "to":80, "format":"px", "tip": false, "range":false}'>
          </div>
          <div class="field">
            <label>Box Shadow Blur Strength</label>
            <input class="rangers" type="text" value="0" name="boxShadowBlur" data-ranger='{"step":1,"from":0, "to":80, "format":"px", "tip": false, "range":false}'>
          </div>
          <div class="field">
            <label>Box Shadow Spread Strength</label>
            <input class="rangers" type="text" value="0" name="boxShadowSpread" data-ranger='{"step":1,"from":-80, "to":80, "format":"px", "tip": false, "range":false}'>
          </div>
          <div class="field">
            <label>Box Shadow Position</label>
            <div class="content-center">
              <button name="shadowInset" data-tooltip="Inner Shadow" data-type="inset" class="yoyo small white icon button"><i class="icon wysiwyg border inset"></i></button>
              <button name="shadowOutset" data-tooltip="Outer Shadow" data-type="outset" class="yoyo small white icon button active"><i class="icon wysiwyg border outset"></i></button>
            </div>
          </div>
          <div class="field">
            <label>Box Shadow Color</label>
            <div id="shadowColor" class="yoyo colorpicker"></div>
          </div>
        </div>
      </div>
    </article>
    <article class="yoyo accordion">
      <div class="header"><span>Background</span><i class="icon angle down"></i></div>
      <div class="content">
        <div class="yoyo block fields">
          <div class="field">
            <button name="bgColor" data-tab="bgColor" data-tooltip="Color" data-type="color" class="yoyo small white icon button"><i class="icon wysiwyg color"></i></button>
            <button name="bgGradient" data-tab="bgGradient" data-tooltip="Gradient" data-type="gradient" class="yoyo small white icon button"><i class="icon wysiwyg gradient"></i></button>
            <button name="bgImage" data-tab="bgImage" data-tooltip="Image" data-type="image" class="yoyo small white icon button"><i class="icon wysiwyg photo"></i></button>
            <button name="bgNone" data-tooltip="None" data-type="none" class="yoyo small white icon button"><i class="icon ban"></i></button>
          </div>
        </div>
        <div id="bgColor" class="yoyo tab active">
          <div class="yoyo block fields">
            <div class="field">
              <label>Background Color</label>
              <div id="backgroundColor" class="yoyo colorpicker"></div>
            </div>
          </div>
        </div>
        <div id="bgGradient" class="yoyo tab">
          <div class="yoyo block fields">
            <div class="field">
              <label>Start Color</label>
              <div id="gradColorStart" class="yoyo colorpicker"></div>
            </div>
            <div class="field">
              <label>Stop Color</label>
              <div id="gradColorStop" class="yoyo colorpicker"></div>
            </div>
            <div class="field">
              <label>Start Position</label>
              <input class="rangers" type="text" value="0" name="gradientStart" data-ranger='{"step":1,"from":0, "to":100, "format":"%", "tip": false, "range":false}'>
            </div>
            <div class="field">
              <label>End Position</label>
              <input class="rangers" type="text" value="0" name="gradientStop" data-ranger='{"step":1,"from":0, "to":100, "format":"%", "tip": false, "range":false}'>
            </div>
            <div class="field">
              <label>Direction</label>
              <input class="rangers" type="text" value="0" name="gradientDeg" data-ranger='{"step":1,"from":0, "to":360, "format":"&deg;", "tip": false, "range":false}'>
            </div>
          </div>
        </div>
        <div id="bgImage" class="yoyo tab">
          <div class="yoyo block fields">
            <div class="field">
              <label>Paralax Effect</label>
              <div class="yoyo checkbox toggle fitted inline">
                <input name="imageParalax" type="checkbox" value="1" id="imageParalax">
                <label for="imageParalax"><?php echo Lang::$word->YES;?></label>
              </div>
            </div>
          </div>
          <div id="bgImageWrap"><a>NO IMAGE</a>
          </div>
        </div>
      </div>
    </article>
    <a class="yoyo fluid positive button sectionRestore">Restore</a>
  </div>
</div>