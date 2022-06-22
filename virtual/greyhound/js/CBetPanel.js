function CBetPanel(){
    var _iTotBet;
    var _iCurPage;
    var _iChipSelected;
    var _aPages;
    var _aHistory;
    var _pStartPosExit;
    var _pStartPosAudio;
    
    var _oMaskPanel;
    var _oArrowLeft;
    var _oArrowRight;
    var _oChipPanel;
    var _oMsgBox;
    var _oButExit;
    var _oAudioToggle;
    var _oContainerPages;
    var _oContainer;
    
    this._init = function(){
        _iTotBet = 0;
        _iChipSelected = 0;
        _aHistory = new Array();
        
        _oContainer = new createjs.Container();
        s_oStage.addChild(_oContainer);
        
        var oBg = createBitmap(s_oSpriteLibrary.getSprite("bg_bet_panel"));
        _oContainer.addChild(oBg);
        
        var oSpriteExit = s_oSpriteLibrary.getSprite("but_exit");
        _pStartPosExit = {x:CANVAS_WIDTH - oSpriteExit.width/2 - 10,y:oSpriteExit.height/2 + 10};
        _oButExit = new CGfxButton(_pStartPosExit.x,_pStartPosExit.y,oSpriteExit,_oContainer);
        _oButExit.addEventListener(ON_MOUSE_UP,this.onExit,this);

        if (DISABLE_SOUND_MOBILE === false || s_bMobile === false) {
            var oSprite = s_oSpriteLibrary.getSprite('audio_icon');
            _pStartPosAudio = {x: _pStartPosExit.x - oSpriteExit.width -10, y: (oSprite.height / 2) + 10};
            _oAudioToggle = new CToggle(_pStartPosAudio.x, _pStartPosAudio.y, oSprite, s_bAudioActive,s_oStage);
            _oAudioToggle.addEventListener(ON_MOUSE_UP, this._onAudioToggle, this);
        }
        
        var oSpriteBg = s_oSpriteLibrary.getSprite("simple_bet_panel");
        BET_PANEL_WIDTH = oSpriteBg.width;
        BET_PANEL_HEIGHT = oSpriteBg.height;
        
        _oContainerPages = new createjs.Container();
        _oContainerPages.x = BET_PANEL_X;
        _oContainerPages.y = BET_PANEL_Y;
        _oContainer.addChild(_oContainerPages);
        
        _aPages = new Array();
        _aPages[0] = new CSimpleBetPanel(0,0,_oContainerPages);
        _aPages[1] = new CForecastPanel(BET_PANEL_WIDTH,0,_oContainerPages);
        
        _oChipPanel = new CChipPanel(800,263,_oContainer);
        

        _oMaskPanel = new createjs.Shape();
        _oMaskPanel.graphics.beginFill("rgba(255,255,255,0.01)").drawRect(BET_PANEL_X+6, BET_PANEL_Y,BET_PANEL_WIDTH-12 , BET_PANEL_HEIGHT);
        _oContainer.addChild(_oMaskPanel);
        _oContainerPages.mask = _oMaskPanel;

        _iCurPage = 0;
        
        _oArrowLeft = new CGfxButton(BET_PANEL_X + 7,CANVAS_HEIGHT/2,s_oSpriteLibrary.getSprite("arrow_left"),_oContainer);
        _oArrowLeft.addEventListener(ON_MOUSE_UP,this._onArrowLeft,this);
        
        _oArrowRight = new CGfxButton(BET_PANEL_X + oSpriteBg.width -7,CANVAS_HEIGHT/2,s_oSpriteLibrary.getSprite("arrow_right"),_oContainer);
        _oArrowRight.addEventListener(ON_MOUSE_UP,this._onArrowRight,this);
        
        _oMsgBox = new CMsgBox();
        s_oBetList.reset();
        
        this.refreshButtonPos(s_iOffsetX, s_iOffsetY);
    };
    
    this.unload = function(){
        _oArrowLeft.unload();
        _oArrowRight.unload();
        _oButExit.unload();
        if (DISABLE_SOUND_MOBILE === false || s_bMobile === false) {
            _oAudioToggle.unload();
        }
        _oMsgBox.unload();
        
        _oChipPanel.unload();
        for(var i=0;i<_aPages.length;i++){
            _aPages[i].unload();
        }
            
       s_oStage.removeAllChildren(); 
    };
    
    this.refreshButtonPos = function (iNewX, iNewY) {
        if (DISABLE_SOUND_MOBILE === false || s_bMobile === false) {
            _oAudioToggle.setPosition(_pStartPosAudio.x - iNewX, _pStartPosAudio.y + iNewY);
        }
        
        _oButExit.setPosition(_pStartPosExit.x - iNewX,_pStartPosExit.y + iNewY);
        
    };
    
    this.setChipSelected = function(iIndex){
        _iChipSelected = iIndex;
    };
    
    this.setSimpleBet = function(iIndex,iPlace,iFicheValue,oBut){
        if( (_iTotBet+iFicheValue) > MAX_BET){
            //BET HIGHER THAN MAXIMUM ONE
            _oMsgBox.show(TEXT_ERR_MAX_BET);
            
            return false;
        }
        
        if(iFicheValue > s_iCurMoney ){
            //NOT ENOUGH MONEY FOR THIS BET
            _oMsgBox.show(TEXT_NO_MONEY);
            
            return false;
        }
        
       
        s_oBetList.addSimpleBet(iIndex,iPlace,iFicheValue);

        _iTotBet += iFicheValue;
        _iTotBet=Number(_iTotBet.toFixed(2));
        s_iCurMoney -= iFicheValue;
        s_iCurMoney = parseFloat(s_iCurMoney.toFixed(2));
        s_iGameCash += iFicheValue;
        s_iGameCash = parseFloat(s_iGameCash.toFixed(2));
        _oChipPanel.refreshMoney();
        _oChipPanel.refreshBet(_iTotBet);

        _aHistory.push(oBut);

        return true;
    };
    
    this.setForecastBet = function(iFirst,iSecond,iFicheValue,oBut){
        if( (_iTotBet+iFicheValue) > MAX_BET){
            //BET HIGHER THAN MAXIMUM ONE
            _oMsgBox.show(TEXT_ERR_MAX_BET);
            
            return false;
        }
        
        if(iFicheValue > s_iCurMoney ){
            //NOT ENOUGH MONEY FOR THIS BET
            _oMsgBox.show(TEXT_NO_MONEY);
            
            return false;
        }
        
        
        s_oBetList.addForecastBet(iFirst,iSecond,iFicheValue);

        _iTotBet += iFicheValue;
        _iTotBet=Number(_iTotBet.toFixed(2));
        s_iCurMoney -= iFicheValue;
        s_iCurMoney = parseFloat(s_iCurMoney.toFixed(2));
        s_iGameCash += iFicheValue;
        s_iGameCash = parseFloat(s_iGameCash.toFixed(2));

        _oChipPanel.refreshMoney();
        _oChipPanel.refreshBet(_iTotBet);

        _aHistory.push(oBut);

        return true;
    };
    
    this.refreshPagePos = function(iNextPage,iX){
        _oContainerPages.x = BET_PANEL_X;
        _aPages[_iCurPage].setX(0);
        _aPages[iNextPage].setX(iX);
    };
    
    this.clearBet = function(){
        for(var i=0;i<_aHistory.length;i++){
            _aHistory[i].clearBet();
        }
        
        s_iCurMoney += _iTotBet;
        s_iCurMoney = parseFloat(s_iCurMoney.toFixed(2));
        s_iGameCash -= _iTotBet;
        s_iGameCash = parseFloat(s_iGameCash.toFixed());
        
        _iTotBet = 0;
        
        _aPages[0].clearBet();
	s_oBetList.reset();
        _oChipPanel.refreshBet(0);
        _oChipPanel.refreshMoney();
    };
    
    this._onArrowLeft = function(){
        var iPrevPage = _iCurPage;
        _iCurPage++;
        if(_iCurPage === _aPages.length){
            _iCurPage = 0;
            iPrevPage = _aPages.length-1;
        }

        _aPages[_iCurPage].setX(BET_PANEL_WIDTH);       
        createjs.Tween.get(_oContainerPages).to({x: -BET_PANEL_WIDTH + BET_PANEL_X}, 500,createjs.Ease.cubicOut).call(function () {s_oBetPanel.refreshPagePos(iPrevPage,BET_PANEL_WIDTH);});
    };
    
    this._onArrowRight = function(){
        var iPrevPage = _iCurPage;
        _iCurPage--;
        if(_iCurPage < 0){
            _iCurPage = _aPages.length-1;
        }
        
        _aPages[_iCurPage].setX(-BET_PANEL_WIDTH);
        
        createjs.Tween.get(_oContainerPages).to({x: BET_PANEL_X+ BET_PANEL_WIDTH}, 500,createjs.Ease.cubicOut).call(function () {s_oBetPanel.refreshPagePos(iPrevPage,-BET_PANEL_WIDTH);});
    };
    
    this.onStartExit = function(){
        if(_iTotBet < MIN_BET){
            _oMsgBox.show(TEXT_ERR_MIN_BET);
        }else{
            this.unload();
            s_oMain.gotoGame(_iTotBet);
			$(s_oMain).trigger("bet_placed",_iTotBet);
        }
        
    };
    
    this.onExit = function(){
		$(s_oMain).trigger("end_session");
        this.unload();
        
        s_oMain.gotoMenu();
    };

    this._onAudioToggle = function () {
        Howler.mute(s_bAudioActive);
        s_bAudioActive = !s_bAudioActive;
    };
    
    this.getChipSelected = function(){
        return _iChipSelected;
    };
    
    s_oBetPanel = this;
    this._init();
}

var s_oBetPanel = null;