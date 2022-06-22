function CTrackBg(oParentContainer){
    var _bUpdate;
    var _iCurBgFrame;
    var _iUpdateStep;
    var _iCurStep;
    var _aSpriteBg;
    
    var _oContainer;
    var _oParentContainer;
    
    this._init = function(){
        _oContainer = new createjs.Container();
        _oParentContainer.addChild(_oContainer);

        _iCurBgFrame = 0;
        _iUpdateStep = 2;
        _iCurStep = 0;
        
        _aSpriteBg = new Array();
        
        for(var i=0;i<NUM_TRACK_BG;i++){
            var oBg = createBitmap(s_oSpriteLibrary.getSprite("bg_track_"+i));
            if(i>0){
                oBg.visible = false;
            }
            _oContainer.addChild(oBg);
            
            _aSpriteBg[i] = oBg;
        }
        
        _bUpdate = true;
    };
    
    this.update = function(){
        if(!_bUpdate){
            return _iCurBgFrame;
        }

        _iCurStep++;
        if(_iCurStep === _iUpdateStep){
            _iCurStep = 0;

            _iCurBgFrame++;
            _aSpriteBg[_iCurBgFrame].visible = true;
            _aSpriteBg[_iCurBgFrame-1].visible = false;
            if(_iCurBgFrame === _aSpriteBg.length-1){
                _bUpdate = false;
                s_oGame.checkGreyhoundArrival();
            }else if(_iCurBgFrame === 17){
                s_oGame.startGreyhounds();
            }
        }
        
        return _iCurBgFrame;
    };
    
    _oParentContainer = oParentContainer;
    this._init();
}