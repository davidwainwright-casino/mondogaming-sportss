function CGate(iX,iY,iIndex,oParentContainer){
    var _oSprite;
    var _oParentContainer;
    
    this._init = function(iX,iY,iIndex){
        var oSprite = s_oSpriteLibrary.getSprite("gate_"+iIndex);
        var oData = {
            images: [oSprite],
            // width, height & registration point of each sprite
            frames: {width: oSprite.width/5, height: oSprite.height},
            animations: {idle: [0,0],anim:[0,4,"stop_anim"],stop_anim:[4,4]}
        };
        
        var oSpriteSheet = new createjs.SpriteSheet(oData);
        _oSprite = createSprite(oSpriteSheet,"idle",0,0,oSprite.width/5,oSprite.height);
        _oSprite.x = iX;
        _oSprite.y = iY;
        _oParentContainer.addChild(_oSprite);
    };
    
    this.unload = function(){
        _oParentContainer.removeChild(_oSprite);
    };
    
    this.playAnim = function(){
        _oSprite.gotoAndPlay("anim");
    };
    
    this.decreaseX = function(iX){
        _oSprite.x -= iX;
    };
    
    _oParentContainer = oParentContainer;
    this._init(iX,iY,iIndex);
}