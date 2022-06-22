function CPaytablePanel(oParentContainer){
    var _aCards;
    var _oButPlay;
    var _oContainer;
    var _oParentContainer;
    
    var _oThis;
    
    this._init = function(){
        _oContainer = new createjs.Container();
        _oContainer.on("click",function(){});
        _oContainer.visible = false;
        _oParentContainer.addChild(_oContainer);
        
        var oBg = createBitmap(s_oSpriteLibrary.getSprite('msg_box'));
        _oContainer.addChild(oBg); 
        
        var oPaytableText = new CTLText(_oContainer, 
                    CANVAS_WIDTH/2-250, 250, 500, 44, 
                    44, "center", "#ff7803", PRIMARY_FONT, 1,
                    0, 0,
                    TEXT_PAYTABLE,
                    true, true, false,
                    false);

        
        this._initCards();
        
        _oButPlay = new CTextButton(CANVAS_WIDTH/2,CANVAS_HEIGHT - 150,s_oSpriteLibrary.getSprite('but_gui'),TEXT_PLAY,PRIMARY_FONT,"#ffffff",50,0,_oContainer);
        _oButPlay.addEventListener(ON_MOUSE_UP, this._onButPlay, this);
    };
    
    this.unload = function(){
        _oButPlay.unload();
        _oContainer.off("click",function(){});
    };
    
    this.show = function(){
        _oContainer.visible = true;
        createjs.Ticker.paused = true;
    };
    
    this.hide = function(){
        _oContainer.visible = false;
        createjs.Ticker.paused = false;
    };
    
    this._initCards = function(){
        var oSprite = s_oSpriteLibrary.getSprite('card_cell');
        var iCellSize = oSprite.width/4;
        var oData = {   
                        images: [oSprite], 
                        // width, height & registration point of each sprite
                        frames: {width: iCellSize, height: iCellSize}, 
                        animations: {state_empty:[0],state_fill:[1],state_extracted:[2],state_highlight:[3]}
                   };
                   
        var oSpriteSheet = new createjs.SpriteSheet(oData);  
        
        _aCards = new Array();
        _aCards[0] = new CPaytableCard(800,660,iCellSize,0,oSpriteSheet,_oContainer);
        _aCards[1] = new CPaytableCard(800,480,iCellSize,1,oSpriteSheet,_oContainer);
        _aCards[2] = new CPaytableCard(800,300,iCellSize,2,oSpriteSheet,_oContainer);
    };
    
    this.initPrizes = function(aPrizes){
        for(var i=0;i<_aCards.length;i++){
            _aCards[i].setMsg(TEXT_PAYTABLE_PRIZES[i] + ": x"+aPrizes[i]);
        }
    };
    
    this._onButPlay = function(){
        _oThis.hide();
    };
    
    _oThis = this;
    _oParentContainer = oParentContainer;
    
    this._init();
}