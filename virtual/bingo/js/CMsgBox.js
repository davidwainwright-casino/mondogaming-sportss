function CMsgBox(oSpriteBg){
    
    var _oBg;
    var _oGroup;

    var _oMsgText;
    var _oButExit;
    
    this._init = function(oSpriteBg){
        _oGroup = new createjs.Container();
        _oGroup.alpha = 0;
        _oGroup.visible=false;
        
        
        _oBg = createBitmap(oSpriteBg);
        _oGroup.addChild(_oBg);
        
	

        _oMsgText = new CTLText(_oGroup, 
                    CANVAS_WIDTH/2-250, (CANVAS_HEIGHT/2)-200, 500, 250, 
                    100, "center", "#fff", PRIMARY_FONT, 1,
                    0, 0,
                    " ",
                    true, true, true,
                    false );

        
        _oButExit = new CTextButton(CANVAS_WIDTH/2,CANVAS_HEIGHT/2 + 150,s_oSpriteLibrary.getSprite('but_gui'),TEXT_EXIT,PRIMARY_FONT,"#ffffff",50,0,_oGroup);
        _oButExit.addEventListener(ON_MOUSE_UP, this._onExit, this);

        s_oStage.addChild(_oGroup);
    };

    
    this.show = function(szText){

	playSound("game_over",1,false);

        _oMsgText.refreshText(szText);
        
        _oGroup.visible = true;
        createjs.Tween.get(_oGroup).to({alpha:1 }, 500);
    };
    
    this._onExit = function(){
        s_oStage.removeChild(_oGroup);
        
        $(s_oMain).trigger("end_session");
    };
    
    this._init(oSpriteBg);
    
    return this;
}
