function CPaytableCard(iX,iY,iCellSize,iRows,oSpriteSheet,oParentContainer){
    
    var _oMsg;
    var _oContainer;
    var _oParentContainer;
    
    this._init = function(iX,iY,iCellSize,iRows,oSpriteSheet){
        _oContainer = new createjs.Container();
        _oContainer.x = iX;
        _oContainer.y = iY;
        _oParentContainer.addChild(_oContainer);
        
        var iScale = 0.3;
        var iXPos = 0;
        var iYPos = 0;
        for(var i=0;i<CARD_ROWS;i++){
            for(var j=0;j<CARD_COLS;j++){
                if(iRows<i){
                    var oCell = createSprite(oSpriteSheet,"state_empty",0,0,iCellSize,iCellSize);
                }else{
                    var oCell = createSprite(oSpriteSheet,"state_highlight",0,0,iCellSize,iCellSize);
                }
                
                oCell.x = iXPos;
                oCell.y = iYPos;
                oCell.scaleX = oCell.scaleY = iScale;
                _oContainer.addChild(oCell);
                
                iXPos += iCellSize*iScale; 
            }
            iXPos = 0;
            iYPos += iCellSize*iScale;
        }
        
        _oMsg = new CTLText(_oContainer, 
                    (iCellSize*iScale*CARD_COLS)/2 - 196, iYPos + 8, 392, 40, 
                    40, "center", "#fff", PRIMARY_FONT, 1,
                    0, 0,
                    TEXT_PAYTABLE_PRIZES[iRows],
                    true, true, false,
                    false);

    };
    
    this.setMsg = function(szText){
        _oMsg.text = szText;
    };
    
    _oParentContainer = oParentContainer;
    
    this._init(iX,iY,iCellSize,iRows,oSpriteSheet);
}