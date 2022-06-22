function CBetTableButton(iXPos,iYPos,oSprite,szName,oContainer,bSelfEnlight){
    var _bSelfEnlight;
    var _aCbCompleted;
    var _aCbOwner;
    var _oButton;
    var _oParams;
    
    var _iBetMultiplier;
    var _iBetWin;
    var _szName;
    var _aNumbersToBet;
    var _oContainer;

    
    this._init =function(iXPos,iYPos,oSprite,szName,oContainer,bSelfEnlight){
        _bSelfEnlight = bSelfEnlight;
        _szName =szName;
        _aCbCompleted=new Array();
        _aCbOwner =new Array();
        _oContainer = oContainer;
        
        _oButton = createBitmap( oSprite);
        _oButton.x = iXPos;
        _oButton.y = iYPos; 
                                   
        _oButton.regX = oSprite.width/2;
        _oButton.regY = oSprite.height/2;
		if (!s_bMobile){
            _oButton.cursor = "pointer";
		}
        this._initListener();
        _oContainer.addChild(_oButton);
			
        _aNumbersToBet=new Array();
        _aNumbersToBet=_szName.split("_");
        if(_aNumbersToBet.length>1){
                _aNumbersToBet.splice(0,1);
        }else{
                this._assignNumber();
        }

        this._assignBetMultiplier();
    };
    
    this.unload = function(){
       _oButton.off("mousedown", this.buttonDown);
       _oButton.off("pressup" , this.buttonRelease); 
       _oButton.off("rollover",this.mouseOver);
       _oButton.off("rollout",this.mouseOut);
       
       _oContainer.removeChild(_oButton);
    };
    
    this.setVisible = function(bVisible){
        _oButton.visible = bVisible;
    };
    
    this._assignNumber = function(){
            _aNumbersToBet = s_oGameSettings.getNumbersForButton(_szName);
    };
		
    this._assignBetMultiplier = function(){
        switch(_aNumbersToBet.length){
            case 0:{
                _iBetMultiplier=s_oGameSettings.getBetMultiplierForButton(_szName);
                _iBetWin= s_oGameSettings.getBetWinForButton(_szName);
                break;
            }
            default:{
                _iBetMultiplier=_aNumbersToBet.length;
                _iBetWin=Math.floor(36/_aNumbersToBet.length);
            }
        }
    };
    
    this._initListener = function(){
       _oButton.on("mousedown", this.buttonDown);
       _oButton.on("pressup" , this.buttonRelease);  
       _oButton.on("rollover",this.mouseOver);
       _oButton.on("rollout",this.mouseOut);
    };
    
    this.addEventListener = function( iEvent,cbCompleted, cbOwner ){
        _aCbCompleted[iEvent]=cbCompleted;
        _aCbOwner[iEvent] = cbOwner; 
    };
    
    this.addEventListenerWithParams = function( iEvent,cbCompleted, cbOwner,oParams ){
        _aCbCompleted[iEvent]=cbCompleted;
        _aCbOwner[iEvent] = cbOwner; 
        
        _oParams =oParams;
    };
    
    this.buttonRelease = function(){
        playSound("click",1,false);
        

        if(_aCbCompleted[ON_MOUSE_UP]){
            _aCbCompleted[ON_MOUSE_UP].call(_aCbOwner[ON_MOUSE_UP],
                                    {numbers:_aNumbersToBet,bet_mult:_iBetMultiplier,bet_win:_iBetWin,name:_szName,num_fiches:1},false);
        }
    };
    
    this.buttonDown = function(){
       if(_aCbCompleted[ON_MOUSE_DOWN]){
           _aCbCompleted[ON_MOUSE_DOWN].call(_aCbOwner[ON_MOUSE_DOWN],
                                                {button:_szName,numbers:_aNumbersToBet,bet_mult:_iBetMultiplier,bet_win:_iBetWin,num_fiches:1},false);
       }
    };
    
    this.mouseOver = function(){
        if(_aCbCompleted[ON_MOUSE_OVER]){
            if(_bSelfEnlight){
                 _aCbCompleted[ON_MOUSE_OVER].call(_aCbOwner[ON_MOUSE_OVER],{numbers:_aNumbersToBet,enlight:_szName});
            }else{
                _aCbCompleted[ON_MOUSE_OVER].call(_aCbOwner[ON_MOUSE_OVER],{numbers:_aNumbersToBet});
            }
           
       }
    };
    
    this.mouseOut = function(){
        if(_aCbCompleted[ON_MOUSE_OUT]){
            if(_bSelfEnlight){
                _aCbCompleted[ON_MOUSE_OUT].call(_aCbOwner[ON_MOUSE_OUT],{numbers:_aNumbersToBet,enlight:_szName});
            }else{
                _aCbCompleted[ON_MOUSE_OUT].call(_aCbOwner[ON_MOUSE_OUT],{numbers:_aNumbersToBet});
            }
           
       }
    };
    
    this.setPosition = function(iXPos,iYPos){
         _oButton.x = iXPos;
         _oButton.y = iYPos;
    };
    
    this.setX = function(iXPos){
         _oButton.x = iXPos;
    };
    
    this.setY = function(iYPos){
         _oButton.y = iYPos;
    };
    
    this.rotate = function(iDegree){
        _oButton.rotation = iDegree;
    };
    
    this.getButtonImage = function(){
        return _oButton;
    };
    
    
    this.getX = function(){
        return _oButton.x;
    };
    
    this.getY = function(){
        return _oButton.y;
    };

    this._init(iXPos,iYPos,oSprite,szName,oContainer,bSelfEnlight);
}