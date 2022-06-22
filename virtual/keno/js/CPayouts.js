function CPayouts(iX, iY){
    
    var _iCurAlpha = 0;
    var _iHighlightIndex;
    
    var _oPanel;
    var _oTextHits;
    var _oTextPays;
    var _oTextPayouts;

    var _oMoneyText;
    var _oParent;
    
    var _aPayoutsPosY;
    var _aHitsText;
    var _aPaysText;
    
  
    this._init = function(iX, iY){
        
        _oPanel = new createjs.Container();
        _oPanel.x = iX;
        _oPanel.y = iY;
        s_oStage.addChild(_oPanel);
        
        var oBg = createBitmap(s_oSpriteLibrary.getSprite('payouts'));
        _oPanel.addChild(oBg);
        
        var oWinBg = createBitmap(s_oSpriteLibrary.getSprite('win_panel'));
        oWinBg.x = -6;
        oWinBg.y = 577;
        _oPanel.addChild( oWinBg);
        
        var iHitsX = 80;
        var iPaysX = 210;
        
        _oTextPayouts = new CTLText(_oPanel, 
                    20, 20, 250, 40, 
                    50, "center", "#ffffff", PRIMARY_FONT, 1,
                    0, 0,
                    TEXT_PAYOUTS,
                    true, true, false,
                    false );

        
        _oTextHits = new CTLText(_oPanel, 
                    20, 110, 120, 30, 
                    30, "center", "#ffffff", PRIMARY_FONT, 1,
                    0, 0,
                    TEXT_HITS,
                    true, true, false,
                    false );

        
        _oTextPays = new CTLText(_oPanel, 
                    iPaysX-60, 110, 120, 30, 
                    30, "center", "#ffffff", PRIMARY_FONT, 1,
                    0, 0,
                    TEXT_PAYS,
                    true, true, false,
                    false );

        
        
        
        var iOffset = 50;
        _aPayoutsPosY = new Array();
        _aHitsText = new Array();
        _aPaysText = new Array();
        for(var i=0; i<6; i++){
            _aPayoutsPosY[i] = 190 + i* iOffset;
            
            _aHitsText[i] = new createjs.Text("-","36px "+PRIMARY_FONT, "#ffffff");
            _aHitsText[i].x = iHitsX;
            _aHitsText[i].y = _aPayoutsPosY[i];
            _aHitsText[i].textAlign = "center";
            _aHitsText[i].textBaseline = "middle";  
            _oPanel.addChild(_aHitsText[i]);
            
            _aPaysText[i] = new createjs.Text("-","36px "+PRIMARY_FONT, "#ffffff");
            _aPaysText[i].x = iPaysX;
            _aPaysText[i].y = _aPayoutsPosY[i];
            _aPaysText[i].textAlign = "center";
            _aPaysText[i].textBaseline = "middle";  
            _oPanel.addChild(_aPaysText[i]);
        }
        
        _oMoneyText = new CTLText(_oPanel, 
                    20, 620, 250, 60, 
                    40, "center", "#ffffff", PRIMARY_FONT, 1,
                    0, 0,
                    TEXT_CURRENCY+"0",
                    true, true, false,
                    false );

        
    };    
    
    this.unload = function(){
        s_oStage.removeChild(_oPanel);
    };
    
    this.updatePayouts = function(iVal){
        if(iVal < 0){
            for(var i=0; i<6; i++){
                _aHitsText[i].text = "-";
                _aPaysText[i].text = "-";
            }
            return;
        }

        for(var i=0; i<PAYOUTS[iVal].hits.length; i++){
            _aHitsText[i].text = PAYOUTS[iVal].hits[i];
            _aPaysText[i].text = PAYOUTS[iVal].pays[i];
        }
        
        for(var i=PAYOUTS[iVal].hits.length; i<6; i++){
            _aHitsText[i].text = "-";
            _aPaysText[i].text = "-";
        }        
    };
    
    this.showWin = function(szValue){
        _oMoneyText.refreshText(TEXT_CURRENCY + szValue);
    };
    
    this.highlightWin = function(iHits){
        for(var i=0; i<6; i++){
            if(_aHitsText[i].text === iHits){             
                _iHighlightIndex = i;
                this._flicker(i);
               
                break;
            }
        }
        
        
        
    };
    
    this._flicker = function(iIndex){
        if(_iCurAlpha === 1){
            _iCurAlpha = 0;

            createjs.Tween.get(_aHitsText[iIndex]).to({alpha:_iCurAlpha }, 250,createjs.Ease.cubicOut);
            createjs.Tween.get(_aPaysText[iIndex]).to({alpha:_iCurAlpha }, 250,createjs.Ease.cubicOut).call(function(){_oParent._flicker(iIndex);});
        }else{
            _iCurAlpha = 1;

            createjs.Tween.get(_aHitsText[iIndex]).to({alpha:_iCurAlpha }, 250,createjs.Ease.cubicOut);
            createjs.Tween.get(_aPaysText[iIndex]).to({alpha:_iCurAlpha }, 250,createjs.Ease.cubicOut).call(function(){_oParent._flicker(iIndex);});
        }
    };
    
    this.stopHighlight = function(){
        
        if(_aHitsText[_iHighlightIndex]){
            createjs.Tween.removeTweens(_aHitsText[_iHighlightIndex]);
            createjs.Tween.removeTweens(_aPaysText[_iHighlightIndex]);
            _aHitsText[_iHighlightIndex].alpha = 1;
            _aPaysText[_iHighlightIndex].alpha = 1;
        }
        
        
    };
    
    _oParent = this;
    this._init(iX, iY);
};
