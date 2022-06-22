function CInterface(){
    var _oAudioToggle;
    var _oButExit;
    var _oButMin;
    var _oButPlus;
    var _oMoneyDisplay;
    var _oBetDisplay;
    var _oButPlay1;
    var _oButPlay5;
    var _oButUndo;
    var _oButClear;
    var _oButFullscreen;
    var _fRequestFullScreen = null;
    var _fCancelFullScreen = null;
    
    var _pStartPosExit;
    var _pStartPosAudio;
    var _pStartPosFullscreen;
    
    this._init = function(){                
        var oExitX;        
        
        var oSprite = s_oSpriteLibrary.getSprite('but_exit');
        _pStartPosExit = {x: CANVAS_WIDTH - (oSprite.height/2)- 20, y: (oSprite.height/2) + 10};
        _oButExit = new CGfxButton(_pStartPosExit.x, _pStartPosExit.y, oSprite,true);
        _oButExit.addEventListener(ON_MOUSE_UP, this._onExit, this);
        
        oExitX = CANVAS_WIDTH - (oSprite.width/2) - 100;
        _pStartPosAudio = {x: oExitX-15, y: (oSprite.height/2) + 10};
        
        if(DISABLE_SOUND_MOBILE === false || s_bMobile === false){
            var oSprite = s_oSpriteLibrary.getSprite('audio_icon');
            _oAudioToggle = new CToggle(_pStartPosAudio.x,_pStartPosAudio.y,oSprite,s_bAudioActive);
            _oAudioToggle.addEventListener(ON_MOUSE_UP, this._onAudioToggle, this);          
        }
        
        var doc = window.document;
        var docEl = doc.documentElement;
        _fRequestFullScreen = docEl.requestFullscreen || docEl.mozRequestFullScreen || docEl.webkitRequestFullScreen || docEl.msRequestFullscreen;
        _fCancelFullScreen = doc.exitFullscreen || doc.mozCancelFullScreen || doc.webkitExitFullscreen || doc.msExitFullscreen;
        
        if(ENABLE_FULLSCREEN === false){
            _fRequestFullScreen = false;
        }
        
        if (_fRequestFullScreen && screenfull.enabled){
            oSprite = s_oSpriteLibrary.getSprite('but_fullscreen');
            _pStartPosFullscreen = {x:oSprite.width/4 + 10,y:oSprite.height/2 + 10};

            _oButFullscreen = new CToggle(_pStartPosFullscreen.x,_pStartPosFullscreen.y,oSprite,s_bFullscreen,true);
            _oButFullscreen.addEventListener(ON_MOUSE_UP, this._onFullscreenRelease, this);
        }
        
        var oSprite = s_oSpriteLibrary.getSprite('money_panel');
        _oMoneyDisplay = new CDisplayPanel(370,CANVAS_HEIGHT - 225,oSprite,TEXT_CURRENCY +START_PLAYER_MONEY,PRIMARY_FONT,"#ffffff",40);

        var oSprite = s_oSpriteLibrary.getSprite('plus_display');
        _oBetDisplay = new CDisplayPanel(480,CANVAS_HEIGHT - 130,oSprite,"$1",PRIMARY_FONT,"#ffffff",40, false, s_oStage);

        var oSprite = s_oSpriteLibrary.getSprite('but_plus');
        _oButPlus = new CTextToggle(638,CANVAS_HEIGHT - 130,oSprite,TEXT_PLUS,PRIMARY_FONT,"#ffffff",70, s_oStage);
        _oButPlus.enable();
        _oButPlus.setTextPosition(0,20);
        _oButPlus.addEventListener(ON_MOUSE_UP, this._onButPlusRelease, this);

        var oSprite = s_oSpriteLibrary.getSprite('but_plus');
        _oButMin = new CTextToggle(320,CANVAS_HEIGHT - 130,oSprite,TEXT_MIN,PRIMARY_FONT,"#ffffff",70, s_oStage);
        _oButMin.enable();
        _oButMin.setTextPosition(0,20);
        _oButMin.setScaleX(-1);
        _oButMin.addEventListener(ON_MOUSE_UP, this._onButMinRelease, this);

        var oSprite = s_oSpriteLibrary.getSprite('but_generic');
        _oButPlay1 = new CTextToggle(820,CANVAS_HEIGHT - 130,oSprite, TEXT_PLAY1,PRIMARY_FONT,"#ffffff",30, s_oStage);
        _oButPlay1.disable();
        _oButPlay1.setTextPosition(0,10);
        _oButPlay1.addEventListener(ON_MOUSE_UP, this._onPlay1, this);
        
        var oSprite = s_oSpriteLibrary.getSprite('but_generic');
        _oButPlay5 = new CTextToggle(1060,CANVAS_HEIGHT - 130,oSprite, TEXT_PLAY5,PRIMARY_FONT,"#ffffff",30, s_oStage);
        _oButPlay5.disable();
        _oButPlay5.setTextPosition(0,10);
        _oButPlay5.addEventListener(ON_MOUSE_UP, this._onPlay5, this);

        var oSprite = s_oSpriteLibrary.getSprite('but_generic');
        _oButUndo = new CTextToggle(1300,CANVAS_HEIGHT - 130,oSprite, TEXT_UNDO,PRIMARY_FONT,"#ffffff",30, s_oStage);
        _oButUndo.enable();
        _oButUndo.setTextPosition(0,10);
        _oButUndo.addEventListener(ON_MOUSE_UP, this._onUndo, this);
        
        var oSprite = s_oSpriteLibrary.getSprite('but_generic');
        _oButClear = new CTextToggle(1540,CANVAS_HEIGHT - 130,oSprite, TEXT_CLEAR,PRIMARY_FONT,"#ffffff",30, s_oStage);
        _oButClear.enable();
        _oButClear.setTextPosition(0,10);
        _oButClear.addEventListener(ON_MOUSE_UP, this._onClear, this);
       
       this.refreshButtonPos(s_iOffsetX,s_iOffsetY);
    };
    
    this.unload = function(){
        if(DISABLE_SOUND_MOBILE === false || s_bMobile === false){
            _oAudioToggle.unload();
            _oAudioToggle = null;
        }
        _oButExit.unload();
        _oMoneyDisplay.unload();
        _oBetDisplay.unload();
        _oButMin.unload();
        _oButPlus.unload();
        _oButPlay1.unload();
        _oButPlay5.unload();
        _oButUndo.unload();
        _oButClear.unload();
        
        if (_fRequestFullScreen && screenfull.enabled){
            _oButFullscreen.unload();
        }
        
        s_oInterface = null;
        
    };
    
    this.refreshButtonPos = function(iNewX,iNewY){
        _oButExit.setPosition(_pStartPosExit.x - iNewX,iNewY + _pStartPosExit.y);
        if(DISABLE_SOUND_MOBILE === false || s_bMobile === false){
            _oAudioToggle.setPosition(_pStartPosAudio.x - iNewX,iNewY + _pStartPosAudio.y);
        }
        if (_fRequestFullScreen && screenfull.enabled){
            _oButFullscreen.setPosition(_pStartPosFullscreen.x + iNewX,_pStartPosFullscreen.y + iNewY);
        }
    };

    this.refreshBet = function(szValue){
        _oBetDisplay.setText(TEXT_CURRENCY + szValue);
    };

    this.refreshMoney = function(szValue){
        _oMoneyDisplay.setText(TEXT_CURRENCY + szValue)
    };
    
    this.enablePlus = function(bVal){
        if(bVal){
            _oButPlus.enable();
        }else {
            _oButPlus.disable();
        }        
    };
    
    this.enableMin = function(bVal){
        if(bVal){
            _oButMin.enable();
        }else {
            _oButMin.disable();
        } 
    };

    this.enablePlay1 = function(bVal){
        if(bVal){
            _oButPlay1.enable();
        }else {
            _oButPlay1.disable();
        }        
    };

    this.enablePlay5 = function(bVal){
        if(bVal){
            _oButPlay5.enable();
        }else {
            _oButPlay5.disable();
        }
    };
    
    this.enableUndo = function(bVal){
        if(bVal){
            _oButUndo.enable();
        }else {
            _oButUndo.disable();
        }
    };

    this.enableClear = function(bVal){
        if(bVal){
            _oButClear.enable();
        }else {
            _oButClear.disable();
        }
    };

    this.enableAllButton = function(bVal){
        this.enablePlus(bVal);
        this.enableMin(bVal);
        this.enablePlay1(bVal);
        this.enablePlay5(bVal);
        this.enableUndo(bVal);
        this.enableClear(bVal);
        
    };

    this._onClear = function(){
        s_oGame.clearSelection();
    };
    
    this._onUndo = function(){
        s_oGame.undoSelection();
    };
    
    this._onButPlusRelease = function(){
        s_oGame.selectBet("add");
    };
    
    this._onButMinRelease = function(){
        s_oGame.selectBet("remove");
    };
    
    this._onPlay1 = function(){
        s_oGame.play();
    };
    
    this._onPlay5 = function(){
        s_oGame.play5();
    };
    
    this._onAudioToggle = function(){
        Howler.mute(s_bAudioActive);
        s_bAudioActive = !s_bAudioActive;
    };
    
    this.resetFullscreenBut = function(){
	if (_fRequestFullScreen && screenfull.enabled){
		_oButFullscreen.setActive(s_bFullscreen);
	}
    };
    
    this._onFullscreenRelease = function(){
        if(s_bFullscreen) { 
		_fCancelFullScreen.call(window.document);
	}else{
		_fRequestFullScreen.call(window.document.documentElement);
	}
	
	sizeHandler();
    };
    
    this._onExit = function(){
        $(s_oMain).trigger("end_session");
        
        var iCurPlayerMoney = s_oGame.getCurMoney();
        $(s_oMain).trigger("share_event",iCurPlayerMoney);
        
      s_oGame.onExit();  
    };
    
    s_oInterface = this;
    
    this._init();
    
    return this;
}

var s_oInterface = null;