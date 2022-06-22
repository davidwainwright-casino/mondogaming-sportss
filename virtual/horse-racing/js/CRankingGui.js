function CRankingGui(aNames,oParentContainer){
    var _iWidthMask;
    var _iStepMask;
    var _aBibs;
    var _aPlacePos;
    var _pStartPos;
    
    var _oFillRadar;
    var _oRadarMask;
    var _oContainer;
    var _oParentContainer;
    
    this._init = function(aNames){
        var oSprite = s_oSpriteLibrary.getSprite("rank_panel");
        _pStartPos = {x:0,y:CANVAS_HEIGHT - oSprite.height + 4};

        _oContainer = new createjs.Container();
        _oContainer.x = _pStartPos.x;
        _oContainer.y = _pStartPos.y;
        _oParentContainer.addChild(_oContainer);
        
        var oBg = createBitmap(oSprite);
        _oContainer.addChild(oBg);
        
        _aPlacePos = new Array();
        _aPlacePos[0] = new createjs.Point(938,44); //POS #1
        _aPlacePos[1] = new createjs.Point(818,44); //POS #2
        _aPlacePos[2] = new createjs.Point(695,44); //POS #3
        _aPlacePos[3] = new createjs.Point(570,44); //POS #4
        _aPlacePos[4] = new createjs.Point(450,44); //POS #5
        _aPlacePos[5] = new createjs.Point(320,44); //POS #6
        _aPlacePos[6] = new createjs.Point(201,44); //POS #7
        _aPlacePos[7] = new createjs.Point(86,44);  //POS #8
        
        this._initBibs(aNames);
        
        
        var oSpriteBar = s_oSpriteLibrary.getSprite("fill_bar");
        _oFillRadar = createBitmap(oSpriteBar);
        _oFillRadar.x = 130;
        _oFillRadar.y = 114;
        _oContainer.addChild(_oFillRadar);
        
        _iWidthMask = oSpriteBar.width;
        _iStepMask = _iWidthMask/NUM_TRACK_BG;
        
        _oRadarMask = new createjs.Shape();
        _oRadarMask.graphics.beginFill("rgba(255,255,255,0.01)").drawRect(_oFillRadar.x, _oFillRadar.y-2,0.01,10);
        _oContainer.addChild(_oRadarMask);
        _oFillRadar.mask = _oRadarMask;
        
        this.refreshButtonPos();
    };
    
    this.refreshButtonPos = function () {
        _oContainer.y = _pStartPos.y - s_iOffsetY;
    };
    
    this._initBibs = function(aNames){
        _aBibs = new Array();
        for(var i=0;i<NUM_HORSES;i++){
            var oContainerBib = new createjs.Container();
            oContainerBib.x = _aPlacePos[i].x;
            oContainerBib.y = _aPlacePos[i].y;
            _oContainer.addChild(oContainerBib);
            
            var oSpriteBib = createBitmap(s_oSpriteLibrary.getSprite("bib_gui_"+i));
            oContainerBib.addChild(oSpriteBib);
            
            var oNameText = new createjs.Text(aNames[i].toUpperCase(), "12px " + TERTIARY_FONT, "#fff");
            oNameText.textAlign = "right";
            oNameText.textBaseline = "alphabetic";
            oNameText.x = 56;
            oNameText.y = 12;
            oContainerBib.addChild(oNameText);
            
            _aBibs.push(oContainerBib);
        }
    };
    
    this.refreshRank = function(aRank){
        for(var i=0;i<aRank.length;i++){
            var iBibIndex = aRank[i].index;

            createjs.Tween.get(_aBibs[iBibIndex]).to({x: _aPlacePos[i].x}, 1000,createjs.Ease.cubicOut);
        }
    };
    
    this.refreshRadar = function(iNum){
        _oRadarMask.graphics.clear();
        _oRadarMask.graphics.beginFill("rgba(255,255,255,0.01)").drawRect(_oFillRadar.x, _oFillRadar.y-2,_iStepMask*iNum,10);
    };
    
    _oParentContainer = oParentContainer;
    this._init(aNames);
}