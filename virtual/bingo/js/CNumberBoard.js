function CNumberBoard(iXPos,iYPos,oParentContainer){
    var BOARD_ROWS = 5;
    var BOARD_COLS = 18;
    var _aCells;
    var _oContainer;
    var _oParentContainer;
    
    this._init= function(iXPos,iYPos){
        _oContainer = new createjs.Container();
        _oContainer.x = iXPos;
        _oContainer.y = iYPos;
        _oParentContainer.addChild(_oContainer);
        
        this._initGrid();
    };
    
    this._initGrid = function(){
        var oSprite = s_oSpriteLibrary.getSprite('board_cell');
        var oData = {   
                        images: [oSprite], 
                        // width, height & registration point of each sprite
                        frames: {width: oSprite.width/3, height: oSprite.height}, 
                        animations: {state_1:[0],state_2:[1],state_3:[2]}
                   };
                   
        var oSpriteSheet = new createjs.SpriteSheet(oData); 
        
        var iX = iXPos;
        var iY = iYPos;
        var szState = "state_1";
        _aCells = new Array();
        for(var i=0;i<BOARD_ROWS;i++){
            for(var j=0;j<BOARD_COLS;j++){
                var oCell = new CNumberBoardCell(iX,iY,(i*BOARD_COLS)+(j+1),oSprite.width/3,oSprite.height,oSpriteSheet,_oParentContainer);
                szState = szState === "state_1"?"state_2":"state_1";
                oCell.setState(szState);
                _aCells.push(oCell);
                
                iX += oSprite.width/3;
            }
            
            iX = iXPos;
            iY += oSprite.height;
            szState = szState === "state_1"?"state_2":"state_1";
        }
    };
    
    this.reset = function(){
        var szState = "state_1";
        for(var i=0;i<_aCells.length;i++){
            szState = szState === "state_1"?"state_2":"state_1";
            _aCells[i].setState(szState);
            if((i+1)%BOARD_COLS === 0){
                szState = szState === "state_1"?"state_2":"state_1";
            }
        }
    };
    
    this.numExtracted = function(iNum){
        _aCells[iNum-1].setState("state_3");
    };
    
    _oParentContainer = oParentContainer;
    
    this._init(iXPos,iYPos);
}