function CCard(iX,iY,oContainer,szFotogram,iRank,iSuit){
    var _bHold;
    var _szFotogram;
    var _iRank;
    var _iSuit;
    
    var _aCbCompleted;
    var _aCbOwner;
    
    var _oCardSprite;
    var _oHoldSprite;
    var _oHitArea;
    var _oSelection;
    var _oContainer;
    var _oThisCard;
                
    this._init = function(iX,iY,oContainer,szFotogram,iRank,iSuit){
        _bHold = false;
        _oContainer = oContainer;
        _szFotogram = szFotogram;
        _iRank = iRank;
        _iSuit = iSuit;
        
        var oSprite = s_oSpriteLibrary.getSprite('card_spritesheet');
        var oData = {   // image to use
                        images: [oSprite], 
                        // width, height & registration point of each sprite
                        frames: {width: CARD_WIDTH, height: CARD_HEIGHT, regX: CARD_WIDTH/2, regY: CARD_HEIGHT/2}, 
                        animations: {  card_1_1: [0],card_1_2:[1],card_1_3:[2],card_1_4:[3],card_1_5:[4],card_1_6:[5],card_1_7:[6],card_1_8:[7],
                                       card_1_9:[8],card_1_10:[9],card_1_J:[10],card_1_Q:[11],card_1_K:[12],
                                       card_2_1: [13],card_2_2:[14],card_2_3:[15],card_2_4:[16],card_2_5:[17],card_2_6:[18],card_2_7:[19],
                                       card_2_8:[20], card_2_9:[21],card_2_10:[22],card_2_J:[23],card_2_Q:[24],card_2_K:[25],
                                       card_3_1: [26],card_3_2:[27],card_3_3:[28],card_3_4:[29],card_3_5:[30],card_3_6:[31],card_3_7:[32],
                                       card_3_8:[33], card_3_9:[34],card_3_10:[35],card_3_J:[36],card_3_Q:[37],card_3_K:[38],
                                       card_4_1: [39],card_4_2:[40],card_4_3:[41],card_4_4:[42],card_4_5:[43],card_4_6:[44],card_4_7:[45],
                                       card_4_8:[46], card_4_9:[47],card_4_10:[48],card_4_J:[49],card_4_Q:[50],card_4_K:[51],back:[52]}         
        };

        var oSpriteSheet = new createjs.SpriteSheet(oData);
        _oCardSprite = createSprite(oSpriteSheet,"back",CARD_WIDTH/2,CARD_HEIGHT/2,CARD_WIDTH,CARD_HEIGHT);
        _oCardSprite.x = iX;
        _oCardSprite.y = iY;
        _oCardSprite.stop();
        _oCardSprite.shadow = new createjs.Shadow("#000000", 5, 5, 5);
        _oContainer.addChild(_oCardSprite);
        
        oSprite = s_oSpriteLibrary.getSprite('hold');
        _oHoldSprite = createBitmap(oSprite);
        _oHoldSprite.regX = oSprite.width/2;
        _oHoldSprite.x = iX;
        _oHoldSprite.y = iY + 76;
        _oHoldSprite.visible = false;
        _oContainer.addChild(_oHoldSprite);
        
        oSprite = s_oSpriteLibrary.getSprite('card_selection');
        _oSelection = createBitmap(oSprite);
        _oSelection.x = iX;
        _oSelection.y = iY;
        _oSelection.regX = oSprite.width/2;
        _oSelection.regY = oSprite.height/2;
        _oSelection.visible = false;
        _oContainer.addChild(_oSelection);
        
        _oHitArea = new createjs.Shape();
        _oHitArea.graphics.beginFill("rgba(255,255,255,0.01)").drawRect(iX - (CARD_WIDTH/2), iY - (CARD_HEIGHT/2), CARD_WIDTH, CARD_HEIGHT);
        _oHitArea.on("click",this._onSelected);
        _oHitArea.cursor = "pointer";
        _oContainer.addChild(_oHitArea);
        
        _aCbCompleted=new Array();
        _aCbOwner =new Array();
    };
    
    this.unload = function(){
        _oHitArea.off("click",this._onSelected);
        _oContainer.removeChild(_oCardSprite);
    };
    
    this.reset = function(){
        _bHold = false;
        _oSelection.visible = false;
        _oCardSprite.shadow = new createjs.Shadow("#000000", 5, 5, 5);
    };
    
    this.addEventListener = function( iEvent,cbCompleted, cbOwner ){
        _aCbCompleted[iEvent]=cbCompleted;
        _aCbOwner[iEvent] = cbOwner; 
    };
    
    this.changeInfo = function(szFotogram,iRank,iSuit){
        _szFotogram = szFotogram;
        _iRank = iRank;
        _iSuit = iSuit;
    };
    
    this.setValue = function(){
        _oCardSprite.gotoAndStop(_szFotogram);
        
        var oParent = this;
        createjs.Tween.get(_oCardSprite).to({scaleX:1}, 200).call(function(){oParent.cardShown()});
    };
    
    this.setHold = function(bHold){
        _bHold = bHold;
        _oHoldSprite.visible = _bHold;
    };
    
    this.toggleHold = function(){
		if(createjs.Tween.hasActiveTweens(_oCardSprite)){
			return
		}
        _bHold = !_bHold;
        _oHoldSprite.visible = _bHold;
        
        playSound("press_hold",1,false);
    };
		
    this.showCard = function(){
        var oParent = this;
        createjs.Tween.get(_oCardSprite).to({scaleX:0.1}, 200).call(function(){oParent.setValue()});
    };
		
    this.hideCard = function(){
        var oParent = this;
        createjs.Tween.get(_oCardSprite).to({scaleX:0.1}, 200).call(function(){oParent.setBack()});
    };
    
    this.setBack = function(){
        _oCardSprite.gotoAndStop("back");
        var oParent = this;
        createjs.Tween.get(_oCardSprite).to({scaleX:1}, 200).call(function(){oParent.cardHidden()});
    };
		
    this.cardShown = function(){
        if(_aCbCompleted[ON_CARD_SHOWN]){
            _aCbCompleted[ON_CARD_SHOWN].call(_aCbOwner[ON_CARD_SHOWN]);
        }
    };
    
    this.cardHidden = function(){
        if(_aCbCompleted[ON_CARD_HIDE]){
            _aCbCompleted[ON_CARD_HIDE].call(_aCbOwner[ON_CARD_HIDE],this);
        }
    };
    
    this.highlight = function(){
        _oCardSprite.shadow = new createjs.Shadow("#fff000", 0, 0, 15);
        _oSelection.visible = true;
    };

    this._onSelected = function(){
        s_oGame.onCardSelected(_oThisCard);
    };
    
    this.getRank = function(){
        return _iRank;
    };
		
    this.getSuit = function(){
        return _iSuit;
    };

    this.getFotogram = function(){
        return _szFotogram;
    };
    
    this.isHold = function(){
        return _bHold;
    };
    
    _oThisCard = this;
    
    this._init(iX,iY,oContainer,szFotogram,iRank,iSuit);
                
}