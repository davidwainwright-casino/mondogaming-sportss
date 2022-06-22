function CNumberBoardCell(iXPos,iYPos,szText,iWidth,iHeight,oSpriteSheet,oParentContainer){
    var _oBgCell;
    var _oText;
    var _oTextBack;
    var _oContainer;
    var _oParentContainer;
    
    this._init = function(iXPos,iYPos,szText,iWidth,iHeight,oSpriteSheet){
        _oContainer = new createjs.Container();
        _oContainer.x = iXPos;
        _oContainer.y = iYPos;
        _oParentContainer.addChild(_oContainer);

        _oBgCell = createSprite(oSpriteSheet, "", 0,0,iWidth, iHeight);
        _oContainer.addChild(_oBgCell);
        
        _oTextBack = new createjs.Text(szText,"36px " +PRIMARY_FONT, "#000");
        _oTextBack.x = iWidth/2 + 1;
        _oTextBack.y = iHeight/2 + 1;
        _oTextBack.textAlign = "center";
        _oTextBack.textBaseline = "middle";
        _oContainer.addChild(_oTextBack);
        
        _oText = new createjs.Text(szText,"36px " +PRIMARY_FONT, "#fff");
        _oText.x = iWidth/2;
        _oText.y = iHeight/2;
        _oText.textAlign = "center";
        _oText.textBaseline = "middle";
        _oContainer.addChild(_oText);
    };

    this.setState = function(szState){
        _oBgCell.gotoAndStop(szState);
        
        if(szState === "state_3"){
            _oText.color = "#ff7803";
        }else{
            _oText.color = "#fff";
        }
        
    };
    
    _oParentContainer = oParentContainer;
    
    this._init(iXPos,iYPos,szText,iWidth,iHeight,oSpriteSheet);
}