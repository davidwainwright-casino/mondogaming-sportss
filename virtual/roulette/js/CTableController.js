function CTableController(){
    var _aEnlights;
    var _oContainer;
    
    var _aCbCompleted;
    var _aCbOwner;
    
    this._init = function(){
        _oContainer = new createjs.Container();
        _oContainer.x = 184;
        _oContainer.y = 150;
        s_oStage.addChild(_oContainer);
        
        var oBg = createBitmap(s_oSpriteLibrary.getSprite('board_roulette'));
        _oContainer.addChild(oBg);
        
        this._initEnlights();
        
        //INIT ALL BUTTONS
        var oBut;
        /*******************TWELVE BET***************/
        oBut = new CBetTableButton(221,289,s_oSpriteLibrary.getSprite('hit_area_horizontal'),"first12",_oContainer,true);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
        
        oBut = new CBetTableButton(470,289,s_oSpriteLibrary.getSprite('hit_area_horizontal'),"second12",_oContainer,true);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
        
        oBut = new CBetTableButton(717,289,s_oSpriteLibrary.getSprite('hit_area_horizontal'),"third12",_oContainer,true);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
        
        /*************************SIMPLE BETS******************/
        
        oBut = new CBetTableButton(50,176,s_oSpriteLibrary.getSprite('hit_area_0'),"bet_0",_oContainer,false);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }

        oBut = new CBetTableButton(50,64,s_oSpriteLibrary.getSprite('hit_area_0'),"bet_37",_oContainer,false);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
        
        
        var oSprite = s_oSpriteLibrary.getSprite('hit_area_number');

        var iCurX = 128;
        var iCurY = 194;
        for(var i=1;i<NUMBERS_TO_BET;i++){
            oBut = new CBetTableButton(iCurX,iCurY,oSprite,"bet_"+i,_oContainer,false);
            oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
            if(s_bMobile === false){
                oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
                oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
            }
            
            if(i%3 === 0){
                iCurX += WIDTH_CELL_NUMBER + 3; 
                iCurY = 194;
            }else{
                iCurY -= HEIGHT_CELL_NUMBER + 3;
            }
        }

        /**********************COUPLE BET***********************/
        oBut = new CBetTableButton(97,195,s_oSpriteLibrary.getSprite('hit_area_couple_vertical'),"bet_0_1",_oContainer,false);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
        
        oBut = new CBetTableButton(97,120,s_oSpriteLibrary.getSprite('hit_area_couple_vertical'),"bet_0_2_37",_oContainer,false);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }

        oBut = new CBetTableButton(97,45,s_oSpriteLibrary.getSprite('hit_area_couple_vertical'),"bet_3_37",_oContainer,false);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
        
        var iCurX = 159;
        var iCurY = 194;
        for(var j=1;j<34;j++){
            oBut = new CBetTableButton(iCurX,iCurY,s_oSpriteLibrary.getSprite('hit_area_couple_vertical'),"bet_"+j+"_"+(j+3),_oContainer,false);
            oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
            if(s_bMobile === false){
                oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
                oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
            }
            
            if(j%3 === 0){
                iCurX += WIDTH_CELL_NUMBER + 3; 
                iCurY = 194;
            }else{
                iCurY -= HEIGHT_CELL_NUMBER + 3;
            }
        }
        
        
        
	/********************COUPLE BET HORIZONTAL***********************/
        var iCurX = 128;
        var iCurY = 157;
        var iCont = 1;
        for(var j=1;j<25;j++){
            oBut = new CBetTableButton(iCurX,iCurY,s_oSpriteLibrary.getSprite('hit_area_couple_horizontal'),"bet_"+iCont+"_"+(iCont+1),_oContainer,false);
            oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
            if(s_bMobile === false){
                oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
                oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
            }
            
            if(j%2 === 0){
                iCurX += WIDTH_CELL_NUMBER + 3; 
                iCurY = 157;
                iCont += 2;
            }else{
                iCurY -= HEIGHT_CELL_NUMBER + 3;
                iCont++;
            }
        }
	
        /*********************TRIPLE BET*******************/
        oBut = new CBetTableButton(96,158,s_oSpriteLibrary.getSprite('hit_area_small'),"bet_0_1_2",_oContainer,false);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
        
        oBut = new CBetTableButton(96,84,s_oSpriteLibrary.getSprite('hit_area_small'),"bet_2_3_37",_oContainer,false);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
        
        var iCurX = 128;
        var iCurY = 232;
        var iCont = 1;
        var oSprite = s_oSpriteLibrary.getSprite('hit_area_couple_horizontal');
        for(var j=1;j<13;j++){
            oBut = new CBetTableButton(iCurX,iCurY,oSprite,"bet_"+iCont+"_"+(iCont+1)+"_"+(iCont+2),_oContainer,false);
            oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
            if(s_bMobile === false){
                oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
                oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
            }
            
            iCurX += WIDTH_CELL_NUMBER + 3;
            iCont += 3;
        }

        /******************QUADRUPLE BET******************/
        
        
        oBut = new CBetTableButton(96,232,s_oSpriteLibrary.getSprite('hit_area_small'),"bet_0_1_2_3_37",_oContainer,false);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
        
        var iCurX = 158;
        var iCurY = 158;
        var iCont = 1;
        for(var k=1;k<23;k++){
            oBut = new CBetTableButton(iCurX,iCurY,s_oSpriteLibrary.getSprite('hit_area_small'),"bet_"+iCont+"_"+(iCont+1)+"_"+(iCont+3)+"_"+(iCont+4),_oContainer,false);
            oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
            if(s_bMobile === false){
                oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
                oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
            }
            
            if(k%2 === 0){
                iCurX += WIDTH_CELL_NUMBER + 3; 
                iCurY = 157;
                iCont += 2;
            }else{
                iCurY -= HEIGHT_CELL_NUMBER + 3;
                iCont++;
            }
        }
        
        /****************SESTUPLE BET**********************/
        var iCurX = 158;
        var iCurY = 232;
        var iCont = 1;
        var oSprite = s_oSpriteLibrary.getSprite('hit_area_small');
        for(var k=1;k<12;k++){
            oBut = new CBetTableButton(iCurX,iCurY,oSprite,"bet_"+iCont+"_"+(iCont+1)+"_"+(iCont+2)+"_"+(iCont+3)+"_"+(iCont+4)+"_"+(iCont+5),_oContainer,false);
            oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
            if(s_bMobile === false){
                oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
                oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
            }
            
            iCont += 3;
            iCurX += WIDTH_CELL_NUMBER + 3;
        }
        
       
        /****************COL BET*****************/
        
        oBut = new CBetTableButton(872,194,s_oSpriteLibrary.getSprite('hit_area_number'),"col1",_oContainer,true);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
        
        oBut = new CBetTableButton(872,120,s_oSpriteLibrary.getSprite('hit_area_number'),"col2",_oContainer,true);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
        
        oBut = new CBetTableButton(872,46,s_oSpriteLibrary.getSprite('hit_area_number'),"col3",_oContainer,true);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
        
        /****************OTHER BETS******************/
        
        oBut = new CBetTableButton(159,400,s_oSpriteLibrary.getSprite('hit_area_horizontal_half'),"first18",_oContainer,true);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
        
        oBut = new CBetTableButton(281,400,s_oSpriteLibrary.getSprite('hit_area_horizontal_half'),"even",_oContainer,true);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
        
        oBut = new CBetTableButton(408,400,s_oSpriteLibrary.getSprite('hit_area_color'),"black",_oContainer,true);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
        
        oBut = new CBetTableButton(533,400,s_oSpriteLibrary.getSprite('hit_area_color'),"red",_oContainer,true);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
        
        oBut = new CBetTableButton(656,400,s_oSpriteLibrary.getSprite('hit_area_horizontal_half'),"odd",_oContainer,true);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
        
        oBut = new CBetTableButton(778,400,s_oSpriteLibrary.getSprite('hit_area_horizontal_half'),"second18",_oContainer,true);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
        
        _aCbCompleted=new Array();
        _aCbOwner =new Array();
    };
    
    this._initEnlights = function(){
        var oBmp;
        _aEnlights = new Array();
        
        /*********************NUMBER ENLIGHT*****************/
        oBmp = new CEnlight(5,121,s_oSpriteLibrary.getSprite('enlight_0'),_oContainer);
        _aEnlights["oEnlight_0"] = oBmp;
        
        oBmp = new CEnlight(5,10,s_oSpriteLibrary.getSprite('enlight_0'),_oContainer);
        _aEnlights["oEnlight_37"] = oBmp;
            
        var iCurX = 98;
        var iCurY = 159;
        var oSprite = s_oSpriteLibrary.getSprite('enlight_number');
        for(var i=0;i<36;i++){
            oBmp = new CEnlight(iCurX,iCurY,oSprite,_oContainer);
            _aEnlights["oEnlight_"+(i+1)] = oBmp;
            
            if((i+1)%3 === 0){
                iCurX += oSprite.width + 3; 
                iCurY = 159;
            }else{
                iCurY -= oSprite.height + 3;
            }
            
            
        }

        /*********************OTHER ENLIGHTS*****************/
        
        oBmp = new CEnlight(842,159,s_oSpriteLibrary.getSprite('enlight_number'),_oContainer);
        _aEnlights["oEnlight_col1"] = oBmp;
        
        oBmp = new CEnlight(842,85,s_oSpriteLibrary.getSprite('enlight_number'),_oContainer);
        _aEnlights["oEnlight_col2"] = oBmp;
        
        oBmp = new CEnlight(842,11,s_oSpriteLibrary.getSprite('enlight_number'),_oContainer);
        _aEnlights["oEnlight_col3"] = oBmp;
        
        oBmp = new CEnlight(98,234,s_oSpriteLibrary.getSprite('enlight_horizontal'),_oContainer);
        _aEnlights["oEnlight_first12"] = oBmp;
        
        oBmp = new CEnlight(347,234,s_oSpriteLibrary.getSprite('enlight_horizontal'),_oContainer);
        _aEnlights["oEnlight_second12"] = oBmp;
        
        oBmp = new CEnlight(595,234,s_oSpriteLibrary.getSprite('enlight_horizontal'),_oContainer);
        _aEnlights["oEnlight_third12"] = oBmp;
        
        oBmp = new CEnlight(98,345,s_oSpriteLibrary.getSprite('enlight_horizontal_half'),_oContainer);
        _aEnlights["oEnlight_first18"] = oBmp;
        
        oBmp = new CEnlight(220,345,s_oSpriteLibrary.getSprite('enlight_horizontal_half'),_oContainer);
        _aEnlights["oEnlight_even"] = oBmp;
        
        oBmp = new CEnlight(347,348,s_oSpriteLibrary.getSprite('enlight_color'),_oContainer);
        _aEnlights["oEnlight_black"] = oBmp;
        
        oBmp = new CEnlight(470,348,s_oSpriteLibrary.getSprite('enlight_color'),_oContainer);
        _aEnlights["oEnlight_red"] = oBmp;
        
        oBmp = new CEnlight(595,345,s_oSpriteLibrary.getSprite('enlight_horizontal_half'),_oContainer);
        _aEnlights["oEnlight_odd"] = oBmp;
        
        oBmp = new CEnlight(717,345,s_oSpriteLibrary.getSprite('enlight_horizontal_half'),_oContainer);
        _aEnlights["oEnlight_second18"] = oBmp;
    };
	
    this.unload = function(){
            for(var i=0;i<_oContainer.getNumChildren();i++){
                    var oBut = _oContainer.getChildAt(i);
                    if(oBut instanceof CBetTableButton){
                            oBut.unload();
                    }
            }
    };
    
    this.addEventListener = function( iEvent,cbCompleted, cbOwner ){
        _aCbCompleted[iEvent]=cbCompleted;
        _aCbOwner[iEvent] = cbOwner; 
    };
    
    this._onBetPress = function(oParams){
        var aBets=oParams.numbers;

        if (aBets !== null) {
            if(_aCbCompleted[ON_SHOW_BET_ON_TABLE]){
                _aCbCompleted[ON_SHOW_BET_ON_TABLE].call(_aCbOwner[ON_SHOW_BET_ON_TABLE],oParams,false);
            }
        }
    };
    
    this._onBetNumberOver = function(oParams){
        
        var aBets=oParams.numbers;
        if(aBets !== null){
            if(_aCbCompleted[ON_SHOW_ENLIGHT]){
                _aCbCompleted[ON_SHOW_ENLIGHT].call(_aCbOwner[ON_SHOW_ENLIGHT],oParams);
            }
        }
    };
    
    this._onBetNumberOut = function(oParams){
        var aBets=oParams.numbers;
        if(aBets !== null){
            if(_aCbCompleted[ON_HIDE_ENLIGHT]){
                _aCbCompleted[ON_HIDE_ENLIGHT].call(_aCbOwner[ON_HIDE_ENLIGHT],oParams);
            }
        }
    };
    
    this.enlight = function(szEnlight){
        _aEnlights[szEnlight].show();
    };
    
    this.enlightOff = function(szEnlight){
        _aEnlights[szEnlight].hide();
    };
    
    this.getEnlightX = function(iNumberExtracted){
        return _aEnlights["oEnlight_"+iNumberExtracted].getX();
    };
    
    this.getEnlightY = function(iNumberExtracted){
        return _aEnlights["oEnlight_"+iNumberExtracted].getY();
    };
    
    this.getContainer = function(){
        return _oContainer;
    };
    
    this.getX = function(){
        return _oContainer.x;
    };
    
    this.getY = function(){
        return _oContainer.x;
    };
    
    this._init();
}