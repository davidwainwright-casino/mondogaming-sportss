function CGameOver(){
    var _oButRecharge;
    var _oButExit;
    var _oContainer;
    
    this._init = function(){
        _oContainer = new createjs.Container();
        s_oStage.addChild(_oContainer);

        var oBg = createBitmap(s_oSpriteLibrary.getSprite('msg_box'));
        _oContainer.addChild(oBg);
        
        var oTextTitleShadow = new CTLText(_oContainer, 
                    CANVAS_WIDTH/2-198, (CANVAS_HEIGHT/2)-98, 400, 50, 
                    50, "center", "#000", FONT1, 1,
                    0, 0,
                    TEXT_GAMEOVER,
                    true, true, false,
                    false );
                    
        
        var oTextTitle = new CTLText(_oContainer, 
                    CANVAS_WIDTH/2-200, (CANVAS_HEIGHT/2)-100, 400, 50, 
                    50, "center", "#fff", FONT1, 1,
                    0, 0,
                    TEXT_GAMEOVER,
                    true, true, false,
                    false );
        
        _oButRecharge = new CTextButton(CANVAS_WIDTH/2 -100,450,s_oSpriteLibrary.getSprite('but_game_bg'),TEXT_RECHARGE,FONT1,"#fff",14,0,_oContainer);
        _oButRecharge.addEventListener(ON_MOUSE_UP, this._onRecharge, this);
        
        _oButExit = new CTextButton(CANVAS_WIDTH/2 + 100,450,s_oSpriteLibrary.getSprite('but_game_bg'),TEXT_EXIT,FONT1,"#fff",14,0,_oContainer);
        _oButExit.addEventListener(ON_MOUSE_UP, this._onExit, this);
        
        this.hide();
    };
	
    this.unload = function(){
        _oButRecharge.unload();
        _oButExit.unload();
    };
    
    this.show = function(){
        _oContainer.visible = true;
    };
    
    this.hide = function(){
        _oContainer.visible = false;
    };
    
    this._onRecharge = function(){
        if(AUTOMATIC_RECHARGE){
            s_oGame.recharge();
        }
        $(s_oMain).trigger("recharge");
    };
    
    this._onExit = function(){
        $(s_oMain).trigger("end_session");
        s_oGame.onExit();
    };
    
    this._init();
}