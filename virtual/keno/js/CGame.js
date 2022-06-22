function CGame(oData){
    var _bTouchActive;
    var _bInitGame;
    var _bWin;

    var _iBank;
    var _iCurBet;
    var _iCurPlayerMoney;
    var _iTotalNum;
    var _iHitsNumber;
    var _iCountPlays;
    var _iAdCounter;
    
    var _aCell;
    var _aNumSelected;
    var _aListSelected;
    var _aCombination;

    var _oInterface;
    var _oEndPanel = null;
    var _oParent;
    var _oCellContainer;
    var _oPayoutsTable;
    var _oAnimBalls;
    var _oBlockScreen;
    
    this._init = function(){
        
        _bTouchActive=false;
        _bInitGame=true;
        
        _iBank = BANK;
        _iCurPlayerMoney = START_PLAYER_MONEY;
        _iCurBet = BET[3];
        _iTotalNum = 0;
        _iCountPlays = 1;
        _iAdCounter = 0;
        
        _aListSelected = new Array();
        
        var oBg = createBitmap(s_oSpriteLibrary.getSprite('bg_game'));
        s_oStage.addChild(oBg); 

        _oInterface = new CInterface();
        _oInterface.refreshBet(_iCurBet);           
 
        _oCellContainer = new createjs.Container();
        _oCellContainer.x = CANVAS_WIDTH/2;
        _oCellContainer.y = CANVAS_HEIGHT/2;
        s_oStage.addChild(_oCellContainer);

        this._initCells();        

        _oPayoutsTable = new CPayouts(1360,203);

        var oSprite = s_oSpriteLibrary.getSprite('hole');
        var oHole = createBitmap(oSprite);
        oHole.regX = oSprite.width/2;
        oHole.regY = oSprite.height/2;
        oHole.x = 365;
        oHole.y = 750;
        s_oStage.addChild(oHole);

        _oAnimBalls = new CAnimBalls(365, 260);
        
        var oSprite = s_oSpriteLibrary.getSprite('tube');
        var oTube = createBitmap(oSprite);
        oTube.x = 315;
        oTube.y = 205;
        s_oStage.addChild(oTube);
        
        var graphics = new createjs.Graphics().beginFill("rgba(158,158,158,0.01)").drawRect(0, 200, CANVAS_WIDTH, CANVAS_HEIGHT-200);
        _oBlockScreen = new createjs.Shape(graphics);
        _oBlockScreen.on("click", function(){});
        _oBlockScreen.visible = false;
        s_oStage.addChild(_oBlockScreen);

		if(_iCurBet>_iCurPlayerMoney){
                for(var i=0; i<80; i++){
					_aCell[i].block(true);
				}
        }

    };
    
    this._initCells = function(){
      
        var oSprite = s_oSpriteLibrary.getSprite('num_button');
        var iCellWidth = oSprite.width/2 -5;
        var iCellHeight = oSprite.height - 5;
        
        var iNumRow = 8;
        var iNumCol = 10;
        
        var oStartPos = {x: -(iNumCol*iCellWidth)/2 + iCellWidth/2 - 40, y: -(iNumRow*iCellHeight)/2 + iCellHeight/2 + 10};
     
        
     
        _aCell = new Array();
        _aNumSelected = new Array();
        var iNewHeight = 0;
        for(var i=0; i<80; i++){
            _aCell[i] = new CNumToggle(oStartPos.x +(i%iNumCol)*iCellWidth, oStartPos.y + iCellHeight*iNewHeight, i+1, _oCellContainer);
            _aCell[i].addEventListenerWithParams(ON_MOUSE_UP, this._onButNumRelease, this, i);
            if(i%iNumCol === iNumCol-1){
                iNewHeight++;
            }            
            _aNumSelected[i] = false;
        }

        var oSprite = s_oSpriteLibrary.getSprite('number')
        var oNumber = createBitmap(oSprite);
        oNumber.regX = oSprite.width/2;
        oNumber.regY = oSprite.height/2;
        oNumber.x = CANVAS_WIDTH/2 -35;
        oNumber.y = CANVAS_HEIGHT/2 + 10;
        s_oStage.addChild(oNumber);

    };
    
    this._onButNumRelease = function(iNum){

        this._clearAllSelected();

        if(_aNumSelected[iNum]){
            _iTotalNum--;
            _aNumSelected[iNum] = false;
            for(var i=0; i<_aListSelected.length; i++){
                if(_aListSelected[i] === iNum){
                    _aListSelected.splice(i,1);
                }
            }            
        }else {
            _iTotalNum++;
            _aNumSelected[iNum] = true;
            _aListSelected.push(iNum);
        }
        
        for(var i=0; i<_aListSelected.length; i++){
            _aCell[_aListSelected[i]].setActive(true);
        }
        
        this._checkActiveButton();

        _oPayoutsTable.updatePayouts(_iTotalNum-1);
        
        if(_iTotalNum > 9){
            for(var i=0; i<_aNumSelected.length; i++){
                if(!_aNumSelected[i]){
                    _aCell[i].block(true);
                }
            }
        } else {
            for(var i=0; i<_aNumSelected.length; i++){
                    _aCell[i].block(false);
            }
        }        
    };
   
    this._checkActiveButton = function(){
        if(_iTotalNum<2){
            _oInterface.enablePlay1(false);
            _oInterface.enablePlay5(false);
            
        } else {
            _oInterface.enablePlay1(true);
            if(_iCurBet * 5 > _iCurPlayerMoney){
                _oInterface.enablePlay5(false);
            } else {
                _oInterface.enablePlay5(true);
            }
        }
    };
   
    this.clearSelection = function(){        
        
        _aListSelected = new Array();
        
        this._clearAllExtracted();
        
        _iTotalNum = 0;
        _oPayoutsTable.updatePayouts(_iTotalNum-1);
        
        for(var i=0; i<_aNumSelected.length; i++){
            _aNumSelected[i] = false;
            _aCell[i].block(false);
            _aCell[i].setActive(false);
        }

        this._checkActiveButton();
    };
   
    this.undoSelection = function(){
        this._clearAllExtracted();
        
        if(_iTotalNum === 0){
            return;
        }
        var iNum = _aListSelected.pop();
        _iTotalNum--;
        _aNumSelected[iNum] = false;
        _aCell[iNum].setActive(false);
        for(var i=0; i<_aNumSelected.length; i++){
            _aCell[i].block(false);
        }
        this._checkActiveButton();
        _oPayoutsTable.updatePayouts(_iTotalNum-1);
    };
    
    this.selectBet = function(szType){
        this._clearAllExtracted();
        
        var iIndex;
        for(var i=0; i<BET.length; i++){
            if(BET[i] === _iCurBet){
                iIndex = i;
            }
        }
        
        if(szType === "add"){
            if(iIndex !== BET.length-1 && BET[iIndex +1] <= _iCurPlayerMoney){
                iIndex++;
            }
        } else {
            if(iIndex !== 0){
                iIndex--;
            }
        }
        
        _iCurBet = BET[iIndex];
        _oInterface.refreshBet(_iCurBet);
        this._checkActiveButton();
        
    };
   
    this.play5 = function(){
        _iCountPlays = 5;
        this.play();        
    };
    
    this.tryShowAd = function(){
        _iAdCounter++;
        if(_iAdCounter === AD_SHOW_COUNTER){
            _iAdCounter = 0;
            $(s_oMain).trigger("show_interlevel_ad");
        }
    };
   
    this.play = function(){
        //Detect max prize to win
        this._clearAllExtracted();

        if(_iTotalNum < 2){
            return;
        }
        
        this.smartBlockGUI(false);
        _iCurPlayerMoney -= _iCurBet;
        _iBank += _iCurBet;
        _iCurPlayerMoney = parseFloat(_iCurPlayerMoney.toFixed(1));          
        _oInterface.refreshMoney(_iCurPlayerMoney);
        
        var iWinIndex = null;
        for(var i=0; i<PAYOUTS[_iTotalNum-1].pays.length; i++){
            var iTotalWin = PAYOUTS[_iTotalNum-1].pays[i] * _iCurBet;
            if(iTotalWin <= _iBank){
                iWinIndex = i;
                break;
            }
        }

        if(iWinIndex === null){
            this._extractLosingCombination();
        } else {
            this._checkWin(iWinIndex);
        }   
        
        $(s_oMain).trigger("bet_placed",{tot_bet:_iCurBet,money:_iCurPlayerMoney,num_selected:_iTotalNum});
    };
   
    this._checkWin = function(iMaxWinIndex){
        var iRandWin = Math.random()*100;
        if(iRandWin < WIN_OCCURRENCE[_iTotalNum-1]){
            
            this._extractWinCombination(iMaxWinIndex);
            
        } else {
            this._extractLosingCombination();
        }        
    };
   
    this._extractWinCombination = function(iMaxWinIndex){
        
        _bWin = true;
        
        var aWinOccurrenceList = new Array();
        for(var i=PAYOUTS[_iTotalNum-1].pays.length-1; i>=iMaxWinIndex; i--){
            for(var j=0; j<PAYOUTS[_iTotalNum-1].occurrence[i]; j++){
                aWinOccurrenceList.push(PAYOUTS[_iTotalNum-1].hits[i]);
            }
        }
        
        var iRandWinIndex = Math.floor(Math.random()*aWinOccurrenceList.length);
        
        //Copy win numbers
        var aWinTempList = new Array();
        for(var i=0; i<_aListSelected.length; i++){
            aWinTempList[i] = _aListSelected[i]+1;
        }        
        shuffle(aWinTempList);
        
        //Copy lose numbers
        var aLoseTempList = new Array();
        for(var i=0; i<_aNumSelected.length; i++){
            if(!_aNumSelected[i]){
                aLoseTempList.push(i+1);
            }
        }
        shuffle(aLoseTempList);
        
        //Extract combination
        _aCombination = new Array();
        for(var i=0; i<20; i++){
            if(i<aWinOccurrenceList[iRandWinIndex]){
                _aCombination.push(aWinTempList[i]);
            } else {
                _aCombination.push(aLoseTempList[i]);
            }            
        }        
        shuffle(_aCombination);       
        //_oPayoutsTable.highlightWin(aWinOccurrenceList[iRandWinIndex])
        for(var i=0; i<20; i++){
            //_aCell[_aCombination[i]-1].setExtracted(true);           
        }  
        
        _iHitsNumber = aWinOccurrenceList[iRandWinIndex];
        this._animExtraction();
        
    };
   
    this._extractLosingCombination = function(){
        
        _bWin = false;
        
        var iMaxFakeWinNumber = (PAYOUTS[_iTotalNum-1].hits[PAYOUTS[_iTotalNum-1].hits.length-1]) -1;
        
        var iWinNumberToExtract = Math.round(Math.random()*iMaxFakeWinNumber);
        //Copy win numbers
        var aWinTempList = new Array();
        for(var i=0; i<_aListSelected.length; i++){
            aWinTempList[i] = _aListSelected[i]+1;
        }        
        shuffle(aWinTempList);
        
        //Copy lose numbers
        var aLoseTempList = new Array();
        for(var i=0; i<_aNumSelected.length; i++){
            if(!_aNumSelected[i]){
                aLoseTempList.push(i+1);
            }
        }
        shuffle(aLoseTempList);
        
        //Extract combination
        _aCombination = new Array();
        for(var i=0; i<20; i++){
            if(i<iWinNumberToExtract){
                _aCombination.push(aWinTempList[i]);
            } else {
                _aCombination.push(aLoseTempList[i]);
            }            
        }        
        shuffle(_aCombination);
        
        _iHitsNumber = 0;
        this._animExtraction();
    };
    
    this._animExtraction = function(){        
        //Calculate ball final position
        var aBallPos = new Array();
        for(var i=0; i<20; i++){
            aBallPos.push(_aCell[ (_aCombination[i] -1) ].getGlobalPosition())
        }

        _oAnimBalls.startAnimation(aBallPos);        
    };
   
    this._checkContinueGame = function(){        
        //Update Money
        for(var i=0; i<PAYOUTS[_iTotalNum-1].hits.length; i++){
            if(PAYOUTS[_iTotalNum-1].hits[i] === _iHitsNumber){
                var iTotalWin = (_iCurBet*PAYOUTS[_iTotalNum-1].pays[i]);
                iTotalWin = parseFloat(iTotalWin.toFixed(1));
                _iCurPlayerMoney += iTotalWin;
                _iBank -= iTotalWin;
                _oPayoutsTable.showWin(iTotalWin);
                _oPayoutsTable.highlightWin(_iHitsNumber);
				
                break;
            }
        }        
        _oInterface.refreshMoney(_iCurPlayerMoney);
        
        if(_bWin){
            
            playSound("win",1,false);
            
        }
        //trace("_iCurPlayerMoney: "+_iCurPlayerMoney);
		//trace("_iBank: "+_iBank);
        
        _oAnimBalls.resetBalls();
        this.highlightCell();
        
        //////CHECK IF CAN CONTINUE GAME////
        
        $(s_oMain).trigger("save_score",_iCurPlayerMoney);
        if(_iCurBet>_iCurPlayerMoney){
            
            var iNewIndex = null;
            for(var i=0; i<BET.length; i++){
                if(BET[i]<=_iCurPlayerMoney){
                    iNewIndex = i;                    
                }
            }
            if(iNewIndex !== null){
                _iCurBet = BET[iNewIndex];
                _oInterface.refreshBet(_iCurBet);
            } else {
                //END GAME
                this.gameOver();
                return;
            }        
            
        }
        
        if(_iCountPlays === 1){
            this.smartBlockGUI(true);
            return;
        } else {
            _iCountPlays--;
            setTimeout(function(){ _oParent.play(); }, 2000);
            
        }
        
        
        /////////////////////////////////////
    };
   
    this.showExtracted = function(iIndex, iColor){
        _aCell[_aCombination[iIndex]-1].setExtracted(true,iColor);
    };
   
    this._clearAllExtracted = function(){
        //createjs.Tween.removeAllTweens();
        _oPayoutsTable.showWin(0);
        _oPayoutsTable.stopHighlight();
        for(var i=0; i<_aNumSelected.length; i++){
            _aCell[i].setExtracted(false,0);
        }
        
        for(var j=0; j<_aListSelected.length; j++){ 
            _aCell[_aListSelected[j]].setActive(true);          
        }       
    };
    
    this._clearAllSelected = function(){
        //createjs.Tween.removeAllTweens();
        _oPayoutsTable.showWin(0);
        _oPayoutsTable.stopHighlight();
        for(var i=0; i<_aNumSelected.length; i++){
            _aCell[i].setExtracted(false,0);
        }       
    };
    
    this.smartBlockGUI = function(bVal){
        this.tryShowAd();
        
        if(bVal){
            _oBlockScreen.visible = false;
            _oInterface.enableAllButton(true);
            
            if(_iCurBet*5 <= _iCurPlayerMoney ){
                _oInterface.enablePlay5(true);
            } else {
                _oInterface.enablePlay5(false);
            }
            
        } else {
            _oBlockScreen.visible = true;
            _oInterface.enableAllButton(false);
        }
    };
    
    this.getCurMoney = function(){
        return _iCurPlayerMoney;
    };
       
    
    this.unload = function(){
        _bInitGame = false;
        
        _oInterface.unload();
        if(_oEndPanel !== null){
            _oEndPanel.unload();
        }
        
        createjs.Tween.removeAllTweens();
        s_oStage.removeAllChildren();

    };
 
    this.onExit = function(){
        
        this.unload();
        s_oMain.gotoMenu();
    };
    
    this._onExitHelp = function () {
         _bStartGame = true;
    };
    
    this.gameOver = function(){  
        _oEndPanel = CEndPanel(s_oSpriteLibrary.getSprite('msg_box'));
        _oEndPanel.show();
    };

    this.highlightCell = function(){
        
        for(var i=0; i<_aCombination.length; i++){
            for(var j=0; j<_aListSelected.length; j++){
                if(_aCombination[i] === _aListSelected[j]+1){
                    _aCell[_aListSelected[j]].highlight();
                }                
            }           
        }
    };

    this.update = function(){
        
    };

    s_oGame=this;
    
    WIN_OCCURRENCE = oData.win_occurrence;
    PAYOUTS = oData.payouts;
    BANK = oData.bank_money;
    START_PLAYER_MONEY = oData.start_player_money;
    ANIMATION_SPEED = oData.animation_speed;
    
    AD_SHOW_COUNTER = oData.ad_show_counter;
    
    _oParent=this;
    this._init();
}

var s_oGame;
