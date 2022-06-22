function CInterface(iMoney,iBet){
    var _pStartPosAudio;
    var _pStartPosExit;
    var _pStartPosFullscreen;
    
    var _oButExit;
    var _oArrowLeft;
    var _oArrowRight;
    var _oBetOneBut;
    var _oBetMaxBut;
    var _oDealBut;
    var _oAudioToggle;
    var _oMoneyText;
    var _oWinText;
    var _oBetText;
    var _oTotBetText;
    var _oLosePanel;
    var _oButFullscreen;
    var _fRequestFullScreen = null;
    var _fCancelFullScreen = null;
    
    this._init = function(iMoney,iBet){
        
        var oSprite = s_oSpriteLibrary.getSprite('but_exit');
        _pStartPosExit = {x:CANVAS_WIDTH - (oSprite.width/2) - 2,y:(oSprite.height/2) + 2};
        _oButExit = new CGfxButton(_pStartPosExit.x,_pStartPosExit.y,oSprite,s_oStage);
        _oButExit.addEventListener(ON_MOUSE_UP, this._onExit, this);
        
        
        if(DISABLE_SOUND_MOBILE === false || s_bMobile === false){
            _pStartPosAudio = {x:_oButExit.getX() - oSprite.width,y:(oSprite.height/2) + 2};
            _oAudioToggle = new CToggle(_pStartPosAudio.x,_pStartPosAudio.y,s_oSpriteLibrary.getSprite('audio_icon'),s_bAudioActive);
            _oAudioToggle.addEventListener(ON_MOUSE_UP, this._onAudioToggle, this);
            
            _pStartPosFullscreen = {x:_pStartPosAudio.x - oSprite.width - 10,y:_pStartPosAudio.y};
        }else{
            _pStartPosFullscreen = {x:_oButExit.getX() - oSprite.width - 10,y:(oSprite.height/2) + 2};
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
            _oButFullscreen = new CToggle(_pStartPosFullscreen.x,_pStartPosFullscreen.y,oSprite,s_bFullscreen,true);
            _oButFullscreen.addEventListener(ON_MOUSE_UP, this._onFullscreenRelease, this);
        }
        
        var oDisplayWin = createBitmap(s_oSpriteLibrary.getSprite('display_bg'));
        oDisplayWin.x = 480;
        oDisplayWin.y = 25;
        s_oStage.addChild(oDisplayWin);
        
        var oWinTextBg = new CTLText(s_oStage, 
                    470, 20, 100, 22, 
                    22, "left", "#fff", FONT1, 1,
                    0, 0,
                    TEXT_WIN,
                    true, true, false,
                    false );
        
        var oDisplayBet = createBitmap(s_oSpriteLibrary.getSprite('display_bg'));
        oDisplayBet.x = 480;
        oDisplayBet.y = 93;
        s_oStage.addChild(oDisplayBet);
        
        var oBetTextBg = new CTLText(s_oStage, 
                    470, 90, 100, 22, 
                    22, "left", "#fff", FONT1, 1,
                    0, 0,
                    TEXT_BET,
                    true, true, false,
                    false );
        
        var oDisplayMoney = createBitmap(s_oSpriteLibrary.getSprite('display_bg'));
        oDisplayMoney.x = 470;
        oDisplayMoney.y = 687;
        s_oStage.addChild(oDisplayMoney);
        
        var oMoneyTextBg = new CTLText(s_oStage, 
                    460, 685, 100, 22, 
                    22, "left", "#fff", FONT1, 1,
                    0, 0,
                    TEXT_MONEY,
                    true, true, false,
                    false );
	
	_oMoneyText = new CTLText(s_oStage, 
                    480, CANVAS_HEIGHT - 66, 210, 30, 
                    30, "center", "#ffde00", FONT2, 1,
                    0, 0,
                    iMoney.toFixed(2)+TEXT_CURRENCY,
                    true, true, false,
                    false );
        
        _oBetText = new CTLText(s_oStage, 
                    490, 110, 210, 30, 
                    30, "center", "#ffde00", FONT2, 1,
                    0, 0,
                    iBet.toFixed(2)+TEXT_CURRENCY,
                    true, true, false,
                    false );
        
        _oWinText = new CTLText(s_oStage, 
                    490, 40, 210, 30, 
                    30, "center", "#ffde00", FONT2, 1,
                    0, 0,
                    "0"+TEXT_CURRENCY,
                    true, true, false,
                    false );
        
        var oBigDisplay = createBitmap(s_oSpriteLibrary.getSprite('big_display'));
        oBigDisplay.x = 770;
        oBigDisplay.y = 686;
        s_oStage.addChild(oBigDisplay);
        
        _oTotBetText = new CTLText(s_oStage, 
                    780, 700, 108, 40, 
                    40, "center", "#ffde00", FONT2, 1,
                    0, 0,
                    iBet.toFixed(2)+TEXT_CURRENCY,
                    true, true, false,
                    false );
        
        var oSprite = s_oSpriteLibrary.getSprite('logo_game');
        var oLogo = createBitmap(oSprite);
        oLogo.x = CANVAS_WIDTH/2;
        oLogo.y = 17;
        oLogo.regX = oSprite.width/2;
        s_oStage.addChild(oLogo);
        
        var oSprite = s_oSpriteLibrary.getSprite('but_left');
        _oArrowLeft = new CGfxButton(744,722,oSprite,s_oStage);
        _oArrowLeft.addEventListener(ON_MOUSE_UP, this._onButLeftRelease, this);

        oSprite = s_oSpriteLibrary.getSprite('but_right');
        _oArrowRight = new CGfxButton(930,722,oSprite,s_oStage);
        _oArrowRight.addEventListener(ON_MOUSE_UP, this._onButRightRelease, this);
        
        oSprite = s_oSpriteLibrary.getSprite('but_game_bg');
        _oBetOneBut = new CTextButton(1040,716,oSprite,TEXT_BET_ONE,FONT1,"#ffffff",23,0,s_oStage);
        _oBetOneBut.addEventListener(ON_MOUSE_UP, this._onButBetOneRelease, this);
        
        _oBetMaxBut = new CTextButton(1190,716,oSprite,TEXT_MAX_BET,FONT1,"#ffffff",23,0,s_oStage);
        _oBetMaxBut.addEventListener(ON_MOUSE_UP, this._onButBetMaxRelease, this);
        
        _oDealBut = new CTextButton(1340,716,oSprite,TEXT_DEAL,FONT1,"#ffffff",30,0,s_oStage);
        _oDealBut.addEventListener(ON_MOUSE_UP, this._onButDealRelease, this);
        
        _oLosePanel = new createjs.Container();
        _oLosePanel.visible = false;
        _oLosePanel.x = 710;
        _oLosePanel.y = 500;
        s_oStage.addChild(_oLosePanel);
        
        var oFade = new createjs.Shape();
        oFade.graphics.beginFill("rgba(0,0,0,0.7)").drawRect(0,0,500,100);
        _oLosePanel.addChild(oFade);
        
        var oText = new CTLText(_oLosePanel, 
                    0, 0, 500, 100, 
                    50, "center", "#fff", FONT1, 1,
                    10, 5,
                    TEXT_NO_WIN,
                    true, true, false,
                    false );
        
        this.refreshButtonPos (s_iOffsetX,s_iOffsetY);
    };
    
    this.unload = function(){
        _oButExit.unload();
        _oArrowLeft.unload();
        _oArrowRight.unload();
        _oBetOneBut.unload();
        _oBetMaxBut.unload();
        _oDealBut.unload();

        if(DISABLE_SOUND_MOBILE === false || s_bMobile === false){
            _oAudioToggle.unload();
            _oAudioToggle = null;
        }
        
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
            _oButFullscreen.setPosition(_pStartPosFullscreen.x - iNewX,_pStartPosFullscreen.y + iNewY);
        }
    };
    
    this.setState = function(iState){
        switch(iState){
            case STATE_GAME_CHOOSE_HOLD:{
                _oDealBut.changeText(TEXT_DRAW);
                break;
            }
			case STATE_GAME_DRAW:
            case STATE_GAME_EVALUATE:{
                _oDealBut.changeText(TEXT_DEAL);
                break;
            }
        }
    };
    
    this.resetHand = function(){
        this.refreshWin(0);
        _oLosePanel.visible = false;
    };
    
    this.refreshMoney = function(iMoney,iBet){
        _oMoneyText.refreshText(iMoney.toFixed(2)+TEXT_CURRENCY);
        _oBetText.refreshText(iBet.toFixed(2)+TEXT_CURRENCY);
    };
    
    this.refreshWin = function(iWin){
        _oWinText.refreshText(iWin.toFixed(2)+TEXT_CURRENCY);
    };
    
    this.refreshBet = function(iBet){
        _oTotBetText.refreshText(iBet.toFixed(2)+TEXT_CURRENCY);
    };
    
    this.showLosePanel = function(){
        _oLosePanel.visible = true;
    };

    this._onButLeftRelease = function(){
        s_oGame._onButLeftRelease();
    };
    
    this._onButRightRelease = function(){
        s_oGame._onButRightRelease();
    };
    
    this._onButBetOneRelease = function(){
        s_oGame._onButBetOneRelease();
    };
    
    this._onButBetMaxRelease = function(){
        s_oGame._onButBetMaxRelease();
    };
    
    this._onButDealRelease = function(){
        s_oGame._onButDealRelease();
    };
    
    this._onExit = function(){
        s_oGame.onExit();  
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
    
    s_oInterface = this;
    
    this._init(iMoney,iBet);
    
    return this;
}

var s_oInterface = null;