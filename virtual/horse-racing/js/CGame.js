function CGame(iTotBet) {
    var _bUpdate;
    var _bWin;
    var _bCheckArrival;
    var _bCachingTrack;
    var _iTotBet = iTotBet;
    var _iWin;
    var _iHorseArrived;
    var _iTimeElaps;
    var _aFinalRank;
    var _aWinList;
    var _aHorses;
    var _aCages;
    var _aGates;
    var _aHorseNames;
    
    var _oTrackContainer;
    var _oFlashlight;
    var _oTrack;
    var _oInterface;
    var _oRankingPanel;
    var _oArrivalPanel;
    var _oFpsText;
    
    this._init = function () {
        _bUpdate = false;
        _bCheckArrival = false;
        _bCachingTrack = false;
        _iHorseArrived = 0;
        _iTimeElaps = 0;
        _aHorseNames = s_oGameSettings.getAllHorseNames()
        
        setVolume("soundtrack", 0);
        s_oTweenController = new CTweenController();
        
        _oTrackContainer = new createjs.Container();
        s_oStage.addChild(_oTrackContainer);
 
        _oTrack = new CTrackBg(_oTrackContainer);

        _oRankingPanel = new CRankingGui(_aHorseNames,s_oStage);
        _oArrivalPanel = new CArrivalPanel(CANVAS_WIDTH,246,s_oStage);
        _oInterface = new CInterface();
        
        this.generateFinalRank();
        
        this._prepareHorses();
        
        _oFlashlight = new createjs.Shape();
        _oFlashlight.graphics.beginFill("white").drawRect(0, 0, CANVAS_WIDTH, CANVAS_HEIGHT);
        _oFlashlight.alpha = 0;
        s_oStage.addChild(_oFlashlight);

        $(s_oMain).trigger("start_level", 1);
        
        
        _oFpsText = new createjs.Text("", "40px " + PRIMARY_FONT, "#000");
        _oFpsText.textAlign = "center";
        _oFpsText.textBaseline = "alphabetic";
	_oFpsText.x = CANVAS_WIDTH/2;
        _oFpsText.y = 220;
	//s_oStage.addChild(_oFpsText);
        
        setTimeout(function(){s_oGame.startRace();},1000);
        playSound("start_race",1,0);
        this.refreshButtonPos();
    };
    
    this.unload = function () {
        stopSound("start_race");
        _oInterface.unload();
        createjs.Tween.removeAllTweens();
        s_oStage.removeAllChildren();
        
        s_oGame = null;
    };
    
    this.refreshButtonPos = function () {
        _oInterface.refreshButtonPos(s_iOffsetX,s_iOffsetY);
        _oRankingPanel.refreshButtonPos(s_iOffsetX,s_iOffsetY);
        _oArrivalPanel.refreshButtonPos(s_iOffsetX,s_iOffsetY);
    };
    
    this.pause = function(){
        _bUpdate = false;
        pauseSound("start_race");
         
        for(var i=0;i<NUM_HORSES;i++){
            _aHorses[i].pauseAnim();
        }
    };
    
    this.unpause = function(){
        _bUpdate = true;
        playSound("start_race");
        
        for(var i=0;i<NUM_HORSES;i++){
            _aHorses[i].unpauseAnim();
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
        for(var i=0;i<_aGates.length;i++){
            _aGates[i].playAnim();
        }
        
        
        _oInterface.blockExit(false);
        _bUpdate = true;
        
        
        _oTrack.startTrack();
        setTimeout(function(){s_oGame.startHorses();},100);
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
        
        _iWin = parseFloat(_iWin.toFixed(2));
        
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
        
        var aPercLosingHorse = s_oGameSettings.getHorsePercentageArray();
        while(aRank.length < NUM_HORSES){
            var iRand = Math.floor(Math.random()*aPercLosingHorse.length);
            var iElemToRemove = aPercLosingHorse[iRand];
            aRank.unshift(iElemToRemove);
            //REMOVE HORSE INDEX FROM PERC ARRAY
            var j = aPercLosingHorse.length-1;
            while(j>=0){
                if(aPercLosingHorse[j] === iElemToRemove){
                    aPercLosingHorse.splice(j,1);
                }
                j--;
            }
        }
        
        return aRank;
    };
    
    this._prepareHorses = function(){
        _aCages = new Array();
        _aGates = new Array();
        _aHorses = new Array();
        
        var aPath = s_oGameSettings.getRandomPath();
        
        var aGatePos = [{x:266,y:233},{x:248,y:234},{x:229,y:235},{x:208,y:237},{x:183,y:239},{x:154,y:241},{x:120,y:244},{x:83,y:246},{x:62,y:249}];
        for(var i=0;i<NUM_HORSES;i++){
            var oCage = createBitmap(s_oSpriteLibrary.getSprite("cage_"+i));
            _oTrackContainer.addChild(oCage);
            _aCages.push(oCage);
        
            var oInfo = s_oGameSettings.getHorseInfo(i);
            var iRank = _aFinalRank.indexOf(i);
            _aHorses[i] = new CHorse(oInfo.start,i+1,_aHorseNames[i],oInfo.scale,aPath["place_"+(iRank+1)],_oTrackContainer);
            
            var oGate = new CGate(aGatePos[i].x,aGatePos[i].y,i,_oTrackContainer);
            _aGates.push(oGate);
        }
        
        var oCage = createBitmap(s_oSpriteLibrary.getSprite("cage_"+NUM_HORSES));
        _oTrackContainer.addChild(oCage);
        _aCages.push(oCage);
        
        var oGate = new CGate(aGatePos[NUM_HORSES].x,aGatePos[NUM_HORSES].y,NUM_HORSES,_oTrackContainer);
        _aGates.push(oGate);
    };
    
    this.startHorses = function(){
        for(var i=0;i<NUM_HORSES;i++){
            _aHorses[i].startRace();
        }
    };
    
    this.horsePhotofinish = function(iIndex,szName){
        _oInterface.blockExit(true);
        _oArrivalPanel.refreshRank(iIndex,szName);
        
        _iHorseArrived++;
        if(_iHorseArrived < 4){
            _bUpdate = false;
            _iTimeElaps = TIME_CHECK_RANK;
            this._playFlashAnim();
        }else if(_iHorseArrived === 6){
            if(_bWin || _iWin > 0){
				s_iCurMoney += _iWin;
				s_iGameCash -= _iWin;
				s_iCurMoney = parseFloat(s_iCurMoney.toFixed(2));
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
    
    this.checkHorseArrival = function(){
        _bCheckArrival = true;
        this._refreshRank();
    };
    
    this._playFlashAnim = function(){
        for(var i=0;i<NUM_HORSES;i++){
            _aHorses[i].pauseAnim();
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
            
            for(var i=0;i<NUM_HORSES;i++){
                _aHorses[i].unpauseAnim();
            }

            _oTrackContainer.filters = [];
            _oTrackContainer.cache(0,0,CANVAS_WIDTH,CANVAS_HEIGHT);
            _bUpdate = true;
        },1000);
    };
    
    this.moveCages = function(iFrame){
        if(iFrame === 4){
            //REMOVE CAGES
            for(var i=0;i<_aCages.length;i++){
                _oTrackContainer.removeChild(_aCages[i]); 
            }
            
            for(var j=0;j<_aGates.length;j++){
                _aGates[j].unload();
            }
        
            return;
        }
        
        var aPosDiff = [123,255,370];
        for(var i=0;i<_aCages.length;i++){
            _aCages[i].x -= aPosDiff[iFrame-1]; 
        }

        for(var j=0;j<_aGates.length;j++){
            _aGates[j].decreaseX(aPosDiff[iFrame-1]);
        }
    };
    
    this._refreshRank = function(){
        var aRank = new Array();
        for(var i=0;i<NUM_HORSES;i++){
            var iX = _aHorses[i].getX();
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

        _oRankingPanel.refreshRadar(iNumBg);
        
        for(var i=0;i<NUM_HORSES;i++){
            _aHorses[i].update(_bCheckArrival);
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
        
        //_oFpsText.text = "FPS: "+s_iCurFps;
    };

    s_oGame = this;

    this._init();
}

var s_oGame = null;
var s_oTweenController;