function CGame(oData){
    var _bUpdate = false;
    var _bWinAssigned;
    var _iState;
    var _iBetMult;
    var _iTimeElaps;
    var _iFactor;
    var _iNumberExtracted;
    var _iCasinoCash;
    var _iCountLastNeighbors;
    var _iHandCont;
    var _aBetMultHistory;
    var _aBetWinHistory;
    var _aNumFicheHistory;
    var _aNumExtractedHistory;
    var _aFichesToMove;
    var _aRebetHistory;
        
    var _oBg;
    var _oMySeat;
    var _oPlaceHolder;
    var _oInterface;
    var _oTableController;
    var _oMsgBox;
    var _oWheelAnim;
    var _oFinalBet;
    var _oNeighborsPanel;
    var _oGameOverPanel;
    
    
    this._init = function(){
        s_oTweenController = new CTweenController();
        s_oGameSettings = new CRouletteSettings();
        
        _oBg = createBitmap(s_oSpriteLibrary.getSprite('bg_game'));
        s_oStage.addChild(_oBg);
        
        _oTableController = new CTableController();
        _oTableController.addEventListener(ON_SHOW_ENLIGHT,this._onShowEnlight);
        _oTableController.addEventListener(ON_HIDE_ENLIGHT,this._onHideEnlight);
        _oTableController.addEventListener(ON_SHOW_BET_ON_TABLE,this._onShowBetOnTable);

        _iCountLastNeighbors = 0;
        _iHandCont = 0;
        _iState=-1;
        _iBetMult=NUMBERS_TO_BET;
        _aBetMultHistory=new Array();
        _aBetWinHistory = new Array();
        _aNumFicheHistory = new Array();
        _aRebetHistory = new Array();

        _oMySeat = new CSeat();
        _oInterface = new CInterface();
        _oFinalBet = new CFinalBetPanel(816,564);
        
        _oWheelAnim = new CWheelAnim(0,0);
        _oNeighborsPanel  = new CNeighborsPanel(_oMySeat.getCredit());
        
        _oGameOverPanel = new CGameOver();
        
        _oMsgBox = new CMsgBox();
		
        
		
        _aNumExtractedHistory=new Array();

        _iTimeElaps=0;
        this._onSitDown();
	
        _bUpdate = true;
    };
    
    this.unload = function(){
        stopSound("wheel_sound");
        _oInterface.unload();
        _oTableController.unload();
        _oMsgBox.unload();
        _oFinalBet.unload();
        _oNeighborsPanel.unload();
        _oGameOverPanel.unload();
        _oWheelAnim.unload();

        s_oStage.removeAllChildren();
    };
    
    
    
    this._setState = function(iState){
        _iState=iState;

        switch(iState){
            case STATE_GAME_WAITING_FOR_BET:{
                _oInterface.enableBetFiches();
                _oInterface.setCurBet(0);
                
                _oInterface.hideBlock();
                break;
            }
        }
    };
    
    this._resetTable = function(){
        _iTimeElaps = 0;
        _iBetMult=NUMBERS_TO_BET;
        _aBetMultHistory=new Array();
        _aBetWinHistory = new Array();
        _aNumFicheHistory = new Array();

        if(_oPlaceHolder !== null){
            _oTableController.getContainer().removeChild(_oPlaceHolder);
            _oPlaceHolder = null;
        }

        _oMySeat.reset();
        _oNeighborsPanel.reset();

        if (_oMySeat.getCredit() < 0.1) {
            _iState = -1;
            _oInterface.hideBlock();
            _oGameOverPanel.show();
        }else{
            _oInterface.enableRebet();
            this._setState(STATE_GAME_WAITING_FOR_BET);
        }
        
        _iHandCont++;
        if(_iHandCont === NUM_HAND_FOR_ADS){
            _iHandCont = 0;
            $(s_oMain).trigger("show_interlevel_ad");
        }
    };
    
    this._startRouletteAnim = function(){
        _oInterface.disableBetFiches();

        _iNumberExtracted = this._generateWinLoss();
        _aNumExtractedHistory.push(_iNumberExtracted);

        _iTimeElaps = 0;
        _iFactor = 0;
    };
    
    this._startBallSpinAnim = function(){
        var aNumbersBetted=_oMySeat.getNumbersBetted();
        var oWins=aNumbersBetted[_iNumberExtracted];
        var iWin=roundDecimal(oWins.win,2);
        _oWheelAnim.startSpin(0,s_oGameSettings.getFrameForBallSpin(0,_iNumberExtracted),_iNumberExtracted,iWin);
    };
    
    this._generateWinLoss = function(){
        var iRandIndex;
        var aNumbersBetted = _oMySeat.getNumbersBetted();
        var aTmpNumbers = _oMySeat.getNumberSelected();
        var iWin = aNumbersBetted[aTmpNumbers[0][0]].win;
        var iWinOccurence;
        var iRand;
        
        
        _iCasinoCash += _oMySeat.getCurBet();
        _iCasinoCash = parseFloat(_iCasinoCash.toFixed(2));

        if(_iCasinoCash < iWin){
            iWinOccurence = 0;
            iRand = Math.floor(Math.random() * (100));
        }else if(WIN_OCCURRENCE === -1){
            iWinOccurence = NUMBERS_TO_BET-_iBetMult;
            iRand = Math.floor(Math.random() * (38));
        }else{
            iWinOccurence = WIN_OCCURRENCE;
            iRand = Math.floor(Math.random() * (100));
        }

        if (iRand >= iWinOccurence) {
            _bWinAssigned = false;
        }else {
            _bWinAssigned = true;
        }

        if(_bWinAssigned){
            do{
                iRandIndex=Math.floor(Math.random() * aNumbersBetted.length);
                iWin = aNumbersBetted[iRandIndex].win;
                
            }while(iWin === 0);

            _iNumberExtracted=iRandIndex;
        }else{
            var aTmpNumbers=new Array();
            for(var k=0;k<NUMBERS_TO_BET;k++){
                    aTmpNumbers.push(k);
            }
            do{
                if(aTmpNumbers.length === 0){
                    iRandIndex=Math.floor(Math.random() * aNumbersBetted.length);
                    break;
                }
                iRandIndex=Math.floor(Math.random() * aTmpNumbers.length);
                iWin = aNumbersBetted[iRandIndex].win;

                aTmpNumbers.splice(iRandIndex,1);
            }while(iWin >= _oMySeat.getCurBet());

            _iNumberExtracted = iRandIndex;
        }

        return _iNumberExtracted;
    };
    
    this._rouletteAnimEnded = function(){
        _iTimeElaps = 0;     
        
        this._setState(STATE_GAME_SHOW_WINNER);
        
        stopSound("wheel_sound");

        var aNumbersBetted=_oMySeat.getNumbersBetted();
        var oWins=aNumbersBetted[_iNumberExtracted];
        var iWin=roundDecimal(oWins.win,2);
        _aFichesToMove = new Array();

        for(var j=0;j<aNumbersBetted.length;j++){
                var oRes=aNumbersBetted[j];
                if(oRes.win>0){
                    for(var k=0;k<oRes.mc.length;k++){
                        _aFichesToMove.push(oRes.mc[k]);
                        var oEndPos = s_oGameSettings.getAttachOffset("oDealerWin");
                        oRes.mc[k].setEndPoint(oEndPos.x,oEndPos.y);
                    }
                }
        }

        if(oWins.mc){
            for(var i=0;i<oWins.mc.length;i++){
                var oEndPos = s_oGameSettings.getAttachOffset("oReceiveWin");
                oWins.mc[i].setEndPoint(oEndPos.x,oEndPos.y);
            }
        }
        _oInterface.refreshNumExtracted(_aNumExtractedHistory);

        //ATTACH PLACEHOLDER THAT SHOW THE NUMBER EXTRACTED
        _oPlaceHolder = createBitmap(s_oSpriteLibrary.getSprite('placeholder'));

        if(_iNumberExtracted === 0){
                _oPlaceHolder.x = _oTableController.getEnlightX(_iNumberExtracted) +26;
                _oPlaceHolder.y = _oTableController.getEnlightY(_iNumberExtracted) + 34;
        }else if(_iNumberExtracted === 37){
            _oPlaceHolder.x = _oTableController.getEnlightX(_iNumberExtracted)  + 26;
            _oPlaceHolder.y = _oTableController.getEnlightY(_iNumberExtracted)  + 34;
        }else{
                _oPlaceHolder.x = _oTableController.getEnlightX(_iNumberExtracted) + 8;
                _oPlaceHolder.y = _oTableController.getEnlightY(_iNumberExtracted) + 16;
        }
        
        _oPlaceHolder.regX = 6;
        _oPlaceHolder.regY = 20;
        _oTableController.getContainer().addChild(_oPlaceHolder);

        _oMySeat.showWin(iWin);
        if(iWin > 0){
            _iCasinoCash -= iWin;
        }
        
        _iCasinoCash = parseFloat(_iCasinoCash.toFixed(2));

        $(s_oMain).trigger("save_score",_oMySeat.getCredit());

        _oInterface.refreshMoney(_oMySeat.getCredit()-iWin, iWin);
    };
    
    this.showMsgBox = function(szText){
        _oMsgBox.show(szText);
    };
    
    this.onRecharge = function() {
        _oMySeat.recharge(TOTAL_MONEY);
        _oInterface.setMoney(_oMySeat.getCredit());

        this._setState(STATE_GAME_WAITING_FOR_BET);
        
        _oGameOverPanel.hide();
        
        $(s_oMain).trigger("recharge");
    };
    
    this.onSpin = function(){
        if(_oNeighborsPanel.isVisible()){
                _oNeighborsPanel.onExit();
        }
	
        if (_oMySeat.getCurBet() === 0) {
                return;
        }
        
        if(_oMySeat.getCurBet() < MIN_BET){
            _oMsgBox.show(TEXT_ERROR_MIN_BET);
            _oInterface.enableBetFiches();
            _oInterface.enableSpin(true);
            return;
        }

        if(_oInterface.isBlockVisible()){
                return;
        }

        _oInterface.showBlock();

        _oNeighborsPanel.hide();
        _oFinalBet.hide();
        _oInterface.enableSpin(false);
        
        $(s_oMain).trigger("bet_placed",_oMySeat.getCurBet());
        
        this._startRouletteAnim();
        this._startBallSpinAnim();

        this._setState(STATE_GAME_SPINNING);
		
        playSound("wheel_sound",1,false);
        
    };
    
    this._onSitDown = function(){
        this._setState(STATE_GAME_WAITING_FOR_BET);
        _oMySeat.setInfo(TOTAL_MONEY, _oTableController.getContainer());
        _oInterface.setMoney(TOTAL_MONEY);
        _oInterface.setCurBet(0);
    };
    
    this._onShowBetOnTable = function(oParams,bRebet){
        var szBut = oParams.button;
        var aNumbers = oParams.numbers;
        _iBetMult -= oParams.bet_mult;
        _aBetMultHistory.push(oParams.bet_mult);
        

        var iBetWin = oParams.bet_win;
        var iNumFiches = oParams.num_fiches;
        var iIndexFicheSelected;
        var iFicheValue;
        
        if(!bRebet){
            iIndexFicheSelected = _oInterface.getCurFicheSelected();

            if(_aBetWinHistory.length === 0){
                _aRebetHistory = new Array();
                _oInterface.disableRebet();
            }
            _aRebetHistory.push({button:oParams.button,numbers:oParams.numbers,bet_mult:oParams.bet_mult,bet_win:oParams.bet_win,
                                                            num_fiches:oParams.num_fiches,neighbors:false,value:iIndexFicheSelected});
        }else{
            iIndexFicheSelected = oParams.value;
        }
        
        iFicheValue=s_oGameSettings.getFicheValues(iIndexFicheSelected);
        _aBetWinHistory.push(iBetWin);
        _aNumFicheHistory.push(iNumFiches);
        
        
        var iCurBet=_oMySeat.getCurBet();
        if( (_oMySeat.getCredit() - (iFicheValue * iNumFiches)) < 0){
            //SHOW MSG BOX
            _oMsgBox.show(TEXT_ERROR_NO_MONEY_MSG);
            _oNeighborsPanel.reset();
            return;
        }
        if( (iCurBet + (iFicheValue * iNumFiches)) > MAX_BET ){
            _oMsgBox.show(TEXT_ERROR_MAX_BET_REACHED);
            _oNeighborsPanel.reset();
            return;
        }

        switch(szBut){
                case "oBetFinalsBet":{
                        _oMySeat.createPileForMultipleNumbers(iFicheValue,iIndexFicheSelected,aNumbers,iBetWin,iNumFiches);
                        break;
                }
                default:{
                        _oMySeat.addFicheOnTable(iFicheValue,iIndexFicheSelected,aNumbers,iBetWin,szBut);
                }
        }
        _oInterface.setMoney(_oMySeat.getCredit());
        _oInterface.setCurBet(_oMySeat.getCurBet());
        _oInterface.enableSpin(true);
        
        playSound("chip",1,false);
    };
    
    this._onShowBetOnTableFromNeighbors = function(oParams,bRebet){
        var aNumbers = oParams.numbers;
        _iBetMult -= oParams.bet_mult;
        _aBetMultHistory.push(oParams.bet_mult);

        var iBetWin = oParams.bet_win;
        var iNumFiches = oParams.num_fiches;
        if(!bRebet){
            if(_aBetWinHistory.length === 0){
                _aRebetHistory = new Array();
                _oInterface.disableRebet();
            }
            _aRebetHistory.push({button:oParams.button,numbers:oParams.numbers,bet_mult:oParams.bet_mult,bet_win:oParams.bet_win,
                                        num_fiches:oParams.num_fiches,value:_oInterface.getCurFicheSelected(),num_clicked:oParams.num_clicked,neighbors:true});
        }

        _aBetWinHistory.push(iBetWin);
        _aNumFicheHistory.push(iNumFiches);

        var iFicheValue=s_oGameSettings.getFicheValues(oParams.value);

        //var iCurBet=_oMySeat.getCurBet();

        if( (iFicheValue * iNumFiches)>_oMySeat.getCredit() ){
            //SHOW MSG BOX
            _oMsgBox.show(TEXT_ERROR_NO_MONEY_MSG);
            _oNeighborsPanel.reset();
            return;
        }


        _oMySeat.createPileForMultipleNumbers(iFicheValue,oParams.value,aNumbers,iBetWin,iNumFiches);
        
        _oInterface.setMoney(_oMySeat.getCredit());
        _oInterface.setCurBet(_oMySeat.getCurBet());
        _oInterface.enableSpin(true);
        
        playSound("chip",1,false);
    };
    
    this._onShowEnlight = function(oParams){
        var aBets = oParams.numbers;
        
        for(var i=0;i<aBets.length;i++){
            _oTableController.enlight("oEnlight_"+aBets[i]);
        }

        var szEnlight=oParams.enlight;
        if(szEnlight){
            _oTableController.enlight("oEnlight_"+szEnlight);
        }
    };
    
    this._onHideEnlight = function(oParams){
        var aBets=oParams.numbers;
        for(var i=0;i<aBets.length;i++){
                _oTableController.enlightOff("oEnlight_"+aBets[i]);
        }

        var szEnlight=oParams.enlight;
        if(szEnlight){
            _oTableController.enlightOff("oEnlight_"+szEnlight);
        }
    };
    
    this.onClearLastBet = function(){
        if(_aBetMultHistory.length>0){
                var iBetMultToRemove = _aBetMultHistory.pop();
                _iBetMult += iBetMultToRemove;
        }
		
        if(_aBetMultHistory.length === 0){
                _oInterface.enableSpin(false);
        }
		
        _oMySeat.clearLastBet(_aBetWinHistory.pop(),_aNumFicheHistory.pop());
        _oInterface.setMoney(_oMySeat.getCredit());
        _oInterface.setCurBet(_oMySeat.getCurBet());
        _oNeighborsPanel.clearLastBet();
        if(_aRebetHistory.length > 0){
            _aRebetHistory.pop();
        }  
    };
    
    this.onClearAllBets = function(){
        _oMySeat.clearAllBets();
        _oInterface.setMoney(_oMySeat.getCredit());
        _oInterface.setCurBet(_oMySeat.getCurBet());
        _oInterface.enableSpin(false);
        _oNeighborsPanel.reset();
        _aRebetHistory = new Array();
        _iBetMult=NUMBERS_TO_BET;
    };
    
    this.onRebet = function(){
        for(var i=0;i<_aRebetHistory.length;i++){
            if(_aRebetHistory[i].neighbors === true){
                _oNeighborsPanel.rebet(_aRebetHistory[i].num_clicked);
            }else{
                this._onShowBetOnTable(_aRebetHistory[i],true);
            }
            
        }
    };
    
    this.onFinalBetShown = function(){
        if(_oFinalBet.isVisible()){
            _oFinalBet.hide();
        }else{
            _oFinalBet.show();	
        }
    };
    
    this.onOpenNeighbors = function(){
        _oFinalBet.hide();
        _oNeighborsPanel.showPanel(_oInterface.getCurFicheSelected(),_oMySeat.getCredit(),_oMySeat.getCurBet());
    };
   
    this.onExit = function(){
        $(s_oMain).trigger("end_session");
        $(s_oMain).trigger("save_score",_oMySeat.getCredit());
        $(s_oMain).trigger("share_event",_oMySeat.getCredit());
        
		this.unload();
        s_oMain.gotoMenu();
        
    };
    
    this._updateWaitingBet = function(){
        if(_oNeighborsPanel.isVisible()){
            return;
        }

        if(TIME_WAITING_BET !== 0){
            _iTimeElaps += s_iTimeElaps;
            if(_iTimeElaps > TIME_WAITING_BET){
                    _iTimeElaps = 0;
                    this.onSpin();
            }
        }
        
        
    };
    
    this._updateSpinning = function(){/*
        _iTimeElaps += s_iTimeElaps;
        
        if (_iTimeElaps > TIME_SPINNING) {
            this._rouletteAnimEnded();
        }*/
    };
    
    this._updateShowWinner = function(){
        _iTimeElaps+=s_iTimeElaps;
        if(_iTimeElaps>TIME_SHOW_WINNER){
            _iTimeElaps=0;
            s_oGame._setState(STATE_DISTRIBUTE_FICHES);
            
        }
    };
    
    this._updateDistributeFiches = function(){
        _iTimeElaps += s_iTimeElaps;
        if(_iTimeElaps > TIME_FICHES_MOV){
            _iTimeElaps = 0;
            playSound("fiche_collect",1,false);
            
            this._resetTable();
        }else{
            var fLerp = s_oTweenController.easeInOutCubic( _iTimeElaps, 0, 1, TIME_FICHES_MOV);
            for(var i=0;i<_aFichesToMove.length;i++){
                _aFichesToMove[i].updatePos(fLerp);
            }
        }
    };
    
    this.update = function(){
        if(_bUpdate === false){
            return;
        }
        
        switch(_iState){
            case STATE_GAME_WAITING_FOR_BET:{
                    this._updateWaitingBet();
                    break;
            }
            case STATE_GAME_SPINNING:{
                    this._updateSpinning();
                    break;
            }
            case STATE_GAME_SHOW_WINNER:{
                    this._updateShowWinner();
                    break;
            }
            case STATE_DISTRIBUTE_FICHES:{
                    this._updateDistributeFiches();
                    break;
            }
        }
        
        if(_oWheelAnim.isVisible()){
            _oWheelAnim.update();
        }
        
    };
    
    s_oGame = this;
    
    TOTAL_MONEY = oData.money;
    MIN_BET = oData.min_bet;
    MAX_BET = oData.max_bet;
    TIME_WAITING_BET = oData.time_bet;
    TIME_SHOW_WINNER = oData.time_winner;
    WIN_OCCURRENCE = oData.win_occurrence;
    NUM_HAND_FOR_ADS = oData.num_hand_before_ads;
    _iCasinoCash = oData.casino_cash;
    
    this._init();
}

var s_oGame;
var s_oTweenController;
var s_oGameSettings;