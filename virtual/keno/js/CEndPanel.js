function CEndPanel(oSpriteBg){
    
    var _oBg;
    var _oGroup;
    
    var _oMsgTextBack;
    var _oMsgText;
    var _oListener;
    
    this._init = function(oSpriteBg){
        _oGroup = new createjs.Container();
        _oGroup.alpha = 0;
        _oGroup.visible=false;
        s_oStage.addChild(_oGroup);
        
        _oBg = createBitmap(oSpriteBg);
        _oGroup.addChild(_oBg);
        
	_oMsgTextBack = new CTLText(_oGroup, 
                    CANVAS_WIDTH/2 -248, (CANVAS_HEIGHT/2)-148, 500, 300, 
                    60, "center", "#000", PRIMARY_FONT, 1,
                    0, 0,
                    " ",
                    true, true, true,
                    false );

        
        _oMsgText = new CTLText(_oGroup, 
                    CANVAS_WIDTH/2 -250, (CANVAS_HEIGHT/2)-150, 500, 300, 
                    60, "center", "#fff", PRIMARY_FONT, 1,
                    0, 0,
                    " ",
                    true, true, true,
                    false );
        
    };
    
    this.unload = function(){
        _oGroup.off("mousedown",_oListener);
    };
    
    this._initListener = function(){
        _oListener = _oGroup.on("mousedown",this._onExit);
    };
    
    this.show = function(){
	playSound("game_over",1,false);
        
        
        _oMsgTextBack.refreshText(TEXT_GAMEOVER);
        _oMsgText.refreshText(TEXT_GAMEOVER);
        
        _oGroup.visible = true;
        
        var oParent = this;
        createjs.Tween.get(_oGroup).to({alpha:1 }, 500).call(function() {oParent._initListener();});
    };
    
    this._onExit = function(){
        _oGroup.off("mousedown",_oListener);
        s_oStage.removeChild(_oGroup);
        
        $(s_oMain).trigger("end_session");
        
        s_oGame.onExit();
    };
    
    this._init(oSpriteBg);
    
    return this;
}
