function CDisplayPanel(iXPos,iYPos,oSprite,szText,szFont,szColor,iFontSize){
    
    var _bBlock;
    
    var _iScale;
    
    var _aCbCompleted;
    var _aCbOwner;
    var _oPanel;
    var _oText;
    
    this._init =function(iXPos,iYPos,oSprite,szText,szFont,szColor,iFontSize){
        
        _bBlock = false;
        
        _iScale = 1;
        
        _aCbCompleted=new Array();
        _aCbOwner =new Array();
        
        _oPanel = new createjs.Container();
        _oPanel.x = iXPos;
        _oPanel.y = iYPos;
        _oPanel.regX = oSprite.width/2;
        _oPanel.regY = oSprite.height/2;
        s_oStage.addChild(_oPanel);
        


        var oButtonBg = createBitmap( oSprite);
        _oPanel.addChild(oButtonBg);
 
        _oText = new CTLText(_oPanel, 
                    0, 0, oSprite.width, oSprite.height, 
                    iFontSize, "center", szColor, szFont, 1,
                    0, 0,
                    szText,
                    true, true, false,
                    false );
 
        
    };
    
    this.unload = function(){       
       s_oStage.removeChild(_oPanel);
    };
    
    this.setVisible = function(bVisible){
        _oPanel.visible = bVisible;
    };    

    
    this.setText = function(szText){
        _oText.refreshText(szText);
    };
    
    this.setPosition = function(iXPos,iYPos){
         _oPanel.x = iXPos;
         _oPanel.y = iYPos;
    };
    
    this.setX = function(iXPos){
         _oPanel.x = iXPos;
    };
    
    this.setY = function(iYPos){
         _oPanel.y = iYPos;
    };
    
    this.getButtonImage = function(){
        return _oPanel;
    };

    this.getX = function(){
        return _oPanel.x;
    };
    
    this.getY = function(){
        return _oPanel.y;
    };

    this.setScale = function(iVal){
        _iScale = iVal;
        _oPanel.scaleX = iVal;
        _oPanel.scaleY = iVal;
    };

    this._init(iXPos,iYPos,oSprite,szText,szFont,szColor,iFontSize);
    
    return this;
    
}
