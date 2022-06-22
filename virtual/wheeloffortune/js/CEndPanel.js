function CEndPanel(oSpriteBg){
    
    var _oBg;
    var _oGroup;
    
    var _oMsgTextBack;
    var _oMsgText;
    
    this._init = function(oSpriteBg){
        _oGroup = new createjs.Container();
        _oGroup.alpha = 0;
        _oGroup.visible=false;
        s_oStage.addChild(_oGroup);
        
        _oBg = createBitmap(oSpriteBg);
        _oGroup.addChild(_oBg);
        
	_oMsgTextBack = new CTLText(_oGroup, 
                    CANVAS_WIDTH/2 -398, 222, 800, 600, 
                    70, "center", "#000", PRIMARY_FONT, 1,
                    20, 20,
                    "",
                    true, true, true,
                    false );
                    
        

        _oMsgText = new CTLText(_oGroup, 
                    CANVAS_WIDTH/2 -400, 220, 800, 600, 
                    70, "center", "#fff", PRIMARY_FONT, 1,
                    20, 20,
                    "",
                    true, true, true,
                    false );
    };
    
    this.unload = function(){
        _oGroup.off("mousedown",this._onExit);
    };
    
    this._initListener = function(){
        _oGroup.on("mousedown",this._onExit);
    };
    
    this.show = function(){
	playSound("game_over",1,false);
	
        
        $(s_oMain).trigger("show_interlevel_ad");
        
        _oMsgTextBack.refreshText(TEXT_GAMEOVER);
        _oMsgText.refreshText(TEXT_GAMEOVER);
        
        _oGroup.visible = true;
        
        var oParent = this;
        createjs.Tween.get(_oGroup).to({alpha:1 }, 500).call(function() {oParent._initListener();});

    };
    
    this._onExit = function(){
        _oGroup.off("mousedown",this._onExit);
        s_oStage.removeChild(_oGroup);
        $(s_oMain).trigger("end_session");        
        s_oGame.onExit();
    };
    
    this._init(oSpriteBg);
    
    return this;
}
