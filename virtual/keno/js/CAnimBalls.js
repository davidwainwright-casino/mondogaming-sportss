function CAnimBalls(iX, iY){
    
    var _iTimeAnim;
    var _iOffset;
    var _iLastBall;
    
    var _aBall;
    var _aBallPos;
    var _aBallGlassPos;
    
    var _oParent;
    
    this._init = function(iX, iY){
        
        _iTimeAnim = ANIMATION_SPEED;
        _iLastBall = 7;
        
        
        
        var oSprite = s_oSpriteLibrary.getSprite('ball');
        var oData = {   
                        images: [oSprite], 
                        // width, height & registration point of each sprite
                        frames: {width: oSprite.width/NUM_DIFFERENT_BALLS, height: oSprite.height, regX:(oSprite.width/NUM_DIFFERENT_BALLS)/2, regY:oSprite.height/2}, 
                        animations: {red:[0],green:[1],cyan:[0],violet:[1],blue:[1]}
                   };
                   
        var oSpriteSheet = new createjs.SpriteSheet(oData);         
        
        
        _aBall = new Array();
        _aBallGlassPos = new Array();
        _iOffset = oSprite.height;
        
        for(var i=0; i<28; i++){
            var iRandomColor = Math.floor(Math.random()*NUM_DIFFERENT_BALLS);
            _aBall[i] = createSprite(oSpriteSheet, iRandomColor,(oSprite.width/NUM_DIFFERENT_BALLS)/2,oSprite.height/2,oSprite.width/NUM_DIFFERENT_BALLS,oSprite.height);//new createBitmap(oSprite);
            _aBall[i].gotoAndStop(iRandomColor);

            _aBall[i].x = iX;
            
            if(i>_iLastBall){
                _aBall[i].alpha = 0;
                _aBall[i].scaleX = _aBall[i].scaleY = 0;
                _aBall[i].y = iY + (_iLastBall)*_iOffset;
                
            } else {
                _aBall[i].y = iY + i*_iOffset;
                _aBallGlassPos[i] = iY + i*_iOffset;
            }

        }
        
        for(var i=0; i<28; i++){
            s_oStage.addChild(_aBall[28-i-1]);
        }
        
        
        //this.startAnim(0);
        //this.startAnimation();
    };
    
    this.unload = function(){
        for(var i=0; i<26; i++){
            s_oStage.removeChild(_aBall[i]);
        }
    };
    
    this.startAnimation = function(aBallPosition){
        _aBallPos = new Array();
        for(var i=0; i<20; i++){
            _aBallPos[i] = aBallPosition[i];
        }
        this._animBalls(0);
    };
    
    this._animBalls = function(iCurBall){
        playSound("launch_ball",1,false);
        
        
        createjs.Tween.get(_aBall[iCurBall]).to({y: -200}, _iTimeAnim*2,createjs.Ease.quartOut).to({y: _aBallPos[iCurBall].y}, _iTimeAnim*3,createjs.Ease.bounceOut).call(function(){
            s_oGame.showExtracted(iCurBall, _aBall[iCurBall].currentFrame);
            _aBall[iCurBall].visible = false;
            if(iCurBall < 19){                

            } else {
                s_oGame._checkContinueGame();
            }
        });       
        createjs.Tween.get(_aBall[iCurBall]).to({x: _aBallPos[iCurBall].x}, _iTimeAnim*5);
        
        var iCont = 0;
        for(var i=iCurBall+1; i<iCurBall+_iLastBall+1; i++){
            createjs.Tween.get(_aBall[i]).to({y: _aBallGlassPos[iCont]}, _iTimeAnim); 
            iCont++;
        };
        
        createjs.Tween.get(_aBall[iCurBall+_iLastBall+1]).to({scaleX: 1, scaleY :1, alpha:1}, _iTimeAnim, createjs.Ease.cubicIn).call(function(){
            if(iCurBall < 19){
                _oParent._animBalls(iCurBall+1);
            }   
        });  
    };
    
    this.resetBalls = function(){

        for(var i=0; i<28; i++){
            _aBall[i].visible = true;
            _aBall[i].x = iX;
            if(i<=_iLastBall){
                _aBall[i].y = iY + i*_iOffset;
                _aBall[i].gotoAndStop(_aBall[20+i].currentFrame);
                
            } else if(i>_iLastBall){
                var iRandomColor = Math.floor(Math.random()*NUM_DIFFERENT_BALLS);
                _aBall[i].gotoAndStop(iRandomColor);
                _aBall[i].alpha = 0;
                _aBall[i].scaleX = _aBall[i].scaleY = 0;
                _aBall[i].y = iY + (_iLastBall)*_iOffset;
            } else {
                var iRandomColor = Math.floor(Math.random()*NUM_DIFFERENT_BALLS);
                _aBall[i].gotoAndStop(iRandomColor);
                _aBall[i].y = iY + i*_iOffset;
            }
            
            createjs.Tween.removeTweens(_aBall[i]);
        }
        
        
    };
    
    _oParent = this;
    this._init(iX, iY);
} 