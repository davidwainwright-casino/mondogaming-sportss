function CWheelAnim(iX,iY){
    var _bUpdate;
    var _bBallSpin;
    var _iWin;
    var _iCurBallSprite;
    var _iCurBallSpin;
    var _iCurWheelIndex;
    var _iFrameCont;
    var _iCurBallIndex;
    var _aWheelAnimSprites;
    var _aWheelMaskSprites;
    var _aBallPos;
    var _oBall;
    var _oCurWheelSprite;
    var _oCurWheelMaskSprite;
    var _oNumExtractedText;
    var _oResultText;
    var _oShowNumber;
    var _oNumberColorBg;
    var _oListenerClick;
    var _oContainer;
    
    this._init= function(iX,iY){
        _iCurWheelIndex= 0;
        _iFrameCont = 0;
        _bBallSpin = false;

        _oContainer = new createjs.Container();
        _oContainer.visible = false;
        _oContainer.x = iX;
        _oContainer.y = iY;
	_oListenerClick = _oContainer.on("click",function(){});
        s_oStage.addChild(_oContainer);
        
	var oFade = new createjs.Shape();
        oFade.graphics.beginFill("rgba(0,0,0,0.7)").drawRect(0,0,CANVAS_WIDTH,CANVAS_HEIGHT);
        _oContainer.addChild(oFade);
		
        var oBgWheel = createBitmap(s_oSpriteLibrary.getSprite("bg_wheel"));
        oBgWheel.x = 240;
        oBgWheel.y = 159;
        _oContainer.addChild(oBgWheel);
        
        _aWheelAnimSprites = new Array();
        for(var i=0;i<NUM_MASK_BALL_SPIN_FRAMES;i++){
            var oImage = createBitmap(s_oSpriteLibrary.getSprite('wheel_numbers_'+i)); 
            oImage.x = 418;
            oImage.y = 219;
            oImage.visible = false;
            _oContainer.addChild(oImage);
            _aWheelAnimSprites.push(oImage);
        }
        
        this._initBall();

        
        _aWheelMaskSprites = new Array();
        for(var j=0;j<NUM_MASK_BALL_SPIN_FRAMES;j++){
            var oImage = createBitmap(s_oSpriteLibrary.getSprite('wheel_handle_'+j));
            oImage.x = 519;
            oImage.y = 186;
            oImage.visible = false;
            _oContainer.addChild(oImage);
            _aWheelMaskSprites.push(oImage);
        }
        
        _oCurWheelSprite = _aWheelAnimSprites[0];
        _oCurWheelSprite.visible = true;
        
        _oCurWheelMaskSprite = _aWheelMaskSprites[0];
        _oCurWheelMaskSprite.visible = true;
        
        _oShowNumber = new createjs.Container();
        _oShowNumber.visible = false;
        _oShowNumber.x = CANVAS_WIDTH/2;
        _oShowNumber.y = CANVAS_HEIGHT/2;
        _oContainer.addChild(_oShowNumber);
        
        var oSprite = s_oSpriteLibrary.getSprite("show_number_panel");
        var oBgShowNumber = createBitmap(oSprite);
        _oShowNumber.addChild(oBgShowNumber);
        
        var oData = {   
                        images: [s_oSpriteLibrary.getSprite("show_number_bg")], 
                        // width, height & registration point of each sprite
                        frames: {width: 117, height: 117, regX: 58, regY: 58}, 
                        animations: {black:[0],red:[1],green:[2]}
                   };
                   
        var oSpriteSheet = new createjs.SpriteSheet(oData);
        _oNumberColorBg = createSprite(oSpriteSheet, "black",58,58,117,117);
        _oNumberColorBg.x = oSprite.width/2;
        _oNumberColorBg.y = oSprite.height/2;
        _oShowNumber.addChild(_oNumberColorBg);
        
        _oNumExtractedText = new createjs.Text("36","80px "+FONT2, "#fff");
        _oNumExtractedText.textAlign = "center";
        _oNumExtractedText.textBaseline = "middle";
        _oNumExtractedText.x = oSprite.width/2;
        _oNumExtractedText.y = oSprite.height/2 + 7;
        _oShowNumber.addChild(_oNumExtractedText);
        
        var oSpriteResultBg = s_oSpriteLibrary.getSprite("but_bg");
        var oBgResult = createBitmap(oSpriteResultBg);
        oBgResult.regX = oSpriteResultBg.width/2;
        oBgResult.x = oSprite.width/2;
        oBgResult.y = oSprite.height - 12;
        _oShowNumber.addChild(oBgResult);
        
        _oResultText = new createjs.Text("","22px "+FONT1, "#fff");
        _oResultText.textAlign = "center";
        _oResultText.textBaseline = "middle";
        _oResultText.x = oSprite.width/2;
        _oResultText.y = oSprite.height + 20;
        _oShowNumber.addChild(_oResultText);
        
        _oShowNumber.regX = oSprite.width/2;
        _oShowNumber.regY = oSprite.height/2;
    };
    
    this.unload = function(){
        _oContainer.off("click",_oListenerClick);
    };
    
    this._initBall = function(){
        _aBallPos = new Array();
        
        _aBallPos.push({x:892.9,y:358.95})
        _aBallPos.push({x:889.4,y:338.95})
        _aBallPos.push({x:880.9,y:320.45})
        _aBallPos.push({x:870.9,y:303.45})
        _aBallPos.push({x:857.65,y:287.2})
        _aBallPos.push({x:842.4,y:272.2})
        _aBallPos.push({x:825.9,y:257.45})
        _aBallPos.push({x:808.15,y:245.7})
        _aBallPos.push({x:788.15,y:234.45})
        _aBallPos.push({x:767.9,y:224.45})
        _aBallPos.push({x:746.9,y:217.2})
        _aBallPos.push({x:724.4,y:210.7})
        _aBallPos.push({x:702.15,y:205.2})
        _aBallPos.push({x:680.15,y:201.7})
        _aBallPos.push({x:657.15,y:199.45})
        _aBallPos.push({x:634.15,y:198.95})
        _aBallPos.push({x:609.15,y:199.95})
        _aBallPos.push({x:586.4,y:202.2})
        _aBallPos.push({x:564.15,y:206.2})
        _aBallPos.push({x:541.65,y:211.2})
        _aBallPos.push({x:519.15,y:218.2})
        _aBallPos.push({x:498.9,y:227.45})
        _aBallPos.push({x:478.9,y:236.7})
        _aBallPos.push({x:461.15,y:248.95})
        _aBallPos.push({x:444.15,y:261.45})
        _aBallPos.push({x:429.15,y:275.7})
        _aBallPos.push({x:416.65,y:291.45})
        _aBallPos.push({x:406.65,y:308.95})
        _aBallPos.push({x:399.15,y:326.7})
        _aBallPos.push({x:394.4,y:345.7})
        _aBallPos.push({x:394.4,y:365.7})
        _aBallPos.push({x:396.65,y:385.7})
        _aBallPos.push({x:402.4,y:405.2})
        _aBallPos.push({x:411.65,y:424.95})
        _aBallPos.push({x:425.9,y:444.2})
        _aBallPos.push({x:444.15,y:462.2})
        _aBallPos.push({x:465.9,y:477.95})
        _aBallPos.push({x:491.15,y:492.45})
        _aBallPos.push({x:519.15,y:504.7})
        _aBallPos.push({x:549.9,y:512.95})
        _aBallPos.push({x:582.4,y:518.7})
        _aBallPos.push({x:615.4,y:520.45})
        _aBallPos.push({x:648.4,y:518.45})
        _aBallPos.push({x:681.4,y:513.45})
        _aBallPos.push({x:711.9,y:505.2})
        _aBallPos.push({x:739.65,y:493.45})
        _aBallPos.push({x:764.65,y:478.7})
        _aBallPos.push({x:786.15,y:461.95})
        _aBallPos.push({x:802.9,y:444.45})
        _aBallPos.push({x:816.15,y:424.7})
        _aBallPos.push({x:825.15,y:404.7})
        _aBallPos.push({x:829.9,y:384.7})
        _aBallPos.push({x:829.9,y:364.7})
        _aBallPos.push({x:825.9,y:345.95})
        _aBallPos.push({x:818.9,y:327.2})
        _aBallPos.push({x:808.15,y:310.2})
        _aBallPos.push({x:795.15,y:293.95})
        _aBallPos.push({x:779.65,y:279.45})
        _aBallPos.push({x:761.65,y:267.2})
        _aBallPos.push({x:742.4,y:256.45})
        _aBallPos.push({x:721.15,y:247.95})
        _aBallPos.push({x:698.65,y:240.45})
        _aBallPos.push({x:673.65,y:236.95})
        _aBallPos.push({x:650.65,y:234.45})
        _aBallPos.push({x:625.65,y:233.95})
        _aBallPos.push({x:603.15,y:235.45})
        _aBallPos.push({x:579.9,y:238.7})
        _aBallPos.push({x:556.9,y:246.2})
        _aBallPos.push({x:534.4,y:254.2})
        _aBallPos.push({x:514.4,y:265.7})
        _aBallPos.push({x:497.65,y:278.2})
        _aBallPos.push({x:482.15,y:292.45})
        _aBallPos.push({x:468.9,y:307.7})
        _aBallPos.push({x:460.65,y:326.2})
        _aBallPos.push({x:455.65,y:344.7})
        _aBallPos.push({x:454.4,y:364.7})
        _aBallPos.push({x:458.15,y:384.7})
        _aBallPos.push({x:466.9,y:403.7})
        _aBallPos.push({x:480.15,y:421.95})
        _aBallPos.push({x:498.15,y:438.2})
        _aBallPos.push({x:520.65,y:453.2})
        _aBallPos.push({x:546.65,y:463.7})
        _aBallPos.push({x:575.4,y:471.45})
        _aBallPos.push({x:605.4,y:475.2})
        _aBallPos.push({x:635.4,y:474.95})
        _aBallPos.push({x:664.4,y:469.95})
        _aBallPos.push({x:690.9,y:460.7})
        _aBallPos.push({x:714.15,y:447.95})
        _aBallPos.push({x:732.65,y:431.2})
        _aBallPos.push({x:743.4,y:418.7})
        _aBallPos.push({x:749.4,y:411.2})
        _aBallPos.push({x:752.15,y:397.95})
        _aBallPos.push({x:757.65,y:379.45})
        _aBallPos.push({x:757.65,y:379.45})
        _aBallPos.push({x:755.65,y:375.7})
        _aBallPos.push({x:756.15,y:366.2})
        _aBallPos.push({x:756.15,y:356.2})
        _aBallPos.push({x:753.65,y:344.95})
        _aBallPos.push({x:751.4,y:346.45})
        _aBallPos.push({x:749.9,y:348.45})
        _aBallPos.push({x:752.65,y:354.45})
        _aBallPos.push({x:754.15,y:360.45})
        _aBallPos.push({x:754.9,y:366.2})
        _aBallPos.push({x:755.9,y:372.45})
        _aBallPos.push({x:755.9,y:378.2})
        _aBallPos.push({x:755.4,y:384.2})
        _aBallPos.push({x:754.15,y:390.45})
        _aBallPos.push({x:752.9,y:396.7})
        _aBallPos.push({x:750.4,y:403.2})
        _aBallPos.push({x:747.65,y:409.2})
        _aBallPos.push({x:744.65,y:414.7})
        _aBallPos.push({x:740.65,y:420.45})
        _aBallPos.push({x:736.15,y:425.7})
        _aBallPos.push({x:731.4,y:430.95})
        _aBallPos.push({x:725.65,y:435.45})
        _aBallPos.push({x:719.9,y:440.45})
        _aBallPos.push({x:713.15,y:445.2})
        _aBallPos.push({x:706.4,y:449.95})
        _aBallPos.push({x:698.65,y:453.95})
        _aBallPos.push({x:691.15,y:457.45})
        _aBallPos.push({x:681.9,y:460.95})
        _aBallPos.push({x:674.4,y:462.7})
        _aBallPos.push({x:665.65,y:465.45})
        _aBallPos.push({x:657.65,y:467.95})
        _aBallPos.push({x:648.9,y:469.45})
        _aBallPos.push({x:639.4,y:469.95})
        _aBallPos.push({x:630.15,y:470.7})
        _aBallPos.push({x:620.9,y:470.95})
        _aBallPos.push({x:611.4,y:469.95})
        _aBallPos.push({x:602.15,y:469.7})
        _aBallPos.push({x:593.65,y:467.95})
        _aBallPos.push({x:584.9,y:466.2})
        _aBallPos.push({x:575.9,y:463.7})
        _aBallPos.push({x:567.4,y:460.7})
        _aBallPos.push({x:559.4,y:457.95})
        _aBallPos.push({x:551.65,y:453.95})
        _aBallPos.push({x:543.9,y:449.95})
        _aBallPos.push({x:537.15,y:445.7})
        _aBallPos.push({x:531.4,y:441.45})
        _aBallPos.push({x:524.65,y:435.2})
        _aBallPos.push({x:518.4,y:431.2})
        _aBallPos.push({x:513.4,y:424.95})
        _aBallPos.push({x:509.15,y:420.2})
        _aBallPos.push({x:505.9,y:413.7})
        _aBallPos.push({x:502.4,y:408.2})
        _aBallPos.push({x:498.9,y:403.2})
        _aBallPos.push({x:497.9,y:396.7})
        _aBallPos.push({x:494.65,y:390.95})
        _aBallPos.push({x:494.15,y:384.2})
        _aBallPos.push({x:493.65,y:378.45})
        _aBallPos.push({x:493.4,y:372.2})
        _aBallPos.push({x:493.65,y:366.95})
        _aBallPos.push({x:493.9,y:360.7})
        _aBallPos.push({x:494.9,y:354.95})
        _aBallPos.push({x:496.9,y:348.7})
        _aBallPos.push({x:499.15,y:343.7})
        _aBallPos.push({x:502.15,y:338.45})
        _aBallPos.push({x:505.65,y:332.7})
        _aBallPos.push({x:508.9,y:328.2})
        _aBallPos.push({x:512.9,y:323.45})
        _aBallPos.push({x:516.9,y:318.7})
        _aBallPos.push({x:521.4,y:313.95})
        _aBallPos.push({x:526.4,y:309.2})
        _aBallPos.push({x:531.9,y:305.2})
        _aBallPos.push({x:537.65,y:301.7})
        _aBallPos.push({x:543.65,y:298.45})
        _aBallPos.push({x:550.15,y:294.7})
        _aBallPos.push({x:556.15,y:292.45})
        _aBallPos.push({x:562.4,y:289.95})
        _aBallPos.push({x:569.15,y:287.2})
        _aBallPos.push({x:576.15,y:285.2})
        _aBallPos.push({x:583.4,y:283.95})
        _aBallPos.push({x:590.65,y:282.45})
        _aBallPos.push({x:597.15,y:280.95})
        _aBallPos.push({x:605.4,y:280.2})
        _aBallPos.push({x:612.65,y:279.95})
        _aBallPos.push({x:619.9,y:279.7})
        _aBallPos.push({x:627.4,y:279.2})
        _aBallPos.push({x:634.4,y:280.2})
        _aBallPos.push({x:642.65,y:280.7})
        _aBallPos.push({x:649.9,y:281.95})
        _aBallPos.push({x:656.65,y:282.95})
        _aBallPos.push({x:663.9,y:284.2})
        _aBallPos.push({x:670.65,y:286.45})
        _aBallPos.push({x:677.65,y:288.7})
        _aBallPos.push({x:684.9,y:291.2})
        _aBallPos.push({x:691.65,y:293.7})
        _aBallPos.push({x:697.4,y:296.2})
        _aBallPos.push({x:703.9,y:299.7})
        _aBallPos.push({x:709.9,y:303.7})
        _aBallPos.push({x:715.15,y:306.95})
        _aBallPos.push({x:721.4,y:310.95})
        _aBallPos.push({x:726.4,y:314.7})
        _aBallPos.push({x:730.9,y:319.45})
        _aBallPos.push({x:734.4,y:323.7})
        _aBallPos.push({x:738.15,y:328.7})
        _aBallPos.push({x:741.4,y:333.95})
        _aBallPos.push({x:744.4,y:338.95})
        _aBallPos.push({x:747.65,y:344.2})
        _aBallPos.push({x:748.65,y:349.7})

        _oBall = createBitmap(s_oSpriteLibrary.getSprite("ball"));
        _oBall.x = _aBallPos[0].x;
        _oBall.y = _aBallPos[0].y;
        _oContainer.addChild(_oBall);
        
        _iCurBallIndex = 0;
    };
    
    this.hide = function(){
        _oShowNumber.visible = false;
        _oContainer.visible = false;
        _iCurBallIndex = 0;
    };
    
    this.startSpin = function(iRandSpin,iStartFrame,iNumExtracted,iWin){
        this.playToFrame(iStartFrame);
        
        _iWin = iWin;
        _iCurBallSpin = iRandSpin;
        _iCurBallSprite = 2;
        _bBallSpin = true;
        _oContainer.visible = true;
        
        this.setShowNumberInfo(iNumExtracted);
        _bUpdate = true;
    };
    
    this.setShowNumberInfo = function(iNumExtracted){
        if(iNumExtracted === DOUBLE_ZERO){
            iNumExtracted = "00";
        }
        _oNumExtractedText.text = iNumExtracted;
        if(_iWin > 0){
            _oResultText.font = "18px "+FONT1;
            _oResultText.text = TEXT_YOU_WIN + " "+_iWin+TEXT_CURRENCY;
        }else{
             _oResultText.font = "22px "+FONT1;
            _oResultText.text = TEXT_YOU_LOSE;
        }
        
        
        switch(s_oGameSettings.getColorNumber(iNumExtracted)){
                case COLOR_BLACK:{
                    _oNumberColorBg.gotoAndStop("black");
                    break;
                }
                case COLOR_RED:{
                    _oNumberColorBg.gotoAndStop("red");
                    break;
                }
                case COLOR_ZERO:{
                    _oNumberColorBg.gotoAndStop("green");
                    break;
                }
        }  
    };
    
    this._showNumberExtracted = function(){
        _oShowNumber.scaleX = _oShowNumber.scaleY = 0.1;
        _oShowNumber.visible = true;
        createjs.Tween.get(_oShowNumber).to({scaleX:1,scaleY:1}, 800,createjs.Ease.cubicOut);  
        
        if(DISABLE_SOUND_MOBILE === false || s_bMobile === false){
            if(_iWin>0){
                playSound("win",1,false);
            }else{
                playSound("lose",1,false);
            }
            
        }
    };
    
    this.playToFrame = function(iFrame){
        _oCurWheelSprite.visible = false;
        _iCurWheelIndex = iFrame;
        _aWheelAnimSprites[_iCurWheelIndex].visible= true;
        _oCurWheelSprite = _aWheelAnimSprites[_iCurWheelIndex];
        
        _oCurWheelMaskSprite.visible = false;
        _aWheelMaskSprites[_iCurWheelIndex].visible= true;
        _oCurWheelMaskSprite = _aWheelMaskSprites[_iCurWheelIndex];
    };
    
    this.nextFrame = function(){
        _oCurWheelSprite.visible = false;
        _iCurWheelIndex++;
        _aWheelAnimSprites[_iCurWheelIndex].visible= true;
        _oCurWheelSprite = _aWheelAnimSprites[_iCurWheelIndex];
        
        _oCurWheelMaskSprite.visible = false;
        _aWheelMaskSprites[_iCurWheelIndex].visible= true;
        _oCurWheelMaskSprite = _aWheelMaskSprites[_iCurWheelIndex];
    };
    
    this._ballSpin = function(){
        _oBall.x = _aBallPos[_iCurBallIndex].x;
        _oBall.y = _aBallPos[_iCurBallIndex].y;
        
        _iCurBallIndex++;
        if(_iCurBallIndex === (NUM_BALL_SPIN_FRAMES)){
            _bUpdate = false;
            _iCurBallIndex = NUM_BALL_SPIN_FRAMES-1;
            s_oGame._rouletteAnimEnded();
            this.hide();
        }else if(_iCurBallIndex === NUM_BALL_SPIN_FRAMES/2){
            this._showNumberExtracted();
        }
    };
    
    this.isVisible = function(){
        return _oContainer.visible;
    };
    
    this.update = function(){
        if(_bUpdate === false){
            return;
        }
        
        _iFrameCont++;
        
        if(_iFrameCont === 2){
            _iFrameCont = 0;
            if(_bBallSpin){
            
                this._ballSpin();
                
                if (  _iCurWheelIndex === (NUM_MASK_BALL_SPIN_FRAMES-1)) {
                    this.playToFrame(0);
                }else{
                    this.nextFrame();
                }
            }
        }
        
    };
    
    this._init(iX,iY);
}