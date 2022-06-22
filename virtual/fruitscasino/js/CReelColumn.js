function CReelColumn(iIndex,iXPos,iYPos,iDelay){
    var _bUpdate;
    var _bReadyToStop;
    var _bContainsFinalSymbols;
    var _iIndex;
    var _iCol;
    var _iDelay;
    var _iContDelay;
    var _iCurState;
    var _iCntFrames;
    var _iMaxFrames;
    var _iStartY;
    var _iCurStartY;
    var _iFinalY;
    var _aSymbols;
    var _oContainer;
    
    this._init = function(iIndex,iXPos,iYPos,iDelay){
        _bUpdate = false;
        _bReadyToStop = false;
        _bContainsFinalSymbols = false;
        _iContDelay = 0;
        _iIndex = iIndex;
        _iDelay = iDelay;
        
        if(_iIndex < NUM_REELS){
            _iCol = _iIndex;
        }else{
            _iCol = _iIndex - NUM_REELS;
        }
        
        _iCntFrames = 0;
        _iMaxFrames = MAX_FRAMES_REEL_EASE;
        _iCurState = REEL_STATE_START;
        _iCurStartY = _iStartY = iYPos;
        _iFinalY = _iCurStartY + (SYMBOL_SIZE * NUM_ROWS);
        
        this.initContainer(iXPos,iYPos);
    };
    
    this.initContainer = function(iXPos,iYPos){
        _oContainer = new createjs.Container();
        _oContainer.x = iXPos;
        _oContainer.y = iYPos;
        
        var iX = 0;
        var iY = 0;
        _aSymbols = new Array();
        for(var i=0;i<NUM_ROWS;i++){
             var iRandIndex = Math.floor(Math.random()* s_aRandSymbols.length);
             var iRandSymbol = s_aRandSymbols[iRandIndex];
             var oSprite = createSprite(s_aSymbolData[iRandSymbol], "static",0,0,SYMBOL_SIZE,SYMBOL_SIZE);
             oSprite.stop();
             oSprite.x = iX;
             oSprite.y = iY;
             _oContainer.addChild(oSprite);
             
             _aSymbols[i] = oSprite;
             
             iY += SYMBOL_SIZE;
        }
       
        s_oStage.addChild(_oContainer);
    };
    
    this.unload = function(){
        s_oStage.removeChild(_oContainer);
    };
    
    this.activate = function(){
        _iCurStartY = _oContainer.y;
        _iFinalY = _iCurStartY + (SYMBOL_SIZE * NUM_ROWS);
        _bUpdate = true;
    };
    
    this._setSymbol = function(aSymbols){
        _oContainer.removeAllChildren();
        
        var iX = 0;
        var iY = 0;
        for(var i=0;i<aSymbols.length;i++){
            var oSprite = new createjs.Sprite(s_aSymbolData[aSymbols[i]], "static",0,0,SYMBOL_SIZE,SYMBOL_SIZE);
             oSprite.stop();
             oSprite.x = iX;
             oSprite.y = iY;
             _oContainer.addChild(oSprite);
             _aSymbols[i] = oSprite;
             
             iY += SYMBOL_SIZE;
        }
    };
    
    this.restart = function(aSymbols,bReadyToStop) {
        _oContainer.y = _iCurStartY = REEL_START_Y;
        _iFinalY = _iCurStartY + (SYMBOL_SIZE *NUM_ROWS);
        this._setSymbol(aSymbols);

        _bReadyToStop = bReadyToStop;
        if (_bReadyToStop) {
            _iCntFrames = 0;
            _iMaxFrames = MAX_FRAMES_REEL_EASE;
            _iCurState = REEL_STATE_STOP;
            for (var i = 0; i < NUM_ROWS; i++) {
                _aSymbols[i].gotoAndStop("static");
            }
            _bContainsFinalSymbols = true;
            
        }else{
            for (var i = 0; i < NUM_ROWS; i++) {
                _aSymbols[i].gotoAndStop("moving");
            }
        }
    };
    
    this.setReadyToStop = function() {
        _iCntFrames = 0;
        _iMaxFrames = MAX_FRAMES_REEL_EASE;
        _iCurState = REEL_STATE_STOP;
    };
    
    this.isReadyToStop = function(){
        return _bReadyToStop;
    };
    
    this._updateStart = function(){
        if(_iCntFrames === 0 && _iIndex < NUM_REELS){
            playSound("start_reel", 0.3,false);
        }
        
        _iCntFrames++;
        if ( _iCntFrames > _iMaxFrames ){
            _iCntFrames = 0;
            _iMaxFrames /= 2;
            _iCurState++;
            _iCurStartY = _oContainer.y;
            _iFinalY = _iCurStartY + (SYMBOL_SIZE * NUM_ROWS);
        }
        
        var fLerpY = s_oTweenController.easeInBack( _iCntFrames, 0 ,1, _iMaxFrames);
        
        var iValue = s_oTweenController.tweenValue( _iCurStartY, _iFinalY, fLerpY);
        _oContainer.y = iValue;
    };
    
    this._updateMoving = function(){
        _iCntFrames++;
        if ( _iCntFrames > _iMaxFrames ){
            _iCntFrames = 0;
            _iCurStartY = _oContainer.y;
            _iFinalY = _iCurStartY + (SYMBOL_SIZE * NUM_ROWS);
        }
        
        var fLerpY = s_oTweenController.easeLinear( _iCntFrames, 0 ,1, _iMaxFrames);
        var iValue = s_oTweenController.tweenValue( _iCurStartY, _iFinalY, fLerpY);
        _oContainer.y = iValue;	
    };
    
    this._updateStopping = function(){
        _iCntFrames++;
        
        if ( _iCntFrames >= _iMaxFrames ){
            _bUpdate = false;
            _iCntFrames = 0;
            _iMaxFrames = MAX_FRAMES_REEL_EASE;
            _iCurState = REEL_STATE_START;
            _iContDelay = 0;
            _bReadyToStop = false;

            if(_bContainsFinalSymbols){
                _bContainsFinalSymbols = false;
                _oContainer.y = REEL_OFFSET_Y;
                
            }
            s_oGame.stopNextReel();
        }else{
            var fLerpY = s_oTweenController.easeOutCubic( _iCntFrames, 0 ,1, _iMaxFrames);
            var iValue = s_oTweenController.tweenValue( _iCurStartY, _iFinalY, fLerpY);
            _oContainer.y = iValue;	
        }
        
        
    };

    this.update = function() {
        if (_bUpdate === false) {
            return;
        }
        
        _iContDelay++;

	if (_iContDelay > _iDelay) {
            if(_bReadyToStop === false && (_oContainer.y > REEL_ARRIVAL_Y) ){
                s_oGame.reelArrived(_iIndex, _iCol);
            }
            switch(_iCurState) {
                case REEL_STATE_START: {
                    this._updateStart();
                    break;
                }
                case REEL_STATE_MOVING: {
                    this._updateMoving();
                    break;
                }
                case REEL_STATE_STOP: {
                    this._updateStopping();
                    break;
                }
            }
        }
    };
    
    this._init(iIndex,iXPos,iYPos,iDelay);
    
}