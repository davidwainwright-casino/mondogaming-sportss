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
        _pStartPos = {x:0,y:CANVAS_HEIGHT - oSprite.height+ 6};

        _oContainer = new createjs.Container();
        _oContainer.x = _pStartPos.x;
        _oContainer.y = _pStartPos.y;
        _oParentContainer.addChild(_oContainer);
        
        var oBg = createBitmap(oSprite);
        _oContainer.addChild(oBg);
        
        _aPlacePos = new Array();
        _aPlacePos[0] = new createjs.Point(780,48); //POS #1
        _aPlacePos[1] = new createjs.Point(642,48); //POS #2
        _aPlacePos[2] = new createjs.Point(504,48); //POS #3
        _aPlacePos[3] = new createjs.Point(366,48); //POS #4
        _aPlacePos[4] = new createjs.Point(228,48); //POS #5
        _aPlacePos[5] = new createjs.Point(90,48);  //POS #6
        
        this._initBibs(aNames);
        
        _iWidthMask = 800;
        _iStepMask = _iWidthMask/400;
        _oFillRadar = createBitmap(s_oSpriteLibrary.getSprite("fill_bar"));
        _oFillRadar.x = 107;
        _oFillRadar.y = 116;
        _oContainer.addChild(_oFillRadar);
        
        _oRadarMask = new createjs.Shape();
        _oRadarMask.graphics.beginFill("rgba(255,255,255,0.01)").drawRect(_oFillRadar.x, _oFillRadar.y-2,0.01,10);
        _oContainer.addChild(_oRadarMask);
        _oFillRadar.mask = _oRadarMask;
        
        this.refreshButtonPos(s_iOffsetX, s_iOffsetY);
    };
    
    this.refreshButtonPos = function (iNewX, iNewY) {
        _oContainer.y = _pStartPos.y - iNewY;
    };
    
    this._initBibs = function(aNames){
        _aBibs = new Array();
        for(var i=0;i<NUM_GREYHOUNDS;i++){
            var oContainerBib = new createjs.Container();
            oContainerBib.x = _aPlacePos[i].x;
            oContainerBib.y = _aPlacePos[i].y;
            _oContainer.addChild(oContainerBib);
            
            var oSpriteBib = createBitmap(s_oSpriteLibrary.getSprite("bib_gui_"+i));
            oContainerBib.addChild(oSpriteBib);
            
            var oNameText = new createjs.Text(aNames[i].toUpperCase(), "12px " + PRIMARY_FONT, "#fff");
            oNameText.textAlign = "right";
            oNameText.textBaseline = "alphabetic";
            oNameText.x = 50;
            oNameText.y = 10;
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