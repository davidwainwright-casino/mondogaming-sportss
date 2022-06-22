function CAreYouSurePanel(oParentContainer){
    var _oMsg;
    var _oButYes;
    var _oButNo;
    var _oContainer;
    var _oParentContainer;
    
    this._init = function(){
        _oContainer = new createjs.Container();
        _oContainer.visible = false;
        _oParentContainer.addChild(_oContainer);
        
        var oBg = createBitmap(s_oSpriteLibrary.getSprite('msg_box'));
        _oContainer.addChild(oBg); 
        
        _oMsg = new CTLText(_oContainer, 
                    CANVAS_WIDTH/2-250, (CANVAS_HEIGHT/2)-200, 500, 250, 
                    100, "center", "#fff", PRIMARY_FONT, 1,
                    0, 0,
                    TEXT_ARE_SURE,
                    true, true, true,
                    false );
        
        _oButYes = new CTextButton(CANVAS_WIDTH/2 + 170,770,s_oSpriteLibrary.getSprite('but_gui'),TEXT_YES,PRIMARY_FONT,"#ffffff",50,0,_oContainer);
        _oButYes.addEventListener(ON_MOUSE_UP, this._onButYes, this);
        
        _oButNo = new CTextButton(CANVAS_WIDTH/2 - 170,770,s_oSpriteLibrary.getSprite('but_gui'),TEXT_NO,PRIMARY_FONT,"#ffffff",50,0,_oContainer);
        _oButNo.addEventListener(ON_MOUSE_UP, this._onButNo, this);
        
    };
    
    this.show = function(){
        createjs.Ticker.paused = true;
        _oContainer.visible = true;
    };
    
    this._onButYes = function(){
        createjs.Ticker.paused = false;
        s_oGame.onExit();
    };
    
    this._onButNo = function(){
        createjs.Ticker.paused = false;
        _oContainer.visible = false;
    };
    
    _oParentContainer = oParentContainer;
    
    this._init();
}