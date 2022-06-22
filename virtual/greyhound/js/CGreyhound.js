function CGreyhound(pStartPos,iBibNumber,szName,iScale,aXList,oParentContainer){
    var _bUpdate;
    var _bArrived;
    var _iBibNumber;
    var _iCntFrames;
    var _iMaxFrames;
    var _iCurStartX;
    var _iCurXIndex;
    var _iArrivalX;
    var _szName;
    var _aXList;
    var _oSprite;
    var _oParentContainer;
    
    var _oThis;
    
    this._init = function(pStartPos,iBibNumber,szName,iScale,aXList){
        _bArrived = false;
        _iBibNumber = iBibNumber;
        _iCurXIndex = 0;
        _iArrivalX = ARRIVAL_X - (GREYHOUND_WIDTH/2)*iScale + 30;
        _szName = szName;
        _aXList = aXList;

        var oSprite = s_oSpriteLibrary.getSprite("greyhound_"+iBibNumber);
        var oData = {
            framerate:40,
            images: [oSprite],
            // width, height & registration point of each sprite
            frames: {width: GREYHOUND_WIDTH, height: GREYHOUND_HEIGHT,regX: GREYHOUND_WIDTH/2,regY:GREYHOUND_HEIGHT},
            animations: {idle: [0,0], anim: [0,21],start:[22],anim_1:[5,21,"anim"],anim_2:[10,21,"anim"],anim_3:[15,21,"anim"]}
        };

        var oSpriteSheet = new createjs.SpriteSheet(oData);
        
        
        _oSprite = createSprite(oSpriteSheet,"start",GREYHOUND_WIDTH/2,GREYHOUND_HEIGHT,GREYHOUND_WIDTH,GREYHOUND_HEIGHT);
        _oSprite.x = pStartPos.x;
        _oSprite.y = pStartPos.y;
        _oSprite.scaleX = _oSprite.scaleY = iScale;
        _oSprite.alpha = 0;
        _oParentContainer.addChild(_oSprite);
    };
    
    this.startRace = function(){
        var szAnim = "anim_" + (Math.floor(Math.random() * 3 ) + 1);
        createjs.Tween.get(_oSprite).to({alpha: 1}, 150).call(function(){_oSprite.gotoAndPlay(szAnim);});

        _iCurStartX = _oSprite.x;
        _iCntFrames = 0;
        _iMaxFrames = _aXList[_iCurXIndex].frame;
        _bUpdate = true;
    };
    
    this.pauseAnim = function(){
      _oSprite.paused = true;  
    };
    
    this.unpauseAnim = function(){
        _oSprite.paused = false; 
    };
    
    this.getX = function(){
        return _oSprite.x;
    };
    
    this.update = function(bCheckArrival){
        if(!_bUpdate){
            return;
        }
        
        _iCntFrames++;
        
        if ( _iCntFrames >= _iMaxFrames ){
            _iCurXIndex++;
            
            if(_iCurXIndex < _aXList.length){
                _iCntFrames = 0;
                _iMaxFrames = _aXList[_iCurXIndex].frame;
                _iCurStartX = _oSprite.x;
            }else{
                _bUpdate = false;
            }
            
        }else{
            var fLerpX = s_oTweenController.easeLinear( _iCntFrames, 0 ,1, _iMaxFrames);
            var iValue = s_oTweenController.tweenValue( _iCurStartX, _aXList[_iCurXIndex].x, fLerpX);
            _oSprite.x = iValue;
        }

        if(bCheckArrival && !_bArrived && _oSprite.x >= _iArrivalX){
            _bArrived = true;
            s_oGame.greyhoundPhotofinish(_iBibNumber-1,_szName);
        }
    };
    
    _oThis = this;
    _oParentContainer = oParentContainer;
    this._init(pStartPos,iBibNumber,szName,iScale,aXList);
}