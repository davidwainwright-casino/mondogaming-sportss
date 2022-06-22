function CFichesController(oContainer){
    
    var _aValueFichesInPos;
    var _aMcFichesInPos;
    var _aBetStackData;
    var _aBetStackTag;
    var _aFichesOnTable;
		
    var _oContainer;
    
    this._init = function(oContainer){
        _oContainer=oContainer;
        this.reset();
    };
    
    this.reset = function(){
        this._removeAllFichesOnTable();

        _aValueFichesInPos=new Array();
        _aMcFichesInPos=new Array();
        _aBetStackData=new Array();
        _aBetStackTag=new Array();
        _aFichesOnTable=new Array();
    };
    
    this.setFicheOnTable = function(iIndexFicheSelected,szNameAttach,aMcFiches){
        this.addFicheOnTable(iIndexFicheSelected,szNameAttach,aMcFiches);
        _aBetStackTag.push({tag:"oBetSingle",num:1});
    };
		
    this.addFicheOnTable = function(iIndexFicheSelected,szNameAttach,aMcFiches){
        if(_aValueFichesInPos[szNameAttach] === undefined){
            _aValueFichesInPos[szNameAttach] = 0;
        }

        var iFicheValue = s_oGameSettings.getFicheValues(iIndexFicheSelected);
        _aValueFichesInPos[szNameAttach] += iFicheValue;
        _aValueFichesInPos[szNameAttach] = roundDecimal(_aValueFichesInPos[szNameAttach],1);

        var aFiches = s_oGameSettings.generateFichesPileByIndex(_aValueFichesInPos[szNameAttach]);
        aFiches.sort(function(a, b){return a-b});

        this._removeFichesPile(_aMcFichesInPos[szNameAttach]);
        _aMcFichesInPos[szNameAttach] = new Array();

        var oPos = s_oGameSettings.getAttachOffset(szNameAttach);
        var iXPos =oPos.x;
        var iYPos =oPos.y;
        for(var k=0;k<aFiches.length;k++){
                aMcFiches.push(this._attachFichesPile(aFiches[k],szNameAttach,iXPos,iYPos));
                iYPos-=5;
        }

        _aBetStackData.push({index:szNameAttach,value:iIndexFicheSelected});
    };
		
    this._attachFichesPile = function(iIndexFicheSelected,szNameAttach,iXPos,iYPos){
        var oFicheMc = new CFiche(iXPos,iYPos,iIndexFicheSelected,_oContainer);

        _aMcFichesInPos[szNameAttach].push(oFicheMc);
        _aFichesOnTable.push(oFicheMc);

        return oFicheMc;
    };
		
    this.createPileForMultipleNumbers = function(iIndexFicheSelected,aNumbers,aFichesMc){
        for(var i=0;i<aNumbers.length;i++){
                this.addFicheOnTable(iIndexFicheSelected,"bet_"+aNumbers[i],aFichesMc);
        }

        _aBetStackTag.push({tag:"oBetMultiple",num:aNumbers.length});
    };
		
    this._removeAllFichesOnTable = function(){
        if(_aFichesOnTable){
            for(var i=0;i<_aFichesOnTable.length;i++){
                if(_oContainer.contains(_aFichesOnTable[i].getSprite())){
                    _oContainer.removeChild(_aFichesOnTable[i].getSprite());
                }
            }
        }
    };
		
    this._removeFichesPile = function(aFiches){
        for(var i in aFiches){
            _oContainer.removeChild(aFiches[i].getSprite());
        }
    };
		
    this.clearLastBet = function(){
        if(_aBetStackTag.length === 0){
                return 0;
        }

        var oBetTag =_aBetStackTag.pop();
        var iNumIteration=oBetTag.num;
        var iFicheValue;
        for(var i=0;i<iNumIteration;i++){
                var oBetInfo=_aBetStackData.pop();
                iFicheValue=s_oGameSettings.getFicheValues(oBetInfo.value);
                _aValueFichesInPos[oBetInfo.index] -= iFicheValue;
                _aValueFichesInPos[oBetInfo.index] = roundDecimal(_aValueFichesInPos[oBetInfo.index],1);

                var aFiches = s_oGameSettings.generateFichesPileByIndex(_aValueFichesInPos[oBetInfo.index]);
                aFiches.sort(function(a, b){return a-b});

                this._removeFichesPile(_aMcFichesInPos[oBetInfo.index]);
                _aMcFichesInPos[oBetInfo.index]=new Array();

                var oPos = s_oGameSettings.getAttachOffset(oBetInfo.index);
                var iXPos=oPos.x;
                var iYPos=oPos.y;
                for(var k=0;k<aFiches.length;k++){
                        this._attachFichesPile(aFiches[k],oBetInfo.index,iXPos,iYPos);
                        iYPos-=5;
                }
        }


        return iFicheValue*iNumIteration;
    };
		
    this.clearAllBets = function(){
        var iLen=_aBetStackData.length;
        for(var i=0;i<iLen;i++){
            this.clearLastBet();
        }
    };
    
    this._init(oContainer);
}