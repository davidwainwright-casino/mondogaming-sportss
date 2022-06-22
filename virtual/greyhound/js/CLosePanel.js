function CLosePanel(oParentContainer){
    var _iWidthBib;
    var _iHeightBib;
    var _aBibs;
    var _oSpriteSheetBib;
    
    var _oButSkip;
    var _oWinText;
    var _oContainer;
    var _oParentContainer;
    
    this._init = function(){
        _oContainer = new createjs.Container();
        _oContainer.visible = false;
        _oParentContainer.addChild(_oContainer);
        
        var oBg = createBitmap(s_oSpriteLibrary.getSprite("lose_panel"));
        _oContainer.addChild(oBg);
        
        var oText = new createjs.Text(TEXT_NO_WIN, "50px " + PRIMARY_FONT, "#fff");
        oText.textAlign = "center";
        oText.textBaseline = "alphabetic";
        oText.x = CANVAS_WIDTH/2;
        oText.y = 285;
        _oContainer.addChild(oText);
        
        
        var oSprite = s_oSpriteLibrary.getSprite("bibs");
        _iWidthBib = oSprite.width/3;
        _iHeightBib = oSprite.height/2;
        
        var oData = {
            images: [oSprite],
            // width, height & registration point of each sprite
            frames: {width: _iWidthBib, height: _iHeightBib},
            animations: {bib_0: [0], bib_1: [1],bib_2:[2],bib_3:[3],bib_4:[4],bib_5:[5]}
        };

        _oSpriteSheetBib = new createjs.SpriteSheet(oData);
        
        _aBibs = new Array();
        var oBib1 = createSprite(_oSpriteSheetBib,"bib_0",0,0,_iWidthBib,_iHeightBib);
        oBib1.x = CANVAS_WIDTH/2 - 100 - _iWidthBib/2;
        oBib1.y = 350;
        _oContainer.addChild(oBib1);
        
        _aBibs.push(oBib1);
        
        var oBib2 = createSprite(_oSpriteSheetBib,"bib_0",0,0,_iWidthBib,_iHeightBib);
        oBib2.x = CANVAS_WIDTH/2 - _iWidthBib/2;
        oBib2.y = 350;
        _oContainer.addChild(oBib2);
        
        _aBibs.push(oBib2);
        
        var oBib3 = createSprite(_oSpriteSheetBib,"bib_0",0,0,_iWidthBib,_iHeightBib);
        oBib3.x = CANVAS_WIDTH/2 + 100 - _iWidthBib/2;
        oBib3.y = 350;
        _oContainer.addChild(oBib3);
        
        _aBibs.push(oBib3);
        
        _oWinText = new createjs.Text(TEXT_WIN + ": 0.00 " + TEXT_CURRENCY, "30px " + PRIMARY_FONT, "#fff");
        _oWinText.textAlign = "center";
        _oWinText.textBaseline = "alphabetic";
        _oWinText.x = CANVAS_WIDTH/2;
        _oWinText.y = 510;
        _oContainer.addChild(_oWinText);
        
        _oButSkip = new CGfxButton(700,480,s_oSpriteLibrary.getSprite("but_skip"),_oContainer);
        _oButSkip.addEventListener(ON_MOUSE_UP,this.onSkip,this);
    };
    
    this.unload = function(){
        _oButSkip.unload();
    };
    
    this.show = function(aRank){
        for(var j=0;j<3;j++){
             _aBibs[j].gotoAndStop("bib_"+aRank[j]);
        }
         
        _oContainer.visible = true;
        _oContainer.alpha = 0;
        createjs.Tween.get(_oContainer).wait(1000).to({alpha: 1}, 500,createjs.Ease.cubicOut);
    };

    this.onSkip = function(){   
        s_oGame.returnInBetPanel();
    };
    
    _oParentContainer = oParentContainer;
    this._init();
}