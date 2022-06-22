function CHelpPanel(){
    var _oText1;
    var _oText1Back;
    var _oText2;
    var _oText2Back;    

    var _oHelpBg;
    var _oGroup;
    var _oParent;

    this._init = function(){
        _oGroup = new createjs.Container();
        _oGroup.alpha=0;
        s_oStage.addChild(_oGroup);
        
        var oParent = this;
        _oHelpBg = createBitmap(s_oSpriteLibrary.getSprite('bg_help'));
        _oGroup.addChild(_oHelpBg);
        
       
  
        _oText1Back = new CTLText(_oGroup, 
                    CANVAS_WIDTH/2-98, (CANVAS_HEIGHT/2)-312, 600, 280, 
                    70, "center", "#000000", PRIMARY_FONT, 1,
                    2, 2,
                    TEXT_HELP1,
                    true, true, true,
                    false );
                    

  
        _oText1 = new CTLText(_oGroup, 
                    CANVAS_WIDTH/2-100, (CANVAS_HEIGHT/2)-310, 600, 280, 
                    70, "center", "#fff", PRIMARY_FONT, 1,
                    2, 2,
                    TEXT_HELP1,
                    true, true, true,
                    false );
        
        var oText2Pos = {x: CANVAS_WIDTH/2 -200, y: (CANVAS_HEIGHT/2)+150};
  
        _oText2Back = new CTLText(_oGroup, 
                    CANVAS_WIDTH/2-498, (CANVAS_HEIGHT/2)+102, 600, 180, 
                    70, "center", "#000000", PRIMARY_FONT, 1,
                    2, 2,
                    TEXT_HELP2,
                    true, true, true,
                    false );
  
        _oText2 = new CTLText(_oGroup, 
                    CANVAS_WIDTH/2-500, (CANVAS_HEIGHT/2)+100, 600, 180, 
                    70, "center", "#fff", PRIMARY_FONT, 1,
                    2, 2,
                    TEXT_HELP2,
                    true, true, true,
                    false );
        
        

        createjs.Tween.get(_oGroup).to({alpha:1}, 700);        
        
        _oGroup.on("pressup",function(){oParent._onExitHelp();});
     
    };

    this.unload = function(){
        s_oStage.removeChild(_oGroup);

        var oParent = this;
        _oGroup.off("pressup",function(){oParent._onExitHelp()});
    };

    this._onExitHelp = function(){
        _oParent.unload();

    };

    _oParent=this;
    this._init();

}
