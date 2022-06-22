function CGame(oData){
    var _bBlock;
    var _bWinCurHand;
    var _iMoney;
    var _iGameCash;
    var _iState;
    var _iCurBet;
    var _iCurWin;
    var _iCurBetIndex;
    var _iCurCreditIndex;
    var _iCurIndexDeck;
    var _iCurState;
    var _iHandCont;
    var _aCurHand = new Array();
    var _aCardDeck;
    var _oGameSettings;
    var _oHandEvaluator;
    
    var _oBg;
    var _oInterface;
    var _oPayTable;
    var _oGameOverPanel;
    var _oCardAttach;

    this._init = function(){
        s_oPayTableSettings = new CPayTableSettings();
        _iMoney  = TOTAL_MONEY;
        _iGameCash = GAME_CASH;

        _oBg = createBitmap(s_oSpriteLibrary.getSprite('bg_game'));
        s_oStage.addChild(_oBg);

        _oCardAttach = new createjs.Container();
        _oCardAttach.x = 600;
        _oCardAttach.y = 530;
        s_oStage.addChild(_oCardAttach);
        
        _oPayTable = new CPayTable(550,149);

        _bBlock = false;
        _iCurBetIndex = 0;
        _iCurCreditIndex = 0;
        _iCurWin = 0;
        _iHandCont = 0;
        _iCurBet = parseFloat(BET_TYPE[_iCurBetIndex] * (_iCurCreditIndex+1));
		
		
        _oPayTable.setCreditColumn(_iCurCreditIndex);
        
        _iCurState = STATE_GAME_WAITING_FOR_BET;

        _oInterface = new CInterface(_iMoney,BET_TYPE[_iCurBetIndex]);

	_oGameOverPanel = new CGameOver();
      
        _oGameSettings=new CGameSettings();
        _oHandEvaluator = new CHandEvaluator();
        
        _iCurIndexDeck = 0;
        _aCardDeck = new Array();
        _aCardDeck = _oGameSettings.getShuffledCardDeck();

        //FIND MIN WIN
        MIN_WIN = (COMBO_PRIZES[COMBO_PRIZES.length-1] * BET_TYPE[_iCurBetIndex]) * (_iCurCreditIndex+1);

        this.placeFakeCardForStarting();
        
        setVolume("soundtrack",SOUNDTRACK_VOLUME_IN_GAME);
        
    };
    
    this.unload = function(){
        _oInterface.unload();
	_oGameOverPanel.unload();
        s_oStage.removeAllChildren();
    };
    
    this.resetHand = function(){
        _iCurWin = 0;
        //SHUFFLE CARD DECK EVERYTIME A NEW HAND STARTS
        _iCurIndexDeck = 0;
        _aCardDeck = new Array();
        _aCardDeck = _oGameSettings.getShuffledCardDeck();
        
        for(var i=0;i<_aCurHand.length;i++){
            _aCurHand[i].reset();
        }
        _oInterface.resetHand();
        _oPayTable.resetHand();
        
        this.checkMoney();
        
        _bBlock = false;
        _iCurState = STATE_GAME_WAITING_FOR_BET;
        
        
    };
    
    this.checkMoney = function(){
        if(_iMoney < _iCurBet){
            //NOT ENOUGH MONEY
            _iCurBetIndex = 0;
            _iCurCreditIndex = 0;
            _iCurBet = parseFloat(BET_TYPE[_iCurBetIndex] * (_iCurCreditIndex+1));
            _oPayTable.setCreditColumn(_iCurCreditIndex);
			
            if(_iMoney < _iCurBet){
                this._gameOver();
            }else{
                _oInterface.refreshMoney(_iMoney,_iCurBet);
                _oInterface.refreshBet(BET_TYPE[_iCurBetIndex]);
            }
            
        }
    };
    
    this.changeState = function(iState){
        _iState=iState;

        switch(_iState){
            case STATE_GAME_DEALING:{
                
                break;
            }
        }
    };
    
    this.placeFakeCardForStarting = function(){
        var oSprite = s_oSpriteLibrary.getSprite('card_spritesheet');
        var oData = {   // image to use
                        images: [oSprite], 
                        // width, height & registration point of each sprite
                        frames: {width: CARD_WIDTH, height: CARD_HEIGHT, regX: CARD_WIDTH/2, regY: CARD_HEIGHT/2}, 
                        animations: {  card_1_1: [0],card_1_2:[1],card_1_3:[2],card_1_4:[3],card_1_5:[4],card_1_6:[5],card_1_7:[6],card_1_8:[7],
                                       card_1_9:[8],card_1_10:[9],card_1_J:[10],card_1_Q:[11],card_1_K:[12],
                                       card_2_1: [13],card_2_2:[14],card_2_3:[15],card_2_4:[16],card_2_5:[17],card_2_6:[18],card_2_7:[19],
                                       card_2_8:[20], card_2_9:[21],card_2_10:[22],card_2_J:[23],card_2_Q:[24],card_2_K:[25],
                                       card_3_1: [26],card_3_2:[27],card_3_3:[28],card_3_4:[29],card_3_5:[30],card_3_6:[31],card_3_7:[32],
                                       card_3_8:[33], card_3_9:[34],card_3_10:[35],card_3_J:[36],card_3_Q:[37],card_3_K:[38],
                                       card_4_1: [39],card_4_2:[40],card_4_3:[41],card_4_4:[42],card_4_5:[43],card_4_6:[44],card_4_7:[45],
                                       card_4_8:[46], card_4_9:[47],card_4_10:[48],card_4_J:[49],card_4_Q:[50],card_4_K:[51],back:[52]}
                        
        };
        
        var iX = 0;
        var iY = 0;
        for(var i=0;i<5;i++){
            var oSpriteSheet = new createjs.SpriteSheet(oData);
            var oBackCard = createSprite(oSpriteSheet,"back",CARD_WIDTH/2,CARD_HEIGHT/2,CARD_WIDTH,CARD_HEIGHT);
            oBackCard.x = iX;
            oBackCard.y = iY;
            oBackCard.shadow = new createjs.Shadow("#000000", 5, 5, 5);
            _oCardAttach.addChild(oBackCard);
            
            iX += 180;
        }
    };

    this.dealCards = function(){
        if(_bBlock){
            return;
        }
        if(_iMoney <= 0){
            return;
        }
        
        _bBlock = true;
        
        _oCardAttach.removeAllChildren();
        
        var iRandWin;

        if(_iGameCash < MIN_WIN){
            iRandWin = WIN_OCCURRENCE+1;
        }else{
            iRandWin = Math.floor(Math.random()*101);
        }
        
        if(iRandWin > WIN_OCCURRENCE){
            //LOSE
            do{
                this._createCard();
            }while( _oHandEvaluator.evaluate(_aCurHand) < HIGH_CARD);
            _bWinCurHand = false;
        }else{
            //WIN
            this._createCard();
            _bWinCurHand = true;
        }
        
        
        //DECREASE MONEY
        _iMoney -= _iCurBet;
        _iMoney = parseFloat(_iMoney.toFixed(2));
        _iGameCash += _iCurBet;
        _oInterface.refreshMoney(_iMoney,_iCurBet);
        
        playSound("card",1,false);
        $(s_oMain).trigger("bet_placed",_iCurBet);
        
        _iCurState = STATE_GAME_DEAL;
    };
    
    this._createCard = function(){
        
        for(var j=0;j<_aCurHand.length;j++){
            _aCurHand[j].unload();
        }
        
        var iX = 0;
        var iY = 0;
        _aCurHand = new Array();
        for(var i=0;i<5;i++){
            var oCard = new CCard(iX,iY,_oCardAttach,_aCardDeck[_iCurIndexDeck].fotogram,_aCardDeck[_iCurIndexDeck].rank,_aCardDeck[_iCurIndexDeck].suit);
            oCard.addEventListener(ON_CARD_SHOWN,this._onCardShown);
            oCard.addEventListener(ON_CARD_HIDE,this._onCardHide);
            _aCurHand.push(oCard);
            _iCurIndexDeck++;
            this._checkDeckLength();
            iX += 180;
            
            oCard.showCard();
        }
    };
    
    this.drawCards = function(){
        if(_bBlock){
            return;
        }
        
        _bBlock = true;
        
        playSound("card",1,false);
        
        var iNumHold = _aCurHand.length;
        for(var i=0;i<_aCurHand.length;i++){
            if(_aCurHand[i].isHold() === false){
                _aCurHand[i].hideCard();
                iNumHold--;
            }
        }
        
        if(iNumHold === _aCurHand.length){
            _iCurState = STATE_GAME_DRAW;
            this._onCardShown();
        } 
    };
    
    this._checkDeckLength = function(){
        if(_iCurIndexDeck >= _aCardDeck.length){
            _aCardDeck = _oGameSettings.getShuffledCardDeck();
            _iCurIndexDeck = 0;
        }
    };
    
     this.assignWin = function(iRet){
        playSound("win",1,false);

        var aSortedHand = _oHandEvaluator.getSortedHand();
		
	
        for(var i=0;i<_aCurHand.length;i++){
            for(var j=0;j<aSortedHand.length;j++){
                if(aSortedHand[j].rank === _aCurHand[i].getRank() && aSortedHand[j].suit === _aCurHand[i].getSuit()){
                    _aCurHand[i].highlight();
                    break;
                }
            }
        }
        
        _oPayTable.showWinAnim(_iCurCreditIndex,iRet);
        _iCurWin = s_oPayTableSettings.getWin(_iCurCreditIndex,iRet) * BET_TYPE[_iCurBetIndex];

        _iMoney += _iCurWin;
        _iMoney = parseFloat(_iMoney.toFixed(2));
        _iGameCash -= _iCurWin;
        
        _oInterface.refreshWin(_iCurWin);
        _oInterface.refreshMoney(_iMoney,_iCurBet);
     };
     
    this.recharge = function(){
        _iMoney = TOTAL_MONEY;
        _oPayTable.setCreditColumn(_iCurCreditIndex);
        
        
        this.checkMoney();
        _oInterface.refreshMoney(_iMoney,_iCurBet);
        _oInterface.refreshBet(BET_TYPE[_iCurBetIndex]);
        
        _oGameOverPanel.hide(); 
    };
     
    this._gameOver = function(){
        _oGameOverPanel.show();
    };
    
    this.onCardSelected = function(oCard){
        if(_iCurState !== STATE_GAME_CHOOSE_HOLD){
            return;
        }
        
        oCard.toggleHold();
    };
    
    this._onCardShown = function(){
        if(_iCurState === STATE_GAME_CHOOSE_HOLD){
            return;
        }
        
        switch(_iCurState){
            case STATE_GAME_DEAL:{
                    _iCurState = STATE_GAME_CHOOSE_HOLD;
                    _oInterface.setState(_iCurState);
                    break;
            }
            case STATE_GAME_DRAW:{

                    var iRet = _oHandEvaluator.evaluate(_aCurHand);
                    _oInterface.setState(_iCurState);

                    if(iRet !== HIGH_CARD){
                        s_oGame.assignWin(iRet);
                    }else{
                        playSound("lose",1,false);
                        _oInterface.showLosePanel();
                    }
                    
                    _iHandCont++;
                    if(_iHandCont === NUM_HAND_FOR_ADS){
                        _iHandCont = 0;
                        $(s_oMain).trigger("save_score",[_iMoney]);
                        $(s_oMain).trigger("show_interlevel_ad");
                    }else if(_iHandCont === NUM_HAND_FOR_ADS-1){
                        $(s_oMain).trigger("share_event",[_iMoney]);
                    }
                    
                    
                    _iCurState = STATE_GAME_EVALUATE;
                    break;
            } 
            case STATE_GAME_EVALUATE:{
                _oInterface.setState(_iCurState);
                break;
            }
        }
        
        _bBlock = false;
    };
    
    this._onCardHide = function(){
        if(_iCurState === STATE_GAME_DRAW){
            return;
        }
        
        _iCurState = STATE_GAME_DRAW;
        
        if(_bWinCurHand){
            var iCont = 0;
            do{
                s_oGame._changeCardValue();
                var iRet = _oHandEvaluator.evaluate(_aCurHand);
                
                var iWin;
                if(iRet === HIGH_CARD){
                    iWin = 0;
                }else{
                    iWin = s_oPayTableSettings.getWin(_iCurCreditIndex,iRet) * BET_TYPE[_iCurBetIndex];
                }
                 iCont++;
            }while( (iRet === HIGH_CARD ||  _iGameCash <  iWin ) && iCont<5000);
            
            if(iCont >= 5000){
                //USER MUST LOSE BECAUSE CAN'T FIND ANY WINNING HAND
                do{
                    s_oGame._changeCardValue();
                    var iRet = _oHandEvaluator.evaluate(_aCurHand);
                }while( iRet < HIGH_CARD);
                _bWinCurHand = false;
            }
            
        }else{
            do{
                s_oGame._changeCardValue();
                var iRet = _oHandEvaluator.evaluate(_aCurHand);
            }while(iRet < HIGH_CARD);
        }
        
        for(var i=0;i<5;i++){
            _aCurHand[i].setHold(false);
        }
    };
    
    this._changeCardValue = function(){
        for(var i=0;i<5;i++){
            if(_aCurHand[i].isHold() === false){
                _aCurHand[i].changeInfo(_aCardDeck[_iCurIndexDeck].fotogram,_aCardDeck[_iCurIndexDeck].rank,_aCardDeck[_iCurIndexDeck].suit);
                _aCurHand[i].showCard();
                _iCurIndexDeck++;    
                this._checkDeckLength();
            }
        }
    };

    this._onButDealRelease = function(){
        switch(_iCurState){
            case STATE_GAME_WAITING_FOR_BET:{
                    this.dealCards();
                    break;
            }
            case STATE_GAME_CHOOSE_HOLD:{
                    this.drawCards();
                    break;
            }
            case STATE_GAME_EVALUATE:{
                    this.resetHand();
                    this.dealCards();
                    break;
            }
        }
    };
    
    this._onButLeftRelease = function(){
        if(_iCurBetIndex === 0 || _bBlock || (_iCurState !== STATE_GAME_WAITING_FOR_BET && _iCurState !== STATE_GAME_EVALUATE)){
            return;
        }
        
        MIN_WIN = COMBO_PRIZES[COMBO_PRIZES.length-1];
        
        _iCurBetIndex--;
        var iNewBet= parseFloat(BET_TYPE[_iCurBetIndex] * (_iCurCreditIndex+1));
        if(_iMoney < iNewBet){
            _iCurBetIndex++;
            return;
        }
        
        _iCurBet = iNewBet;
        _oInterface.refreshMoney(_iMoney,_iCurBet);
        _oInterface.refreshBet(BET_TYPE[_iCurBetIndex]);
        
        MIN_WIN = (COMBO_PRIZES[COMBO_PRIZES.length-1] * BET_TYPE[_iCurBetIndex]) * (_iCurCreditIndex+1);

    };
    
    this._onButRightRelease = function(){
        if(_iCurBetIndex === BET_TYPE.length-1 || _bBlock || (_iCurState !== STATE_GAME_WAITING_FOR_BET && _iCurState !== STATE_GAME_EVALUATE)){
            return;
        }
        
        _iCurBetIndex++;
        var iNewBet= parseFloat(BET_TYPE[_iCurBetIndex] * (_iCurCreditIndex+1));
        if(_iMoney < iNewBet){
            _iCurBetIndex--;
            return;
        }
        
        _iCurBet = iNewBet;
        _oInterface.refreshMoney(_iMoney,_iCurBet);
        _oInterface.refreshBet(BET_TYPE[_iCurBetIndex]);
        
        MIN_WIN = (COMBO_PRIZES[COMBO_PRIZES.length-1] * BET_TYPE[_iCurBetIndex]) * (_iCurCreditIndex+1);

    };
    
    this._onButBetOneRelease = function(){
        if(_bBlock || (_iCurState !== STATE_GAME_WAITING_FOR_BET && _iCurState !== STATE_GAME_EVALUATE)){
            return;
        }
        
        _iCurCreditIndex++;
        if(_iCurCreditIndex === NUM_BETS){
            _iCurCreditIndex = 0;
        }
        
        var iNewBet= parseFloat(BET_TYPE[_iCurBetIndex] * (_iCurCreditIndex+1));
        if(_iMoney < iNewBet){
            if(_iCurCreditIndex === 0){
                _iCurCreditIndex = NUM_BETS-1;
            }else{
                _iCurCreditIndex--;
            }
            return;
        }
        
        _iCurBet = iNewBet;
        _oInterface.refreshMoney(_iMoney,_iCurBet);
        
        _oPayTable.setCreditColumn(_iCurCreditIndex);
        
        MIN_WIN = (COMBO_PRIZES[COMBO_PRIZES.length-1] * BET_TYPE[_iCurBetIndex]) * (_iCurCreditIndex+1);
    };
    
    this._onButBetMaxRelease = function(){
        if(_bBlock || (_iCurState !== STATE_GAME_WAITING_FOR_BET && _iCurState !== STATE_GAME_EVALUATE) ){
            return;
        }
	_bBlock = true;
		
        _iCurCreditIndex = NUM_BETS-1;
        var iNewBet= parseFloat(BET_TYPE[_iCurBetIndex] * (_iCurCreditIndex+1));
        if(_iMoney < iNewBet){
            this._gameOver();
            return;
        }
        
        _iCurBet = iNewBet;
        _oInterface.refreshMoney(_iMoney,_iCurBet);
        _oPayTable.setCreditColumn(_iCurCreditIndex);
        
        MIN_WIN = (COMBO_PRIZES[COMBO_PRIZES.length-1] * BET_TYPE[_iCurBetIndex]) * (_iCurCreditIndex+1);
        
	this.resetHand();
        this.dealCards();
    };
    
    this.onExit = function(){
        this.unload();

        s_oMain.gotoMenu();
        $(s_oMain).trigger("end_session");
    };

    this.update = function(){

    };
    
    s_oGame = this;
    
    WIN_OCCURRENCE = oData.win_occurrence;
    GAME_CASH = oData.game_cash;
    BET_TYPE = oData.bets;
    COMBO_PRIZES = oData.combo_prizes;
    TOTAL_MONEY = oData.money;
    AUTOMATIC_RECHARGE = oData.recharge;
    NUM_HAND_FOR_ADS = oData.num_hand_before_ads;
    
    this._init();
}

var s_oGame;
 var s_oPayTableSettings;