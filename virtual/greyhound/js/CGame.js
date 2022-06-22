function CGame(iTotBet) {
    var _bUpdate;
    var _bWin;
    var _bCheckArrival;
    var _bCachingTrack;
    var _iTotBet = iTotBet;
    var _iWin;
    var _iGreyhoundArrived;
    var _iTimeElaps;
    var _aFinalRank;
    var _aWinList;
    var _aGreyHounds;
    var _aGreyhoundNames;
    
    var _oTrackContainer;
    var _oFlashlight;
    var _oTrack;
    var _oInterface;
    var _oRankingPanel;
    var _oArrivalPanel;
    
    this._init = function () {
        _bUpdate = false;
        _bCheckArrival = false;
        _bCachingTrack = false;
        _iGreyhoundArrived = 0;
        _iTimeElaps = 0;
        _aGreyhoundNames = s_oGameSettings.getAllGreyhoundNames()
        
        setVolume("soundtrack", 0);
        s_oTweenController = new CTweenController();
        
        _oTrackContainer = new createjs.Container();
        s_oStage.addChild(_oTrackContainer);
 
        _oTrack = new CTrackBg(_oTrackContainer);

        _oRankingPanel = new CRankingGui(_aGreyhoundNames,s_oStage);
        _oArrivalPanel = new CArrivalPanel(CANVAS_WIDTH,240,s_oStage);
        _oInterface = new CInterface();
        
        this.generateFinalRank();
        
        this._prepareGreyHounds();
        
        _oFlashlight = new createjs.Shape();
        _oFlashlight.graphics.beginFill("white").drawRect(0, 0, CANVAS_WIDTH, CANVAS_HEIGHT);
        _oFlashlight.alpha = 0;
        s_oStage.addChild(_oFlashlight);

        $(s_oMain).trigger("start_level", 1);
        
        
        setTimeout(function(){s_oGame.startRace();},1000);

        this.refreshButtonPos(s_iOffsetX, s_iOffsetY);
    };
    
    this.unload = function () {
        stopSound("start_race");
        _oInterface.unload();
        createjs.Tween.removeAllTweens();
        s_oStage.removeAllChildren();
        
        s_oGame = null;
    };
    
    this.refreshButtonPos = function (iNewX, iNewY) {
        _oInterface.refreshButtonPos(iNewX,iNewY);
        _oRankingPanel.refreshButtonPos(iNewX,iNewY);
    };
    
    this.pause = function(){
        _bUpdate = false;
        pauseSound("start_race");
        for(var i=0;i<NUM_GREYHOUNDS;i++){
            _aGreyHounds[i].pauseAnim();
        }
    };
    
    this.unpause = function(){
        _bUpdate = true;
        playSound("start_race");
        
        for(var i=0;i<NUM_GREYHOUNDS;i++){
            _aGreyHounds[i].unpauseAnim();
        }
    };
    
    this.onExit = function () {
        setVolume("soundtrack", 1);
        
        s_oGame.unload();
        s_oMain.gotoMenu();

        $(s_oMain).trigger("end_session");
        $(s_oMain).trigger("show_interlevel_ad");
        $(s_oMain).trigger("share_event",[s_iCurMoney]);
    };
    
    this.gotoBetPanel = function(){
        setVolume("soundtrack", 1);
        
        s_oGame.unload();
        s_oMain.gotoBetPanel();
    };
    
    this.startRace = function(){
        playSound("start_race",1,0);
        _oInterface.blockExit(false);
        _bUpdate = true;
    };
    
    this.generateFinalRank = function(){       
        var iMinWin = s_oBetList.getMinWin();
        if(iMinWin > s_iGameCash){
            //USER MUST LOSE
            this._generateLosingResult();
        }else{
            var iRandWin = Math.random()*100;
            if(iRandWin < WIN_OCCURRENCE){
                //WIN
                this._generateWinResult();
            }else{
                //LOSE
                this._generateLosingResult();
            }
        }
    };
    
    this._generateWinResult = function(){
        _bWin = true;
        do{
            _aFinalRank = this._generateRandomRank();
            var oRet = s_oBetList.getTotWinWithCurRank(_aFinalRank);
            _iWin = oRet.tot_win;
        }while(_iWin <= _iTotBet);
        
        _aWinList = oRet.win_list;
    };
    
    this._generateLosingResult = function(){
        _bWin = false;
        do{
            _aFinalRank = this._generateRandomRank();
            var oRet = s_oBetList.getTotWinWithCurRank(_aFinalRank);
            _iWin = oRet.tot_win;
        }while(_iWin > _iTotBet);
        
        _aWinList = oRet.win_list;
    };
    
    this._generateRandomRank = function(){
        var aRank = new Array();
        
        var aPercLosingGreyhound = s_oGameSettings.getGreyhoundPercentageArray();
        while(aRank.length < NUM_GREYHOUNDS){
            var iRand = Math.floor(Math.random()*aPercLosingGreyhound.length);
            var iElemToRemove = aPercLosingGreyhound[iRand];
            aRank.unshift(iElemToRemove);
            //REMOVE GREYHOUND INDEX FROM PERC ARRAY
            var j = aPercLosingGreyhound.length-1;
            while(j>=0){
                if(aPercLosingGreyhound[j] === iElemToRemove){
                    aPercLosingGreyhound.splice(j,1);
                }
                j--;
            }
        }
        
        return aRank;
    };
    
    this._prepareGreyHounds = function(){
        _aGreyHounds = new Array();
        var aPath = s_oGameSettings.getRandomPath();
        for(var i=0;i<NUM_GREYHOUNDS;i++){
            var oInfo = s_oGameSettings.getGreyhoundInfo(i);
            var iRank = _aFinalRank.indexOf(i);

            _aGreyHounds[i] = new CGreyhound(oInfo.start,i+1,_aGreyhoundNames[i],oInfo.scale,aPath["place_"+(iRank+1)],_oTrackContainer);
        }
    };
    
    this.startGreyhounds = function(){
        for(var i=0;i<NUM_GREYHOUNDS;i++){
            _aGreyHounds[i].startRace();
        }
    };
    
    this.greyhoundPhotofinish = function(iIndex,szName){
        _oInterface.blockExit(true);
        _oArrivalPanel.refreshRank(iIndex,szName);
        
        _iGreyhoundArrived++;
        if(_iGreyhoundArrived < 4){
            _bUpdate = false;
            _iTimeElaps = TIME_CHECK_RANK;
            this._playFlashAnim();
        }else if(_iGreyhoundArrived === 6){
            if(_bWin || _iWin > 0){
                s_iCurMoney += _iWin;
                s_iCurMoney = parseFloat(s_iCurMoney.toFixed(2));
                s_iGameCash -= _iWin;
                s_iGameCash = parseFloat(s_iGameCash.toFixed(2)); 
        
                _oInterface.showWinPanel(_iWin,_aWinList,_aFinalRank);
            }else{
                _oInterface.showLosePanel(_aFinalRank);
            }
            stopSound("start_race");
            setVolume("soundtrack", 1);

            $(s_oMain).trigger("save_score",s_iCurMoney);
        }
    };
    
    this.checkGreyhoundArrival = function(){
        _bCheckArrival = true;
    };
    
    this._playFlashAnim = function(){
        for(var i=0;i<NUM_GREYHOUNDS;i++){
            _aGreyHounds[i].pauseAnim();
        }
        
        playSound("photo", 1, 0);
        
        createjs.Tween.get(_oFlashlight).to({alpha: 0.8}, 200).call(function () {
            
            var matrix = new createjs.ColorMatrix().adjustSaturation(-100);
            _oTrackContainer.filters = [
                 new createjs.ColorMatrixFilter(matrix)
            ];

            _oTrackContainer.cache(0,0,CANVAS_WIDTH,CANVAS_HEIGHT);
            _bCachingTrack = true;
            createjs.Tween.get(_oFlashlight).to({alpha: 0}, 400).call(function(){
                                                                                s_oGame.restoreRaceAfterFlash();
                                                                            });
        });
    };
    
    this.restoreRaceAfterFlash= function(){
        setTimeout(function(){
            
            for(var i=0;i<NUM_GREYHOUNDS;i++){
                _aGreyHounds[i].unpauseAnim();
            }

            _oTrackContainer.filters = [];
            _oTrackContainer.cache(0,0,CANVAS_WIDTH,CANVAS_HEIGHT);
            _bUpdate = true;
        },1000);
    };
    
    this._refreshRank = function(){
        var aRank = new Array();
        for(var i=0;i<NUM_GREYHOUNDS;i++){
            var iX = _aGreyHounds[i].getX();
            aRank[i] = {index:i,x:iX};
        }
        
        aRank.sort(this.compareXPos);
        
        _oRankingPanel.refreshRank(aRank);
    };
    
    this.compareXPos = function(a,b) {
        if (a.x > b.x)
           return -1;
        if (a.x < b.x)
          return 1;
        return 0;
    };
    
    this.returnInBetPanel = function(){
        s_iAdCounter++;
        if(s_iAdCounter === AD_SHOW_COUNTER){
            s_iAdCounter = 0;
            $(s_oMain).trigger("show_interlevel_ad");
        }
        
        s_oGame.gotoBetPanel();
    };
    
    this.update = function () {
        if(!_bUpdate){
            return;
        }
        
        var iNumBg = _oTrack.update();

        _oRankingPanel.refreshRadar(iNumBg-16);
        
        for(var i=0;i<NUM_GREYHOUNDS;i++){
            _aGreyHounds[i].update(_bCheckArrival);
        }
        
        if(_bCachingTrack){
            _oTrackContainer.updateCache();
        }
        
        //REFRESH RANK GUI
        _iTimeElaps += s_iTimeElaps;
        if(_iTimeElaps > TIME_CHECK_RANK && !_bCheckArrival){
            _iTimeElaps = 0;
            this._refreshRank();
        }
    };

    s_oGame = this;

    this._init();
}

var s_oGame = null;
var s_oTweenController;