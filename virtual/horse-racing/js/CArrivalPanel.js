function CArrivalPanel(iX,iY,oParentContainer){
    var _bShow;
    var _iNumArrived;
    var _aTexts;
    var _aBibs;
    var _pStartPos;
    var _pEndPos;
    
    var _oContainer;
    var _oParentContainer;
    
    this._init = function(iX,iY){
        _bShow = false;
        _iNumArrived = 0;
        _pStartPos = {x:iX,y:iY};
        
        _oContainer = new createjs.Container();
        _oContainer.x = _pStartPos.x;
        _oContainer.y = _pStartPos.y;
        _oParentContainer.addChild(_oContainer);
        
        var oSprite = s_oSpriteLibrary.getSprite("panel_arrival");
        var oBg = createBitmap(oSprite);
        _oContainer.addChild(oBg);
        
        _pEndPos = {x:CANVAS_WIDTH-oSprite.width-s_iOffsetX,y:_pStartPos.y};
        
        var oSprite = s_oSpriteLibrary.getSprite("bibs");
        var iWidthBib = oSprite.width/4;
        var iHeightBib = oSprite.height/2;
        var iScaleBib = 0.45;
        var oData = {
            images: [oSprite],
            // width, height & registration point of each sprite
            frames: {width: iWidthBib, height: iHeightBib},
            animations: {bib_0: [0], bib_1: [1],bib_2:[2],bib_3:[3],bib_4:[4],bib_5:[5],bib_6:[6],bib_7:[7]}
        };

        var oSpriteSheetBib = new createjs.SpriteSheet(oData);
        
        _aTexts = new Array();
        _aBibs = new Array();
        var iYPos = 4;
        for(var i=0;i<NUM_HORSES;i++){
            var oBib = createSprite(oSpriteSheetBib,"bib_0",0,0,iWidthBib,iHeightBib);
            oBib.x = 10;
            oBib.y = iYPos;
            oBib.visible = false;
            oBib.scaleX = oBib.scaleY = iScaleBib;
            _oContainer.addChild(oBib);
            
            _aBibs.push(oBib);
            
            var oNameText = new createjs.Text("", "16px " + TERTIARY_FONT, "#fff");
            oNameText.textAlign = "left";
            oNameText.textBaseline = "alphabetic";
            oNameText.x = oBib.x + (iWidthBib*iScaleBib) + 5;
            oNameText.y = oBib.y + 20;
            _oContainer.addChild(oNameText);
            
            _aTexts.push(oNameText);
            
            iYPos += iHeightBib*iScaleBib +1;
        }
        
        
    };
    
    this.refreshButtonPos = function () {
        if(_bShow){
            _oContainer.x = _pEndPos.x - s_iOffsetX;
        }else{
            _oContainer.x = _pStartPos.x - s_iOffsetX;
        }
        
    };
    
    this.show = function(){
        _bShow = true;
        createjs.Tween.get(_oContainer).to({x: _pEndPos.x}, 500,createjs.Ease.cubicOut);
    };
    
    this.hide = function(){
        _bShow = false;
        createjs.Tween.get(_oContainer).to({x: _pStartPos.x}, 500,createjs.Ease.cubicOut);
    };
    
    this.refreshRank = function(iIndex,szName){
        _aTexts[_iNumArrived].text = szName;
        _aBibs[_iNumArrived].gotoAndStop("bib_"+iIndex);
        _aBibs[_iNumArrived].visible = true;
        
        if(_iNumArrived === 0){
            this.show();
        }
        _iNumArrived++;
    };
    
    _oParentContainer = oParentContainer;
    this._init(iX,iY);
}