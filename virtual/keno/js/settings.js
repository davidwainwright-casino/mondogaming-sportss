var CANVAS_WIDTH = 1920;
var CANVAS_HEIGHT = 1080;

var EDGEBOARD_X = 250;
var EDGEBOARD_Y = 80;

var FPS_TIME      = 1000/24;
var DISABLE_SOUND_MOBILE = false;

var PRIMARY_FONT = "Lora";
var SECONDARY_FONT = "Arial";
var PRIMARY_FONT_COLOUR = "#FFFFFF";

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

var BET = new Array();
BET = [0.10, 0.20, 0.30, 0.50, 1, 2, 3, 5];

var SOUNDTRACK_VOLUME_IN_GAME = 1;
var ENABLE_FULLSCREEN;
var ENABLE_CHECK_ORIENTATION;
var SHOW_CREDITS;