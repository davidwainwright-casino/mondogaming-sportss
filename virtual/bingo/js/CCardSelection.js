function CCardSelection(iMoney,oParentContainer){
    var _iMoney;
    var _iCurCoinBet;
    var _iTotBet;
    var _iNumCards;
    var _iIndexNumBalls;
    var _iCurNumBalls;
    var _iCurNumBallActivated;
    var _aCards;
    var _pStartPosCoin;
    var _pStartPosMoney;
    var _pStartPosTotBet;
    var _pStartPosButCoin;
    var _pStartPosPlay;
    var _pStartPosExit;
    var _pStartPosAudio;
    var _pStartPosFullscreen;

    var _oAudioToggle;
    var _oButExit;
    var _oMoneyText;
    var _oCoinText;
    var _oTotBetText;
    var _oNumCardsText;
    var _oButBetPlus;
    var _oButBetMin;
    var _oButCardsPlus;
    var _oButCardsMin;
    var _oButNumBall1;
    var _oButNumBall2;
    var _oButNumBall3;
    var _oButPlay;
    var _oMsgBox;
    var _oButFullscreen;
    var _fRequestFullScreen = null;
    var _fCancelFullScreen = null;
    var _oContainer;
    var _oParentContainer;
    
    this._init = function(iMoney){
        _iMoney = iMoney;
        _iCurCoinBet = 0;
        _iNumCards = 4;
        _iCurNumBalls = NUM_EXTRACTIONS[0];
        _iCurNumBallActivated = 0;
        _iTotBet = COIN_BETS[0] * _iNumCards;
        _iIndexNumBalls = 0;
        
        _oContainer = new createjs.Container();
        _oContainer.on("click",function(){});
        _oParentContainer.addChild(_oContainer);
        
        var oBg = createBitmap(s_oSpriteLibrary.getSprite('bg_select_card'));
        _oContainer.addChild(oBg);
        
        var oMsgOutlineText =  new CTLText(_oContainer, 
                    CANVAS_WIDTH/2-450, 150, 900, 100, 
                    100, "center", "#fff", PRIMARY_FONT, 1,
                    0, 0,
                    TEXT_SELECT_CARD,
                    true, true, false,
                    false );
        
        oMsgOutlineText.setOutline(4);


        var oMsgText =  new CTLText(_oContainer, 
                    CANVAS_WIDTH/2-450, 150, 900, 100, 
                    100, "center", "#ff7803", PRIMARY_FONT, 1,
                    0, 0,
                    TEXT_SELECT_CARD,
                    true, true, false,
                    false );
        
        _pStartPosMoney = {x:310,y:CANVAS_HEIGHT - 90};
        _oMoneyText = new CDisplayText(_pStartPosMoney.x,_pStartPosMoney.y,s_oSpriteLibrary.getSprite('plus_display'),_iMoney+TEXT_CURRENCY,TEXT_MONEY,40,_oContainer);

        _pStartPosCoin = {x:640,y:CANVAS_HEIGHT -90};
        _oCoinText = new CDisplayText(_pStartPosCoin.x,_pStartPosCoin.y,s_oSpriteLibrary.getSprite('display_small'),COIN_BETS[_iCurCoinBet]+TEXT_CURRENCY,TEXT_COIN,37,_oContainer);
        
        var oSprite = s_oSpriteLibrary.getSprite('but_plus');
        _pStartPosButCoin = {x:806,y:CANVAS_HEIGHT - 50};
        _oButBetPlus = new CTextSpritesheetBut(_pStartPosButCoin.x,_pStartPosButCoin.y,oSprite,TEXT_PLUS,PRIMARY_FONT,"#ffffff",70, false, _oContainer);
        _oButBetPlus.enable();
        _oButBetPlus.addEventListener(ON_MOUSE_UP, this._onButBetPlusRelease, this);

        var oSprite = s_oSpriteLibrary.getSprite('but_plus');
        _pStartPosButCoin = {x:596,y:CANVAS_HEIGHT - 50};
        _oButBetMin = new CTextSpritesheetBut(_pStartPosButCoin.x,_pStartPosButCoin.y,oSprite,TEXT_MIN,PRIMARY_FONT,"#ffffff",70, false, _oContainer);
        _oButBetMin.disable();
        _oButBetMin.setScaleX(-1);
        _oButBetMin.addEventListener(ON_MOUSE_UP, this._onButBetMinRelease, this);
        
        _pStartPosTotBet = {x:872,y:CANVAS_HEIGHT - 90};
        _oTotBetText = new CDisplayText(_pStartPosTotBet.x,_pStartPosTotBet.y,s_oSpriteLibrary.getSprite('plus_display'),_iTotBet+TEXT_CURRENCY,TEXT_TOT_BET,40,_oContainer);
       
        var oSelectNumCardsText = new CTLText(_oContainer, 
                    CANVAS_WIDTH/2-400, 370, 400, 34, 
                    34, "center", "#fff", PRIMARY_FONT, 1,
                    0, 0,
                    TEXT_SELECT_NUM_CARDS,
                    true, true, true,
                    false );

        
        _oNumCardsText = new createjs.Text(_iNumCards," 44px " +PRIMARY_FONT, "#fff");
        _oNumCardsText.x = CANVAS_WIDTH/2 - 200;
        _oNumCardsText.y = 480;
        _oNumCardsText.textAlign = "center";
        _oNumCardsText.textBaseline = "middle";
        _oContainer.addChild(_oNumCardsText);
        
        var oSprite = s_oSpriteLibrary.getSprite('but_plus');
        _oButCardsPlus = new CTextSpritesheetBut(CANVAS_WIDTH/2 - 50,480,oSprite,TEXT_PLUS,PRIMARY_FONT,"#ffffff",70, false, _oContainer);
        _oButCardsPlus.enable();
        _oButCardsPlus.addEventListener(ON_MOUSE_UP, this._onButCardPlusRelease, this);

        var oSprite = s_oSpriteLibrary.getSprite('but_plus');
        _oButCardsMin = new CTextSpritesheetBut(CANVAS_WIDTH/2 - 350,480,oSprite,TEXT_MIN,PRIMARY_FONT,"#ffffff",70, false, _oContainer);
        _oButCardsMin.enable();
        _oButCardsMin.setScaleX(-1);
        _oButCardsMin.addEventListener(ON_MOUSE_UP, this._onButCardMinRelease, this);
        
        var oChooseNumBallsText = new createjs.Text(TEXT_SELECT_NUM_BALLS," 34px " +PRIMARY_FONT, "#fff");
        oChooseNumBallsText.x = CANVAS_WIDTH/2 - 200;
        oChooseNumBallsText.y = 626;
        oChooseNumBallsText.textAlign = "center";
        oChooseNumBallsText.textBaseline = "middle";
        _oContainer.addChild(oChooseNumBallsText);
        
        var oSpriteBallBut = s_oSpriteLibrary.getSprite('but_ball');
        _oButNumBall1 = new CToggleText(true,CANVAS_WIDTH/2 - oSpriteBallBut.width - 200,700,oSpriteBallBut,oSpriteBallBut.width/2,oSpriteBallBut.height,
                                                                                                NUM_EXTRACTIONS[0],PRIMARY_FONT,"#ffffff",50, _oContainer);
        _oButNumBall1.addEventListener(ON_MOUSE_UP, this._onButNumBall1Release, this);
        
        _oButNumBall2 = new CToggleText(false,CANVAS_WIDTH/2 - 200,700,oSpriteBallBut,oSpriteBallBut.width/2,oSpriteBallBut.height,
                                                                                                NUM_EXTRACTIONS[1],PRIMARY_FONT,"#ffffff",50, _oContainer);
        _oButNumBall2.addEventListener(ON_MOUSE_UP, this._onButNumBall2Release, this);
        
        _oButNumBall3 = new CToggleText(false,CANVAS_WIDTH/2 + oSpriteBallBut.width - 200,700,oSpriteBallBut,oSpriteBallBut.width/2,oSpriteBallBut.height,
                                                                                                NUM_EXTRACTIONS[2],PRIMARY_FONT,"#ffffff",50, _oContainer);
        _oButNumBall3.addEventListener(ON_MOUSE_UP, this._onButNumBall3Release, this);
        
        this._initCards();
        
        _pStartPosPlay = {x:1350,y:CANVAS_HEIGHT-50};
        _oButPlay = new CTextButton(_pStartPosPlay.x,_pStartPosPlay.y,s_oSpriteLibrary.getSprite('but_gui'),TEXT_PLAY,PRIMARY_FONT,"#ffffff",50,0,_oContainer);
        _oButPlay.addEventListener(ON_MOUSE_UP, this._onButPlay, this);
        
        var oSprite = s_oSpriteLibrary.getSprite('but_exit');
        _pStartPosExit = {x: CANVAS_WIDTH - (oSprite.height/2)- 10, y: (oSprite.height/2) + 10};
        _oButExit = new CGfxButton(_pStartPosExit.x, _pStartPosExit.y, oSprite,true);
        _oButExit.addEventListener(ON_MOUSE_UP, this._onExit, this);
        
        if(DISABLE_SOUND_MOBILE === false || s_bMobile === false){
            _pStartPosAudio = {x:_pStartPosExit.x - oSprite.width - 10,y:_pStartPosExit.y};
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
        
        if (this._checkIfEnoughMoney() === false){
            _oButBetPlus.disable();
            _oButCardsPlus.disable();
        }
        
        this.refreshButtonPos(s_iOffsetX,s_iOffsetY);
    };
    
    this.refreshButtonPos = function(iNewX,iNewY){
        _oMoneyText.setPosition(_pStartPosMoney.x,_pStartPosMoney.y - iNewY);
        _oTotBetText.setPosition(_pStartPosTotBet.x, _pStartPosTotBet.y - iNewY);
        _oCoinText.setPosition(_pStartPosCoin.x, _pStartPosCoin.y - iNewY);
        _oButBetPlus.setPosition(_oButBetPlus.getX(),_pStartPosButCoin.y - iNewY);
        _oButBetMin.setPosition(_oButBetMin.getX(),_pStartPosButCoin.y - iNewY);
        _oButPlay.setPosition(_pStartPosPlay.x,_pStartPosPlay.y - iNewY);
        _oButExit.setPosition(_pStartPosExit.x - iNewX,iNewY + _pStartPosExit.y);
        
        if(DISABLE_SOUND_MOBILE === false || s_bMobile === false){
            _oAudioToggle.setPosition(_pStartPosAudio.x - iNewX,iNewY + _pStartPosAudio.y);
        }
        if (_fRequestFullScreen && screenfull.enabled){
            _oButFullscreen.setPosition(_pStartPosFullscreen.x + iNewX,_pStartPosFullscreen.y + iNewY);
        }
    };
    
    this.unload = function(){
        _oButBetPlus.unload();
        _oButBetMin.unload();
        _oButCardsPlus.unload();
        _oButCardsMin.unload();
        _oButNumBall1.unload();
        _oButNumBall2.unload();
        _oButNumBall3.unload();
        _oButPlay.unload();
        if(DISABLE_SOUND_MOBILE === false || s_bMobile === false){
            _oAudioToggle.unload();
            _oAudioToggle = null;
        }
        if (_fRequestFullScreen && screenfull.enabled){
            _oButFullscreen.unload();
        }
        _oButExit.unload();
    
        _oContainer.off("click",function(){});
        _oParentContainer.removeChild(_oContainer);
        s_oCardSelection = null;
    };
    
    this._initCards = function(){
        var oPaytableText = new CTLText(_oContainer, 
                    CANVAS_WIDTH/2+120, 275, 370, 34, 
                    34, "center", "#ff7803", PRIMARY_FONT, 1,
                    0, 0,
                    TEXT_PAYTABLE,
                    true, true, false,
                    false );

        
        var oSprite = s_oSpriteLibrary.getSprite('card_cell');
        var iCellSize = oSprite.width/4;
        var oData = {   
                        images: [oSprite], 
                        // width, height & registration point of each sprite
                        frames: {width: iCellSize, height: iCellSize}, 
                        animations: {state_empty:[0],state_fill:[1],state_extracted:[2],state_highlight:[3]}
                   };
                   
        var oSpriteSheet = new createjs.SpriteSheet(oData);  
        
        _aCards = new Array();
        _aCards[0] = new CPaytableCard(1100,666,iCellSize,0,oSpriteSheet,_oContainer);
        _aCards[1] = new CPaytableCard(1100,500,iCellSize,1,oSpriteSheet,_oContainer);
        _aCards[2] = new CPaytableCard(1100,330,iCellSize,2,oSpriteSheet,_oContainer);

        this.initPrizes(PAYTABLE_INFO[_iIndexNumBalls]);
    };
    
    this.initPrizes = function(aPrizes){
        for(var i=0;i<_aCards.length;i++){
            _aCards[i].setMsg(TEXT_PAYTABLE_PRIZES[i] + ": x"+aPrizes[i]);
        }
    };
    
    this._checkIfEnoughMoney = function(){
        if(_iMoney < _iTotBet){
            return false;
        }
        return true;
    };
    
    this._onButBetPlusRelease = function(){
        _iCurCoinBet++;
        _oCoinText.changeText(TEXT_CURRENCY+COIN_BETS[_iCurCoinBet]);
        _iTotBet = (COIN_BETS[_iCurCoinBet]/COIN_BETS[0]) * (COIN_BETS[0]*_iNumCards);
        _oTotBetText.changeText(_iTotBet + TEXT_CURRENCY);
        
        if( _iCurCoinBet === COIN_BETS.length-1 || this._checkIfEnoughMoney() === false){
            _oButBetPlus.disable();
        }
        _oButBetMin.enable();
        
    };
    
    this._onButBetMinRelease = function(){
        _iCurCoinBet--;
        _oCoinText.changeText(TEXT_CURRENCY+COIN_BETS[_iCurCoinBet]);
        _iTotBet = (COIN_BETS[_iCurCoinBet]/COIN_BETS[0]) * (COIN_BETS[0]*_iNumCards);
        _oTotBetText.changeText(_iTotBet + TEXT_CURRENCY);
        
        if(_iCurCoinBet === 0 || this._checkIfEnoughMoney() === false){
            _oButBetMin.disable();
        }
        _oButBetPlus.enable();
    };
    
    this._onButCardPlusRelease = function(){
        _iNumCards++;
        _oNumCardsText.text = _iNumCards;
        _iTotBet = (COIN_BETS[_iCurCoinBet]/COIN_BETS[0]) * (COIN_BETS[0]*_iNumCards);
        _oTotBetText.changeText(_iTotBet + TEXT_CURRENCY);
        
        if(_iNumCards === MAX_CARDS){
            _oButCardsPlus.disable();
        }
        
        _oButCardsMin.enable();
    };
    
    this._onButCardMinRelease = function(){
        _iNumCards--;
        _oNumCardsText.text = _iNumCards;
        _iTotBet = (COIN_BETS[_iCurCoinBet]/COIN_BETS[0]) * (COIN_BETS[0]*_iNumCards);
        _oTotBetText.changeText(_iTotBet + TEXT_CURRENCY);
        
        if(_iNumCards === MIN_CARDS){
            _oButCardsMin.disable();
        }
        
        _oButCardsPlus.enable();
    };
    
    this._onButNumBall1Release = function(){
        if(_iCurNumBalls !== NUM_EXTRACTIONS[0]){
            _iCurNumBalls = NUM_EXTRACTIONS[0];
            
            _oButNumBall2.activate(false);
            _oButNumBall3.activate(false);
        }else{
            _oButNumBall1.activate(true);
            _iCurNumBallActivated = 0;
        }
        
        _iIndexNumBalls = 0;
        this.initPrizes(PAYTABLE_INFO[_iIndexNumBalls]);
    };
    
    this._onButNumBall2Release = function(){
        if(_iCurNumBalls !== NUM_EXTRACTIONS[1]){
            _iCurNumBalls = NUM_EXTRACTIONS[1];
            
            _oButNumBall1.activate(false);
            _oButNumBall3.activate(false);
        }else{
            _oButNumBall2.activate(true);
            _iCurNumBallActivated = 1;
        }
        
        _iIndexNumBalls = 1;
        this.initPrizes(PAYTABLE_INFO[_iIndexNumBalls]);
    };
    
    this._onButNumBall3Release = function(){
        if(_iCurNumBalls !== NUM_EXTRACTIONS[2]){
            _iCurNumBalls = NUM_EXTRACTIONS[2];
            
            _oButNumBall2.activate(false);
            _oButNumBall1.activate(false);
        }else{
            _oButNumBall3.activate(true);
            _iCurNumBallActivated = 2;
        }
        
        _iIndexNumBalls = 2;
        this.initPrizes(PAYTABLE_INFO[_iIndexNumBalls]);
    };
    
    this._onButPlay = function(){
        if(this._checkIfEnoughMoney() === false){
            //NOT ENOUGH MONEY FOR THIS BET
            _oMsgBox = new CMsgBox(s_oSpriteLibrary.getSprite('msg_box'));
            _oMsgBox.show(TEXT_GAMEOVER);
            return;
        }
        
        //DECREASE MONEY
        _iMoney -= _iTotBet;
        
        s_oCardSelection.unload();
        s_oGame.initGame(_iNumCards,_iCurNumBalls,COIN_BETS[_iCurCoinBet],_iMoney,_iTotBet,_iCurNumBallActivated);
    };
    
    this._onAudioToggle = function(){
        Howler.mute(s_bAudioActive);
        s_bAudioActive = !s_bAudioActive;
    };
    
    this._onExit = function(){
        s_oCardSelection.unload();
        s_oMain.gotoMenu();  
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
    
    _oParentContainer = oParentContainer;
    
    s_oCardSelection = this;
    
    this._init(iMoney);
}

var s_oCardSelection = null;