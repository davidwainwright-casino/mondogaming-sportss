<?php
  /**
   * Load Language Section
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');
?>
<?php
  $i = 0;
  $html = '';

  switch ($this->type):
      case "plugins":
      case "modules":
          foreach ($this->xmlel as $pkey):
              $i++;
              $html .= '
		  <div class="item">
			<div class="content"><span data-editable="true" data-set=\'{"type": "phrase", "id": ' . $i . ',"key":"' . $pkey['data'] .
                  '", "path":"' . $this->abbr . '/' . $this->fpath . '"}\'>' . $pkey . '</span></div>
			<div class="content shrink"><span class="yoyo small tiny label">' . $pkey['data'] . '</span></div>
		  </div>';
          endforeach;
          break;

      case "filter":
          foreach ($this->section as $pkey):
              $i++;
              $html .= '
		  <div class="item">
			<div class="content"><span data-editable="true" data-set=\'{"type": "phrase", "id": ' . $i . ',"key":"' . $pkey['data'] .
                  '", "path":"' . $this->abbr . '/lang.xml"}\'>' . $pkey . '</span></div>
			<div class="content shrink"><span class="yoyo small tiny label">' . $pkey['data'] . '</span></div>
		  </div>';
          endforeach;
          break;

      default:
          foreach ($this->xmlel as $pkey):
              $i++;
              $html .= '
		  <div class="item">
			<div class="content"><span data-editable="true" data-set=\'{"type": "phrase", "id": ' . $i . ',"key":"' . $pkey['data'] .
                  '", "path":"' . $this->abbr . '/lang.xml"}\'>' . $pkey . '</span></div>
			<div class="content shrink"><span class="yoyo small tiny label">' . $pkey['data'] . '</span></div>
		  </div>';
          endforeach;
          break;

  endswitch;
  echo $html;