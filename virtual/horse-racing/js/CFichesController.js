function CFichesController(iScale,oParentContainer){
    var _bFichesUpdate;
    var _iTimeElaps;
    var _iValue;
    var _iCurScale;
    var _iPrevValue;
    var _pFicheStartPos;
    var _pStartingPoint;
    var _pEndingPoint;

    var _oFichesAttach;
    var _oTextAttach;
    var _oBetAmountText;
    var _oBetAmountBackText;
    
    var _aCbCompleted;
    var _aCbOwner;
    var _oParentContainer;
    
    this._init= function(iScale){
        _iCurScale = iScale;

        _oFichesAttach = new createjs.Container();
        _oParentContainer.addChild(_oFichesAttach);

	_pFicheStartPos=new CVector2();
        _pFicheStartPos.set(_oFichesAttach.x,_oFichesAttach.y);
        
        _oTextAttach  = new createjs.Container();
        _oParentContainer.addChild(_oTextAttach);
        
        var szFontSize = 18*iScale;
        _oBetAmountBackText =  new createjs.Text("",szFontSize+"px "+PRIMARY_FONT, "#000");
        _oBetAmountBackText.textAlign = "center";
        _oTextAttach.addChild(_oBetAmountBackText);
        
        _oBetAmountText =  new createjs.Text("",szFontSize+"px "+PRIMARY_FONT, "#fff");
        _oBetAmountText.textAlign = "center";
        _oTextAttach.addChild(_oBetAmountText);

        _iTimeElaps=0;
        _iValue = _iPrevValue = 0;
        _bFichesUpdate = false;

        _aCbCompleted=new Array();
        _aCbOwner =new Array();
    };
    
    this.addEventListener = function( iEvent,cbCompleted, cbOwner ){
        _aCbCompleted[iEvent]=cbCompleted;
        _aCbOwner[iEvent] = cbOwner; 
    };
    
    this.reset = function(){
        _bFichesUpdate=false;

        _iValue=0;

        _oFichesAttach.removeAllChildren();

        _oFichesAttach.x=_pFicheStartPos.getX();
        _oFichesAttach.y=_pFicheStartPos.getY();
        
        _oBetAmountBackText.text = "";
        _oBetAmountText.text = "";
    };
    
    this.setPrevValue = function(iValue){
        _iPrevValue = iValue;
    };
    
    this.refreshFiches = function(aFiches,iXPos,iYPos){
        aFiches = aFiches.sortOn('value','index');

        var iXOffset=iXPos;
        var iYOffset=iYPos;

        _iValue = 0;

        var iCont=0;

        for(var i=0;i<aFiches.length;i++){
                var oSpriteChip = s_oSpriteLibrary.getSprite("fiche_"+aFiches[i].index);
                var oNewFiche = createBitmap(oSpriteChip);
                oNewFiche.regX = oSpriteChip.width/2;
                oNewFiche.regY = oSpriteChip.height/2;
                oNewFiche.scaleX=iScale;
                oNewFiche.scaleY=iScale;

                _oFichesAttach.addChild(oNewFiche);

                oNewFiche.x = iXOffset;
                oNewFiche.y = iYOffset;
                
                iYOffset -= 5;
                iCont++;
                if(iCont>9 ){
                    iCont=0;
                    iXOffset += FICHE_WIDTH;
                    iYOffset=iYPos;	
                }

                _iValue+=aFiches[i].value;
        }
        
        playSound("chip", 1, 0);
        
        
        _oBetAmountText.x = iXPos;
        _oBetAmountText.y = iYPos + (25*_iCurScale);
        _oBetAmountText.text = _iValue.toFixed(2);
        _oBetAmountBackText.x = iXPos + 2;
        _oBetAmountBackText.y = iYPos + (27*_iCurScale);
        _oBetAmountBackText.text = _iValue.toFixed(2);
    };
		
    this.createFichesPile = function(iAmount,iX,iY){
        this.reset();
        var aFichesValue = CHIP_VALUES;
        var aFichesPile = new Array();

        do{
            var iMinValue=aFichesValue[aFichesValue.length-1];
            var iCont=aFichesValue.length-1;
            while(iMinValue>iAmount){
                iCont--;
                iMinValue=aFichesValue[iCont];
            }

            var iNumFiches=Math.floor(iAmount/iMinValue);

            for(var i=0;i<iNumFiches;i++){
                aFichesPile.push({value:iMinValue,index:s_oGameSettings.getIndexForFiches(iMinValue)});
            }
            
            if(Math.floor(iAmount/iMinValue) === (iAmount/iMinValue)){
                var iRestAmount = 0;
            }else{
                var iRestAmount=iAmount%iMinValue;
            }

            iAmount=iRestAmount.toFixed(2);
        }while(iRestAmount>0);			

        this.refreshFiches(aFichesPile,iX,iY*_iCurScale);
    };
	
    this.initMovement = function(iXEnd,iYEnd){
        _iPrevValue = _iValue;
        _pStartingPoint=new CVector2(_oFichesAttach.x,_oFichesAttach.y);
        _pEndingPoint=new CVector2(iXEnd,iYEnd);
        
        _oBetAmountText.text = "";
        _oBetAmountBackText.text = "";
        
        _bFichesUpdate = true;
    };
		
    this.getValue = function(){
        return _iValue;
    };
    
    this.getPrevBet = function(){
        return _iPrevValue;
    };
	
    this.update = function(iTime){
        if(!_bFichesUpdate){
                return;
        }

        _iTimeElaps+=iTime;
        if(_iTimeElaps>TIME_FICHES_MOV){
            _iTimeElaps=0;
            _bFichesUpdate=false;
            
        }else{

            var fLerp = easeInOutCubic( _iTimeElaps, 0, 1, TIME_FICHES_MOV);
            var oPoint = new CVector2();
            var oPoint = tweenVectors(_pStartingPoint, _pEndingPoint, fLerp,oPoint);

            _oFichesAttach.x=oPoint.getX();
            _oFichesAttach.y=oPoint.getY();
        }
    };
    
    _oParentContainer = oParentContainer;
    this._init(iScale);
    
}