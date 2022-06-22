function CPayTableSettings(){
    
    var _aWins;
    
    this._init = function(){
        
        _aWins = new Array();
        for(var i=0;i<NUM_BETS;i++){
            _aWins[i] = new Array();
            for(var j=0;j<WIN_COMBINATIONS;j++){
                _aWins[i][j] = COMBO_PRIZES[j] * (i+1);
            }
        }
    };
    
    this.getWin = function(iBet,iCombo){
        return _aWins[iBet][iCombo];
    };
    
    this._init();
}