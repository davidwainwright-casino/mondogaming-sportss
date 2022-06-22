//THIS CLASS CREATE A STANDARD TOGGLE BUTTON (WITH TEXT INSIDE) WITH A SPRITESHEET (2 STATES: ON,OFF)
function CToggleText(bActive,iXPos,iYPos,oSprite,iWidth,iHeight,szText,szFont,szColor,iFontSize,oParentContainer){
    var _bActive;
    var _iWidth;
    var _iHeight;
    var _aCbCompleted;
    var _aCbOwner;
    var _oListenerDown;
    var _oListenerUp;
    
    var _oButton;
    var _oText;
    var _oContainer;
    var _oParentContainer;
    
	//INITIALIZE THE BUTTON
    this._init = function(bActive,iXPos,iYPos,oSprite,iWidth,iHeight,szText,szFont,szColor,iFontSize){
        _aCbCompleted=new Array();
        _aCbOwner =new Array();
        
        _bActive = bActive;
        _iWidth = iWidth;
        _iHeight = iHeight;
        
        _oContainer = new createjs.Container();
        _oContainer.x = iXPos;
        _oContainer.y = iYPos; 
        _oContainer.regX = _iWidth/2;
        _oContainer.regY = _iHeight/2;

        _oParentContainer.addChild(_oContainer);
        
        
        var oData = {   
                        images: [oSprite], 
                        // width, height & registration point of each sprite
                        frames: {width: _iWidth, height: _iHeight}, 
                        animations: {on:[0],off:[1]}
                   };
                   
         var oSpriteSheet = new createjs.SpriteSheet(oData);
         
         if(_bActive){
            _oButton = createSprite(oSpriteSheet, "on",0,0,_iWidth, _iHeight);
         }else{
             _oButton = createSprite(oSpriteSheet, "off",0,0,_iWidth, _iHeight);
         }
        
        _oButton.stop();
        _oButton.cursor = "pointer";
        _oContainer.addChild(_oButton);
        
        _oText = new createjs.Text(szText,iFontSize+"px "+szFont, szColor);
        _oText.textAlign = "center";
        _oText.shadow = new createjs.Shadow("#000", 2, 2, 2);
        _oText.textBaseline = "middle";
        _oText.lineHeight = 24;
        _oText.x = _iWidth/2;
        _oText.y = _iHeight/2 ;
        
        _oContainer.addChild(_oText);
        
        this._initListener();
    };
    
	//REMOVE BUTTON FROM THE CANVAS
    this.unload = function(){
       _oContainer.off("mousedown", _oListenerDown);
       _oContainer.off("pressup" , _oListenerUp);      
	   
       _oParentContainer.removeChild(_oButton);
    };
    
    //EDIT STATE
    this.activate = function(bActive){
        _bActive = bActive;
        if(_bActive){
            _oButton.gotoAndStop("on");
        }else{
            _oButton.gotoAndStop("off");
        }
    };
    
    this.setPosition = function(iX,iY){
        _oContainer.x = iX;
        _oContainer.y = iY;
    };
    
    this.setScale = function(iScale){
        _oContainer.scaleX = _oContainer.scaleY = iScale;
    };
    
    //ADD ALL EVENT LISTENERS FOR THE BUTTON
    this._initListener = function(){
        _oListenerDown = _oContainer.on("mousedown", this.buttonDown);
       _oListenerUp = _oContainer.on("pressup" , this.buttonRelease);
       
    };
    
    //ADD LISTENER
    this.addEventListener = function( iEvent,cbCompleted, cbOwner ){
        _aCbCompleted[iEvent]=cbCompleted;
        _aCbOwner[iEvent] = cbOwner; 
    };
    
    //BUTTON CLICKED
    this.buttonRelease = function(){
        playSound("click",1,false);
        _bActive = !_bActive;
        
        if(_bActive){
            _oButton.gotoAndStop("on");
        }else{
            _oButton.gotoAndStop("off");
        }

        if(_aCbCompleted[ON_MOUSE_UP]){
            _aCbCompleted[ON_MOUSE_UP].call(_aCbOwner[ON_MOUSE_UP],{active:_bActive});
        }
    };
    
	//BUTTON PRESSED
    this.buttonDown = function(){

       if(_aCbCompleted[ON_MOUSE_DOWN]){
           _aCbCompleted[ON_MOUSE_DOWN].call(_aCbOwner[ON_MOUSE_DOWN]);
       }
    };
    
    this.getContainer = function(){
        return _oContainer;
    };
    
    _oParentContainer = oParentContainer;
    
    this._init(bActive,iXPos,iYPos,oSprite,iWidth,iHeight,szText,szFont,szColor,iFontSize);
}