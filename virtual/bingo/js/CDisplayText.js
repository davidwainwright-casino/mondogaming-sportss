function CDisplayText(iX,iY,oSpriteBg,szText,szTitle,iFontSize,oParentContainer){
    var _oText;
    var _oTitleText;
    var _oContainer;
    var _oParentContainer;
    
    this._init = function(iX,iY,oSpriteBg,szText,szTitle,iFontSize){
        _oContainer = new createjs.Container();
        _oContainer.x = iX;
        _oContainer.y = iY;
        _oParentContainer.addChild(_oContainer);
        
        var oBg = createBitmap(oSpriteBg);
        _oContainer.addChild(oBg);
        
        _oText = new CTLText(_oContainer, 
                    0, 0, oSpriteBg.width, oSpriteBg.height, 
                    iFontSize, "center", "#fff", PRIMARY_FONT, 1,
                    10, 0,
                    szText,
                    true, true, false,
                    false );

        
        _oTitleText = new CTLText(_oContainer, 
                    0, -40, oSpriteBg.width, 36, 
                    36, "center", "#fff", PRIMARY_FONT, 1,
                    10, 0,
                    szTitle,
                    true, true, false,
                    false );

    };
    
    this.setPosition = function(iX,iY){
        _oContainer.x = iX;
        _oContainer.y = iY;
    };
    
    this.changeText = function(szText){
        _oText.refreshText(szText);
    };
    
    _oParentContainer = oParentContainer;
    
    this._init(iX,iY,oSpriteBg,szText,szTitle,iFontSize);
}