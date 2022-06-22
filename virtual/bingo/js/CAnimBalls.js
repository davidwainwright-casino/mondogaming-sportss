function CAnimBalls(iX, iY,iNumBall,oParentContainer){
    var _iNumBalls;
    var _iTimeAnim;
    var _iOffset;
    var _iLastBall;
    var _iHeight;
    var _iWidthPreviewBall;
    var _iCounter;
    var _iCounterPreview;
    var _iYEndTube;
    var _iFirstBallOutOfTube;
    var _iTimeoutId;
    var _aBall;
    var _aBallPreview;
    
    var _oMask;
    var _oMaskPreview;
    var _oSpriteSheetBall;
    var _oSpriteSheetBallPreview;
    var _oRemainingBallsText;
    var _oParent;
    var _oContainerBallInTube;
    var _oContainerPreview;
    var _oContainer;
    var _oParentContainer;
    
    this._init = function(iX, iY,iNumBall){
        _iTimeAnim = ANIMATION_SPEED;
        _iLastBall = 7;
        _iNumBalls = iNumBall;
        _iFirstBallOutOfTube = 1;

        _oContainer = new createjs.Container();
        _oContainer.x = iX;
        _oContainer.y = iY;
        _oParentContainer.addChild(_oContainer);
        
        _oContainerBallInTube = new createjs.Container();
        _oContainer.addChild(_oContainerBallInTube);
        
        var oSprite = s_oSpriteLibrary.getSprite('ball');
        var oData = {   
                        images: [oSprite], 
                        // width, height & registration point of each sprite
                        frames: {width: oSprite.width/NUM_DIFFERENT_BALLS, height: oSprite.height, regX:(oSprite.width/NUM_DIFFERENT_BALLS)/2, regY:oSprite.height/2}, 
                        animations: {red:[0],green:[1],cyan:[0],violet:[1],blue:[1]}
                   };
                   
        _oSpriteSheetBall = new createjs.SpriteSheet(oData);         
        
        _iHeight = oSprite.height;
        
        var oSpriteTube = s_oSpriteLibrary.getSprite('tube');
        var oTube = createBitmap(oSpriteTube);
        oTube.x = -48;
        oTube.y = 24;
        
        var graphics = new createjs.Graphics().beginFill("rgba(255,0,0,0.01)").drawRoundRectComplex(oTube.x , oTube.y + 301, 296, 80,0,40,40,0);
        _oMask =  new createjs.Shape(graphics);
        
        _aBall = new Array();
        _iOffset = oSprite.height;
        
        _oContainerPreview = new createjs.Container();
        _oContainer.addChild(_oContainerPreview);
        
        var iBallY = oSpriteTube.height - oSprite.height - oSprite.height/2; 
        for(var i=0; i<7; i++){
            var iRandomColor = Math.floor(Math.random()*NUM_DIFFERENT_BALLS);
            _aBall[i] = new CBallExtracted(0,iBallY,oSprite.width/NUM_DIFFERENT_BALLS, oSprite.height,iRandomColor,32,3,_oSpriteSheetBall,_oContainerBallInTube);

            iBallY -= oSprite.height;
        }
        
        _oContainer.addChild(oTube);
        _oContainer.addChild(_oMask);
        
        _iYEndTube = _aBall[0].getY();
        
        //CREATE PREVIEW BALL
        var oSprite = s_oSpriteLibrary.getSprite('ball_preview');
        var oData = {   
                        images: [oSprite], 
                        // width, height & registration point of each sprite
                        frames: {width: oSprite.width/NUM_DIFFERENT_BALLS, height: oSprite.height, regX:(oSprite.width/NUM_DIFFERENT_BALLS)/2, regY:oSprite.height/2}, 
                        animations: {red:[0],green:[1],cyan:[0],violet:[1],blue:[1]}
                   };
                   
        _oSpriteSheetBallPreview = new createjs.SpriteSheet(oData);  
        
        _aBallPreview = new Array();

        _iWidthPreviewBall = (oSprite.width/NUM_DIFFERENT_BALLS)
        var oBallPreview = new CBallExtracted(50 - (_iWidthPreviewBall/2),122 + oSprite.height/2,_iWidthPreviewBall, oSprite.height,_aBall[0].getColor(),80,5,
                                                                                                                                _oSpriteSheetBallPreview,_oContainerPreview);
        _aBallPreview.push(oBallPreview);
        
        var oSprite = s_oSpriteLibrary.getSprite('number_extract_bg');
        var oBgNumber = createBitmap(oSprite);
        oBgNumber.x = 40;
        oBgNumber.y = 112;
        _oContainer.addChild(oBgNumber);
        
        var graphics = new createjs.Graphics().beginFill("rgba(255,255,255,0.01)").drawCircle(oBgNumber.x + oSprite.width/2,oBgNumber.y + oSprite.height/2,92);
        _oMaskPreview = new createjs.Shape(graphics);
        _oContainer.addChild(_oMaskPreview);
        
        _oRemainingBallsText = new createjs.Text("0/"+_iNumBalls," 24px " +PRIMARY_FONT, "#fff");
        _oRemainingBallsText.x = 150;
        _oRemainingBallsText.y = 304;
        _oRemainingBallsText.textAlign = "center";
        _oRemainingBallsText.textBaseline = "middle";
        _oContainer.addChild(_oRemainingBallsText);
        
        oBallPreview.setMask(_oMaskPreview);
    };
    
    this.unload = function(){
        clearTimeout(_iTimeoutId);
        _oParentContainer.removeChild(_oContainer);
    };
    
    this.reset = function(iNumBall){
        _iNumBalls = iNumBall;
        _oRemainingBallsText.text = "0/"+_iNumBalls;
    };
       
    this.extractNextBall = function(iNum,iCurBall){
        _iCounter = 0;
        
        playSound("launch_ball",1,false);
        //MOVE NUMBERED BALLS
        for(var i=0;i<_aBall.length;i++){
            if(_aBall[i].getY() > _iYEndTube){
                _aBall[i].setMask(_oMask);
                var iNewX = _aBall[i].getX() + _iHeight;
                _aBall[i].moveX(iNewX,this.placeNewBall,this);
                
            }else{
                var iNewY = _aBall[i].getY() + _iHeight;
                var szNum = "";
                if(iNewY > _iYEndTube){
                    szNum = iNum;
                }
                _aBall[i].moveY(iNewY,szNum,this.placeNewBall,this);
            }
        }
        
        _iCounterPreview = 0;
        //REFRESH PREVIEW BALL
        _aBallPreview[_aBallPreview.length-1].setText(iNum);
        for(var j=0;j<_aBallPreview.length;j++){
            var iNewX = _aBallPreview[j].getX() + _iWidthPreviewBall;
            _aBallPreview[j].moveX(iNewX,this.placeNewPreviewBall,this);
        }
        
        _oRemainingBallsText.text = iCurBall+"/"+_iNumBalls;
    };
    
    this.placeNewBall = function(){
        _iCounter++;
        if(_iCounter === _aBall.length){
            //ATTACH NEW BALL
            var oSprite = s_oSpriteLibrary.getSprite('ball');
            var iRandomColor = Math.floor(Math.random()*NUM_DIFFERENT_BALLS);
            var oBall = new CBallExtracted(0,_aBall[_aBall.length-1].getY() - oSprite.height,oSprite.width/NUM_DIFFERENT_BALLS, oSprite.height,iRandomColor,
                                                                                                                                    32,3,_oSpriteSheetBall,_oContainerBallInTube);
            
            _aBall.push(oBall);

            if(_aBall[0].getX() > (_iHeight*3)  ){
                _aBall[0].unload();
                _aBall.splice(0,1);
            }else{
                _iFirstBallOutOfTube++;
            }
            
            _iTimeoutId = setTimeout(function(){
                                    if(s_oGame !== null){
                                        s_oGame.extractNextNumber();
                                    }
                                },TIME_EXTRACTION);
        }
    };
    
    this.placeNewPreviewBall = function(){
        _iCounterPreview++;
        if(_iCounterPreview === _aBallPreview.length){
            //ATTACH NEW BALL
            var oSprite = s_oSpriteLibrary.getSprite('ball_preview');
            var oBall = new CBallExtracted(50 - (_iWidthPreviewBall/2),122 + oSprite.height/2,oSprite.width/NUM_DIFFERENT_BALLS, oSprite.height,
                                                                                        _aBall[_iFirstBallOutOfTube].getColor(),80,5,_oSpriteSheetBallPreview,_oContainerPreview);
            oBall.setMask(_oMaskPreview);
            
            _aBallPreview.push(oBall);
            
            if(_aBallPreview[0].getX() > (_iWidthPreviewBall)  ){
                _aBallPreview[0].unload();
                _aBallPreview.splice(0,1);
            }
        }
    };

    _oParent = this;
    _oParentContainer = oParentContainer;
    
    this._init(iX, iY,iNumBall);
} 