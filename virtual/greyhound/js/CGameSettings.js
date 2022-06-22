function CGameSettings(oJsonData){
    var _aGreyhoundNames;
    var _aGreyhoundWinOdd;
    var _aGreyhoundPlaceOdd;
    var _aGreyhoundShowOdd;
    var _aForecastOdd;
    var _aFichesValue;
    var _aPathSequence;
    var _aGreyhoundStartInfo;
    
    var _oJsonData;
    
    this._init = function(oJsonData){
        _oJsonData = oJsonData;
        
        var aNames = _oJsonData.greyhound_names;
        NUM_GREYHOUNDS = aNames.length;
        _aGreyhoundNames = new Array();
        for(var i=0;i<NUM_GREYHOUNDS;i++){
           _aGreyhoundNames[i] = aNames[i];
        }
        
        this._initSimpleOdd();
        this._initForecastOdd();
        this._initPaths();
        this._initGreyhoundInfo();
        
        _aFichesValue=CHIP_VALUES;
    };
    
    this._initSimpleOdd = function(){
        //STORE ODD INFOS
        var aOddWin = _oJsonData.odd_win_bet;
        var aOddPlace = _oJsonData.odd_place_bet;
        var aOddShow = _oJsonData.odd_show_bet;
        _aGreyhoundWinOdd = new Array();
        _aGreyhoundPlaceOdd = new Array();
        _aGreyhoundShowOdd = new Array();
        for(var j=0;j<aOddWin.length;j++){
            _aGreyhoundWinOdd[j] = aOddWin[j];
            _aGreyhoundPlaceOdd[j] = aOddPlace[j];
            _aGreyhoundShowOdd[j] = aOddShow[j];
        }
    };
    
    this._initForecastOdd = function(){
        var aForecast = _oJsonData.forecast;
        
        _aForecastOdd = new Array();
        for(var i=0;i<NUM_GREYHOUNDS;i++){
            _aForecastOdd[i] = new Array();
        }
        
        for(var k=0;k<aForecast.length;k++){
            _aForecastOdd[aForecast[k].first-1][aForecast[k].second-1] = aForecast[k].odd;
        }
    };
    
    this._initPaths = function(){
        _aPathSequence = new Array();
        
        //PATH SEQUENCE #1
        var aPlace1 = [{x:230,frame:30},{x:500,frame:180},{x:600,frame:180},{x:400,frame:200},{x:ARRIVAL_X - GREYHOUND_WIDTH/2,frame:210},{x:CANVAS_WIDTH+GREYHOUND_WIDTH,frame:30}];
        var aPlace2 = [{x:250,frame:30},{x:600,frame:200},{x:650,frame:180},{x:450,frame:180},{x:ARRIVAL_X - GREYHOUND_WIDTH/2-150,frame:210},{x:CANVAS_WIDTH+GREYHOUND_WIDTH,frame:35}];
        var aPlace3 = [{x:170,frame:30},{x:400,frame:150},{x:450,frame:210},{x:400,frame:140},{x:300,frame:130},{x:ARRIVAL_X - GREYHOUND_WIDTH/2-250,frame:140},{x:CANVAS_WIDTH+GREYHOUND_WIDTH,frame:40}];
        var aPlace4 = [{x:190,frame:30},{x:340,frame:150},{x:360,frame:300},{x:ARRIVAL_X - GREYHOUND_WIDTH/2-390,frame:320},{x:CANVAS_WIDTH+GREYHOUND_WIDTH,frame:45}];
        var aPlace5 = [{x:220,frame:30},{x:350,frame:280},{x:480,frame:190},{x:320,frame:100},{x:ARRIVAL_X - GREYHOUND_WIDTH/2-450,frame:200},{x:CANVAS_WIDTH+GREYHOUND_WIDTH,frame:50}];
        var aPlace6 = [{x:210,frame:30},{x:260,frame:290},{x:ARRIVAL_X - GREYHOUND_WIDTH/2-600,frame:480},{x:CANVAS_WIDTH+GREYHOUND_WIDTH,frame:55}];
        _aPathSequence.push({"place_1":aPlace1,"place_2":aPlace2,"place_3":aPlace3,"place_4":aPlace4,"place_5":aPlace5,"place_6":aPlace6});
        
        //PATH SEQUENCE #2
        var aPlace1 = [{x:230,frame:30},{x:450,frame:180},{x:500,frame:180},{x:400,frame:200},{x:ARRIVAL_X - GREYHOUND_WIDTH/2,frame:210},{x:CANVAS_WIDTH+GREYHOUND_WIDTH,frame:30}];
        var aPlace2 = [{x:250,frame:30},{x:600,frame:200},{x:550,frame:180},{x:350,frame:180},{x:ARRIVAL_X - GREYHOUND_WIDTH/2-150,frame:210},{x:CANVAS_WIDTH+GREYHOUND_WIDTH,frame:35}];
        var aPlace3 = [{x:170,frame:30},{x:450,frame:150},{x:550,frame:210},{x:500,frame:140},{x:400,frame:130},{x:ARRIVAL_X - GREYHOUND_WIDTH/2-250,frame:140},{x:CANVAS_WIDTH+GREYHOUND_WIDTH,frame:40}];
        var aPlace4 = [{x:190,frame:30},{x:300,frame:150},{x:360,frame:300},{x:ARRIVAL_X - GREYHOUND_WIDTH/2-390,frame:320},{x:CANVAS_WIDTH+GREYHOUND_WIDTH,frame:45}];
        var aPlace5 = [{x:220,frame:30},{x:350,frame:280},{x:400,frame:190},{x:320,frame:100},{x:ARRIVAL_X - GREYHOUND_WIDTH/2-450,frame:200},{x:CANVAS_WIDTH+GREYHOUND_WIDTH,frame:50}];
        var aPlace6 = [{x:210,frame:30},{x:360,frame:290},{x:ARRIVAL_X - GREYHOUND_WIDTH/2-600,frame:480},{x:CANVAS_WIDTH+GREYHOUND_WIDTH,frame:55}];
        _aPathSequence.push({"place_1":aPlace1,"place_2":aPlace2,"place_3":aPlace3,"place_4":aPlace4,"place_5":aPlace5,"place_6":aPlace6});
        
        //PATH SEQUENCE #3
        var aPlace1 = [{x:230,frame:30},{x:500,frame:180},{x:600,frame:180},{x:600,frame:200},{x:ARRIVAL_X - GREYHOUND_WIDTH/2,frame:210},{x:CANVAS_WIDTH+GREYHOUND_WIDTH,frame:30}];
        var aPlace2 = [{x:250,frame:30},{x:600,frame:200},{x:650,frame:180},{x:500,frame:180},{x:ARRIVAL_X - GREYHOUND_WIDTH/2-150,frame:210},{x:CANVAS_WIDTH+GREYHOUND_WIDTH,frame:35}];
        var aPlace3 = [{x:170,frame:30},{x:400,frame:150},{x:450,frame:210},{x:600,frame:140},{x:450,frame:130},{x:ARRIVAL_X - GREYHOUND_WIDTH/2-250,frame:140},{x:CANVAS_WIDTH+GREYHOUND_WIDTH,frame:40}];
        var aPlace4 = [{x:190,frame:30},{x:340,frame:150},{x:360,frame:300},{x:ARRIVAL_X - GREYHOUND_WIDTH/2-410,frame:320},{x:CANVAS_WIDTH+GREYHOUND_WIDTH,frame:45}];
        var aPlace5 = [{x:220,frame:30},{x:350,frame:280},{x:480,frame:190},{x:320,frame:100},{x:ARRIVAL_X - GREYHOUND_WIDTH/2-550,frame:200},{x:CANVAS_WIDTH+GREYHOUND_WIDTH,frame:50}];
        var aPlace6 = [{x:210,frame:30},{x:260,frame:290},{x:ARRIVAL_X - GREYHOUND_WIDTH/2-650,frame:480},{x:CANVAS_WIDTH+GREYHOUND_WIDTH,frame:55}];
        _aPathSequence.push({"place_1":aPlace1,"place_2":aPlace2,"place_3":aPlace3,"place_4":aPlace4,"place_5":aPlace5,"place_6":aPlace6});
        
        //PATH SEQUENCE #4
        var aPlace1 = [{x:330,frame:30},{x:450,frame:180},{x:550,frame:180},{x:650,frame:200},{x:ARRIVAL_X - GREYHOUND_WIDTH/2,frame:210},{x:CANVAS_WIDTH+GREYHOUND_WIDTH,frame:30}];
        var aPlace2 = [{x:250,frame:30},{x:350,frame:200},{x:450,frame:180},{x:600,frame:180},{x:ARRIVAL_X - GREYHOUND_WIDTH/2-150,frame:210},{x:CANVAS_WIDTH+GREYHOUND_WIDTH,frame:35}];
        var aPlace3 = [{x:270,frame:30},{x:400,frame:150},{x:450,frame:210},{x:600,frame:140},{x:500,frame:130},{x:ARRIVAL_X - GREYHOUND_WIDTH/2-250,frame:140},{x:CANVAS_WIDTH+GREYHOUND_WIDTH,frame:40}];
        var aPlace4 = [{x:290,frame:30},{x:340,frame:150},{x:360,frame:300},{x:ARRIVAL_X - GREYHOUND_WIDTH/2-410,frame:320},{x:CANVAS_WIDTH+GREYHOUND_WIDTH,frame:45}];
        var aPlace5 = [{x:220,frame:30},{x:350,frame:280},{x:400,frame:190},{x:320,frame:100},{x:ARRIVAL_X - GREYHOUND_WIDTH/2-550,frame:200},{x:CANVAS_WIDTH+GREYHOUND_WIDTH,frame:50}];
        var aPlace6 = [{x:210,frame:30},{x:360,frame:290},{x:ARRIVAL_X - GREYHOUND_WIDTH/2-650,frame:480},{x:CANVAS_WIDTH+GREYHOUND_WIDTH,frame:55}];
        _aPathSequence.push({"place_1":aPlace1,"place_2":aPlace2,"place_3":aPlace3,"place_4":aPlace4,"place_5":aPlace5,"place_6":aPlace6});
        
        //PATH SEQUENCE #5
        var aPlace1 = [{x:230,frame:30},{x:350,frame:180},{x:400,frame:180},{x:600,frame:200},{x:ARRIVAL_X - GREYHOUND_WIDTH/2,frame:210},{x:CANVAS_WIDTH+GREYHOUND_WIDTH,frame:30}];
        var aPlace2 = [{x:250,frame:30},{x:350,frame:200},{x:450,frame:180},{x:500,frame:180},{x:ARRIVAL_X - GREYHOUND_WIDTH/2-150,frame:210},{x:CANVAS_WIDTH+GREYHOUND_WIDTH,frame:35}];
        var aPlace3 = [{x:270,frame:30},{x:400,frame:150},{x:450,frame:210},{x:600,frame:140},{x:500,frame:130},{x:ARRIVAL_X - GREYHOUND_WIDTH/2-250,frame:140},{x:CANVAS_WIDTH+GREYHOUND_WIDTH,frame:40}];
        var aPlace4 = [{x:190,frame:30},{x:340,frame:150},{x:360,frame:300},{x:ARRIVAL_X - GREYHOUND_WIDTH/2-390,frame:320},{x:CANVAS_WIDTH+GREYHOUND_WIDTH,frame:45}];
        var aPlace5 = [{x:220,frame:30},{x:350,frame:280},{x:400,frame:190},{x:320,frame:100},{x:ARRIVAL_X - GREYHOUND_WIDTH/2-550,frame:200},{x:CANVAS_WIDTH+GREYHOUND_WIDTH,frame:50}];
        var aPlace6 = [{x:210,frame:30},{x:260,frame:290},{x:ARRIVAL_X - GREYHOUND_WIDTH/2-650,frame:480},{x:CANVAS_WIDTH+GREYHOUND_WIDTH,frame:55}];
        _aPathSequence.push({"place_1":aPlace1,"place_2":aPlace2,"place_3":aPlace3,"place_4":aPlace4,"place_5":aPlace5,"place_6":aPlace6});
        
        //PATH SEQUENCE #6
        var aPlace1 = [{x:230,frame:50},{x:350,frame:200},{x:400,frame:100},{x:500,frame:200},{x:ARRIVAL_X - GREYHOUND_WIDTH/2,frame:250},{x:CANVAS_WIDTH+GREYHOUND_WIDTH,frame:30}];
        var aPlace2 = [{x:250,frame:100},{x:350,frame:150},{x:450,frame:150},{x:500,frame:200},{x:ARRIVAL_X - GREYHOUND_WIDTH/2-150,frame:200},{x:CANVAS_WIDTH+GREYHOUND_WIDTH,frame:35}];
        var aPlace3 = [{x:270,frame:50},{x:400,frame:150},{x:450,frame:200},{x:400,frame:200},{x:ARRIVAL_X - GREYHOUND_WIDTH/2-250,frame:200},{x:CANVAS_WIDTH+GREYHOUND_WIDTH,frame:40}];
        var aPlace4 = [{x:190,frame:30},{x:340,frame:170},{x:360,frame:300},{x:380,frame:100},{x:ARRIVAL_X - GREYHOUND_WIDTH/2-390,frame:200},{x:CANVAS_WIDTH+GREYHOUND_WIDTH,frame:45}];
        var aPlace5 = [{x:220,frame:100},{x:350,frame:200},{x:400,frame:200},{x:320,frame:100},{x:ARRIVAL_X - GREYHOUND_WIDTH/2-550,frame:200},{x:CANVAS_WIDTH+GREYHOUND_WIDTH,frame:50}];
        var aPlace6 = [{x:210,frame:50},{x:260,frame:300},{x:300,frame:200},{x:220,frame:100},{x:ARRIVAL_X - GREYHOUND_WIDTH/2-650,frame:150},{x:CANVAS_WIDTH+GREYHOUND_WIDTH,frame:55}];
        _aPathSequence.push({"place_1":aPlace1,"place_2":aPlace2,"place_3":aPlace3,"place_4":aPlace4,"place_5":aPlace5,"place_6":aPlace6});
        
        //PATH SEQUENCE #7
        var aPlace1 = [{x:230,frame:50},{x:430,frame:300},{x:ARRIVAL_X - GREYHOUND_WIDTH/2,frame:450},{x:CANVAS_WIDTH+GREYHOUND_WIDTH,frame:30}];
        var aPlace2 = [{x:250,frame:100},{x:350,frame:150},{x:450,frame:150},{x:500,frame:200},{x:ARRIVAL_X - GREYHOUND_WIDTH/2-150,frame:200},{x:CANVAS_WIDTH+GREYHOUND_WIDTH,frame:35}];
        var aPlace3 = [{x:270,frame:50},{x:200,frame:150},{x:450,frame:400},{x:ARRIVAL_X - GREYHOUND_WIDTH/2-250,frame:200},{x:CANVAS_WIDTH+GREYHOUND_WIDTH,frame:40}];
        var aPlace4 = [{x:190,frame:30},{x:340,frame:170},{x:360,frame:300},{x:380,frame:100},{x:ARRIVAL_X - GREYHOUND_WIDTH/2-390,frame:200},{x:CANVAS_WIDTH+GREYHOUND_WIDTH,frame:45}];
        var aPlace5 = [{x:220,frame:100},{x:350,frame:200},{x:400,frame:200},{x:320,frame:100},{x:ARRIVAL_X - GREYHOUND_WIDTH/2-550,frame:200},{x:CANVAS_WIDTH+GREYHOUND_WIDTH,frame:50}];
        var aPlace6 = [{x:210,frame:50},{x:260,frame:300},{x:300,frame:200},{x:220,frame:100},{x:ARRIVAL_X - GREYHOUND_WIDTH/2-650,frame:150},{x:CANVAS_WIDTH+GREYHOUND_WIDTH,frame:55}];
        _aPathSequence.push({"place_1":aPlace1,"place_2":aPlace2,"place_3":aPlace3,"place_4":aPlace4,"place_5":aPlace5,"place_6":aPlace6});
        
        //PATH SEQUENCE #8
        var aPlace1 = [{x:230,frame:50},{x:250,frame:300},{x:ARRIVAL_X - GREYHOUND_WIDTH/2,frame:450},{x:CANVAS_WIDTH+GREYHOUND_WIDTH,frame:30}];
        var aPlace2 = [{x:250,frame:50},{x:430,frame:300},{x:ARRIVAL_X - GREYHOUND_WIDTH/2-150,frame:450},{x:CANVAS_WIDTH+GREYHOUND_WIDTH,frame:35}];
        var aPlace3 = [{x:270,frame:50},{x:330,frame:300},{x:ARRIVAL_X - GREYHOUND_WIDTH/2-250,frame:450},{x:CANVAS_WIDTH+GREYHOUND_WIDTH,frame:40}];
        var aPlace4 = [{x:190,frame:50},{x:230,frame:300},{x:ARRIVAL_X - GREYHOUND_WIDTH/2-390,frame:450},{x:CANVAS_WIDTH+GREYHOUND_WIDTH,frame:45}];
        var aPlace5 = [{x:220,frame:50},{x:300,frame:300},{x:ARRIVAL_X - GREYHOUND_WIDTH/2-550,frame:450},{x:CANVAS_WIDTH+GREYHOUND_WIDTH,frame:50}];
        var aPlace6 = [{x:210,frame:50},{x:130,frame:300},{x:ARRIVAL_X - GREYHOUND_WIDTH/2-650,frame:450},{x:CANVAS_WIDTH+GREYHOUND_WIDTH,frame:55}];
        _aPathSequence.push({"place_1":aPlace1,"place_2":aPlace2,"place_3":aPlace3,"place_4":aPlace4,"place_5":aPlace5,"place_6":aPlace6});
        
        //PATH SEQUENCE #9
        var aPlace1 = [{x:210,frame:50},{x:130,frame:300},{x:ARRIVAL_X - GREYHOUND_WIDTH/2,frame:450},{x:CANVAS_WIDTH+GREYHOUND_WIDTH,frame:30}];
        var aPlace2 = [{x:220,frame:50},{x:300,frame:300},{x:ARRIVAL_X - GREYHOUND_WIDTH/2-150,frame:450},{x:CANVAS_WIDTH+GREYHOUND_WIDTH,frame:35}];
        var aPlace3 = [{x:270,frame:50},{x:330,frame:300},{x:ARRIVAL_X - GREYHOUND_WIDTH/2-250,frame:450},{x:CANVAS_WIDTH+GREYHOUND_WIDTH,frame:40}];
        var aPlace4 = [{x:250,frame:50},{x:430,frame:300},{x:ARRIVAL_X - GREYHOUND_WIDTH/2-390,frame:450},{x:CANVAS_WIDTH+GREYHOUND_WIDTH,frame:45}];
        var aPlace5 = [{x:190,frame:50},{x:190,frame:300},{x:ARRIVAL_X - GREYHOUND_WIDTH/2-550,frame:450},{x:CANVAS_WIDTH+GREYHOUND_WIDTH,frame:50}];
        var aPlace6 = [{x:230,frame:50},{x:250,frame:300},{x:ARRIVAL_X - GREYHOUND_WIDTH/2-650,frame:450},{x:CANVAS_WIDTH+GREYHOUND_WIDTH,frame:55}];
        _aPathSequence.push({"place_1":aPlace1,"place_2":aPlace2,"place_3":aPlace3,"place_4":aPlace4,"place_5":aPlace5,"place_6":aPlace6});
        
        //PATH SEQUENCE #10
        var aPlace1 = [{x:330,frame:30},{x:450,frame:180},{x:550,frame:180},{x:650,frame:200},{x:ARRIVAL_X - GREYHOUND_WIDTH/2,frame:210},{x:CANVAS_WIDTH+GREYHOUND_WIDTH,frame:30}];
        var aPlace2 = [{x:250,frame:90},{x:450,frame:300},{x:600,frame:200},{x:ARRIVAL_X - GREYHOUND_WIDTH/2-150,frame:210},{x:CANVAS_WIDTH+GREYHOUND_WIDTH,frame:35}];
        var aPlace3 = [{x:270,frame:30},{x:400,frame:150},{x:450,frame:210},{x:600,frame:140},{x:500,frame:130},{x:ARRIVAL_X - GREYHOUND_WIDTH/2-250,frame:140},{x:CANVAS_WIDTH+GREYHOUND_WIDTH,frame:40}];
        var aPlace4 = [{x:290,frame:30},{x:340,frame:150},{x:360,frame:300},{x:ARRIVAL_X - GREYHOUND_WIDTH/2-410,frame:320},{x:CANVAS_WIDTH+GREYHOUND_WIDTH,frame:45}];
        var aPlace5 = [{x:220,frame:30},{x:350,frame:280},{x:400,frame:190},{x:320,frame:100},{x:ARRIVAL_X - GREYHOUND_WIDTH/2-550,frame:200},{x:CANVAS_WIDTH+GREYHOUND_WIDTH,frame:50}];
        var aPlace6 = [{x:310,frame:30},{x:400,frame:290},{x:ARRIVAL_X - GREYHOUND_WIDTH/2-650,frame:480},{x:CANVAS_WIDTH+GREYHOUND_WIDTH,frame:55}];
        _aPathSequence.push({"place_1":aPlace1,"place_2":aPlace2,"place_3":aPlace3,"place_4":aPlace4,"place_5":aPlace5,"place_6":aPlace6});
    };
    
    
    this._initGreyhoundInfo = function(){
        _aGreyhoundStartInfo = new Array();
        _aGreyhoundStartInfo[0] = {start:new createjs.Point(208,342),scale:0.66};
        _aGreyhoundStartInfo[1] = {start:new createjs.Point(186,352),scale:0.7};
        _aGreyhoundStartInfo[2] = {start:new createjs.Point(163,363),scale:0.74};
        _aGreyhoundStartInfo[3] = {start:new createjs.Point(134,376),scale:0.8};
        _aGreyhoundStartInfo[4] = {start:new createjs.Point(99,394),scale:0.9};
        _aGreyhoundStartInfo[5] = {start:new createjs.Point(53,415),scale:1};
    };
    
    this.getIndexForFiches = function(iValue){
        var iRes=0;
        for(var i=0;i<_aFichesValue.length;i++){
            if(_aFichesValue[i] === iValue){
                    iRes=i;
            }
        }
        return iRes; 
    };
    
    this.getGreyhoundPercentageArray = function(){
        var aPercLoserGreyhound = new Array();
        for(var i=0;i<_aGreyhoundWinOdd.length;i++){
            var iValue = Math.floor(_aGreyhoundWinOdd[i]);
            for(var j=0;j<iValue;j++){
                aPercLoserGreyhound.push(i);
            }
        }
        
        aPercLoserGreyhound = shuffle(aPercLoserGreyhound);
        return aPercLoserGreyhound;
    };
    
    this.getGreyhoundName = function(iIndex){
        return _aGreyhoundNames[iIndex];
    };
    
    this.getAllGreyhoundNames = function(){
        return _aGreyhoundNames;
    };
    
    this.getOddWin = function(iIndex){
        return _aGreyhoundWinOdd[iIndex];
    };
    
    this.getOddPlace = function(iIndex){
        return _aGreyhoundPlaceOdd[iIndex];
    };
    
    this.getOddShow = function(iIndex){
        return _aGreyhoundShowOdd[iIndex];
    };
    
    this.getForecastOdd = function(iFirst,iSecond){
        return _aForecastOdd[iFirst][iSecond];
    };

    this.getRandomPath = function(){
        var iRand = Math.floor(Math.random()*_aPathSequence.length);

        return _aPathSequence[iRand];
    };
    
    this.getGreyhoundInfo = function(iIndex){
        return _aGreyhoundStartInfo[iIndex];
    };
    
    s_oGameSettings = this;
    this._init(oJsonData);
}

var s_oGameSettings = null;