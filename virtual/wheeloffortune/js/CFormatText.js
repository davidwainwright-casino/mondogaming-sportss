function CFormatText (iX, iY, szText, oParentContainer){
    
    var _iPrevLetterWidth;
    
    var _oTextOutline;
    var _oText;
    var _oTextContainer;
    
    this._init = function(iX, iY, szText, oParentContainer){    
        
        _iPrevLetterWidth = 0;
        
        _oTextContainer = new createjs.Container();
        _oTextContainer.x = iX;
        _oTextContainer.y = iY;
        oParentContainer.addChild(_oTextContainer);
        
        var iDim = 85;
        
        var iScale = 20;
        var iLetterOffset = iDim/iScale;
        
        var iReduceOffset = 9;
        
        for(var i=0; i<szText.length; i++){
            
            var szFontTag = iDim + "px";
            
            _oTextOutline = new createjs.Text();
            _oTextOutline.text = szText[i];
            _oTextOutline.font = szFontTag+" "+PRIMARY_FONT;
            _oTextOutline.color = "#000000";
            _oTextOutline.textAlign = "left";
            _oTextOutline.textBaseline = "middle";
            _oTextOutline.x = _iPrevLetterWidth + 2;
            _oTextOutline.y = 6;
            _oTextContainer.addChild(_oTextOutline);

            _oText = new createjs.Text();
            _oText.text = szText[i];
            _oText.font = szFontTag+" "+PRIMARY_FONT;
            _oText.color = "#ffffff";
            _oText.textAlign = "left";
            _oText.textBaseline = "middle";
            _oText.x = _iPrevLetterWidth;
            _oText.y = 4;
            _oTextContainer.addChild(_oText);
            
            _iPrevLetterWidth += _oText.getMeasuredWidth() + iLetterOffset;
            iDim -= iReduceOffset;
            
        }
		
	_oTextContainer.cache(0,-_oTextContainer.getBounds().height/2, _oTextContainer.getBounds().width, _oTextContainer.getBounds().height);
    };
 
    this.unload = function(){
        oParentContainer.removeChild(_oTextContainer);
    };

    this.rotateText = function(iRot){
        _oTextContainer.rotation = iRot;
    };
    
    this._init(iX, iY, szText, oParentContainer);
    
}