function CLeds (iX, iY){
    var _szWinColor;
    var _szAnimColor;
    
    var _iLedState;
    var _iTimeElaps;
    var _iNumIdleAnim;
    var _iCurLed;
    var _iCurLed2;
    
    var _aLeds;
    var _aLedsPos;
    var _aColors;
    
    var _oLedsContainer;
    
    this._init = function(iX, iY){    

        _iNumIdleAnim = 3;
        _iLedState = Math.floor(Math.random()*_iNumIdleAnim);
        _iTimeElaps = 0;
        

        _aLeds = new Array();
        _aLedsPos = new Array();
        _aColors = new Array();
        _aColors = ["white", "green", "blue", "violet", "red", "yellow"];

        _oLedsContainer = new createjs.Container();
        _oLedsContainer.x = iX;
        _oLedsContainer.y = iY;
        s_oStage.addChild(_oLedsContainer);

        var oSprite = s_oSpriteLibrary.getSprite('leds');
        var iLedWidth = 90;
        var iLedHeight = 90;
        var oData = {   // image to use
                        images: [oSprite],
                        //framerate:15,
                        // width, height & registration point of each sprite
                        frames: {width: iLedWidth, height: iLedHeight, regX: iLedWidth/2, regY: iLedHeight/2}, 
                        animations: {  off: [0], white: [1], green: [2], blue: [3], violet: [4], red: [5], yellow: [6]}
                        
        };
        var oSpriteSheet = new createjs.SpriteSheet(oData);        
        
        var oStartLedPos = {x: -427, y:0};
        var vVect = new CVector2(oStartLedPos.x, oStartLedPos.y);
        var iRotation =  (Math.PI*(360/WHEEL_SETTINGS.length))/180;
        
        
        
        for(var i=0; i<WHEEL_SETTINGS.length; i++ ){
            _aLeds[i] = createSprite(oSpriteSheet,"off",0,0,iLedWidth,iLedHeight);            
            _aLeds[i].x = vVect.getX();
            _aLeds[i].y = vVect.getY();
            rotateVector2D(iRotation,vVect);
            _oLedsContainer.addChild(_aLeds[i]);
        }
        
        _aLeds[0].visible = false;
        

    };
 
    this.unload = function(){
        s_oStage.removeChild(_oLedsContainer);
    };
    this.setWinColor = function(szColor){
        _szWinColor = szColor;
    };
    
    this.getState = function(){
        return _iLedState;
    };
    
    this.getNumAnim = function(){
        return _iNumIdleAnim;
    };
    
    this.changeAnim = function(iState){
        _iTimeElaps = 0;
        _iLedState = iState;
        for(var i=0; i<_aLeds.length; i++){
            _aLeds[i].gotoAndStop("off");
        }      
    };
    
    this.animIdle0 = function(){
        _iTimeElaps += s_iTimeElaps;
        
        if(_iTimeElaps >= 0 && _iTimeElaps < ANIM_IDLE1_TIMESPEED/2){
            for(var i=0; i<_aLeds.length; i++){
                if(i%2 === 0){                    
                    _aLeds[i].gotoAndStop("white");
                } else {
                    _aLeds[i].gotoAndStop("off");
                }
                
            }            
        } else if (_iTimeElaps >= ANIM_IDLE1_TIMESPEED/2 && _iTimeElaps < ANIM_IDLE1_TIMESPEED){
            for(var i=0; i<_aLeds.length; i++){
                if(i%2 === 0){
                    _aLeds[i].gotoAndStop("off");
                } else {
                    _aLeds[i].gotoAndStop("white");
                }
            }            
        } else {
            _iTimeElaps = 0;
        }
    
    };
    
    
    this.animIdle1 = function(){
      
        if(_iTimeElaps === 0){
            _iCurLed = 0;
            _aLeds[_iCurLed].gotoAndStop("white");
            _aLeds[_aLeds.length/4].gotoAndStop("white");
            _aLeds[_aLeds.length/2].gotoAndStop("white");
            _aLeds[_aLeds.length*3/4].gotoAndStop("white");
            
        }

        _iTimeElaps += s_iTimeElaps;
        
        if(_iTimeElaps > ANIM_IDLE2_TIMESPEED){
            
            if(_iCurLed === _aLeds.length/4){         
                _iCurLed = 0;
                _iTimeElaps=1;
            }

            if(_iCurLed === 0){
                _aLeds[_aLeds.length-1].gotoAndStop("off");
                _aLeds[0].gotoAndStop("white");
                
                _aLeds[_aLeds.length/4-1].gotoAndStop("off");
                _aLeds[_aLeds.length/4].gotoAndStop("white");
                
                _aLeds[_aLeds.length/2-1].gotoAndStop("off");
                _aLeds[_aLeds.length/2].gotoAndStop("white");
                
                _aLeds[_aLeds.length*3/4-1].gotoAndStop("off");
                _aLeds[_aLeds.length*3/4].gotoAndStop("white");
                
            }  else {
                _aLeds[_iCurLed-1].gotoAndStop("off");
                _aLeds[_iCurLed].gotoAndStop("white");
                
                _aLeds[_aLeds.length/4 + _iCurLed-1].gotoAndStop("off");
                _aLeds[_aLeds.length/4 + _iCurLed].gotoAndStop("white");
                
                _aLeds[_aLeds.length/2 + _iCurLed-1].gotoAndStop("off");
                _aLeds[_aLeds.length/2 + _iCurLed].gotoAndStop("white");
                
                _aLeds[_aLeds.length*3/4 + _iCurLed-1].gotoAndStop("off");
                _aLeds[_aLeds.length*3/4 + _iCurLed].gotoAndStop("white");                
            }
            
            
            _iCurLed++;
            _iTimeElaps=1;
        }       
        
    };
    
    this.animIdle2 = function (){
        
        if(_iTimeElaps === 0){
            _iCurLed = 0;
            _iCurLed2 = _aLeds.length/2;
            _aLeds[_iCurLed].gotoAndStop("white");
            _aLeds[_iCurLed2].gotoAndStop("white");
        }
        
        _iTimeElaps += s_iTimeElaps;
        
        if(_iTimeElaps > ANIM_IDLE3_TIMESPEED){
            
            if(_iCurLed === _aLeds.length/2){         
                _iCurLed = 0;
                _iCurLed2 = _aLeds.length/2;
                _iTimeElaps=1;
            }
            if(_iCurLed === 0){
                _aLeds[_aLeds.length-1].gotoAndStop("off");
                _aLeds[1].gotoAndStop("off");
                _aLeds[0].gotoAndStop("white");
                
                _aLeds[_aLeds.length/2+1].gotoAndStop("off");
                _aLeds[_aLeds.length/2-1].gotoAndStop("off");
                _aLeds[_aLeds.length/2].gotoAndStop("white");
                
            }else {
                _aLeds[_iCurLed-1].gotoAndStop("off");
                _aLeds[_iCurLed].gotoAndStop("white");
                
                if(_iCurLed !== 1){
                        _aLeds[_aLeds.length - _iCurLed + 1].gotoAndStop("off");
                    }                    
                _aLeds[_aLeds.length - _iCurLed].gotoAndStop("white");              


                _aLeds[_iCurLed2 + 1].gotoAndStop("off");
                _aLeds[_iCurLed2].gotoAndStop("white");

                _aLeds[_aLeds.length - _iCurLed2 -1].gotoAndStop("off");
                if(_iCurLed2 !== 0){
                    _aLeds[_aLeds.length - _iCurLed2].gotoAndStop("white");
                }
                               
            }            
            _iCurLed++;
            _iCurLed2--;
            _iTimeElaps=1;           
        }
        
    };
 
    this.animSpin0 = function(){
      
        if(_iTimeElaps === 0){
            _iCurLed = Math.floor(Math.random()*_aLeds.length);
            _aLeds[_iCurLed].gotoAndStop("white");
        }

        _iTimeElaps += s_iTimeElaps;
        
        if(_iTimeElaps > ANIM_SPIN_TIMESPEED){
            
            if(_iCurLed < 0){         
                _iCurLed = _aLeds.length -1;
                _iTimeElaps=1;
            }
            
            if(_iCurLed === _aLeds.length -1){
                _aLeds[0].gotoAndStop("off");
                _aLeds[_aLeds.length - 1].gotoAndStop("white");
            }  else {
                _aLeds[_iCurLed + 1].gotoAndStop("off");
                _aLeds[_iCurLed].gotoAndStop("white");
            }
           
            _iCurLed--;
            _iTimeElaps=1;
        }       
        
    };

    this.animWin0 = function(){
        _iTimeElaps += s_iTimeElaps;
        
        if(_iTimeElaps >= 0 && _iTimeElaps < ANIM_WIN1_TIMESPEED/2){
            for(var i=0; i<_aLeds.length; i++){
                if(i%2 === 0){                    
                    _aLeds[i].gotoAndStop(_szWinColor);
                } else {
                    _aLeds[i].gotoAndStop("off");
                }
                
            }            
        } else if (_iTimeElaps >= ANIM_WIN1_TIMESPEED/2 && _iTimeElaps < ANIM_WIN1_TIMESPEED){
            for(var i=0; i<_aLeds.length; i++){
                if(i%2 === 0){
                    _aLeds[i].gotoAndStop("off");
                } else {
                    _aLeds[i].gotoAndStop(_szWinColor);
                }
            }            
        } else {
            _iTimeElaps = 0;
        }
    
    };
    
    this.animWin1 = function (){
        
        if(_iTimeElaps === 0){
            _iCurLed = 0;
            _iCurLed2 = _aLeds.length/2;
            _szAnimColor = _szWinColor;
            _aLeds[_iCurLed].gotoAndStop(_szAnimColor);
            _aLeds[_iCurLed2].gotoAndStop(_szAnimColor);
        }
        
        _iTimeElaps += s_iTimeElaps;
        
        if(_iTimeElaps > ANIM_WIN2_TIMESPEED){
            
            if(_iCurLed > _aLeds.length/4){         
                _iCurLed = 0;
                _iCurLed2 = _aLeds.length/2;
                _iTimeElaps=1;
                if(_szAnimColor === _szWinColor){
                    _szAnimColor = "off";
                } else {
                    _szAnimColor = _szWinColor;
                }
            }
            if(_iCurLed === 0){
                _aLeds[0].gotoAndStop(_szAnimColor);
                _aLeds[_aLeds.length/2].gotoAndStop(_szAnimColor);
                
            }else if(_iCurLed <= _aLeds.length/4) {
                _aLeds[_iCurLed].gotoAndStop(_szAnimColor);                   
                _aLeds[_aLeds.length - _iCurLed].gotoAndStop(_szAnimColor);              

                _aLeds[_iCurLed2].gotoAndStop(_szAnimColor);
                if(_iCurLed2 !== 0){
                    _aLeds[_aLeds.length - _iCurLed2].gotoAndStop(_szAnimColor);
                }
                               
            } 
            
            _iCurLed++;
            _iCurLed2--;
            _iTimeElaps=1;           
        }
        
    };
    
    this.animLose = function(){
        for(var i=0; i<_aLeds.length; i++){
            _aLeds[i].gotoAndStop(_szWinColor);
        }
        _iLedState = -1;
    };
    
    this.update = function(){
      
        switch(_iLedState) {
            case 0:{
                    this.animIdle0();
               break;
            } case 1: {
                    this.animIdle1();
               break;              
               
            } case 2: {
                    this.animIdle2();
               break;              
               
            } case 3: {
                    this.animSpin0();
               break;              
               
            } case 4: {
                    this.animWin0();
               break;              
               
            } case 5: {
                    this.animWin1();
               break;              
               
            } case 6: {
                    this.animLose();
               break;              
               
            }   

        } 
        
    };
    
    this._init(iX, iY);
    
}