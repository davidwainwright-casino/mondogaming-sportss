function CHorse(pStartPos,iBibNumber,szName,iScale,aXList,oParentContainer){
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
    var _oContainer;
    var _oParentContainer;
    
    var _oThis;
    
    this._init = function(pStartPos,iBibNumber,szName,iScale,aXList){
        _bArrived = false;
        _iBibNumber = iBibNumber;
        _iCurXIndex = 0;
        _iArrivalX = ARRIVAL_X - ((HORSE_WIDTH/2 )*iScale)+70;
        _szName = szName;
        _aXList = aXList;
        
        _oContainer = new createjs.Container();
        _oContainer.x = pStartPos.x;
        _oContainer.y = pStartPos.y;
        _oContainer.scaleX = _oContainer.scaleY = iScale*1.25;
        _oContainer.regX = HORSE_WIDTH/2;
        _oContainer.regY = HORSE_HEIGHT;
        _oParentContainer.addChild(_oContainer);
        
        var oSpriteA = s_oSpriteLibrary.getSprite("horse_"+iBibNumber+"_a");
        var oSpriteB = s_oSpriteLibrary.getSprite("horse_"+iBibNumber+"_b");
        var oData = {
            framerate:30,
            images: [oSpriteA,oSpriteB],
            // width, height & registration point of each sprite
            "frames": [
                        [1, 1, 249, 191, 0, -56, -19],
                        [252, 1, 307, 193, 0, -8, -19],
                        [561, 1, 308, 196, 0, -4, -16],
                        [1, 199, 307, 198, 0, -2, -14],
                        [310, 199, 306, 201, 0, -1, -11],
                        [618, 199, 307, 205, 0, 0, -7],
                        [1, 406, 305, 209, 0, -1, -3],
                        [308, 406, 304, 211, 0, -3, -1],
                        [614, 406, 301, 209, 0, -8, 0],
                        [1, 619, 295, 207, 0, -17, -2],
                        [298, 619, 295, 204, 0, -19, -5],
                        [595, 619, 301, 203, 0, -21, -8],
                        [1, 1, 284, 200, 1, -35, -11],
                        [287, 1, 293, 198, 1, -31, -14],
                        [582, 1, 306, 196, 1, -20, -16]
                    ],
            animations: {idle: [0,0], anim: [1,14],start:[15],anim_1:[1,14,"anim"],anim_2:[6,14,"anim"],anim_3:[11,14,"anim"]}
        };

        var oSpriteSheet = new createjs.SpriteSheet(oData);
        _oSprite = createSprite(oSpriteSheet,"idle",HORSE_WIDTH/2,HORSE_HEIGHT,HORSE_WIDTH,HORSE_HEIGHT);
        _oContainer.addChild(_oSprite);
    };
    
    this.startRace = function(){
        var szAnim = "anim_" + (Math.floor(Math.random() * 3 ) + 1);
        _oSprite.gotoAndPlay(szAnim);

        _iCurStartX = _oContainer.x;
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
        return _oContainer.x;
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
                _iCurStartX = _oContainer.x;
            }else{
                _bUpdate = false;
            }
            
        }else{
            var fLerpX = s_oTweenController.easeLinear( _iCntFrames, 0 ,1, _iMaxFrames);
            var iValue = s_oTweenController.tweenValue( _iCurStartX, _aXList[_iCurXIndex].x, fLerpX);
            _oContainer.x = iValue;
        }

        if(bCheckArrival && !_bArrived && _oContainer.x >= _iArrivalX){
            _bArrived = true;
            s_oGame.horsePhotofinish(_iBibNumber-1,_szName);
        }
    };
    
    _oThis = this;
    _oParentContainer = oParentContainer;
    this._init(pStartPos,iBibNumber,szName,iScale,aXList);
}