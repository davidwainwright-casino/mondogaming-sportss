function CAreYouSurePanel(oParentContainer){
    var _oListener;
    var _oButYes;
    var _oButNo;
    var _oContainer;
    var _oParentContainer;
    
    this._init = function(){
        _oContainer = new createjs.Container();
        _oListener = _oContainer.on("click",function(){});
        _oContainer.visible = false;
        _oParentContainer.addChild(_oContainer);
        
        var oBg = createBitmap(s_oSpriteLibrary.getSprite('msg_box'));
        _oContainer.addChild(oBg);
        
        var oText = new createjs.Text(TEXT_ARE_YOU_SURE, "50px " + PRIMARY_FONT, "#fff");
        oText.textAlign = "center";
        oText.textBaseline = "alphabetic";
        oText.x = CANVAS_WIDTH/2;
        oText.y = 310;
        oText.lineWidth = 400;
        oText.lineHeight = 48;
        _oContainer.addChild(oText);
        
        _oButYes = new CGfxButton(CANVAS_WIDTH/2 + 180,500,s_oSpriteLibrary.getSprite("but_yes"),_oContainer);
        _oButYes.addEventListener(ON_MOUSE_UP,this._onReleaseYes,this);
        
        _oButNo = new CGfxButton(CANVAS_WIDTH/2 - 180,500,s_oSpriteLibrary.getSprite("but_no"),_oContainer);
        _oButNo.addEventListener(ON_MOUSE_UP,this._onReleaseNo,this);
    };
    
    this.unload = function(){
        _oContainer.off("click",_oListener);
        _oButNo.unload();
        _oButYes.unload();
    };
    
    this.show = function(){
        _oContainer.visible = true;
        _oContainer.alpha = 0;
        createjs.Tween.get(_oContainer).to({alpha: 1}, 500,createjs.Ease.cubicOut);
    };
    
    this._onReleaseYes = function(){
        s_oGame.onExit();
    };
    
    this._onReleaseNo = function(){
        _oContainer.visible = false;
        s_oGame.unpause();
    };
    
    _oParentContainer = oParentContainer;
    this._init(oParentContainer);
}