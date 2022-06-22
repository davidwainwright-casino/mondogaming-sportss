function CGameSettings(oJsonData){
    var _aHorseNames;
    var _aHorseWinOdd;
    var _aHorsePlaceOdd;
    var _aHorseShowOdd;
    var _aForecastOdd;
    var _aFichesValue;
    var _aPathSequence;
    var _aHorseStartInfo;
    
    var _oJsonData;
    
    this._init = function(oJsonData){
        _oJsonData = oJsonData;
        
        var aNames = _oJsonData.horse_names;
        NUM_HORSES = aNames.length;
        _aHorseNames = new Array();
        for(var i=0;i<NUM_HORSES;i++){
           _aHorseNames[i] = aNames[i];
        }
        
        this._initSimpleOdd();
        this._initForecastOdd();
        this._initPaths();
        this._initHorseInfo();
        
        _aFichesValue=CHIP_VALUES;
    };
    
    this._initSimpleOdd = function(){
        //STORE ODD INFOS
        var aOddWin = _oJsonData.odd_win_bet;
        var aOddPlace = _oJsonData.odd_place_bet;
        var aOddShow = _oJsonData.odd_show_bet;
        _aHorseWinOdd = new Array();
        _aHorsePlaceOdd = new Array();
        _aHorseShowOdd = new Array();
        for(var j=0;j<aOddWin.length;j++){
            _aHorseWinOdd[j] = aOddWin[j];
            _aHorsePlaceOdd[j] = aOddPlace[j];
            _aHorseShowOdd[j] = aOddShow[j];
        }
    };
    
    this._initForecastOdd = function(){
        var aForecast = _oJsonData.forecast;
        
        _aForecastOdd = new Array();
        for(var i=0;i<NUM_HORSES;i++){
            _aForecastOdd[i] = new Array();
        }
        
        for(var k=0;k<aForecast.length;k++){
            _aForecastOdd[aForecast[k].first-1][aForecast[k].second-1] = aForecast[k].odd;
        }
    };
    
    this._initPaths = function(){
        _aPathSequence = new Array();
        
        //PATH SEQUENCE #1
        var aPlace1 = [{x:230,frame:30},{x:500,frame:160},{x:600,frame:190},{x:400,frame:200},{x:ARRIVAL_X - HORSE_WIDTH/2,frame:210},{x:CANVAS_WIDTH+HORSE_WIDTH,frame:30}];
        var aPlace2 = [{x:250,frame:30},{x:600,frame:180},{x:650,frame:190},{x:450,frame:180},{x:ARRIVAL_X - HORSE_WIDTH/2-150,frame:210},{x:CANVAS_WIDTH+HORSE_WIDTH,frame:35}];
        var aPlace3 = [{x:170,frame:30},{x:400,frame:130},{x:450,frame:220},{x:400,frame:140},{x:300,frame:130},{x:ARRIVAL_X - HORSE_WIDTH/2-250,frame:140},{x:CANVAS_WIDTH+HORSE_WIDTH,frame:40}];
        var aPlace4 = [{x:190,frame:30},{x:340,frame:130},{x:360,frame:310},{x:ARRIVAL_X - HORSE_WIDTH/2-390,frame:320},{x:CANVAS_WIDTH+HORSE_WIDTH,frame:45}];
        var aPlace5 = [{x:220,frame:30},{x:350,frame:260},{x:480,frame:200},{x:320,frame:100},{x:ARRIVAL_X - HORSE_WIDTH/2-450,frame:200},{x:CANVAS_WIDTH+HORSE_WIDTH,frame:50}];
        var aPlace6 = [{x:210,frame:30},{x:260,frame:270},{x:ARRIVAL_X - HORSE_WIDTH/2-600,frame:490},{x:CANVAS_WIDTH+HORSE_WIDTH,frame:55}];
        var aPlace7 = [{x:220,frame:30},{x:290,frame:270},{x:ARRIVAL_X - HORSE_WIDTH/2-800,frame:490},{x:CANVAS_WIDTH+HORSE_WIDTH,frame:60}];
        var aPlace8 = [{x:190,frame:30},{x:200,frame:270},{x:ARRIVAL_X - HORSE_WIDTH/2-1000,frame:490},{x:CANVAS_WIDTH+HORSE_WIDTH,frame:65}];
        _aPathSequence.push({"place_1":aPlace1,"place_2":aPlace2,"place_3":aPlace3,"place_4":aPlace4,"place_5":aPlace5,"place_6":aPlace6,"place_7":aPlace7,"place_8":aPlace8});
        
        
        //PATH SEQUENCE #2
        var aPlace1 = [{x:230,frame:30},{x:450,frame:180},{x:500,frame:170},{x:400,frame:200},{x:ARRIVAL_X - HORSE_WIDTH/2,frame:210},{x:CANVAS_WIDTH+HORSE_WIDTH,frame:30}];
        var aPlace2 = [{x:250,frame:30},{x:600,frame:200},{x:550,frame:180},{x:350,frame:170},{x:ARRIVAL_X - HORSE_WIDTH/2-200,frame:210},{x:CANVAS_WIDTH+HORSE_WIDTH,frame:35}];
        var aPlace3 = [{x:170,frame:30},{x:250,frame:150},{x:350,frame:210},{x:450,frame:140},{x:350,frame:120},{x:ARRIVAL_X - HORSE_WIDTH/2-350,frame:140},{x:CANVAS_WIDTH+HORSE_WIDTH,frame:40}];
        var aPlace4 = [{x:190,frame:30},{x:300,frame:150},{x:360,frame:300},{x:ARRIVAL_X - HORSE_WIDTH/2-490,frame:310},{x:CANVAS_WIDTH+HORSE_WIDTH,frame:45}];
        var aPlace5 = [{x:220,frame:30},{x:350,frame:280},{x:400,frame:180},{x:320,frame:100},{x:ARRIVAL_X - HORSE_WIDTH/2-650,frame:200},{x:CANVAS_WIDTH+HORSE_WIDTH,frame:50}];
        var aPlace6 = [{x:210,frame:30},{x:160,frame:280},{x:ARRIVAL_X - HORSE_WIDTH/2-700,frame:480},{x:CANVAS_WIDTH+HORSE_WIDTH,frame:55}];
        var aPlace7 = [{x:220,frame:30},{x:200,frame:270},{x:-70,frame:190},{x:20,frame:100},{x:ARRIVAL_X - HORSE_WIDTH/2-850,frame:200},{x:CANVAS_WIDTH+HORSE_WIDTH,frame:60}];
        var aPlace8 = [{x:210,frame:30},{x:0,frame:290},{x:ARRIVAL_X - HORSE_WIDTH/2-900,frame:470},{x:CANVAS_WIDTH+HORSE_WIDTH,frame:65}];
        _aPathSequence.push({"place_1":aPlace1,"place_2":aPlace2,"place_3":aPlace3,"place_4":aPlace4,"place_5":aPlace5,"place_6":aPlace6,"place_7":aPlace7,"place_8":aPlace8});
        
        //PATH SEQUENCE #3
        var aPlace1 = [{x:230,frame:30},{x:500,frame:180},{x:600,frame:180},{x:600,frame:200},{x:ARRIVAL_X - HORSE_WIDTH/2,frame:200},{x:CANVAS_WIDTH+HORSE_WIDTH,frame:30}];
        var aPlace2 = [{x:250,frame:30},{x:600,frame:200},{x:650,frame:180},{x:500,frame:180},{x:ARRIVAL_X - HORSE_WIDTH/2-150,frame:200},{x:CANVAS_WIDTH+HORSE_WIDTH,frame:35}];
        var aPlace3 = [{x:170,frame:30},{x:400,frame:150},{x:450,frame:210},{x:300,frame:140},{x:350,frame:130},{x:ARRIVAL_X - HORSE_WIDTH/2-300,frame:130},{x:CANVAS_WIDTH+HORSE_WIDTH,frame:40}];
        var aPlace4 = [{x:190,frame:30},{x:340,frame:150},{x:360,frame:300},{x:ARRIVAL_X - HORSE_WIDTH/2-460,frame:310},{x:CANVAS_WIDTH+HORSE_WIDTH,frame:45}];
        var aPlace5 = [{x:220,frame:30},{x:350,frame:280},{x:480,frame:180},{x:320,frame:100},{x:ARRIVAL_X - HORSE_WIDTH/2-550,frame:200},{x:CANVAS_WIDTH+HORSE_WIDTH,frame:50}];
        var aPlace6 = [{x:210,frame:30},{x:200,frame:280},{x:ARRIVAL_X - HORSE_WIDTH/2-560,frame:480},{x:CANVAS_WIDTH+HORSE_WIDTH,frame:55}];
        var aPlace7 = [{x:200,frame:30},{x:100,frame:270},{x:80,frame:190},{x:20,frame:100},{x:ARRIVAL_X - HORSE_WIDTH/2-850,frame:200},{x:CANVAS_WIDTH+HORSE_WIDTH,frame:60}];
        var aPlace8 = [{x:210,frame:30},{x:20,frame:280},{x:ARRIVAL_X - HORSE_WIDTH/2-1000,frame:480},{x:CANVAS_WIDTH+HORSE_WIDTH,frame:65}];
        _aPathSequence.push({"place_1":aPlace1,"place_2":aPlace2,"place_3":aPlace3,"place_4":aPlace4,"place_5":aPlace5,"place_6":aPlace6,"place_7":aPlace7,"place_8":aPlace8});
        
        //PATH SEQUENCE #4
        var aPlace1 = [{x:330,frame:30},{x:450,frame:180},{x:550,frame:170},{x:650,frame:200},{x:ARRIVAL_X - HORSE_WIDTH/2,frame:210},{x:CANVAS_WIDTH+HORSE_WIDTH,frame:30}];
        var aPlace2 = [{x:250,frame:30},{x:350,frame:200},{x:450,frame:180},{x:600,frame:170},{x:ARRIVAL_X - HORSE_WIDTH/2-150,frame:210},{x:CANVAS_WIDTH+HORSE_WIDTH,frame:35}];
        var aPlace3 = [{x:270,frame:30},{x:400,frame:150},{x:450,frame:200},{x:600,frame:140},{x:500,frame:130},{x:ARRIVAL_X - HORSE_WIDTH/2-350,frame:140},{x:CANVAS_WIDTH+HORSE_WIDTH,frame:40}];
        var aPlace4 = [{x:290,frame:30},{x:340,frame:150},{x:360,frame:300},{x:ARRIVAL_X - HORSE_WIDTH/2-510,frame:310},{x:CANVAS_WIDTH+HORSE_WIDTH,frame:45}];
        var aPlace5 = [{x:220,frame:30},{x:150,frame:280},{x:400,frame:180},{x:320,frame:100},{x:ARRIVAL_X - HORSE_WIDTH/2-550,frame:200},{x:CANVAS_WIDTH+HORSE_WIDTH,frame:50}];
        var aPlace6 = [{x:210,frame:30},{x:60,frame:290},{x:ARRIVAL_X - HORSE_WIDTH/2-650,frame:470},{x:CANVAS_WIDTH+HORSE_WIDTH,frame:55}];
        var aPlace7 = [{x:220,frame:30},{x:90,frame:280},{x:420,frame:180},{x:320,frame:100},{x:ARRIVAL_X - HORSE_WIDTH/2-850,frame:200},{x:CANVAS_WIDTH+HORSE_WIDTH,frame:60}];
        var aPlace8 = [{x:210,frame:30},{x:120,frame:280},{x:ARRIVAL_X - HORSE_WIDTH/2-900,frame:480},{x:CANVAS_WIDTH+HORSE_WIDTH,frame:65}];
        _aPathSequence.push({"place_1":aPlace1,"place_2":aPlace2,"place_3":aPlace3,"place_4":aPlace4,"place_5":aPlace5,"place_6":aPlace6,"place_7":aPlace7,"place_8":aPlace8});
     
        //PATH SEQUENCE #5
        var aPlace1 = [{x:230,frame:30},{x:350,frame:180},{x:400,frame:170},{x:600,frame:200},{x:ARRIVAL_X - HORSE_WIDTH/2,frame:210},{x:CANVAS_WIDTH+HORSE_WIDTH,frame:30}];
        var aPlace2 = [{x:250,frame:30},{x:350,frame:200},{x:450,frame:170},{x:500,frame:180},{x:ARRIVAL_X - HORSE_WIDTH/2-150,frame:210},{x:CANVAS_WIDTH+HORSE_WIDTH,frame:35}];
        var aPlace3 = [{x:270,frame:30},{x:400,frame:150},{x:450,frame:200},{x:600,frame:140},{x:500,frame:130},{x:ARRIVAL_X - HORSE_WIDTH/2-250,frame:140},{x:CANVAS_WIDTH+HORSE_WIDTH,frame:40}];
        var aPlace4 = [{x:190,frame:30},{x:340,frame:140},{x:360,frame:300},{x:ARRIVAL_X - HORSE_WIDTH/2-390,frame:320},{x:CANVAS_WIDTH+HORSE_WIDTH,frame:45}];
        var aPlace5 = [{x:220,frame:30},{x:350,frame:270},{x:400,frame:190},{x:320,frame:100},{x:ARRIVAL_X - HORSE_WIDTH/2-550,frame:200},{x:CANVAS_WIDTH+HORSE_WIDTH,frame:50}];
        var aPlace6 = [{x:210,frame:30},{x:260,frame:280},{x:ARRIVAL_X - HORSE_WIDTH/2-650,frame:480},{x:CANVAS_WIDTH+HORSE_WIDTH,frame:55}];
        var aPlace7 = [{x:240,frame:30},{x:350,frame:270},{x:400,frame:190},{x:320,frame:100},{x:ARRIVAL_X - HORSE_WIDTH/2-750,frame:200},{x:CANVAS_WIDTH+HORSE_WIDTH,frame:60}];
        var aPlace8 = [{x:180,frame:30},{x:260,frame:290},{x:ARRIVAL_X - HORSE_WIDTH/2-1050,frame:470},{x:CANVAS_WIDTH+HORSE_WIDTH,frame:65}];
        _aPathSequence.push({"place_1":aPlace1,"place_2":aPlace2,"place_3":aPlace3,"place_4":aPlace4,"place_5":aPlace5,"place_6":aPlace6,"place_7":aPlace7,"place_8":aPlace8});
          
        //PATH SEQUENCE #6
        var aPlace1 = [{x:230,frame:50},{x:350,frame:200},{x:400,frame:100},{x:500,frame:200},{x:ARRIVAL_X - HORSE_WIDTH/2,frame:240},{x:CANVAS_WIDTH+HORSE_WIDTH,frame:30}];
        var aPlace2 = [{x:250,frame:100},{x:350,frame:150},{x:450,frame:140},{x:500,frame:200},{x:ARRIVAL_X - HORSE_WIDTH/2-150,frame:200},{x:CANVAS_WIDTH+HORSE_WIDTH,frame:35}];
        var aPlace3 = [{x:270,frame:50},{x:400,frame:140},{x:450,frame:200},{x:400,frame:200},{x:ARRIVAL_X - HORSE_WIDTH/2-250,frame:200},{x:CANVAS_WIDTH+HORSE_WIDTH,frame:40}];
        var aPlace4 = [{x:190,frame:30},{x:340,frame:160},{x:360,frame:300},{x:380,frame:100},{x:ARRIVAL_X - HORSE_WIDTH/2-390,frame:200},{x:CANVAS_WIDTH+HORSE_WIDTH,frame:45}];
        var aPlace5 = [{x:220,frame:100},{x:350,frame:200},{x:400,frame:190},{x:320,frame:100},{x:ARRIVAL_X - HORSE_WIDTH/2-550,frame:200},{x:CANVAS_WIDTH+HORSE_WIDTH,frame:50}];
        var aPlace6 = [{x:210,frame:50},{x:260,frame:300},{x:300,frame:190},{x:220,frame:100},{x:ARRIVAL_X - HORSE_WIDTH/2-650,frame:150},{x:CANVAS_WIDTH+HORSE_WIDTH,frame:55}];
        var aPlace7 = [{x:200,frame:100},{x:300,frame:200},{x:400,frame:190},{x:300,frame:100},{x:ARRIVAL_X - HORSE_WIDTH/2-750,frame:200},{x:CANVAS_WIDTH+HORSE_WIDTH,frame:60}];
        var aPlace8 = [{x:230,frame:50},{x:280,frame:290},{x:300,frame:200},{x:240,frame:100},{x:ARRIVAL_X - HORSE_WIDTH/2-780,frame:150},{x:CANVAS_WIDTH+HORSE_WIDTH,frame:65}];
        _aPathSequence.push({"place_1":aPlace1,"place_2":aPlace2,"place_3":aPlace3,"place_4":aPlace4,"place_5":aPlace5,"place_6":aPlace6,"place_7":aPlace7,"place_8":aPlace8});
         
        //PATH SEQUENCE #7
        var aPlace1 = [{x:230,frame:50},{x:430,frame:320},{x:ARRIVAL_X - HORSE_WIDTH/2,frame:420},{x:CANVAS_WIDTH+HORSE_WIDTH,frame:30}];
        var aPlace2 = [{x:250,frame:100},{x:350,frame:150},{x:450,frame:140},{x:500,frame:200},{x:ARRIVAL_X - HORSE_WIDTH/2-250,frame:200},{x:CANVAS_WIDTH+HORSE_WIDTH,frame:35}];
        var aPlace3 = [{x:270,frame:50},{x:200,frame:140},{x:450,frame:400},{x:ARRIVAL_X - HORSE_WIDTH/2-350,frame:200},{x:CANVAS_WIDTH+HORSE_WIDTH,frame:40}];
        var aPlace4 = [{x:190,frame:30},{x:240,frame:160},{x:360,frame:300},{x:380,frame:100},{x:ARRIVAL_X - HORSE_WIDTH/2-490,frame:200},{x:CANVAS_WIDTH+HORSE_WIDTH,frame:45}];
        var aPlace5 = [{x:220,frame:100},{x:350,frame:200},{x:400,frame:190},{x:320,frame:100},{x:ARRIVAL_X - HORSE_WIDTH/2-650,frame:200},{x:CANVAS_WIDTH+HORSE_WIDTH,frame:50}];
        var aPlace6 = [{x:210,frame:50},{x:260,frame:300},{x:300,frame:210},{x:220,frame:100},{x:ARRIVAL_X - HORSE_WIDTH/2-750,frame:130},{x:CANVAS_WIDTH+HORSE_WIDTH,frame:55}];
        var aPlace7 = [{x:260,frame:100},{x:350,frame:200},{x:300,frame:190},{x:320,frame:100},{x:ARRIVAL_X - HORSE_WIDTH/2-850,frame:200},{x:CANVAS_WIDTH+HORSE_WIDTH,frame:60}];
        var aPlace8 = [{x:10,frame:50},{x:260,frame:270},{x:300,frame:200},{x:200,frame:100},{x:ARRIVAL_X - HORSE_WIDTH/2-950,frame:170},{x:CANVAS_WIDTH+HORSE_WIDTH,frame:65}];
        _aPathSequence.push({"place_1":aPlace1,"place_2":aPlace2,"place_3":aPlace3,"place_4":aPlace4,"place_5":aPlace5,"place_6":aPlace6,"place_7":aPlace7,"place_8":aPlace8});
       
        //PATH SEQUENCE #8
        var aPlace1 = [{x:230,frame:50},{x:250,frame:300},{x:ARRIVAL_X - HORSE_WIDTH/2,frame:440},{x:CANVAS_WIDTH+HORSE_WIDTH,frame:30}];
        var aPlace2 = [{x:250,frame:50},{x:430,frame:300},{x:ARRIVAL_X - HORSE_WIDTH/2-250,frame:440},{x:CANVAS_WIDTH+HORSE_WIDTH,frame:35}];
        var aPlace3 = [{x:270,frame:50},{x:330,frame:290},{x:ARRIVAL_X - HORSE_WIDTH/2-450,frame:450},{x:CANVAS_WIDTH+HORSE_WIDTH,frame:40}];
        var aPlace4 = [{x:190,frame:50},{x:230,frame:290},{x:ARRIVAL_X - HORSE_WIDTH/2-590,frame:450},{x:CANVAS_WIDTH+HORSE_WIDTH,frame:45}];
        var aPlace5 = [{x:220,frame:50},{x:300,frame:290},{x:ARRIVAL_X - HORSE_WIDTH/2-650,frame:450},{x:CANVAS_WIDTH+HORSE_WIDTH,frame:50}];
        var aPlace6 = [{x:210,frame:50},{x:130,frame:290},{x:ARRIVAL_X - HORSE_WIDTH/2-750,frame:450},{x:CANVAS_WIDTH+HORSE_WIDTH,frame:55}];
        var aPlace7 = [{x:180,frame:50},{x:280,frame:290},{x:ARRIVAL_X - HORSE_WIDTH/2-800,frame:450},{x:CANVAS_WIDTH+HORSE_WIDTH,frame:60}];
        var aPlace8 = [{x:190,frame:50},{x:150,frame:290},{x:ARRIVAL_X - HORSE_WIDTH/2-950,frame:450},{x:CANVAS_WIDTH+HORSE_WIDTH,frame:65}];
        _aPathSequence.push({"place_1":aPlace1,"place_2":aPlace2,"place_3":aPlace3,"place_4":aPlace4,"place_5":aPlace5,"place_6":aPlace6,"place_7":aPlace7,"place_8":aPlace8});
         
        //PATH SEQUENCE #9
        var aPlace1 = [{x:210,frame:50},{x:130,frame:300},{x:ARRIVAL_X - HORSE_WIDTH/2,frame:440},{x:CANVAS_WIDTH+HORSE_WIDTH,frame:30}];
        var aPlace2 = [{x:220,frame:50},{x:300,frame:300},{x:ARRIVAL_X - HORSE_WIDTH/2-150,frame:440},{x:CANVAS_WIDTH+HORSE_WIDTH,frame:35}];
        var aPlace3 = [{x:270,frame:50},{x:330,frame:300},{x:ARRIVAL_X - HORSE_WIDTH/2-250,frame:440},{x:CANVAS_WIDTH+HORSE_WIDTH,frame:40}];
        var aPlace4 = [{x:250,frame:50},{x:430,frame:300},{x:ARRIVAL_X - HORSE_WIDTH/2-390,frame:440},{x:CANVAS_WIDTH+HORSE_WIDTH,frame:45}];
        var aPlace5 = [{x:190,frame:50},{x:190,frame:300},{x:ARRIVAL_X - HORSE_WIDTH/2-550,frame:440},{x:CANVAS_WIDTH+HORSE_WIDTH,frame:50}];
        var aPlace6 = [{x:230,frame:50},{x:250,frame:300},{x:ARRIVAL_X - HORSE_WIDTH/2-650,frame:440},{x:CANVAS_WIDTH+HORSE_WIDTH,frame:55}];
        var aPlace7 = [{x:190,frame:50},{x:220,frame:300},{x:ARRIVAL_X - HORSE_WIDTH/2-950,frame:440},{x:CANVAS_WIDTH+HORSE_WIDTH,frame:60}];
        var aPlace8 = [{x:200,frame:50},{x:250,frame:300},{x:ARRIVAL_X - HORSE_WIDTH/2-1000,frame:440},{x:CANVAS_WIDTH+HORSE_WIDTH,frame:65}];
        _aPathSequence.push({"place_1":aPlace1,"place_2":aPlace2,"place_3":aPlace3,"place_4":aPlace4,"place_5":aPlace5,"place_6":aPlace6,"place_7":aPlace7,"place_8":aPlace8});
        
        //PATH SEQUENCE #10
        var aPlace1 = [{x:330,frame:30},{x:450,frame:180},{x:550,frame:180},{x:650,frame:200},{x:ARRIVAL_X - HORSE_WIDTH/2,frame:200},{x:CANVAS_WIDTH+HORSE_WIDTH,frame:30}];
        var aPlace2 = [{x:250,frame:90},{x:450,frame:300},{x:600,frame:200},{x:ARRIVAL_X - HORSE_WIDTH/2-150,frame:200},{x:CANVAS_WIDTH+HORSE_WIDTH,frame:35}];
        var aPlace3 = [{x:270,frame:30},{x:400,frame:150},{x:450,frame:200},{x:600,frame:140},{x:500,frame:130},{x:ARRIVAL_X - HORSE_WIDTH/2-300,frame:140},{x:CANVAS_WIDTH+HORSE_WIDTH,frame:40}];
        var aPlace4 = [{x:290,frame:30},{x:340,frame:150},{x:360,frame:300},{x:ARRIVAL_X - HORSE_WIDTH/2-510,frame:310},{x:CANVAS_WIDTH+HORSE_WIDTH,frame:45}];
        var aPlace5 = [{x:220,frame:30},{x:350,frame:280},{x:400,frame:180},{x:320,frame:100},{x:ARRIVAL_X - HORSE_WIDTH/2-650,frame:200},{x:CANVAS_WIDTH+HORSE_WIDTH,frame:50}];
        var aPlace6 = [{x:310,frame:30},{x:400,frame:290},{x:ARRIVAL_X - HORSE_WIDTH/2-750,frame:470},{x:CANVAS_WIDTH+HORSE_WIDTH,frame:55}];
        var aPlace7 = [{x:240,frame:30},{x:300,frame:280},{x:350,frame:180},{x:320,frame:100},{x:ARRIVAL_X - HORSE_WIDTH/2-800,frame:200},{x:CANVAS_WIDTH+HORSE_WIDTH,frame:60}];
        var aPlace8 = [{x:210,frame:30},{x:300,frame:290},{x:ARRIVAL_X - HORSE_WIDTH/2-850,frame:470},{x:CANVAS_WIDTH+HORSE_WIDTH,frame:65}];
        _aPathSequence.push({"place_1":aPlace1,"place_2":aPlace2,"place_3":aPlace3,"place_4":aPlace4,"place_5":aPlace5,"place_6":aPlace6,"place_7":aPlace7,"place_8":aPlace8});
        
    };
    
    
    this._initHorseInfo = function(){
        _aHorseStartInfo = new Array();
        _aHorseStartInfo[0] = {start:new createjs.Point(193,350),scale:0.53};
        _aHorseStartInfo[1] = {start:new createjs.Point(170,360),scale:0.59};
        _aHorseStartInfo[2] = {start:new createjs.Point(144,372),scale:0.65};
        _aHorseStartInfo[3] = {start:new createjs.Point(114,385),scale:0.71};
        _aHorseStartInfo[4] = {start:new createjs.Point(76,400),scale:0.78};
        _aHorseStartInfo[5] = {start:new createjs.Point(30,415),scale:0.85};
        _aHorseStartInfo[6] = {start:new createjs.Point(-10,432),scale:0.92};
        _aHorseStartInfo[7] = {start:new createjs.Point(-66,452),scale:1};
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
    
    this.getHorsePercentageArray = function(){
        var aPercLoserHorse = new Array();
        for(var i=0;i<_aHorseWinOdd.length;i++){
            var iValue = Math.floor(_aHorseWinOdd[i]);
            for(var j=0;j<iValue;j++){
                aPercLoserHorse.push(i);
            }
        }
        
        aPercLoserHorse = shuffle(aPercLoserHorse);
        return aPercLoserHorse;
    };
    
    this.getHorseName = function(iIndex){
        return _aHorseNames[iIndex];
    };
    
    this.getAllHorseNames = function(){
        return _aHorseNames;
    };
    
    this.getOddWin = function(iIndex){
        return _aHorseWinOdd[iIndex];
    };
    
    this.getOddPlace = function(iIndex){
        return _aHorsePlaceOdd[iIndex];
    };
    
    this.getOddShow = function(iIndex){
        return _aHorseShowOdd[iIndex];
    };
    
    this.getForecastOdd = function(iFirst,iSecond){
        return _aForecastOdd[iFirst][iSecond];
    };

    this.getRandomPath = function(){
        var iRand = Math.floor(Math.random()*_aPathSequence.length);
        return _aPathSequence[iRand];
    };
    
    this.getHorseInfo = function(iIndex){
        return _aHorseStartInfo[iIndex];
    };
    
    s_oGameSettings = this;
    this._init(oJsonData);
}

var s_oGameSettings = null;