function CBetList(){
    var _aBetPerGreyhound;
    var _aForecastBet;
    var _aHistory;
    
    this._init = function(){
        this.reset();
    };
    
    this.reset = function(){
        _aBetPerGreyhound = new Array();
        for(var i=0;i<NUM_GREYHOUNDS;i++){
            _aBetPerGreyhound[i] = new Array();
            _aBetPerGreyhound[i]["place_1"] = 0;
            _aBetPerGreyhound[i]["place_2"] = 0;
            _aBetPerGreyhound[i]["place_3"] = 0;
        }
        
        _aForecastBet = new Array();
        for(var i=0;i<NUM_GREYHOUNDS;i++){
            _aForecastBet[i] = new Array();
            for(var j=0;j<NUM_GREYHOUNDS;j++){
                _aForecastBet[i][j] = 0;
            }
        }
        
        _aHistory = new Array();
    };
    
    this.addSimpleBet = function(iGreyhoundIndex,iPlace,iBet){
        _aBetPerGreyhound[iGreyhoundIndex]["place_"+iPlace] += iBet;
        
        var iWin = 0;
        switch(iPlace){
            case 1:{
                    iWin = iBet * s_oGameSettings.getOddWin(iGreyhoundIndex);
                    break;
            }
            case 2:{
                    iWin = iBet * s_oGameSettings.getOddPlace(iGreyhoundIndex);
                    break;
            }
            case 3:{
                    iWin = iBet * s_oGameSettings.getOddShow(iGreyhoundIndex);
                    break;
            }
        }
        _aHistory.push({type_bet:"simple",greyhounds:[{index:iGreyhoundIndex,place:iPlace}],bet:iBet,win:iWin});
    };
    
    this.addForecastBet = function(iFirst,iSecond,iBet){
        _aForecastBet[iFirst][iSecond] += iBet;
        
        
        _aHistory.push({type_bet:"forecast",greyhounds:[{index:iFirst,place:1},{index:iSecond,place:2}],bet:iBet,win:iBet*s_oGameSettings.getForecastOdd(iFirst,iSecond)});
    };
    
    this.getMinWin = function(){

        if(_aHistory.length > 0){
            var iMinWin = _aHistory[0].win;
            for(var i=1;i<_aHistory.length;i++){
                if(iMinWin > _aHistory[i].win){
                    iMinWin = _aHistory[i].win;
                }
            }
            
            return iMinWin;
        }
        
        return 0;
    };
    
    this.getTotWinWithCurRank = function(aRank){
        var iWin = 0;
		var iTotWin = 0;
        var aWinList = new Array();
        //GET WIN AMOUNT FOR "WIN BET" EVENTUALLY
        if(_aBetPerGreyhound[aRank[0]]["place_1"] > 0){
            iWin = _aBetPerGreyhound[aRank[0]]["place_1"] * s_oGameSettings.getOddWin(aRank[0]);
            iWin = parseFloat(iWin.toFixed(2));
			iTotWin += iWin;
            aWinList.push({win:iWin,greyhounds:aRank[0],bet:_aBetPerGreyhound[aRank[0]]["place_1"],type:"win"});
        }
        
                
        //GET WIN AMOUNT FOR "PLACE BET" EVENTUALLY
        if(_aBetPerGreyhound[aRank[0]]["place_2"] > 0){
            iWin = _aBetPerGreyhound[aRank[0]]["place_2"] * s_oGameSettings.getOddPlace(aRank[0]); 
            iWin = parseFloat(iWin.toFixed(2));
			iTotWin += iWin;
            aWinList.push({win:iWin,greyhounds:aRank[0],bet:_aBetPerGreyhound[aRank[0]]["place_2"],type:"place"});
        }
        
        if(_aBetPerGreyhound[aRank[1]]["place_2"] > 0){
            iWin = _aBetPerGreyhound[aRank[1]]["place_2"] * s_oGameSettings.getOddPlace(aRank[1]); 
            iWin = parseFloat(iWin.toFixed(2));
			iTotWin += iWin;
            aWinList.push({win:iWin,greyhounds:aRank[1],bet:_aBetPerGreyhound[aRank[1]]["place_2"],type:"place"});
        }
        
                
        //GET WIN AMOUNT FOR "SHOW BET" EVENTUALLY
        if(_aBetPerGreyhound[aRank[0]]["place_3"] > 0){
            iWin = _aBetPerGreyhound[aRank[0]]["place_3"] * parseFloat(s_oGameSettings.getOddShow(aRank[0]));
            iWin = parseFloat(iWin.toFixed(2));
			iTotWin += iWin;
            aWinList.push({win:iWin,greyhounds:aRank[0],bet:_aBetPerGreyhound[aRank[0]]["place_3"],type:"show"});
        }
        
        if(_aBetPerGreyhound[aRank[1]]["place_3"] > 0){
            iWin = _aBetPerGreyhound[aRank[1]]["place_3"] * s_oGameSettings.getOddShow(aRank[1]);
            iWin = parseFloat(iWin.toFixed(2));
			iTotWin += iWin;
            aWinList.push({win:iWin,greyhounds:aRank[1],bet:_aBetPerGreyhound[aRank[1]]["place_3"],type:"show"});
        }
        
        if(_aBetPerGreyhound[aRank[2]]["place_3"] > 0){
            iWin = _aBetPerGreyhound[aRank[2]]["place_3"] * s_oGameSettings.getOddShow(aRank[2]);
            iWin = parseFloat(iWin.toFixed(2));
			iTotWin += iWin;
            aWinList.push({win:iWin,greyhounds:aRank[2],bet:_aBetPerGreyhound[aRank[2]]["place_3"],type:"show"});
        }
        
        
        //GET WIN AMOUNT FOR FORECAST BET EVENTUALLY
        if(_aForecastBet[aRank[0]][aRank[1]] > 0){
            iWin = _aForecastBet[aRank[0]][aRank[1]] *s_oGameSettings.getForecastOdd(aRank[0],aRank[1]);
            iWin = parseFloat(iWin.toFixed(2));
			iTotWin += iWin;
            aWinList.push({win:iWin,greyhounds:[aRank[0],aRank[1]],bet:_aForecastBet[aRank[0]][aRank[1]],type:"forecast"});
        }
        
        
        return {tot_win:iTotWin,win_list:aWinList};
    };
    
    s_oBetList = this;
    this._init();
}

var s_oBetList = null;