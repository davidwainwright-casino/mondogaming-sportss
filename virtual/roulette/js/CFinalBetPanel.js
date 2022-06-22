function CFinalBetPanel(iX,iY){
    var _aNumberFinals;
    var _oContainer;
    
    this._init = function(iX,iY){
        _aNumberFinals=new Array();
        _aNumberFinals[0]=new Array(0,10,20,30);
        _aNumberFinals[1]=new Array(1,11,21,31);
        _aNumberFinals[2]=new Array(2,12,22,32);
        _aNumberFinals[3]=new Array(3,13,23,33);
        _aNumberFinals[4]=new Array(4,14,24,34);
        _aNumberFinals[5]=new Array(5,15,25,35);
        _aNumberFinals[6]=new Array(6,16,26,36);
        _aNumberFinals[7]=new Array(7,17,27);
        _aNumberFinals[8]=new Array(8,18,28);
        _aNumberFinals[9]=new Array(9,19,29);
                        
        _oContainer = new createjs.Container();
        _oContainer.x = iX;
        _oContainer.y = iY;
        s_oStage.addChild(_oContainer);
        
        var oSprite = s_oSpriteLibrary.getSprite('final_bet_bg');
        var iXOffset = oSprite.width/2;
        var iYOffset = oSprite.height/2;
        for(var i=0;i<10;i++){
            var oBut = new CTextButton(iXOffset,iYOffset,oSprite,""+i,FONT1,"#fff",14,false);
            oBut.addEventListenerWithParams(ON_MOUSE_UP, this._onFinalBetPressed, this,{index:i});
            _oContainer.addChild(oBut.getSprite());
            
            if(i===4){
                iXOffset = oSprite.width/2;
                iYOffset +=oSprite.height;
            }else{
                iXOffset += oSprite.width + 2;
            }
            
        }
        
        _oContainer.visible = false;
    };
	
    this.unload = function(){
        for(var i=0;i<_oContainer.getNumChildren();i++){
                if(oBut instanceof CTextButton){
                        var oBut = _oContainer.getChildAt(i);
                        oBut.unload();
                }
        }
    };
    
    this.show = function(){
        _oContainer.visible = true;
    };
    
    this.hide = function(){
        _oContainer.visible = false;
    };
    
    this._onFinalBetPressed = function(oParams){
        var iIndex=oParams.index;
        var iBetWin=_aNumberFinals[iIndex].length === 4?9:12;
        var iBetMultiplier=_aNumberFinals[iIndex].length === 4?4:3;

        s_oGame._onShowBetOnTable({button:"oBetFinalsBet",numbers:_aNumberFinals[iIndex],bet_mult:iBetMultiplier,
                                                                bet_win:iBetWin,num_fiches:_aNumberFinals[iIndex].length},false);

        this.hide();
    };
    
    this.isVisible = function(){
        return _oContainer.visible;
    };
    
    this._init(iX,iY);
}