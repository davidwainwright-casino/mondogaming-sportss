function CSimpleBetPanel(iX,iY,oParentContainer){
    var _aGreyhoundNames;
    var _aGreyhoundSprite;
    var _aGreyhoundWinOdds;
    var _aGreyhoundPlaceOdds;
    var _aGreyhoundShowOdds;
    var _aButsWin;
    var _aButsPlace;
    var _aButsShow;
    
    var _oContainer;
    var _oParentContainer;
    
    this._init = function(iX,iY){
        _oContainer = new createjs.Container();
        _oContainer.x = iX;
        _oContainer.y = iY;
        _oParentContainer.addChild(_oContainer);
        
        var oBg = createBitmap(s_oSpriteLibrary.getSprite("simple_bet_panel"));
        _oContainer.addChild(oBg);
        
        var oText = new createjs.Text(TEXT_TRAP, "20px " + PRIMARY_FONT, "#fff");
        oText.textAlign = "center";
        oText.textBaseline = "alphabetic";
        oText.x = 56;
        oText.y = 24;
        _oContainer.addChild(oText);
        
        var oText = new createjs.Text(TEXT_GREYHOUND_NAME, "20px " + PRIMARY_FONT, "#fff");
        oText.textAlign = "center";
        oText.textBaseline = "alphabetic";
        oText.x = 180;
        oText.y = 24;
        _oContainer.addChild(oText);
        
        var oText = new createjs.Text(TEXT_WINS, "20px " + PRIMARY_FONT, "#fff");
        oText.textAlign = "center";
        oText.textBaseline = "alphabetic";
        oText.x = 360;
        oText.y = 24;
        _oContainer.addChild(oText);
        
        var oText = new createjs.Text(TEXT_PLACE, "20px " + PRIMARY_FONT, "#fff");
        oText.textAlign = "center";
        oText.textBaseline = "alphabetic";
        oText.x = 500;
        oText.y = 24;
        _oContainer.addChild(oText);
        
        var oText = new createjs.Text(TEXT_SHOW, "20px " + PRIMARY_FONT, "#fff");
        oText.textAlign = "center";
        oText.textBaseline = "alphabetic";
        oText.x = 630;
        oText.y = 24;
        _oContainer.addChild(oText);
        
        var oSprite = s_oSpriteLibrary.getSprite("bibs");
        var oData = {
            images: [oSprite],
            // width, height & registration point of each sprite
            frames: {width: oSprite.width/3, height: oSprite.height/2},
            animations: {bib_0: [0], bib_1: [1],bib_2:[2],bib_3:[3],bib_4:[4],bib_5:[5]}
        };

        var oSpriteSheetBib = new createjs.SpriteSheet(oData);
        
        var iYBib = 38;
        for(var i=0;i<NUM_GREYHOUNDS;i++){
            var oBib = createSprite(oSpriteSheetBib,"bib_"+i,0,0,oSprite.width/3, oSprite.height/2);
            oBib.x = 28;
            oBib.y = iYBib;
            _oContainer.addChild(oBib);
            
            iYBib += oSprite.height/2 + 11;
        }
        
        this._initGreyhoundInfos();
        this._initBetPlaces();
    };
    
    this._initGreyhoundInfos = function(){
        var aNames = s_oGameSettings.getAllGreyhoundNames();
        
        _aGreyhoundNames = new Array();
        _aGreyhoundSprite = new Array();
        var iX = 180;
        var iY = 104;
        for(var i=0;i<NUM_GREYHOUNDS;i++){
            var oSprite = s_oSpriteLibrary.getSprite("greyhound_"+(i+1));
            var oData = {
                framerate:40,
                images: [oSprite],
                // width, height & registration point of each sprite
                frames: {width: GREYHOUND_WIDTH, height: GREYHOUND_HEIGHT,regX: GREYHOUND_WIDTH/2,regY:GREYHOUND_HEIGHT},
                animations: {idle: [0,0], anim: [0,21],start:[22],anim_1:[5,21,"anim"],anim_2:[10,21,"anim"],anim_3:[15,21,"anim"]}
            };

            var oSpriteSheetGreyhound = new createjs.SpriteSheet(oData);

            var oGreyhound = createSprite(oSpriteSheetGreyhound,"idle",GREYHOUND_WIDTH/2,GREYHOUND_HEIGHT,GREYHOUND_WIDTH, GREYHOUND_HEIGHT);
            oGreyhound.x = 150;
            oGreyhound.y = iY;
            oGreyhound.scaleX = oGreyhound.scaleY = 0.5;
            _oContainer.addChild(oGreyhound);
            
            _aGreyhoundSprite.push(oGreyhound);
            
            var oText = new createjs.Text(aNames[i].toUpperCase(), "14px " + TERTIARY_FONT, "#fff");
            oText.textAlign = "right";
            oText.textBaseline = "alphabetic";
            oText.x = iX + 106;
            oText.y = iY - 10;
            _oContainer.addChild(oText);
            
            _aGreyhoundNames[i] = oText;

            iY += 67;
        }
    };
    
    this._initBetPlaces = function(){
        _aButsPlace = new Array();
        _aButsShow = new Array();
        _aButsWin = new Array();
        
        _aGreyhoundWinOdds = new Array();
        _aGreyhoundPlaceOdds = new Array();
        _aGreyhoundShowOdds = new Array();
        var iX = 325;
        var iY = 66;
        var oSprite = s_oSpriteLibrary.getSprite("bet_place");
        for(var i=0;i<NUM_GREYHOUNDS;i++){
            //////////////////BET WIN/////////////////////
            var oText = new createjs.Text(s_oGameSettings.getOddWin(i), "20px " + TERTIARY_FONT, "#fff");
            oText.textAlign = "center";
            oText.textBaseline = "middle";
            oText.x = iX;
            oText.y = iY;
            _oContainer.addChild(oText);
            _aGreyhoundWinOdds[i] = oText;
            
            var oBut = new CButBet(oText.x + 60,iY,oSprite,0.7,_oContainer);
            oBut.addEventListenerWithParams(ON_MOUSE_UP,this._onWinClicked,this,i);
            _aButsWin.push(oBut);

            ////////////////BET PLACE////////////////////
            var oText = new createjs.Text(s_oGameSettings.getOddPlace(i), "20px " + TERTIARY_FONT, "#fff");
            oText.textAlign = "center";
            oText.textBaseline = "middle";
            oText.x = iX + 140;
            oText.y = iY;
            _oContainer.addChild(oText);
            _aGreyhoundPlaceOdds[i] = oText;
            
            var oBut = new CButBet(oText.x + 60,iY,oSprite,0.7,_oContainer);
            oBut.addEventListenerWithParams(ON_MOUSE_UP,this._onPlaceClicked,this,i);
            _aButsPlace.push(oBut);
            
            //BET SHOW
            var oText = new createjs.Text(s_oGameSettings.getOddShow(i), "20px " + TERTIARY_FONT, "#fff");
            oText.textAlign = "center";
            oText.textBaseline = "middle";
            oText.x = iX + 268;
            oText.y = iY;
            _oContainer.addChild(oText);
            _aGreyhoundShowOdds[i] = oText;
            
            var oBut = new CButBet(oText.x + 60,iY,oSprite,0.7,_oContainer);
            oBut.addEventListenerWithParams(ON_MOUSE_UP,this._onShowClicked,this,i);
            _aButsShow.push(oBut);
            
            iY += 67;
        }
    };
    
    this.unload = function(){
        for(var i=0;i<_aButsPlace.length;i++){
            _aButsWin[i].unload();
            _aButsShow[i].unload();
            _aButsPlace[i].unload();
        }
    };
    
    this.setX = function(iX){
        _oContainer.x = iX;
    };
    
    this.clearBet = function(){
        for(var i=0;i<_aGreyhoundSprite.length;i++){
            _aGreyhoundSprite[i].gotoAndStop("idle");
        }
    };
    
    this._onWinClicked = function(iIndex){
        var iFicheValue = CHIP_VALUES[s_oBetPanel.getChipSelected()];
        
        if(s_oBetPanel.setSimpleBet(iIndex,1,iFicheValue,_aButsWin[iIndex])){
            if(_aButsWin[iIndex].getTotBet() === 0){
                _aGreyhoundSprite[iIndex].gotoAndPlay("anim");
            }
            _aButsWin[iIndex].increaseBet(iFicheValue);
        }
    };
    
    this._onPlaceClicked = function(iIndex){
        var iFicheValue = CHIP_VALUES[s_oBetPanel.getChipSelected()];
        if(s_oBetPanel.setSimpleBet(iIndex,2,iFicheValue,_aButsPlace[iIndex])){
            
            if(_aButsPlace[iIndex].getTotBet() === 0){
                _aGreyhoundSprite[iIndex].gotoAndPlay("anim");
            }
            _aButsPlace[iIndex].increaseBet(iFicheValue);
            
        }
    };
    
    this._onShowClicked = function(iIndex){
        var iFicheValue = CHIP_VALUES[s_oBetPanel.getChipSelected()];
        if(s_oBetPanel.setSimpleBet(iIndex,3,iFicheValue,_aButsShow[iIndex])){
            
            if(_aButsShow[iIndex].getTotBet() === 0){
                _aGreyhoundSprite[iIndex].gotoAndPlay("anim");
            }
            _aButsShow[iIndex].increaseBet(iFicheValue);
        }
    };
    
    this.getContainer = function(){
        return _oContainer;
    };
    
    _oParentContainer = oParentContainer;
    this._init(iX,iY);
}