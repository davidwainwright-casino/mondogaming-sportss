function CWheel (iX, iY){
    
    var _aText;
    var _aColors;
    var _aPrize;
    
    var _oWheel;
    var _oWheelContainer;
    
    this._init = function(iX, iY){    
        
        _aText = new Array();
        _aColors = new Array();
        
        _aPrize = new Array();
        for(var i=0; i<WHEEL_SETTINGS.length; i++){
            _aPrize[i] = WHEEL_SETTINGS[i].prize;
        }
        
        
        this._initColors();
        
        var oSprite = s_oSpriteLibrary.getSprite('wheel');
        
        _oWheelContainer = new createjs.Container();
        _oWheelContainer.x = iX;
        _oWheelContainer.y = iY;
        s_oStage.addChild(_oWheelContainer);
		
     
        _oWheel = createBitmap(oSprite);
        _oWheel.regX = oSprite.width/2;
        _oWheel.regY = oSprite.height/2;
        _oWheelContainer.addChild(_oWheel);
	_oWheel.cache(0, 0, oSprite.width, oSprite.height);
        
        this.setText(1);

    };
 
    this.unload = function(){
        s_oStage.removeChild(_oWheelContainer);
        
    };
    
    this._initColors = function(){
        for(var i=0; i<9; i++){
            _aColors[0] = "violet";
        }    
        for(var i=351; i<=360; i++){
            _aColors[i] = "violet";
        }
        
        for (var j=0; j<4; j++){
            for(var i=9+j*SEGMENT_ROT*5; i<27+j*SEGMENT_ROT*5; i++){
                _aColors[i] = "blue";
            }
        }
        
        for (var j=0; j<4; j++){
            for(var i=27+j*SEGMENT_ROT*5; i<45+j*SEGMENT_ROT*5; i++){
                _aColors[i] = "green";
            }
        }
        
        for (var j=0; j<4; j++){
            for(var i=45+j*SEGMENT_ROT*5; i<63+j*SEGMENT_ROT*5; i++){
                _aColors[i] = "yellow";
            }
        }
        
        for (var j=0; j<4; j++){
            for(var i=63+j*SEGMENT_ROT*5; i<81+j*SEGMENT_ROT*5; i++){
                _aColors[i] = "red";
            }
        }
        
        for (var j=0; j<3; j++){
            for(var i=81+j*SEGMENT_ROT*5; i<=99+j*SEGMENT_ROT*5; i++){
                _aColors[i] = "violet";
            }
        }
        
        for(var i=315; i<=333; i++){
            _aColors[i] = "white";
        }
        
    };
    
    this.setText = function(iMultiply){
        var oStartTextPos = {x: -355, y: 3};
        var vVect = new CVector2(oStartTextPos.x, oStartTextPos.y);
        var iLocalRot = SEGMENT_ROT;
        var iRotation =  (Math.PI*SEGMENT_ROT)/180;        
        
        for(var i=0; i<_aPrize.length; i++ ){ 
            var szPrize = (_aPrize[i]*iMultiply).toFixed(2);
            _aText[i] = new CFormatText(vVect.getX(), vVect.getY(), TEXT_CURRENCY + szPrize, _oWheelContainer);
            _aText[i].rotateText(-iLocalRot*i);
            
            rotateVector2D(iRotation,vVect);           
        }
    };
    
    this.clearText = function(){
        for(var i=0; i<_aPrize.length; i++ ){ 
            _aText[i].unload();
        }
    };
    
    this.spin = function(iValue,iTimeMult){
        playSound("start_reel",1,false);
        playSound("reel",0.1,true);

        createjs.Tween.get(_oWheelContainer).to({rotation:_oWheelContainer.rotation + iValue}, WHEEL_SPIN_TIMESPEED*iTimeMult, createjs.Ease.quartOut)
                .call(function(){_oWheelContainer.rotation %= 360; s_oGame.releaseWheel(); if(DISABLE_SOUND_MOBILE === false || s_bMobile === false){stopSound("reel");}});
    };
    
    this.getDegree = function(){
        return _oWheelContainer.rotation;
    };
    
    this.getColor = function(){
        var iDeg = Math.round(_oWheelContainer.rotation);
        return _aColors[iDeg];
    };
	
    this._init(iX, iY);
    
}