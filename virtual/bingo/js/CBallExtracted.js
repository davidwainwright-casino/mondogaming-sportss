function CBallExtracted(iXPos,iYPos,iWidth,iHeight,iColor,iFontSize,iOutline,oSpriteSheet,oParentContainer){
    var _iColor;
    var _oBall;
    var _oTextBack;
    var _oText;
    var _oContainer;
    var _oParentContainer;
    
    this._init = function(iXPos,iYPos,iWidth,iHeight,iColor,iFontSize,iOutline,oSpriteSheet){
        _iColor = iColor;
        
        _oContainer = new createjs.Container();
        _oContainer.x = iXPos;
        _oContainer.y = iYPos;
        _oParentContainer.addChild(_oContainer);
        
        _oBall = createSprite(oSpriteSheet, iColor,iWidth/2,iHeight/2,iWidth,iHeight);
        _oBall.gotoAndStop(iColor);
        _oContainer.addChild(_oBall);
        
        _oTextBack = new createjs.Text("",iFontSize+"px " +PRIMARY_FONT, "#222");
        _oTextBack.textAlign = "center";
        _oTextBack.textBaseline = "middle";
        _oTextBack.outline = iOutline;
        _oContainer.addChild(_oTextBack);
        
        _oText = new createjs.Text("",iFontSize+"px " +PRIMARY_FONT, /*BALL_COLORS[iColor]*/"#fff");
        _oText.textAlign = "center";
        _oText.textBaseline = "middle";
        _oContainer.addChild(_oText);
    };
    
    this.setMask = function(oMask){
        _oContainer.mask = oMask;
        if(navigator.userAgent.indexOf("Chrome/50.0") > -1){
            _oContainer.compositeOperation = "hard-light";
        }
        
    };
    
    this.unload = function(){
        _oParentContainer.removeChild(_oContainer);
    };
    
    this.setText = function(szText){
        _oTextBack.text = szText;
        _oText.text = szText;
    };
    
    this.moveX = function(iNewX,oCallBack,oParent){
        createjs.Tween.get(_oContainer).to({x: iNewX}, 300,createjs.Ease.cubicOut).call(function(){oCallBack.call(oParent);});
    };
    
    this.moveY = function(iNewY,iNum,oCallBack,oParent){
        this.setText(iNum);
        createjs.Tween.get(_oContainer).to({y: iNewY}, 300,createjs.Ease.cubicOut).call(function(){oCallBack.call(oParent);});
    };
    
    this.getX = function(){
        return _oContainer.x + _oBall.x;
    };
    
    this.getY = function(){
        return _oContainer.y + _oBall.y;
    };
    
    this.getColor = function(){
        return _iColor;
    };
    
    _oParentContainer = oParentContainer;
    
    this._init(iXPos,iYPos,iWidth,iHeight,iColor,iFontSize,iOutline,oSpriteSheet);
}