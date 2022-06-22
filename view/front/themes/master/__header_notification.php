<div class="showtopnotes" style="display:none">
<a class="dchat" href="/livechat/php/app.php?widget-mobile"><?= Lang::$word->LIVE_CHAT_SUPPORT; ?></a>
			  <span class="noticeon">
			  <?php $ths = $this->core->noticef;
			  if(!empty($ths)){
				  echo $ths;echo '</br>';
				  $ncount = 1;
				  echo Lang::$word->READ_MORE . ' <a href="/page/noticeboard/">'. Lang::$word->HERE.'</a>';
			  }else{
				   echo Lang::$word->NO_NEW_NOTIFICATION_FOUND;
				   $ncount = 0;
			  }
			  ?></span><hr>
			   <span id="odd-switcher-wrapper">
			   <?= Lang::$word->ODDS_FORMAT; ?>
				    <select id="ThemeSelect" onchange="saveTheme(this.value);">
					 <option value="decimal" selected><?= Lang::$word->DECIMAL; ?></option>
					 <option value="fraction"><?= Lang::$word->FRACTION; ?></option>
					 <option value="american"><?= Lang::$word->AMERICAN; ?></option>
				    </select>
				</span>
				</br>
				<hr>
			<span class="locktime">
				<?= Lang::$word->YOUR_LOCAL_TIME_HAS_BEEN_SET_AT; ?> </br>
                <b class="timer"></b> (<b><?php $lt = $_COOKIE['localtime']; echo $lt/60;?> <?= Lang::$word->HOURS_GMT; ?></b>)</span>
				</br>
				</br>
				<hr>
				
				<!--Lang Switcher-->
        <?php if(Filter::isMobile()): ?>
        <div class="columns shrink">
          <a data-dropdown="#dropdown-langChange" class="yoyo mini secondary button">
          <div class="description">
		  <span class="flag icon en" id="repcl"></span> <span id="ltext" class="ttxt active"><?= Lang::$word->ENGLISH; ?> </span></div>
          <i class="icon small chevron down" id="cvvr"></i>
          </a>
          <div class="yoyo small dropdown menu top-center" id="dropdown-langChange">
		    <?php include_once('language_list.php');?>
          </div>
        </div>
        <?php endif;?>
        <!--Lang Switcher End if($this->core->showsearch)-->
		</br></br>
				
			 </div>
		    <div class="item xp">
			 <div href="#" class="topnotes">
			  <i class="icon bell"></i>
			  <span class="notifone"><?php $ncc = $ncount +1; echo $ncc;?></span>
			 </div>
            </div>