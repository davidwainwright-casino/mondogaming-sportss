function CNumToggle(iXPos,iYPos, iNum, oParentContainer){
    var _bActive;
    var _bBlock;
    
    var _aCbCompleted;
    var _aCbOwner;
    var _oButton;
    var _oButtonBg;
    var _oExtracted;
    var _oListenerMouseDown;
    var _oListenerMouseUp;
    
    var _aParams = [];
    
    this._init = function(iXPos,iYPos, iNum, oParentContainer){
        
        _bBlock = false;
        
        _aCbCompleted=new Array();
        _aCbOwner =new Array();
        
        _oButton = new createjs.Container();
        _oButton.x = iXPos;
        _oButton.y = iYPos;
        _oButton.cursor = "pointer";
        oParentContainer.addChild(_oButton);
        
        var oSprite = s_oSpriteLibrary.getSprite('num_button');
        var oData = {   
                        images: [oSprite],
                        framerate: 5,
                        // width, height & registration point of each sprite
                        frames: {width: oSprite.width/2, height: oSprite.height, regX: (oSprite.width/2)/2, regY: oSprite.height/2}, 
                        animations: {state_true:[0],state_false:[1]}
                   };
                   
        var oSpriteSheet = new createjs.SpriteSheet(oData);
         
        _bActive = false;
        _oButtonBg = createSprite(oSpriteSheet, "state_"+_bActive,(oSprite.width/2)/2,oSprite.height/2,oSprite.width/2,oSprite.height);
        _oButtonBg.stop();
        
        var oSprite = s_oSpriteLibrary.getSprite('ball');
        var oData = {   
                        images: [oSprite], 
                        // width, height & registration point of each sprite
                        frames: {width: oSprite.width/NUM_DIFFERENT_BALLS, height: oSprite.height, regX:(oSprite.width/NUM_DIFFERENT_BALLS)/2, regY:oSprite.height/2}, 
                        animations: {red:[0],green:[1],cyan:[0],violet:[1],blue:[1]}
                   };
                   
        var oSpriteSheet = new createjs.SpriteSheet(oData);
        
        _oExtracted = createSprite(oSpriteSheet, "red",(oSprite.width/NUM_DIFFERENT_BALLS)/2,oSprite.height/2,oSprite.width/NUM_DIFFERENT_BALLS,oSprite.height);//new createBitmap(oSprite);
        _oExtracted.gotoAndStop(0);
        _oExtracted.visible = false;

        _oButton.addChild(_oButtonBg, _oExtracted);
        
        this._initListener();
    };
    
    this.unload = function(){
       _oButton.off("mousedown", _oListenerMouseDown);
       _oButton.off("pressup" , _oListenerMouseUp);
	   
       oParentContainer.removeChild(_oButton);
    };
    
    this._initListener = function(){
       _oListenerMouseDown = _oButton.on("mousedown", this.buttonDown);
       _oListenerMouseUp = _oButton.on("pressup" , this.buttonRelease);      
    };
    
    this.addEventListener = function( iEvent,cbCompleted, cbOwner ){
        _aCbCompleted[iEvent]=cbCompleted;
        _aCbOwner[iEvent] = cbOwner; 
    };
    
    this.addEventListenerWithParams = function(iEvent,cbCompleted, cbOwner,aParams){
        _aCbCompleted[iEvent]=cbCompleted;
        _aCbOwner[iEvent] = cbOwner;
        _aParams = aParams;
    };
    
    this.setActive = function(bActive){
        _bActive = bActive;
        _oButtonBg.gotoAndStop("state_"+_bActive);
    };
    
    this.buttonRelease = function(){
        
        if(_bBlock){
            return;
        }
        
        
        playSound("click",1,false);
        
        _bActive = !_bActive;
        _oButtonBg.gotoAndStop("state_"+_bActive);

        if(_aCbCompleted[ON_MOUSE_UP]){
            _aCbCompleted[ON_MOUSE_UP].call(_aCbOwner[ON_MOUSE_UP],_aParams);
        }
    };
    
    this.buttonDown = function(){
        if(_bBlock){
            return;
        }

       if(_aCbCompleted[ON_MOUSE_DOWN]){
           _aCbCompleted[ON_MOUSE_DOWN].call(_aCbOwner[ON_MOUSE_DOWN],_aParams);
       }
    };
    
    this.setPosition = function(iXPos,iYPos){
         _oButton.x = iXPos;
         _oButton.y = iYPos;
    };
    
    this.getGlobalPosition = function(){
        return {x: _oButton.localToGlobal(0,0).x/s_iScaleFactor, y: _oButton.localToGlobal(0,0).y/s_iScaleFactor};
    };
    
    this.block = function(bVal){
        _bBlock = bVal;
    };
    
    this.setExtracted = function(bVal, iColor){
        _oExtracted.visible = bVal;
        _oExtracted.gotoAndStop(iColor);
    };
    
    this.highlight = function(){        
        _oButtonBg.gotoAndPlay(0);        
    };
    
    this.stopHighlight = function(){        
        _oButtonBg.gotoAndStop(1);
    };
    
    this._init(iXPos,iYPos, iNum, oParentContainer);
}