function CWinPanel(oParentContainer){
    var _iWidthBib;
    var _iHeightBib;
    var _aGreyhound;
    var _oSpriteSheetBib;
    
    var _oButSkip;
    var _oWinText;
    var _oContainer;
    var _oParentContainer;
    
    this._init = function(){
        _oContainer = new createjs.Container();
        _oContainer.visible = false;
        _oParentContainer.addChild(_oContainer);
        
        var oBg = createBitmap(s_oSpriteLibrary.getSprite("win_panel"));
        _oContainer.addChild(oBg);
        
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
        
        _oWinText = new createjs.Text(TEXT_WIN, "30px " + SECONDARY_FONT, "#ffde00");
        _oWinText.textAlign = "center";
        _oWinText.textBaseline = "alphabetic";
        _oWinText.x = CANVAS_WIDTH/2 + 70;
        _oWinText.y = 476;
        _oWinText.lineHeight = 42;
        _oContainer.addChild(_oWinText);
        
        _oButSkip = new CGfxButton(700,491,s_oSpriteLibrary.getSprite("but_skip"),_oContainer);
        _oButSkip.addEventListener(ON_MOUSE_UP,this.onSkip,this);
    };
    
    this.unload = function(){
        _oButSkip.unload();
    };
    
    this.show = function(iWin,aWinList,aRank){
        _oWinText.text = TEXT_WIN + "\n"+iWin+TEXT_CURRENCY;

        //ADD GREYHOUND SPRITES
        var iY = 310;
        for(var j=0;j<3;j++){
            var oSprite = s_oSpriteLibrary.getSprite("greyhound_"+(aRank[j]+1));
            var oData = {
                images: [oSprite],
                frames: {width: GREYHOUND_WIDTH, height: GREYHOUND_HEIGHT,regX: GREYHOUND_WIDTH/2,regY:GREYHOUND_HEIGHT},
                animations: {idle: [18,18], anim: [0,21],start:[22],anim_1:[5,21,"anim"],anim_2:[10,21,"anim"],anim_3:[15,21,"anim"]}
            };

            var oSpriteSheet = new createjs.SpriteSheet(oData);
            var oGreyhound = createSprite(oSpriteSheet,"idle",GREYHOUND_WIDTH/2,GREYHOUND_HEIGHT,GREYHOUND_WIDTH,GREYHOUND_HEIGHT);
            oGreyhound.scaleX = oGreyhound.scaleY = 0.5;
            oGreyhound.x = CANVAS_WIDTH/2 +150;
            oGreyhound.y = iY;
            _oContainer.addChild(oGreyhound);
            
            iY += 68;
        }
        
        //ADD WIN LIST
        var iY = CANVAS_HEIGHT/2 -150;
        for(var i=0;i<aWinList.length;i++){
            var iXOffset;
            if(aWinList[i].type === "forecast"){
                var aBibs = aWinList[i].greyhounds;
                var oBib1 = createSprite(_oSpriteSheetBib,"bib_"+aBibs[0],0,0,_iWidthBib,_iHeightBib);
                oBib1.x = 280;
                oBib1.y = iY;
                oBib1.scaleX = oBib1.scaleY = 0.5;
                _oContainer.addChild(oBib1);
                
                var oText = new createjs.Text("X", "20px " + PRIMARY_FONT, "#fff");
                oText.textAlign = "center";
                oText.textBaseline = "middle";
                oText.x = oBib1.x + _iWidthBib*0.5 + 10;
                oText.y = iY +15;
                _oContainer.addChild(oText);
                
                var oBib2 = createSprite(_oSpriteSheetBib,"bib_"+aBibs[1],0,0,_iWidthBib,_iHeightBib);
                oBib2.x = oText.x + 10;
                oBib2.y = iY;
                oBib2.scaleX = oBib2.scaleY = 0.5;
                _oContainer.addChild(oBib2);
                
                iXOffset = oBib2.x + 35;
            }else{
                var oBib1 = createSprite(_oSpriteSheetBib,"bib_"+aWinList[i].greyhounds,0,0,_iWidthBib,_iHeightBib);
                oBib1.x = 280;
                oBib1.y = iY;
                oBib1.scaleX = oBib1.scaleY = 0.5;
                _oContainer.addChild(oBib1);
                
                iXOffset = oBib1.x + 35;
            }
            
            var oTextType = new createjs.Text((aWinList[i].type).toUpperCase(), "16px " + PRIMARY_FONT, "#ffb400");
            oTextType.textAlign = "left";
            oTextType.textBaseline = "alphabetic";
            oTextType.x = iXOffset;
            oTextType.y = iY + 22;
            _oContainer.addChild(oTextType);
            
            var oTextWin = new createjs.Text(TEXT_WIN + ": " + aWinList[i].win + TEXT_CURRENCY, "16px " + PRIMARY_FONT, "#fff");
            oTextWin.textAlign = "left";
            oTextWin.textBaseline = "alphabetic";
            oTextWin.x = oTextType.x +oTextType.getBounds().width + 10;
            oTextWin.y = iY + 22;
            _oContainer.addChild(oTextWin);
            
            iY += 35;
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