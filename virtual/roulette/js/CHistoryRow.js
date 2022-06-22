function CHistoryRow(iX,iY,oParentContainer){
    
    var _oRedBg;
    var _oGreenBg;
    var _oBlackBg;
    var _oRedText;
    var _oGreenText;
    var _oBlackText;
    var _oContainer;
    var _oParentContainer;
    
    this._init = function(iX,iY){
         _oContainer = new createjs.Container();
         _oContainer.x = iX;
         _oContainer.y = iY;
         oParentContainer.addChild(_oContainer);
        
         _oRedBg = createBitmap(s_oSpriteLibrary.getSprite('circle_red'));
         _oRedBg.visible = false;
         _oContainer.addChild(_oRedBg);
         
         _oGreenBg = createBitmap(s_oSpriteLibrary.getSprite('circle_green'));
         _oGreenBg.visible = false;
         _oGreenBg.x = 24;
         _oContainer.addChild(_oGreenBg);
         
         _oBlackBg = createBitmap(s_oSpriteLibrary.getSprite('circle_black'));
         _oBlackBg.visible = false;
         _oBlackBg.x = 48;
         _oContainer.addChild(_oBlackBg);
         
         _oRedText = new createjs.Text("a","12px "+FONT1, "#fff");
         _oRedText.x = _oRedBg.x +10;
         _oRedText.y = _oRedBg.y +3;
         _oRedText.visible = false;
         _oRedText.textAlign = "center";
         _oContainer.addChild(_oRedText);
         
         _oGreenText = new createjs.Text("a","12px "+FONT1, "#fff");
         _oGreenText.x = _oGreenBg.x +10;
         _oGreenText.y = _oGreenBg.y +3;
         _oGreenText.visible = false;
         _oGreenText.textAlign = "center";
         _oContainer.addChild(_oGreenText);
         
         _oBlackText = new createjs.Text("a","12px "+FONT1, "#fff");
         _oBlackText.x = _oBlackBg.x +10;
         _oBlackText.y = _oBlackBg.y +3;
         _oBlackText.visible = false;
         _oBlackText.textAlign = "center";
         _oContainer.addChild(_oBlackText);
    };
    
    this.showBlack = function(szText){
        _oBlackText.text = szText;
        
        _oRedBg.visible = false;
        _oRedText.visible = false;
        
        _oGreenBg.visible = false;
        _oGreenText.visible = false;
        
        _oBlackBg.visible = true;
        _oBlackText.visible = true;
    };
    
    this.showRed = function(szText){
        _oRedText.text = szText;
        
        _oRedBg.visible = true;
        _oRedText.visible = true;
        
        _oGreenBg.visible = false;
        _oGreenText.visible = false;
        
        _oBlackBg.visible = false;
        _oBlackText.visible = false;
    };
    
    this.showGreen = function(szText){
        _oGreenText.text = szText;
        
        _oRedBg.visible = false;
        _oRedText.visible = false;
        
        _oGreenBg.visible = true;
        _oGreenText.visible = true;
        
        _oBlackBg.visible = false;
        _oBlackText.visible = false;
    };
    
    _oParentContainer = oParentContainer;
    this._init(iX,iY);
}