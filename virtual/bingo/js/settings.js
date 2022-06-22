var CANVAS_WIDTH = 1920;
var CANVAS_HEIGHT = 1080;

var EDGEBOARD_X = 250;
var EDGEBOARD_Y = 80;

var FPS_TIME      = 1000/24;
var DISABLE_SOUND_MOBILE = false;

var PRIMARY_FONT = "aachendeemedregular";

var STATE_LOADING = 0;
var STATE_MENU    = 1;
var STATE_HELP    = 1;
var STATE_GAME    = 3;

var ON_MOUSE_DOWN  = 0;
var ON_MOUSE_UP    = 1;
var ON_MOUSE_OVER  = 2;
var ON_MOUSE_OUT   = 3;
var ON_DRAG_START  = 4;
var ON_DRAG_END    = 5;

var NUM_DIFFERENT_BALLS = 5;
var ANIMATION_SPEED;

var WIN_OCCURRENCE = new Array();
var PAYOUTS = new Array();

var BANK;
var START_PLAYER_MONEY; 

var CARD_ROWS = 3;
var CARD_COLS = 9;

var LABEL_EMPTY = "empty";
var LABEL_FILL = "fill";

var COIN_BETS;
var MIN_CARDS = 1;
var MAX_CARDS = 6;
var NUM_EXTRACTIONS = [45,55,65];
var NUM_NUMBERS = 90;
var PAYTABLE_INFO;
var BUTTON_Y_OFFSET = 3;

var CARD_POSITION = new Array();
CARD_POSITION["num_1"] = [{x:430,y:440,scale:1}];
CARD_POSITION["num_2"] = [{x:300,y:480,scale:0.58},{x:1000,y:480,scale:0.58}];
CARD_POSITION["num_3"] = [{x:308,y:400,scale:0.58},{x:992,y:400,scale:0.58},{x:650,y:650,scale:0.58}];
CARD_POSITION["num_4"] = [{x:310,y:400,scale:0.58},{x:990,y:400,scale:0.58},{x:310,y:650,scale:0.58},{x:990,y:650,scale:0.58}];
CARD_POSITION["num_5"] = [{x:270,y:470,scale:0.41},{x:740,y:470,scale:0.41},{x:1210,y:470,scale:0.41},{x:515,y:650,scale:0.41},{x:985,y:650,scale:0.41}];
CARD_POSITION["num_6"] = [{x:270,y:460,scale:0.41},{x:740,y:460,scale:0.41},{x:1210,y:460,scale:0.41},{x:270,y:650,scale:0.41},{x:740,y:650,scale:0.41},{x:1210,y:650,scale:0.41}];

var BALL_COLORS =["#fdb400","#a3e21a","#1ab9e2","#be1ae0","#ff3a3a"];

var SOUNDTRACK_VOLUME_IN_GAME = 0.4;
var ENABLE_FULLSCREEN;
var ENABLE_CHECK_ORIENTATION;
var SHOW_CREDITS;