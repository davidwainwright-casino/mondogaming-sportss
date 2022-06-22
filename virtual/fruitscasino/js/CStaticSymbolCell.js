function CStaticSymbolCell(iRow,iCol,iXPos,iYPos){
    
    var _iRow;
    var _iCol;
    var _iCurSpriteAnimating = -1;
    var _iLastAnimFrame;
    var _aSprites;
    var _oWinningFrame;
    var _oContainer;
    
    this._init = function(iRow,iCol,iXPos,iYPos){
        _iRow = iRow;
        _iCol = iCol;
        
        _oContainer = new createjs.Container();
        _oContainer.visible = false;
        
        var oParent= this;
        _aSprites = new Array();
        for(var i=0;i<NUM_SYMBOLS;i++){
            var oSprite = createSprite(s_aSymbolAnims[i], "static",0,0,SYMBOL_SIZE,SYMBOL_SIZE);
            oSprite.stop();
            oSprite.x = iXPos;
            oSprite.y = iYPos;
            oSprite.on("animationend", oParent._onAnimEnded, null, false, {index:i});
            _oContainer.addChild(oSprite);
            
            _aSprites[i] = oSprite;
            _aSprites[i].visible = false;
        }
        
        var oData = {   // image to use
                        framerate: 60,
                        images: [s_oSpriteLibrary.getSprite('win_frame_anim')], 
                        // width, height & registration point of each sprite
                        frames: {width: SYMBOL_SIZE, height: SYMBOL_SIZE, regX: 0, regY: 0}, 
                        animations: {  static: [0, 1],anim:[1,19] }
        };

        var oSpriteSheet = new createjs.SpriteSheet(oData);
        
        _oWinningFrame = new createjs.Sprite(oSpriteSheet, "static",0,0,SYMBOL_SIZE,SYMBOL_SIZE);
        _oWinningFrame.stop();
        _oWinningFrame.x = iXPos;
        _oWinningFrame.y = iYPos;
        _oContainer.addChild(_oWinningFrame);
        
        s_oStage.addChild(_oContainer);
    };
    
    this.unload = function(){
        s_oStage.removeChild(_oContainer);
    };
    
    this.hide = function(){
         if(_iCurSpriteAnimating > -1){
            _oWinningFrame.gotoAndStop("static"); 
            _oWinningFrame.visible = false;
            _aSprites[_iCurSpriteAnimating].gotoAndPlay("static");
            _oContainer.visible = false;
        }
    };
    
    this.show = function(iValue){
        _oWinningFrame.gotoAndPlay("anim");
        _oWinningFrame.visible = true;
        
        for(var i=0;i<NUM_SYMBOLS;i++){
            if( (i+1) === iValue){
                _aSprites[i].visible = true;
            }else{
                _aSprites[i].visible = false;
            }
        }

        _aSprites[iValue-1].gotoAndPlay("anim");
        _iCurSpriteAnimating = iValue-1;
        _iLastAnimFrame = _aSprites[iValue-1].spriteSheet.getNumFrames();
        
        _oContainer.visible = true;
    };
    
    this._onAnimEnded = function(evt,oData){
        if(_aSprites[oData.index].currentFrame === _iLastAnimFrame){
            return;
        }
        _aSprites[oData.index].stop();
        setTimeout(function(){_aSprites[oData.index].gotoAndPlay(1);},100);
    };
    
    this.stopAnim = function(){
       _aSprites[_iCurSpriteAnimating].gotoAndStop("static");
       _aSprites[_iCurSpriteAnimating].visible = false;
       
       _oWinningFrame.gotoAndStop("static");
       _oWinningFrame.visible = false;
    };
    
    this._init(iRow,iCol,iXPos,iYPos);
}