function CHistory(iX,iY,oParentContainer){
    var _aHistoryRows;
    var _oContainer;
    var _oParentContainer;
    
    this._init = function(iX,iY){
        _oContainer = new createjs.Container();
        _oContainer.x = iX;
        _oContainer.y = iY;
        _oParentContainer.addChild(_oContainer);
        
        var oSpriteBg = s_oSpriteLibrary.getSprite("history_bg");
        var oBg = createBitmap(oSpriteBg);
        _oContainer.addChild(oBg);
        
        var oTitle = new createjs.Text(TEXT_HISTORY,"12px "+FONT1, "#fff");
        oTitle.textAlign = "center";
        oTitle.x = oSpriteBg.width/2;
        oTitle.y = 5;
        _oContainer.addChild(oTitle);
        
        this._initNumExtracted();
    };
    
    this._initNumExtracted = function(){
        _aHistoryRows = new Array();
        
        var iXPos = 5;
        var iYPos = 24;
        for(var i=0;i<ROW_HISTORY;i++){
            var oRow = new CHistoryRow(iXPos,iYPos,_oContainer);
            _aHistoryRows[i] = oRow;
            iYPos += 22;
        }
    };
    
    this.setPosition = function(iX,iY){
        _oContainer.x = iX;
        _oContainer.y = iY;
    };
    
    this.showBlack = function(iIndex,iNumber){
        _aHistoryRows[iIndex].showBlack(iNumber);
    };
    
    this.showRed = function(iIndex,iNumber){
        _aHistoryRows[iIndex].showRed(iNumber);
    };
    
    this.showGreen = function(iIndex,iNumber){
        _aHistoryRows[iIndex].showGreen(iNumber);
    };
    
    
    _oParentContainer = oParentContainer;
    this._init(iX,iY);
}