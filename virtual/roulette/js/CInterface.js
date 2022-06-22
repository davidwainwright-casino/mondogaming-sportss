function CInterface(){
    var _iIndexFicheSelected;
    var _aFiches;
    var _pStartPosAudio;
    var _pStartPosExit;
    var _pStartPosHistory;
    var _pStartPosFullscreen;
    
    var _oButExit;
    var _oAudioToggle;
    var _oButFullscreen;
    var _fRequestFullScreen = null;
    var _fCancelFullScreen = null;
    var _oTimeTextBack;
    var _oTimeText;
    var _oMoneyAmountText;
    var _oBetAmountText;
    var _oMsgTitle;
    var _oDisplayBg;
    var _oSpinBut;
    var _oClearLastBet;
    var _oClearAllBet;
    var _oBetFinalsBet;
    var _oBetNeighbors;
    var _oRebetBut;
    var _oRollingText;
    var _oHistoryPanel;
    var _oBlock;
    
    this._init = function(){
        
        var oMoneyBg = createBitmap(s_oSpriteLibrary.getSprite('but_bg'));
        oMoneyBg.x = 191;
        oMoneyBg.y = 93;
        s_oStage.addChild(oMoneyBg);
        
        var oMoneyText = new createjs.Text(TEXT_MONEY,"16px "+FONT1, "#fff");
        oMoneyText.textAlign = "center";
        oMoneyText.x = 272;
        oMoneyText.y = 105;
        s_oStage.addChild(oMoneyText);
        
        _oMoneyAmountText = new createjs.Text("","16px "+FONT1, "#fff");
        _oMoneyAmountText.textAlign = "center";
        _oMoneyAmountText.x = 272;
        _oMoneyAmountText.y = 125;
        s_oStage.addChild(_oMoneyAmountText);
        
        var oCurBetBg = createBitmap(s_oSpriteLibrary.getSprite('but_bg'));
        oCurBetBg.x = 350;
        oCurBetBg.y = 93;
        s_oStage.addChild(oCurBetBg);
        
        var oCurBetText = new createjs.Text(TEXT_CUR_BET,"16px "+FONT1, "#fff");
        oCurBetText.textAlign = "center";
        oCurBetText.x = 432;
        oCurBetText.y = 105;
        s_oStage.addChild(oCurBetText);
        
        _oBetAmountText = new createjs.Text("","16px "+FONT1, "#fff");
        _oBetAmountText.textAlign = "center";
        _oBetAmountText.x = 432;
        _oBetAmountText.y = 125;
        s_oStage.addChild(_oBetAmountText);

        _oDisplayBg = createBitmap(s_oSpriteLibrary.getSprite('but_bets'));
        _oDisplayBg.x = 515;
        _oDisplayBg.y = 100;
        s_oStage.addChild(_oDisplayBg);

        _oMsgTitle = new createjs.Text(TEXT_MIN_BET+": "+MIN_BET+"\n"+TEXT_MAX_BET+": "+MAX_BET,"16px "+FONT1, "#fff");
        _oMsgTitle.textAlign = "center";
        _oMsgTitle.lineHeight = 20;
        _oMsgTitle.x = _oDisplayBg.x + 75;
        _oMsgTitle.y = _oDisplayBg.y + 5;
        s_oStage.addChild(_oMsgTitle);
        
        var oLogo = createBitmap(s_oSpriteLibrary.getSprite('logo_game_0'));
        oLogo.x = 856;
        oLogo.y = 612;
        s_oStage.addChild(oLogo);
        
        _oSpinBut = new CTextButton(1107,641,s_oSpriteLibrary.getSprite('spin_but'),"  "+TEXT_SPIN,FONT1,"#fff",19,s_oStage);
        _oSpinBut.setVisible(false);
        _oSpinBut.setAlign("left");
        _oSpinBut.addEventListener(ON_MOUSE_UP, this._onSpin, this);
        
        _oBetNeighbors = new CTextButton(350,641,s_oSpriteLibrary.getSprite('but_bg'),TEXT_NEIGHBORS,FONT1,"#fff",16);
        _oBetNeighbors.addEventListener(ON_MOUSE_UP, this._onNeighborsPanel, this);
        
        _oBetFinalsBet = new CTextButton(508,641,s_oSpriteLibrary.getSprite('but_bg'),TEXT_FINALSBET,FONT1,"#fff",16);
        _oBetFinalsBet.addEventListener(ON_MOUSE_UP, this._onFinalBetShow, this);

        _oRebetBut = new CGfxButton(1064,586,s_oSpriteLibrary.getSprite('but_rebet'),s_oStage);
        _oRebetBut.disable();
        _oRebetBut.addEventListener(ON_MOUSE_UP, this._onRebet, this);
        
        _oClearLastBet = new CGfxButton(1064,526,s_oSpriteLibrary.getSprite('but_clear_last'),s_oStage);
        _oClearLastBet.addEventListener(ON_MOUSE_UP, this._onClearLastBet, this);
        
        _oClearAllBet = new CGfxButton(1064,466,s_oSpriteLibrary.getSprite('but_clear_all'),s_oStage);
        _oClearAllBet.addEventListener(ON_MOUSE_UP, this._onClearAllBet, this);
        
        this._initFichesBut();
        this.disableBetFiches();
        
        _pStartPosHistory = {x:1095,y:158};
        _oHistoryPanel = new CHistory(_pStartPosHistory.x,_pStartPosHistory.y,s_oStage);
        
        
        _iIndexFicheSelected=0;
        _aFiches[_iIndexFicheSelected].select();
        
        var oGraphics = new createjs.Graphics().beginFill("rgba(0,0,0,0.01)").drawRect(0, 0, CANVAS_WIDTH, CANVAS_HEIGHT);
        _oBlock = new createjs.Shape(oGraphics);
        _oBlock.on("click",function(){});
        _oBlock.visible= false;
        s_oStage.addChild(_oBlock);
        
        var oSprite = s_oSpriteLibrary.getSprite('but_exit');
        _pStartPosExit = {x:CANVAS_WIDTH - (oSprite.width/2) - 10,y:(oSprite.height/2) + 10};
        _oButExit = new CGfxButton(_pStartPosExit.x,_pStartPosExit.y,oSprite,s_oStage);
        _oButExit.addEventListener(ON_MOUSE_UP, this._onExit, this);
        
        if(DISABLE_SOUND_MOBILE === false || s_bMobile === false){
            oSprite = s_oSpriteLibrary.getSprite('audio_icon');
            _pStartPosAudio = {x: _pStartPosExit.x - oSprite.width/2 - 10, y: (oSprite.height/2) + 10};
            _oAudioToggle = new CToggle(_pStartPosAudio.x,_pStartPosAudio.y,oSprite,s_bAudioActive,s_oStage);
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
            _pStartPosFullscreen = {x:oSprite.width/4 + 10,y:(oSprite.height/2) + 10};

            _oButFullscreen = new CToggle(_pStartPosFullscreen.x,_pStartPosFullscreen.y,oSprite,s_bFullscreen,s_oStage);
            _oButFullscreen.addEventListener(ON_MOUSE_UP, this._onFullscreenRelease, this);
        }
        
        this.refreshButtonPos(s_iOffsetX, s_iOffsetY);
    };
    
    this.unload = function(){
        _oButExit.unload();
	if(DISABLE_SOUND_MOBILE === false || s_bMobile === false){
            _oAudioToggle.unload();
        }
        if (_fRequestFullScreen && screenfull.enabled){
            _oButFullscreen.unload();
        }
        _oSpinBut.unload();
        _oClearLastBet.unload();
        _oClearAllBet.unload();
        _oBetFinalsBet.unload();
        _oBetNeighbors.unload();

        _oRebetBut.unload();
        s_oInterface = null;
    };
    
    this.refreshButtonPos = function (iNewX, iNewY) {
        if(DISABLE_SOUND_MOBILE === false || s_bMobile === false){
            _oAudioToggle.setPosition(_pStartPosAudio.x - iNewX,_pStartPosAudio.y + iNewY);
        }
        if (_fRequestFullScreen && screenfull.enabled){
            _oButFullscreen.setPosition(_pStartPosFullscreen.x + iNewX,_pStartPosFullscreen.y + iNewY);
        }
        _oButExit.setPosition(_pStartPosExit.x - iNewX,_pStartPosExit.y + iNewY);
    };
    
    this.hideBlock = function(){
        _oBlock.visible = false;
    };
    
    this.showBlock = function(){
        _oBlock.visible = true;
    };
    
    this.enableBetFiches = function(){
        for(var i=0;i<NUM_FICHE_VALUES;i++){
            _aFiches[i].enable();
        }
        _oClearLastBet.enable();
        _oClearAllBet.enable();

        _oBetFinalsBet.enable();
        _oBetNeighbors.enable();

    };
    
    this.disableBetFiches = function(){
        for(var i=0;i<NUM_FICHE_VALUES;i++){
            _aFiches[i].disable();
        }
        _oClearLastBet.disable();
        _oClearAllBet.disable();

        _oBetFinalsBet.disable();
        _oBetNeighbors.disable();

        _oRebetBut.disable();
    };
    
    this.enableRebet = function(){
        _oRebetBut.enable();
    };
    
    this.disableRebet = function(){
        _oRebetBut.disable();
    };

    this.deselectAllFiches = function(){
         for(var i=0;i<NUM_FICHES;i++){
             _aFiches[i].deselect();
         }
    };
    
    this.enableSpin = function(bEnable){
        _oSpinBut.setVisible(bEnable);
    };
    
    this._initFichesBut = function(){
        var oFicheBg = createBitmap(s_oSpriteLibrary.getSprite('chip_box'));
        oFicheBg.x = 102;
        oFicheBg.y = 100;
        s_oStage.addChild(oFicheBg);
        
        //SET FICHES BUTTON
        var iCurX = 142;
        var iCurY = 150;
        _aFiches = new Array();
        for(var i=0;i<NUM_FICHES;i++){
            var oSprite = s_oSpriteLibrary.getSprite('fiche_'+i);
            _aFiches[i] = new CFicheBut(iCurX,iCurY,oSprite);
            _aFiches[i].addEventListenerWithParams(ON_MOUSE_UP, this._onFicheSelected, this,[i]);
            
            iCurY += oSprite.height + 25;
        }
    };
    
    this.refreshTime = function(iTime){
        var szTime = formatTime(iTime);
        _oTimeText.text =  szTime;
        _oTimeTextBack.text = szTime;
    };
    
    this.setMoney = function(iMoney){
        _oMoneyAmountText.text = iMoney.toFixed(2)+TEXT_CURRENCY;
    };
    
    this.setCurBet = function(iCurBet){
        _oBetAmountText.text = iCurBet.toFixed(2) + TEXT_CURRENCY;
    };

    this.refreshMoney = function(iStartMoney, iMoney){
        _oRollingText = new CRollingTextController(_oMoneyAmountText, null, iStartMoney , parseFloat(iMoney), 1000, EASE_LINEAR,TEXT_CURRENCY);
    };
    
    this.refreshNumExtracted = function(aNumExtracted){
        var iLen=aNumExtracted.length-1;
        //TAKE ONLY THE FIRST 20 NUMBERS EXTRACTED
        var iLastNum=-1;

        if(aNumExtracted.length>ROW_HISTORY-1){
                iLastNum=iLen-ROW_HISTORY;
        }

        var iCurIndex=0;
        for(var i=iLen;i>iLastNum;i--){
            switch(s_oGameSettings.getColorNumber(aNumExtracted[i])){
                case COLOR_BLACK:{
                    _oHistoryPanel.showBlack(iCurIndex,aNumExtracted[i]);
                    break;
                }
                case COLOR_RED:{
                    _oHistoryPanel.showRed(iCurIndex,aNumExtracted[i]);
                    break;
                }
                case COLOR_ZERO:{
                    _oHistoryPanel.showGreen(iCurIndex,aNumExtracted[i]);
                    break;
                }
            }

            iCurIndex++;
        }


    };
    
    this.gameOver = function(){
        
    };
    
    this._onBetRelease = function(oParams){
        var aBets=oParams.numbers;
        var iBetMult=oParams.bet_mult;
        var iBetWin=oParams.bet_win;
        if(aBets !== null){
            s_oGame._onShowBetOnTable({button:oParams.name,numbers:aBets,bet_mult:iBetMult,bet_win:iBetWin,num_fiches:oParams.num_fiches},false);
        }
    };
    
    this._onFicheSelected = function(aParams){
        playSound("fiche_collect",1,false);
        
        this.deselectAllFiches();
        
        var iFicheIndex=aParams[0];

        for(var i=0;i<NUM_FICHE_VALUES;i++){
            if(i === iFicheIndex){
               _iIndexFicheSelected = i;
            }
        }
    };
    
    this._onSpin = function(){
            this.disableBetFiches();
            this.enableSpin(false);
            s_oGame.onSpin();    
    };
    
    this._onClearLastBet = function(){
        s_oGame.onClearLastBet();
    };
    
    this._onClearAllBet = function(){
        s_oGame.onClearAllBets();
    };
    
    this._onFinalBetShow = function(){
        s_oGame.onFinalBetShown();
    };
    
    this._onNeighborsPanel = function(){
        s_oGame.onOpenNeighbors();
    };
    
    this._onRebet = function(){
        _oRebetBut.disable();
        s_oGame.onRebet();
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
    
    this.getCurFicheSelected = function(){
        return _iIndexFicheSelected;
    };
    
    this.isBlockVisible = function(){
        return _oBlock.visible;
    };
    
    s_oInterface = this;
    
    this._init();
    
    return this;
}

var s_oInterface = null;;