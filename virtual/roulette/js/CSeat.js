function CSeat(){  
    var _iCurBet;
    var _iCredit;
    var _aNumberBetted;
    var _aNumbersSelected;
    var _aLastBetWinHistory;
    var _oFicheController;
    
    this._init = function(){
        this.reset();
    };
    
    this.reset = function(){
        _aNumberBetted=new Array();
        _aNumbersSelected = new Array();
        _aLastBetWinHistory = new Array();
        
        this.resetNumberWins();

        if(_oFicheController){
            _oFicheController.reset();
        }
        
        _iCurBet=0;
    };
    
    this.setInfo = function(iCredit,oContainerFiche){
        _iCredit=iCredit;
        _iCurBet=0;

        _oFicheController = new CFichesController(oContainerFiche);
    };
	
    this.resetNumberWins = function(){
	for(var i=0;i<NUMBERS_TO_BET;i++){
            _aNumberBetted[i] = {win:0,mc:null};
        }
        
        _aLastBetWinHistory = new Array();
    };
    
    this.setFicheBetted = function(iFicheValue,aNumbers,iWinForBet,aFichesMc,iNumFiches){

        var aTmpWin = new Array();
        var aTmpMc = new Array();
        for(var i=0;i<aNumbers.length;i++){
            var iWin = ( parseFloat(_aNumberBetted[aNumbers[i]].win)+(iWinForBet* (iFicheValue*iNumFiches) )).toFixed(1);
            _aNumberBetted[aNumbers[i]]={win:iWin,mc:aFichesMc};

            aTmpWin.push((iWinForBet* (iFicheValue*iNumFiches) ));
            aTmpMc.push(aFichesMc);
        }
        
        _aLastBetWinHistory.push({win:aTmpWin,mc:aFichesMc});
        
        _aNumbersSelected.push(aNumbers);
        
        var iAmount = (iFicheValue * iNumFiches);
        iAmount = parseFloat(iAmount.toFixed(2));
        _iCurBet+= iAmount;
        _iCurBet = parseFloat(_iCurBet.toFixed(2));
        _iCredit -= iAmount;
        _iCredit = roundDecimal(_iCredit, 1);
    };
	
    this.createPileForMultipleNumbers = function(iFicheValue,iIndexFicheSelected,aNumbers,iBetMult,iNumFiches){
        var aFichesMc=new Array();
        _oFicheController.createPileForMultipleNumbers(iIndexFicheSelected,aNumbers,aFichesMc);
        this.setFicheBetted(iFicheValue,aNumbers,iBetMult,aFichesMc,iNumFiches);
    };
		
    this.addFicheOnTable = function(iFicheValue,iIndexFicheSelected,aNumbers,iBetMult,szNameAttach){
        var aFichesMc=new Array();
        _oFicheController.setFicheOnTable(iIndexFicheSelected,szNameAttach,aFichesMc);
        this.setFicheBetted(iFicheValue,aNumbers,iBetMult,aFichesMc,1);
    };
    
    this.clearLastBet = function(){
        if(_aNumbersSelected.length === 0){
            return;
        }
        
        var iBet = _oFicheController.clearLastBet();
        _iCredit += iBet;
        _iCredit = roundDecimal(_iCredit, 1);
        _iCurBet -= iBet;

        var aLastNums = _aNumbersSelected.pop();
        var oLastWin = _aLastBetWinHistory.pop();
        
        var aLastWin = oLastWin.win;
        for(var i=0;i<aLastNums.length;i++){
            
            if(_aLastBetWinHistory.length > 0){
                var oTmp = _aLastBetWinHistory[_aLastBetWinHistory.length - 1];
                _aNumberBetted[aLastNums[i]] = {win:_aNumberBetted[aLastNums[i]].win - aLastWin[i],mc:oTmp.mc};
            }else{
                _aNumberBetted[aLastNums[i]] = {win:_aNumberBetted[aLastNums[i]].win - aLastWin[i],mc:null};
            }
            
        }
    };
    
    this.clearAllBets = function(){
        this.resetNumberWins();
        _oFicheController.clearAllBets();
        _iCredit += _iCurBet;
        _iCredit = roundDecimal(_iCredit, 1);
        _iCurBet=0;

    };
    
    this.showWin = function(iWin){
        _iCredit += iWin;
        _iCredit = roundDecimal(_iCredit, 1);

    };
    
    this.recharge = function(iMoney) {
        _iCredit = iMoney;
    };
    
    this.getCurBet = function(){
        return _iCurBet;
    };
    
    this.getCredit = function(){
        return _iCredit;
    };
    
    this.getNumbersBetted = function(){
        return _aNumberBetted;
    };
    
    this.getNumberSelected = function(){
        return _aNumbersSelected;
    };
    
    this._init();
}