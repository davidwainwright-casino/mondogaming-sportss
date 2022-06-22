<?php
  /**
   * Image Class
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');

  class Image
  {

      public $quality = 90;

      protected $image;
      protected $filename;
      public $originalInfo;
      protected $width;
      protected $height;
      protected $imagestring;


      /**
       * Image::__construct()
       * 
       * @param mixed $filename
       * @param mixed $width
       * @param mixed $height
       * @param mixed $color
       * @return
       */
      public function __construct($filename = null, $width = null, $height = null, $color = null)
      {
		  ini_set('memory_limit', '512M');
          if ($filename) {
              $this->load($filename);
          } elseif ($width) {
              $this->create($width, $height, $color);
          }
          return $this;
      }


      /**
       * Image::__destruct()
       * 
       * @return
       */
      public function __destruct()
      {
          if ($this->image) {
              imagedestroy($this->image);
          }
      }

      /**
       * Image::autoOrient()
       * 
	   * Rotates and/or flips an image automatically so the orientation will be correct (based on exif 'Orientation')
       * @return
       */
      public function autoOrient()
      {

          switch ($this->originalInfo['exif']['Orientation']) {
              case 1:
                  // Do nothing
                  break;
              case 2:
                  // Flip horizontal
                  $this->flip('x');
                  break;
              case 3:
                  // Rotate 180 counterclockwise
                  $this->rotate(-180);
                  break;
              case 4:
                  // vertical flip
                  $this->flip('y');
                  break;
              case 5:
                  // Rotate 90 clockwise and flip vertically
                  $this->flip('y');
                  $this->rotate(90);
                  break;
              case 6:
                  // Rotate 90 clockwise
                  $this->rotate(90);
                  break;
              case 7:
                  // Rotate 90 clockwise and flip horizontally
                  $this->flip('x');
                  $this->rotate(90);
                  break;
              case 8:
                  // Rotate 90 counterclockwise
                  $this->rotate(-90);
                  break;
          }

          return $this;

      }

      /**
       * Image::bestFit()
       * 
	   * Best fit (proportionally resize to fit in specified width/height)
	   * Shrink the image proportionally to fit inside a $width x $height box
       * @param mixed $max_width
       * @param mixed $max_height
       * @return
       */
      public function bestFit($max_width, $max_height)
      {

          // If it already fits, there's nothing to do
          if ($this->width <= $max_width && $this->height <= $max_height) {
              return $this;
          }

          // Determine aspect ratio
          $aspect_ratio = $this->height / $this->width;

          // Make width fit into new dimensions
          if ($this->width > $max_width) {
              $width = $max_width;
              $height = $width * $aspect_ratio;
          } else {
              $width = $this->width;
              $height = $this->height;
          }

          // Make height fit into new dimensions
          if ($height > $max_height) {
              $height = $max_height;
              $width = $height / $aspect_ratio;
          }

          return $this->resize($width, $height);

      }

      /**
       * Image::blur()
       * 
       * @param string $type - selective|gaussian
       * @param integer $passes - Number of times to apply the filter
       * @return
       */
      public function blur($type = 'selective', $passes = 1)
      {
          switch (strtolower($type)) {
              case 'gaussian':
                  $type = IMG_FILTER_GAUSSIAN_BLUR;
                  break;
              default:
                  $type = IMG_FILTER_SELECTIVE_BLUR;
                  break;
          }
          for ($i = 0; $i < $passes; $i++) {
              imagefilter($this->image, $type);
          }
          return $this;
      }

      /**
       * Image::brightness()
       * 
       * @param mixed $level - Darkest = -255, Lightest = 255
       * @return
       */
      public function brightness($level)
      {
          imagefilter($this->image, IMG_FILTER_BRIGHTNESS, $this->keep_within($level, -255, 255));
          return $this;
      }

      /**
       * Image::contrast()
       * 
       * @param mixed $level - Min = -100, max = 100
       * @return
       */
      public function contrast($level)
      {
          imagefilter($this->image, IMG_FILTER_CONTRAST, $this->keep_within($level, -100, 100));
          return $this;
      }

      /**
       * Image::colorize()
       * 
       * @param mixed $color - Hex color string, array(red, green, blue) or array(red, green, blue, alpha).
       * @param mixed $opacity - Where red, green, blue - integers 0-255, alpha - integer 0-127
       * @return
       */
      public function colorize($color, $opacity)
      {
          $rgba = $this->normalize_color($color);
          $alpha = $this->keepWithin(127 - (127 * $opacity), 0, 127);
          imagefilter($this->image, IMG_FILTER_COLORIZE, $this->keep_within($rgba['r'], 0, 255), $this->keepWithin($rgba['g'], 0, 255), $this->keepWithin($rgba['b'], 0, 255), $alpha);
          return $this;
      }

      /**
       * Image::create()
       * 
	   * Create an image from scratch
       * @param mixed $width - Image width
       * @param mixed $height - If omitted - assumed equal to $width
       * @param mixed $color
       * @return
       */
      public function create($width, $height = null, $color = null)
      {

          $height = $height ? : $width;
          $this->width = $width;
          $this->height = $height;
          $this->image = imagecreatetruecolor($width, $height);
          $this->originalInfo = array(
              'width' => $width,
              'height' => $height,
              'orientation' => $this->getOrientation(),
              'exif' => null,
              'format' => 'png',
              'mime' => 'image/png');

          if ($color) {
              $this->fill($color);
          }

          return $this;

      }

      /**
       * Image::crop()
       * 
	   * Crop an image
       * @param mixed $x1 - Left
       * @param mixed $y1 - Top
       * @param mixed $x2 - Right
       * @param mixed $y2 - Bottom
       * @return
       */
      public function crop($x1, $y1, $x2, $y2)
      {

          // Determine crop size
          if ($x2 < $x1) {
              list($x1, $x2) = array($x2, $x1);
          }
          if ($y2 < $y1) {
              list($y1, $y2) = array($y2, $y1);
          }
          $crop_width = $x2 - $x1;
          $crop_height = $y2 - $y1;

          // Perform crop
          $new = imagecreatetruecolor($crop_width, $crop_height);
          imagealphablending($new, false);
          imagesavealpha($new, true);
          imagecopyresampled($new, $this->image, 0, 0, $x1, $y1, $crop_width, $crop_height, $crop_width, $crop_height);

          // Update meta data
          $this->width = $crop_width;
          $this->height = $crop_height;
          $this->image = $new;

          return $this;

      }

      /**
       * Image::desaturate()
       * 
       * @return
       */
      public function desaturate()
      {
          imagefilter($this->image, IMG_FILTER_GRAYSCALE);
          return $this;
      }

      /**
       * Image::edges()
       * 
       * @return
       */
      public function edges()
      {
          imagefilter($this->image, IMG_FILTER_EDGEDETECT);
          return $this;
      }

      /**
       * Image::emboss()
       * 
       * @return
       */
      public function emboss()
      {
          imagefilter($this->image, IMG_FILTER_EMBOSS);
          return $this;
      }

      /**
       * Image::fill()
       * 
       * @param string $color
       * @return
       */
      public function fill($color = '#000000')
      {

          $rgba = $this->normalizeColor($color);
          $fill_color = imagecolorallocatealpha($this->image, $rgba['r'], $rgba['g'], $rgba['b'], $rgba['a']);
          imagealphablending($this->image, false);
          imagesavealpha($this->image, true);
          imagefilledrectangle($this->image, 0, 0, $this->width, $this->height, $fill_color);

          return $this;

      }

      /**
       * Image::fitToHeight()
       * 
	   * Fit to height (proportionally resize to specified height)
       * @param mixed $height
       * @return
       */
      public function fitToHeight($height)
      {

          $aspect_ratio = $this->height / $this->width;
          $width = $height / $aspect_ratio;

          return $this->resize($width, $height);

      }

      /**
       * Image::fitToWidth()
       * 
	   * Fit to width (proportionally resize to specified width)
       * @param mixed $width
       * @return
       */
      public function fitToWidth($width)
      {

          $aspect_ratio = $this->height / $this->width;
          $height = $width * $aspect_ratio;

          return $this->resize($width, $height);

      }

      /**
       * Image::flip()
       * 
	   * Flip an image horizontally or vertically
       * @param mixed $direction - x/y
       * @return
       */
      public function flip($direction)
      {

          $new = imagecreatetruecolor($this->width, $this->height);
          imagealphablending($new, false);
          imagesavealpha($new, true);

          switch (strtolower($direction)) {
              case 'y':
                  for ($y = 0; $y < $this->height; $y++) {
                      imagecopy($new, $this->image, 0, $y, 0, $this->height - $y - 1, $this->width, 1);
                  }
                  break;
              default:
                  for ($x = 0; $x < $this->width; $x++) {
                      imagecopy($new, $this->image, $x, 0, $this->width - $x - 1, 0, 1, $this->height);
                  }
                  break;
          }

          $this->image = $new;

          return $this;

      }

      /**
       * Image::getWidth()
       * 
	    Get the current width
       * @return
       */
      public function getWidth()
      {
          return $this->width;
      }
	  
      /**
       * Image::getHeight()
       * 
	   * Get the current height
       * @return
       */
      public function getHeight()
      {
          return $this->height;
      }

      /**
       * Image::rientation()
       * 
	   * Get the current orientation
       * @return
       */
      public function getOrientation()
      {

          if (imagesx($this->image) > imagesy($this->image)) {
              return 'landscape';
          }

          if (imagesx($this->image) < imagesy($this->image)) {
              return 'portrait';
          }

          return 'square';

      }

      /**
       * Image::getOriginalInfo()
       * 
	   * Get info about the original image
       * @return
       */
      public function getOriginalInfo()
      {
          return $this->originalInfo;
      }

      /**
       * Image::invert()
       * 
       * @return
       */
      public function invert()
      {
          imagefilter($this->image, IMG_FILTER_NEGATE);
          return $this;
      }

      /**
       * Image::load()
       * 
       * @param mixed $filename - Path to image file
       * @return
       */
      public function load($filename)
      {

          // Require GD library
          if (!extension_loaded('gd')) {
			  Debug::AddMessage("errors", '<i>Exception</i>', 'Required extension GD is not loaded.', "session");
          }
          $this->filename = $filename;
          return $this->getMetaData();
      }

      /**
       * Image::loadBase64()
       * 
       * @param mixed $base64string
       * @return
       */
      public function loadBase64($base64string)
      {
          if (!extension_loaded('gd')) {
              Debug::AddMessage("errors", '<i>Exception</i>', 'Required extension GD is not loaded.', "session");
          }
          //remove data URI scheme and spaces from base64 string then decode it
          $this->imagestring = base64_decode(str_replace(' ', '+', preg_replace('#^data:image/[^;]+;base64,#', '', $base64string)));
          $this->image = imagecreatefromstring($this->imagestring);
          return $this->getMetaData();
      }

      /**
       * Image::meanRemove()
       * 
       * @return
       */
      public function meanRemove()
      {
          imagefilter($this->image, IMG_FILTER_MEAN_REMOVAL);
          return $this;
      }

      /**
       * Image::opacity()
       * 
	   * Changes the opacity level of the image
       * @param mixed $opacity - 0-1
       * @return
       */
      public function opacity($opacity)
      {

          // Determine opacity
          $opacity = $this->keepWithin($opacity, 0, 1) * 100;

          // Make a copy of the image
          $copy = imagecreatetruecolor($this->width, $this->height);
          imagealphablending($copy, false);
          imagesavealpha($copy, true);
          imagecopy($copy, $this->image, 0, 0, 0, 0, $this->width, $this->height);

          // Create transparent layer
          $this->create($this->width, $this->height, array(
              0,
              0,
              0,
              127));

          // Merge with specified opacity
          $this->imageCopyMergeAlpha($this->image, $copy, 0, 0, 0, 0, $this->width, $this->height, $opacity);
          imagedestroy($copy);

          return $this;

      }

      /**
       * Image::output()
       * 
	   * Outputs image without saving
       * @param mixed $format - If omitted or null - format of original file will be used, may be gif|jpg|png
       * @param mixed $quality - Output image quality in percents 0-100. Default 90
       * @return
       */
      public function output($format = null, $quality = null)
      {

          // Determine quality
          $quality = $quality ? : $this->quality;

          // Determine mimetype
          switch (strtolower($format)) {
              case 'gif':
                  $mimetype = 'image/gif';
                  break;
              case 'jpeg':
              case 'jpg':
                  imageinterlace($this->image, true);
                  $mimetype = 'image/jpeg';
                  break;
              case 'png':
                  $mimetype = 'image/png';
                  break;
              default:
                  $info = (empty($this->imagestring)) ? getimagesize($this->filename) : getimagesizefromstring($this->imagestring);
                  $mimetype = $info['mime'];
                  unset($info);
                  break;
          }

          // Output the image
          header('Content-Type: ' . $mimetype);
          switch ($mimetype) {
              case 'image/gif':
                  imagegif($this->image);
                  break;
              case 'image/jpeg':
                  imagejpeg($this->image, null, round($quality));
                  break;
              case 'image/png':
                  imagepng($this->image, null, round(9 * $quality / 100));
                  break;
              default:
				  Debug::AddMessage("errors", '<i>Exception</i>', 'Unsupported image format: ' . $this->filename, "session");
                  break;
          }

          // Since no more output can be sent, call the destructor to free up memory
          $this->__destruct();

      }

      /**
       * Image::outputBase64()
       * 
	   * Outputs image as data base64 to use as img src
       * @param mixed $format
       * @param mixed $quality
       * @return
       */
      public function outputBase64($format = null, $quality = null)
      {

          // Determine quality
          $quality = $quality ? : $this->quality;

          // Determine mimetype
          switch (strtolower($format)) {
              case 'gif':
                  $mimetype = 'image/gif';
                  break;
              case 'jpeg':
              case 'jpg':
                  imageinterlace($this->image, true);
                  $mimetype = 'image/jpeg';
                  break;
              case 'png':
                  $mimetype = 'image/png';
                  break;
              default:
                  $info = getimagesize($this->filename);
                  $mimetype = $info['mime'];
                  unset($info);
                  break;
          }

          // Output the image
          ob_start();
          switch ($mimetype) {
              case 'image/gif':
                  imagegif($this->image);
                  break;
              case 'image/jpeg':
                  imagejpeg($this->image, null, round($quality));
                  break;
              case 'image/png':
                  imagepng($this->image, null, round(9 * $quality / 100));
                  break;
              default:
				  Debug::AddMessage("errors", '<i>Exception</i>', 'Unsupported image format: ' . $this->filename, "session");
                  break;
          }
          $image_data = ob_get_contents();
          ob_end_clean();

          // Returns formatted string for img src
          return 'data:' . $mimetype . ';base64,' . base64_encode($image_data);

      }

      /**
       * Image::overlay()
       * 
	    Overlay an image on top of another, works with 24-bit PNG alpha-transparency
       * @param mixed $overlay - An image filename or a Image object
       * @param string $position - center|top|left|bottom|right|top left|top right|bottom left|bottom right
       * @param integer $opacity - Overlay opacity 0-1
       * @param integer $x_offset - Horizontal offset in pixels
       * @param integer $y_offset - Vertical offset in pixels
       * @return
       */
      public function overlay($overlay, $position = 'center', $opacity = 1, $x_offset = 0, $y_offset = 0)
      {

          // Load overlay image
          if (!($overlay instanceof Image)) {
              $overlay = new Image($overlay);
          }

          // Convert opacity
          $opacity = $opacity * 100;

          // Determine position
          switch (strtolower($position)) {
              case 'top left':
                  $x = 0 + $x_offset;
                  $y = 0 + $y_offset;
                  break;
              case 'top right':
                  $x = $this->width - $overlay->width + $x_offset;
                  $y = 0 + $y_offset;
                  break;
              case 'top':
                  $x = ($this->width / 2) - ($overlay->width / 2) + $x_offset;
                  $y = 0 + $y_offset;
                  break;
              case 'bottom left':
                  $x = 0 + $x_offset;
                  $y = $this->height - $overlay->height + $y_offset;
                  break;
              case 'bottom right':
                  $x = $this->width - $overlay->width + $x_offset;
                  $y = $this->height - $overlay->height + $y_offset;
                  break;
              case 'bottom':
                  $x = ($this->width / 2) - ($overlay->width / 2) + $x_offset;
                  $y = $this->height - $overlay->height + $y_offset;
                  break;
              case 'left':
                  $x = 0 + $x_offset;
                  $y = ($this->height / 2) - ($overlay->height / 2) + $y_offset;
                  break;
              case 'right':
                  $x = $this->width - $overlay->width + $x_offset;
                  $y = ($this->height / 2) - ($overlay->height / 2) + $y_offset;
                  break;
              case 'center':
              default:
                  $x = ($this->width / 2) - ($overlay->width / 2) + $x_offset;
                  $y = ($this->height / 2) - ($overlay->height / 2) + $y_offset;
                  break;
          }

          // Perform the overlay
          $this->imageCopyMergeAlpha($this->image, $overlay->image, $x, $y, 0, 0, $overlay->width, $overlay->height, $opacity);

          return $this;

      }

      /**
       * Image::pixelate()
       * 
       * @param integer $block_size
       * @return
       */
      public function pixelate($block_size = 10)
      {
          imagefilter($this->image, IMG_FILTER_PIXELATE, $block_size, true);
          return $this;
      }

      /**
       * Image::resize()
       * 
	   * Resize an image to the specified dimensions
       * @param mixed $width
       * @param mixed $height
       * @return
       */
      public function resize($width, $height)
      {

          // Generate new GD image
          $new = imagecreatetruecolor($width, $height);

          if ($this->originalInfo['format'] === 'gif') {
              // Preserve transparency in GIFs
              $transparent_index = imagecolortransparent($this->image);
              if ($transparent_index >= 0) {
                  $transparent_color = imagecolorsforindex($this->image, $transparent_index);
                  $transparent_index = imagecolorallocate($new, $transparent_color['red'], $transparent_color['green'], $transparent_color['blue']);
                  imagefill($new, 0, 0, $transparent_index);
                  imagecolortransparent($new, $transparent_index);
              }
          } else {
              // Preserve transparency in PNGs (benign for JPEGs)
              imagealphablending($new, false);
              imagesavealpha($new, true);
          }

          // Resize
          imagecopyresampled($new, $this->image, 0, 0, 0, 0, $width, $height, $this->width, $this->height);

          // Update meta data
          $this->width = $width;
          $this->height = $height;
          $this->image = $new;

          return $this;

      }

      /**
       * Image::rotate()
       * 
	   * Rotate an image
       * @param mixed $angle - 0-360
       * @param string $bg_color
       * @return
       */
      public function rotate($angle, $bg_color = '#000000')
      {

          // Perform the rotation
          $rgba = $this->normalizeColor($bg_color);
          $bg_color = imagecolorallocatealpha($this->image, $rgba['r'], $rgba['g'], $rgba['b'], $rgba['a']);
          $new = imagerotate($this->image, -($this->keepWithin($angle, -360, 360)), $bg_color);
          imagesavealpha($new, true);
          imagealphablending($new, true);

          // Update meta data
          $this->width = imagesx($new);
          $this->height = imagesy($new);
          $this->image = $new;

          return $this;

      }

      /**
       * Image::save()
       * 
	   * The resulting format will be determined by the file extension.
       * @param mixed $filename - If omitted - original file will be overwritten
       * @param mixed $quality - Output image quality in percents 0-100
       * @return
       */
      public function save($filename = null, $quality = null)
      {

          // Determine quality, filename, and format
          $quality = $quality ? : $this->quality;
          $filename = $filename ? : $this->filename;
          $format = $this->fileExt($filename) ? : $this->originalInfo['format'];

          // Create the image
          switch (strtolower($format)) {
              case 'gif':
                  $result = imagegif($this->image, $filename);
                  break;
              case 'jpg':
              case 'jpeg':
                  imageinterlace($this->image, true);
                  $result = imagejpeg($this->image, $filename, round($quality));
                  break;
              case 'png':
                  $result = imagepng($this->image, $filename, round(9 * $quality / 100));
                  break;
              default:
				  Debug::AddMessage("errors", '<i>Exception</i>', 'Unsupported image format: ', "session");
          }

          if (!$result) {
			  Debug::AddMessage("errors", '<i>Exception</i>', 'Unable to save image: ' . $filename, "session");
          }

          return $this;

      }

      /**
       * Image::sepia()
       * 
       * @return
       */
      public function sepia()
      {
          imagefilter($this->image, IMG_FILTER_GRAYSCALE);
          imagefilter($this->image, IMG_FILTER_COLORIZE, 100, 50, 0);
          return $this;
      }

      /**
       * Image::sketch()
       * 
       * @return
       */
      public function sketch()
      {
          imagefilter($this->image, IMG_FILTER_MEAN_REMOVAL);
          return $this;
      }

      /**
       * Image::smooth()
       * 
       * @param mixed $level - Min = -10, max = 10
       * @return
       */
      public function smooth($level)
      {
          imagefilter($this->image, IMG_FILTER_SMOOTH, $this->keepWithin($level, -10, 10));
          return $this;
      }

      /**
       * Image::text()
       * 
	   * Add text to an image
       * @param mixed $text
       * @param mixed $font_file
       * @param integer $font_size
       * @param string $color
       * @param string $position
       * @param integer $x_offset
       * @param integer $y_offset
       * @return
       */
      public function text($text, $font_file, $font_size = 12, $color = '#000000', $position = 'center', $x_offset = 0, $y_offset = 0)
      {

          $angle = 0;

          // Determine text color
          $rgba = $this->normalizeColor($color);
          $color = imagecolorallocatealpha($this->image, $rgba['r'], $rgba['g'], $rgba['b'], $rgba['a']);

          // Determine textbox size
          $box = imagettfbbox($font_size, $angle, $font_file, $text);
          if (!$box) {
			  Debug::AddMessage("errors", '<i>Exception</i>', 'Unable to load font: ' . $font_file, "session");
          }
          $box_width = abs($box[6] - $box[2]);
          $box_height = abs($box[7] - $box[1]);

          // Determine position
          switch (strtolower($position)) {
              case 'top left':
                  $x = 0 + $x_offset;
                  $y = 0 + $y_offset + $box_height;
                  break;
              case 'top right':
                  $x = $this->width - $box_width + $x_offset;
                  $y = 0 + $y_offset + $box_height;
                  break;
              case 'top':
                  $x = ($this->width / 2) - ($box_width / 2) + $x_offset;
                  $y = 0 + $y_offset + $box_height;
                  break;
              case 'bottom left':
                  $x = 0 + $x_offset;
                  $y = $this->height - $box_height + $y_offset + $box_height;
                  break;
              case 'bottom right':
                  $x = $this->width - $box_width + $x_offset;
                  $y = $this->height - $box_height + $y_offset + $box_height;
                  break;
              case 'bottom':
                  $x = ($this->width / 2) - ($box_width / 2) + $x_offset;
                  $y = $this->height - $box_height + $y_offset + $box_height;
                  break;
              case 'left':
                  $x = 0 + $x_offset;
                  $y = ($this->height / 2) - (($box_height / 2) - $box_height) + $y_offset;
                  break;
              case 'right';
                  $x = $this->width - $box_width + $x_offset;
                  $y = ($this->height / 2) - (($box_height / 2) - $box_height) + $y_offset;
                  break;
              case 'center':
              default:
                  $x = ($this->width / 2) - ($box_width / 2) + $x_offset;
                  $y = ($this->height / 2) - (($box_height / 2) - $box_height) + $y_offset;
                  break;
          }

          // Add the text
          imagettftext($this->image, $font_size, $angle, $x, $y, $color, $font_file, $text);

          return $this;

      }

      /**
       * Image::thumbnail()
       * 
	   * This function attempts to get the image to as close to the provided dimensions as possible, and then crops the
	   * remaining overflow (from the center) to get the image to be the size specified. Useful for generating thumbnails.
       * @param mixed $width
       * @param mixed $height
       * @return
       */
      public function thumbnail($width, $height = null)
      {

          // Determine height
          $height = $height ? : $width;

          // Determine aspect ratios
          $current_aspect_ratio = $this->height / $this->width;
          $new_aspect_ratio = $height / $width;

          // Fit to height/width
          if ($new_aspect_ratio > $current_aspect_ratio) {
              $this->fitToHeight($height);
          } else {
              $this->fitToWidth($width);
          }
          $left = floor(($this->width / 2) - ($width / 2));
          $top = floor(($this->height / 2) - ($height / 2));

          // Return trimmed image
          return $this->crop($left, $top, $width + $left, $height + $top);

      }

      /**
       * Image::fileExt()
       * 
	   * Returns the file extension of the specified file
       * @param mixed $filename
       * @return
       */
      protected function fileExt($filename)
      {

          if (!preg_match('/\./', $filename)) {
              return '';
          }

          return preg_replace('/^.*\./', '', $filename);

      }

      /**
       * Image::isImage()
       * 
	   * @param mixed $file
       * @return
       */
      public static function isImage($file)
      {
          $info = pathinfo($file);
          return in_array(strtolower($info['extension']), array("jpg", "jpeg", "gif", "png", "bmp"));
      }
	  
      /**
       * Image::getMetaData()
       * 
	   * Get meta data of image or base64 string
       * @return
       */
      protected function getMetaData()
      {
          //gather meta data
          if (empty($this->imagestring)) {
              $info = getimagesize($this->filename);

              switch ($info['mime']) {
                  case 'image/gif':
                      $this->image = imagecreatefromgif($this->filename);
                      break;
                  case 'image/jpeg':
                      $this->image = imagecreatefromjpeg($this->filename);
                      break;
                  case 'image/png':
                      $this->image = imagecreatefrompng($this->filename);
                      break;
                  default:
                      //throw new Exception('Invalid image: ' . $this->filename);
					  return false;
                      break;
              }
          } elseif (function_exists('getimagesizefromstring')) {
              $info = getimagesizefromstring($this->imagestring);
          } else {
			  Debug::AddMessage("errors", '<i>Exception</i>', 'PHP 5.4 is required to use method getimagesizefromstring', "session");
          }

          $this->originalInfo = array(
              'width' => $info[0],
              'height' => $info[1],
              'orientation' => $this->getOrientation(),
              'exif' => function_exists('exif_read_data') && $info['mime'] === 'image/jpeg' && $this->imagestring === null ? $this->exif = @exif_read_data($this->filename) : null,
              'format' => preg_replace('/^image\//', '', $info['mime']),
              'mime' => $info['mime']);
          $this->width = $info[0];
          $this->height = $info[1];

          imagesavealpha($this->image, true);
          imagealphablending($this->image, true);

          return $this;

      }

      /**
       * Image::imageCopyMergeAlpha()
       * 
	   * Same as PHP's imagecopymerge() function, except preserves alpha-transparency in 24-bit PNGs
       * @param mixed $dst_im
       * @param mixed $src_im
       * @param mixed $dst_x
       * @param mixed $dst_y
       * @param mixed $src_x
       * @param mixed $src_y
       * @param mixed $src_w
       * @param mixed $src_h
       * @param mixed $pct
       * @return
       */
      protected function imageCopyMergeAlpha($dst_im, $src_im, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h, $pct)
      {

          // Get image width and height and percentage
          $pct /= 100;
          $w = imagesx($src_im);
          $h = imagesy($src_im);

          // Turn alpha blending off
          imagealphablending($src_im, false);

          // Find the most opaque pixel in the image (the one with the smallest alpha value)
          $minalpha = 127;
          for ($x = 0; $x < $w; $x++) {
              for ($y = 0; $y < $h; $y++) {
                  $alpha = (imagecolorat($src_im, $x, $y) >> 24) & 0xFF;
                  if ($alpha < $minalpha) {
                      $minalpha = $alpha;
                  }
              }
          }

          // Loop through image pixels and modify alpha for each
          for ($x = 0; $x < $w; $x++) {
              for ($y = 0; $y < $h; $y++) {
                  // Get current alpha value (represents the TANSPARENCY!)
                  $colorxy = imagecolorat($src_im, $x, $y);
                  $alpha = ($colorxy >> 24) & 0xFF;
                  // Calculate new alpha
                  if ($minalpha !== 127) {
                      $alpha = 127 + 127 * $pct * ($alpha - 127) / (127 - $minalpha);
                  } else {
                      $alpha += 127 * $pct;
                  }
                  // Get the color index with new alpha
                  $alphacolorxy = imagecolorallocatealpha($src_im, ($colorxy >> 16) & 0xFF, ($colorxy >> 8) & 0xFF, $colorxy & 0xFF, $alpha);
                  // Set pixel with the new color + opacity
                  if (!imagesetpixel($src_im, $x, $y, $alphacolorxy)) {
                      return;
                  }
              }
          }

          // Copy it
          imagesavealpha($dst_im, true);
          imagealphablending($dst_im, true);
          imagesavealpha($src_im, true);
          imagealphablending($src_im, true);
          imagecopy($dst_im, $src_im, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h);

      }

      /**
       * Image::keepWithin()
       * 
	   * Ensures $value is always within $min and $max range.
	   * If lower, $min is returned. If higher, $max is returned.
       * @param mixed $value
       * @param mixed $min
       * @param mixed $max
       * @return
       */
      protected function keepWithin($value, $min, $max)
      {

          if ($value < $min) {
              return $min;
          }

          if ($value > $max) {
              return $max;
          }

          return $value;

      }

      /**
       * Image::normalizeColor()
       * 
	   * Converts a hex color value to its RGB equivalent
       * @param mixed $color
       * @return
       */
      protected function normalizeColor($color)
      {

          if (is_string($color)) {

              $color = trim($color, '#');

              if (strlen($color) == 6) {
                  list($r, $g, $b) = array(
                      $color[0] . $color[1],
                      $color[2] . $color[3],
                      $color[4] . $color[5]);
              } elseif (strlen($color) == 3) {
                  list($r, $g, $b) = array(
                      $color[0] . $color[0],
                      $color[1] . $color[1],
                      $color[2] . $color[2]);
              } else {
                  return false;
              }
              return array(
                  'r' => hexdec($r),
                  'g' => hexdec($g),
                  'b' => hexdec($b),
                  'a' => 0);

          } elseif (is_array($color) && (count($color) == 3 || count($color) == 4)) {

              if (isset($color['r'], $color['g'], $color['b'])) {
                  return array(
                      'r' => $this->keepWithin($color['r'], 0, 255),
                      'g' => $this->keepWithin($color['g'], 0, 255),
                      'b' => $this->keepWithin($color['b'], 0, 255),
                      'a' => $this->keepWithin(isset($color['a']) ? $color['a'] : 0, 0, 127));
              } elseif (isset($color[0], $color[1], $color[2])) {
                  return array(
                      'r' => $this->keepWithin($color[0], 0, 255),
                      'g' => $this->keepWithin($color[1], 0, 255),
                      'b' => $this->keepWithin($color[2], 0, 255),
                      'a' => $this->keepWithin(isset($color[3]) ? $color[3] : 0, 0, 127));
              }

          }
          return false;
      }

  }