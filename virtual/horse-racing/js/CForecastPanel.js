function CForecastPanel(iX,iY,oParentContainer){
    var _aForecastBut;
    var _oContainer;
    var _oParentContainer;
    
    this._init = function(){
        _oContainer = new createjs.Container();
        _oContainer.x = iX;
        _oContainer.y = iY;
        _oParentContainer.addChild(_oContainer);
        
        var oBg = createBitmap(s_oSpriteLibrary.getSprite("forecast_panel"));
        _oContainer.addChild(oBg);
        
        this._initForecastBets();
    };
    
    this.unload = function(){
        for(var szKey in _aForecastBut){
            if(szKey.indexOf("forecast_") > -1){
                _aForecastBut[szKey].unload();
            }
        }
    };
    
    this._initForecastBets = function(){
        _aForecastBut = new Array();
        var aPos = [{x:26,y:5},{x:250,y:5},{x:474,y:5},{x:698,y:5},
                    {x:26,y:252},{x:250,y:252},{x:474,y:252},{x:698,y:252}];
        for(var i=0;i<NUM_HORSES;i++){
            var iX = aPos[i].x;
            var iY = aPos[i].y;
            this._placeForecastBetForHorse(i,iX,iY);
        }
    };
    
    this._placeForecastBetForHorse = function(iHorseIndex,iX,iY){
        var iScale = 0.55;
        var oSpriteOddBg = s_oSpriteLibrary.getSprite("odd_bg");
        var oSpriteButton = s_oSpriteLibrary.getSprite("bet_place");
        
        var oSprite = s_oSpriteLibrary.getSprite("bibs");
        var iWidthBib = oSprite.width/4;
        var iHeightBib = oSprite.height/2;
        
        var oData = {
            images: [oSprite],
            // width, height & registration point of each sprite
            frames: {width: iWidthBib, height: iHeightBib},
            animations: {bib_0: [0], bib_1: [1],bib_2:[2],bib_3:[3],bib_4:[4],bib_5:[5],bib_6:[6],bib_7:[7]}
        };

        var oSpriteSheetBib = new createjs.SpriteSheet(oData);
        for(var j=0;j<NUM_HORSES;j++){
            
            if(j !== iHorseIndex){
                var oBib1 = createSprite(oSpriteSheetBib,"bib_"+iHorseIndex,0,0,iWidthBib, iHeightBib);
                oBib1.x = iX;
                oBib1.y = iY;
                oBib1.scaleX = oBib1.scaleY = iScale;
                _oContainer.addChild(oBib1);

                var oText = new createjs.Text("X", "12px " + PRIMARY_FONT, "#fff");
                oText.textAlign = "center";
                oText.textBaseline = "middle";
                oText.x = iX + (iWidthBib*iScale) + 10;
                oText.y = iY + (iHeightBib*iScale)/2;
                _oContainer.addChild(oText);
            
                var oBib2 = createSprite(oSpriteSheetBib,"bib_"+j,0,0,iWidthBib/3, iHeightBib/2);
                oBib2.x = oText.x + 10;
                oBib2.y = oBib1.y;
                oBib2.scaleX = oBib2.scaleY = iScale;
                _oContainer.addChild(oBib2);
                
                var oOddBg = createBitmap(oSpriteOddBg);
                oOddBg.x = oBib2.x + (iWidthBib*iScale) + 10;
                oOddBg.y = oBib1.y + 2;
                _oContainer.addChild(oOddBg);
                
                var oTextOdd = new createjs.Text(s_oGameSettings.getForecastOdd(iHorseIndex,j), "18px " + PRIMARY_FONT, "#fff");
                oTextOdd.textAlign = "center";
                oTextOdd.textBaseline = "alphabetic";
                oTextOdd.x = oOddBg.x + oSpriteOddBg.width/2;
                oTextOdd.y = oOddBg.y + oSpriteOddBg.height/2 + 8;
                _oContainer.addChild(oTextOdd);

                
                var oBut = new CButBet(oOddBg.x + oSpriteOddBg.width + (oSpriteButton.width*0.72)/2 +5,oOddBg.y+ (oSpriteButton.height*0.72)/2 - 2,oSpriteButton,0.45,_oContainer);
                oBut.setScale(0.60);
                oBut.addEventListenerWithParams(ON_MOUSE_UP,this._onForecastClicked,this,{first:iHorseIndex,second:j});
                
                _aForecastBut["forecast_"+iHorseIndex+"_"+j] = oBut;
                
                iY += ((iHeightBib-14)*iScale) + 3;
            }
            
            
         }       
    };
    
    this.setX = function(iX){
        _oContainer.x = iX;
    };
    
    this._onForecastClicked = function(oParams){
        var iFicheValue = CHIP_VALUES[s_oBetPanel.getChipSelected()];
        if(s_oBetPanel.setForecastBet(oParams.first,oParams.second,iFicheValue,_aForecastBut["forecast_"+oParams.first+"_"+oParams.second])){
            _aForecastBut["forecast_"+oParams.first+"_"+oParams.second].increaseBet(iFicheValue);
        }
        
        
    };
    
    _oParentContainer = oParentContainer;
    this._init(iX,iY);
}