<?php //error_reporting(0);
/**
 * Index
 *
 * @package Yoyo Framework
 * @author yoyostack.com
 * @copyright 2015
 */
define("_YOYO", true);

include 'init.php';
$router = new Router();
$tpl = App::View(BASEPATH . 'view/');

//admin routes
$router->mount('/admin', function() use ($router, $tpl) {
    //admin login
    $router->match('GET|POST', '/login', function () use ($tpl)
    {
        if (App::Auth()->is_Admin()) {
            Url::redirect(SITEURL . '/admin/');
            exit;
        }

        $tpl->template = 'admin/login.tpl.php';
        $tpl->title = Lang::$word->LOGIN;
    });

    //admin index
    $router->get('/', 'Admin@Index');


    //prematch events
    $router->match('GET|POST', '/prematch-events', function () use ($tpl, $core){
        $tpl = App::View(BASEPATH . 'view/');
        $tpl->dir = "admin/" . $core->theme . "/";
        $tpl->title = "Prematch Sports & Events";
        $tpl->menu = App::Content()->menuTree(true);
        $tpl->template = "admin/" . $core->theme . "/___prematch_events.tpl.php";
    });

    //exchange prematch events
    $router->match('GET|POST', '/exchange-prematch-events', function () use ($tpl, $core){
        $tpl = App::View(BASEPATH . 'view/');
        $tpl->dir = "admin/" . $core->theme . "/";
        $tpl->title = "Exchange Prematch";
        $tpl->menu = App::Content()->menuTree(true);
        $tpl->template = "admin/" . $core->theme . "/__exchange_pre_events.tpl.php";
    });
    //exchange inplay events
    $router->match('GET|POST', '/exchange-inplay-events', function () use ($tpl, $core){
        $tpl = App::View(BASEPATH . 'view/');
        $tpl->dir = "admin/" . $core->theme . "/";
        $tpl->title = "Exchange In-Play";
        $tpl->menu = App::Content()->menuTree(true);
        $tpl->template = "admin/" . $core->theme . "/__exchange_inp_events.tpl.php";
    });
    //prematch events
    $router->match('GET|POST', '/prematch-others', function () use ($tpl, $core){
        $tpl = App::View(BASEPATH . 'view/');
        $tpl->dir = "admin/" . $core->theme . "/";
        $tpl->title = "Prematch others";
        $tpl->menu = App::Content()->menuTree(true);
        $tpl->template = "admin/" . $core->theme . "/___prematch_others.tpl.php";
    });


    //inplay events
    $router->match('GET|POST', '/inplay-events', function () use ($tpl, $core){
        $tpl = App::View(BASEPATH . 'view/');
        $tpl->dir = "admin/" . $core->theme . "/";
        $tpl->title = "In-Play Sports & Events";
        $tpl->menu = App::Content()->menuTree(true);
        $tpl->template = "admin/" . $core->theme . "/___inplay_events.tpl.php";
    });
    //inplay events
    $router->match('GET|POST', '/inplay-active-slips', function () use ($tpl, $core){
        $tpl = App::View(BASEPATH . 'view/');
        $tpl->dir = "admin/" . $core->theme . "/";
        $tpl->title = "In-Play Active slips";
        $tpl->menu = App::Content()->menuTree(true);
        $tpl->template = "admin/" . $core->theme . "/___inplay_active_slips.tpl.php";
    });
    //inplay events
    $router->match('GET|POST', '/inplay-settled-slips', function () use ($tpl, $core){
        $tpl = App::View(BASEPATH . 'view/');
        $tpl->dir = "admin/" . $core->theme . "/";
        $tpl->title = "In-Play Settled Slips";
        $tpl->menu = App::Content()->menuTree(true);
        $tpl->template = "admin/" . $core->theme . "/___inplay_settled_slips.tpl.php";
    });
    //inplay events
    $router->match('GET|POST', '/inplay-others', function () use ($tpl, $core){
        $tpl = App::View(BASEPATH . 'view/');
        $tpl->dir = "admin/" . $core->theme . "/";
        $tpl->title = "In-Playing others";
        $tpl->menu = App::Content()->menuTree(true);
        $tpl->template = "admin/" . $core->theme . "/___inplay_others.tpl.php";
    });


    //casino_slot
    $router->match('GET|POST', '/casino-slot', function () use ($tpl, $core){
        $tpl = App::View(BASEPATH . 'view/');
        $tpl->dir = "admin/" . $core->theme . "/";
        $tpl->title = "Casino, slot & games";
        $tpl->menu = App::Content()->menuTree(true);
        $tpl->template = "admin/" . $core->theme . "/___casino_slot.tpl.php";
    });



    //tickets stats
    $router->match('GET|POST', '/tickets-stats', function () use ($tpl, $core){
        $tpl = App::View(BASEPATH . 'view/');
        $tpl->dir = "admin/" . $core->theme . "/";
        $tpl->title = "Tickets Stats";
        $tpl->menu = App::Content()->menuTree(true);
        $tpl->template = "admin/" . $core->theme . "/___tickets_stats.tpl.php";
    });

    //visitors stats
    $router->match('GET|POST', '/visitors-stats', function () use ($tpl, $core){
        $tpl = App::View(BASEPATH . 'view/');
        $tpl->dir = "admin/" . $core->theme . "/";
        $tpl->title = "Visitors Stats";
        $tpl->menu = App::Content()->menuTree(true);
        $tpl->template = "admin/" . $core->theme . "/___visitors_stats.tpl.php";
    });

    //players mgt
    $router->match('GET|POST', '/players-mgt', function () use ($tpl, $core){
        $tpl = App::View(BASEPATH . 'view/');
        $tpl->dir = "admin/" . $core->theme . "/";
        $tpl->title = "Players Management";
        $tpl->menu = App::Content()->menuTree(true);
        $tpl->template = "admin/" . $core->theme . "/___mgt_players.tpl.php";
    });

    //Agents mgt
    $router->match('GET|POST', '/agents-mgt', function () use ($tpl, $core){
        $tpl = App::View(BASEPATH . 'view/');
        $tpl->dir = "admin/" . $core->theme . "/";
        $tpl->title = "Agents Management";
        $tpl->menu = App::Content()->menuTree(true);
        $tpl->template = "admin/" . $core->theme . "/___mgt_agents.tpl.php";
    });

    //super Agents mgt
    $router->match('GET|POST', '/sagents-mgt', function () use ($tpl, $core){
        $tpl = App::View(BASEPATH . 'view/');
        $tpl->dir = "admin/" . $core->theme . "/";
        $tpl->title = "Agents Management";
        $tpl->menu = App::Content()->menuTree(true);
        $tpl->template = "admin/" . $core->theme . "/___mgt_sagents.tpl.php";
    });

    //User history management
    $router->match('GET|POST', '/users-credit-history', function () use ($tpl, $core){
        $tpl = App::View(BASEPATH . 'view/');
        $tpl->dir = "admin/" . $core->theme . "/";
        $tpl->title = "Users Credit History";
        $tpl->menu = App::Content()->menuTree(true);
        $tpl->template = "admin/" . $core->theme . "/___users_credit_history.tpl.php";
    });

    //Agent History management
    $router->match('GET|POST', '/agents-credit-history', function () use ($tpl, $core){
        $tpl = App::View(BASEPATH . 'view/');
        $tpl->dir = "admin/" . $core->theme . "/";
        $tpl->title = "Agents Credit History";
        $tpl->menu = App::Content()->menuTree(true);
        $tpl->template = "admin/" . $core->theme . "/___agents_credit_history.tpl.php";
    });

    //prematch History management
    $router->match('GET|POST', '/prematch-history', function () use ($tpl, $core){
        $tpl = App::View(BASEPATH . 'view/');
        $tpl->dir = "admin/" . $core->theme . "/";
        $tpl->title = "Prematch History";
        $tpl->menu = App::Content()->menuTree(true);
        $tpl->template = "admin/" . $core->theme . "/___prematch_history.tpl.php";
    });

    //In-Play History management
    $router->match('GET|POST', '/bet-history', function () use ($tpl, $core){
        $tpl = App::View(BASEPATH . 'view/');
        $tpl->dir = "admin/" . $core->theme . "/";
        $tpl->title = "Bet History";
        $tpl->menu = App::Content()->menuTree(true);
        $tpl->template = "admin/" . $core->theme . "/___inplay_history.tpl.php";
    });


    //Deposits management
    $router->match('GET|POST', '/deposits', function () use ($tpl, $core){
        $tpl = App::View(BASEPATH . 'view/');
        $tpl->dir = "admin/" . $core->theme . "/";
        $tpl->title = "Deposits";
        $tpl->menu = App::Content()->menuTree(true);
        $tpl->template = "admin/" . $core->theme . "/___deposits.tpl.php";
    });

    //withdrawals management
    $router->match('GET|POST', '/withdrawals', function () use ($tpl, $core){
        $tpl = App::View(BASEPATH . 'view/');
        $tpl->dir = "admin/" . $core->theme . "/";
        $tpl->title = "Withdrawals";
        $tpl->menu = App::Content()->menuTree(true);
        $tpl->template = "admin/" . $core->theme . "/___withdrawals.tpl.php";
    });

    //Deposits management
    $router->match('GET|POST', '/sagents-transfers', function () use ($tpl, $core){
        $tpl = App::View(BASEPATH . 'view/');
        $tpl->dir = "admin/" . $core->theme . "/";
        $tpl->title = "Super Agents Tranfers";
        $tpl->menu = App::Content()->menuTree(true);
        $tpl->template = "admin/" . $core->theme . "/___sagents-transfers.tpl.php";
    });

    //Deposits management
    $router->match('GET|POST', '/trans-log', function () use ($tpl, $core){
        $tpl = App::View(BASEPATH . 'view/');
        $tpl->dir = "admin/" . $core->theme . "/";
        $tpl->title = "Transactions Logs";
        $tpl->menu = App::Content()->menuTree(true);
        $tpl->template = "admin/" . $core->theme . "/___transactions_logs.tpl.php";
    });

    //risk management
    $router->match('GET|POST', '/risk-management', function () use ($tpl, $core){
        $tpl = App::View(BASEPATH . 'view/');
        $tpl->dir = "admin/" . $core->theme . "/";
        $tpl->title = "Risk Management";
        $tpl->menu = App::Content()->menuTree(true);
        $tpl->template = "admin/" . $core->theme . "/___risk_management.tpl.php";
    });


    //support
    $router->match('GET|POST', '/support', function () use ($tpl, $core){
        $tpl = App::View(BASEPATH . 'view/');
        $tpl->dir = "admin/" . $core->theme . "/";
        $tpl->title = "Support";
        $tpl->menu = App::Content()->menuTree(true);
        $tpl->template = "admin/" . $core->theme . "/___support.tpl.php";
    });




















    //admin users
    $router->mount('/users', function() use ($router, $tpl) {
        $router->match('GET|POST', '/', 'Users@Index');
        $router->get('/grid', 'Users@Index');
        $router->get('/edit/(\d+)', 'Users@Edit');
        $router->get('/new', 'Users@Save');
        $router->get('/history/(\d+)', 'Users@History');
    });

    //admin account
    $router->get('/myaccount', 'Admin@Account');
    $router->get('/mypassword', 'Admin@Password');

    //admin menus
    $router->mount('/menus', function() use ($router, $tpl) {
        $router->get('/', 'Content@Menus');
        $router->get('/edit/(\d+)', 'Content@MenuEdit');
    });

    //admin pages
    $router->mount('/pages', function() use ($router, $tpl) {
        $router->match('GET|POST', '/', 'Content@Pages');
        $router->get('/edit/(\d+)', 'Content@PageEdit');
        $router->get('/new', 'Content@PageSave');
    });

    //admin memberships
    $router->mount('/memberships', function() use ($router, $tpl) {
        $router->match('GET', '/', 'Membership@Index');
        $router->get('/history/(\d+)', 'Membership@History');
        $router->get('/edit/(\d+)', 'Membership@Edit');
        $router->get('/new', 'Membership@Save');
    });

    //admin coupons
    $router->mount('/coupons', function() use ($router, $tpl) {
        $router->get('/', 'Content@Coupons');
        $router->get('/edit/(\d+)', 'Content@CouponEdit');
        $router->get('/new', 'Content@CouponSave');
    });

    //admin email templates
    $router->mount('/templates', function() use ($router, $tpl) {
        $router->get('/', 'Content@Templates');
        $router->get('/edit/(\d+)', 'Content@TemplateEdit');
    });

    //admin custom fields
    $router->mount('/fields', function() use ($router, $tpl) {
        $router->get('/', 'Content@Fields');
        $router->get('/edit/(\d+)', 'Content@FieldEdit');
        $router->get('/new', 'Content@FieldSave');
    });

    //admin countries
    $router->mount('/countries', function() use ($router, $tpl) {
        $router->get('/', 'Content@Countries');
        $router->get('/edit/(\d+)', 'Content@CountryEdit');
    });

    //admin file manager
    $router->get('/manager', 'File@Index');

    //admin page builder
    $router->get('/builder/(\w+)/(\d+)', 'Admin@Builder');

    //admin gateways
    $router->mount('/gateways', function() use ($router, $tpl) {
        $router->get('/', 'Admin@Gateways');
        $router->get('/edit/(\d+)', 'Admin@GatewayEdit');
    });

    //admin languages
    $router->mount('/languages', function() use ($router, $tpl) {
        $router->match('GET', '/', 'Lang@Index');
        $router->get('/edit/(\d+)', 'Lang@Edit');
        $router->get('/translate/(\d+)', 'Lang@Translate');
        $router->get('/new', 'Lang@Save');
    });

    //admin permissions
    $router->mount('/permissions', function() use ($router, $tpl) {
        $router->get('/', 'Admin@Permissions');
        $router->get('/privileges/(\d+)', 'Admin@Privileges');
    });

    //admin tools
    $router->mount('/utilities', function() use ($router, $tpl) {
        $router->get('/', 'Admin@Utilities');
    });

    //admin plugins
    $router->mount('/plugins', function() use ($router, $tpl) {
        $router->get('/', 'Plugins@Index');
        $router->get('/edit/(\d+)', 'Plugins@Edit');
        $router->get('/new', 'Plugins@Save');

        //admin individual plugins
        include_once(APLUGPATH . 'routes.php');
    });

    //admin modules
    $router->mount('/modules', function() use ($router, $tpl) {
        $router->get('/', 'Modules@Index');
        $router->get('/edit/(\d+)', 'Modules@Edit');

        //admin individual modules
        include_once(AMODPATH . 'routes.php');
    });

    //admin backup
    $router->get('/backup', 'Admin@Backup');

    //admin system
    $router->get('/system', 'Admin@System');

    //admin newsletter
    $router->get('/mailer', 'Admin@Mailer');

    //admin layout
    $router->get('/layout', 'Plugins@Layout');

    //admin transactions
    $router->match('GET|POST', '/transactions', 'Admin@Transactions');

    //admin configuration
    $router->get('/configuration', 'Admin@Configuration');

    //admin trash
    $router->get('/trash', 'Admin@Trash');

    //logout
    $router->before('GET', '/logout', function()
    {
        if(App::Auth()->logged_in) {
            App::Auth()->logout();
        }
        Url::redirect(SITEURL . '/admin/');
    });

});

/* front end routes */
$core = App::Core();
//home
$router->match('GET|POST', '/', 'Front@Index');

//exchange
$router->match('GET|POST', '/exchange', function () use ($tpl, $core){
    $tpl = App::View(BASEPATH . 'view/');
    $tpl->dir = "front/themes/" . $core->theme . "/";
    $tpl->title = "Exchange Markets";
    $tpl->keywords = "Exchange cricket, exchange football, free exchange bonus, free cricket exchange betting, sign up bonus exchange.";
    $tpl->description = "With our pre-match betting exchange, you can have large range of events and categories with 100% liquidity. There are over 1000 events every single day to play on.. ";
    $tpl->menu = App::Content()->menuTree(true);
    $tpl->template = "front/themes/" . $core->theme . "/exchange.tpl.php";
});

//sliip history
$router->match('GET|POST', '/bt_accounts', function () use ($tpl, $core){
    $tpl = App::View(BASEPATH . 'view/');
    $tpl->dir = "front/themes/" . $core->theme . "/";
    $tpl->title = "Accounts and History";
    $tpl->keywords = null;
    $tpl->description = null;
    $tpl->menu = App::Content()->menuTree(true);
    $tpl->template = "front/themes/" . $core->theme . "/_bt_accounts.tpl.php";
});

//ex .. Sportsbook Prematch
$router->match('GET|POST', '/sportsbook-prematch', function () use ($tpl, $core){
    $tpl = App::View(BASEPATH . 'view/');
    $tpl->dir = "front/themes/" . $core->theme . "/";
    $tpl->title = "Sportsbook Markets";
    $tpl->keywords = "Upcoming football, football fixtures, cricket fixtures, sports fixtures, upcoming cricket events, coming up sports events, football exchange betting website, football exchange, cricket exchange, sportsbook pre-match events. Free sportsbook betting";
    $tpl->description = "Our prematch sportsbook has over 1000 events every single day with hundreds of betting category to choose at your comfort. Get started with cricmarkets.";
    $tpl->menu = App::Content()->menuTree(true);
    $tpl->template = "front/themes/" . $core->theme . "/sportsbook_prematch.tpl.php";
});

//ex .. Sportsbook Inplay
$router->match('GET|POST', '/upevents', function () use ($tpl, $core){
    $tpl = App::View(BASEPATH . 'view/');
    $tpl->dir = "front/themes/" . $core->theme . "/";
    $tpl->title = "Sportsbook upcoming events";
    $tpl->keywords = "sportsbook exchange, football exchange, baseball exchange, basketball exchange, tennis exchange, american football exchange, cricket exchange";
    $tpl->description = "Sportsbook betting on hundreds and thousands of sporting events. Get started today without taking initial risk with our first deposit bonus";
    $tpl->menu = App::Content()->menuTree(true);
    $tpl->template = "front/themes/" . $core->theme . "/upevents.tpl.php";
});

//super agent
$router->match('GET|POST', '/suppagent', function () use ($tpl, $core)
{
    $tpl = App::View(BASEPATH . 'view/');
    $tpl->dir = "front/themes/" . $core->theme . "/";
    $tpl->title = "Super Agent Panel";
    $tpl->keywords = null;
    $tpl->description = null;
    $tpl->menu = App::Content()->menuTree(true);

    $tpl->template = "front/themes/" . $core->theme . "/suppagent.tpl.php";
});

//for agents panel
$router->match('GET|POST', '/affagent', function () use ($tpl, $core){
    $tpl = App::View(BASEPATH . 'view/');
    $tpl->dir = "front/themes/" . $core->theme . "/";
    $tpl->title = "Affiliate/Agent Panel";
    $tpl->keywords = null;
    $tpl->description = null;
    $tpl->menu = App::Content()->menuTree(true);
    $tpl->template = "front/themes/" . $core->theme . "/affagent.tpl.php";
});


//inplay
/* $router->match('GET|POST', '/inplay', function () use ($tpl, $core)
  {
 $tpl = App::View(BASEPATH . 'view/');
 $tpl->dir = "front/themes/" . $core->theme . "/";
 $tpl->title = "Full Inplay market Events";
 $tpl->keywords = null;
 $tpl->description = null;
 $tpl->menu = App::Content()->menuTree(true);

      $tpl->template = "front/themes/" . $core->theme . "/inplay.tpl.php";
  });
  */

//inplay view

$router->match('GET|POST', '/inplay-view', function () use ($tpl, $core){
    $tpl = App::View(BASEPATH . 'view/');
    $tpl->dir = "front/themes/" . $core->theme . "/";
    $tpl->title = "Sports Exchange In-Play Events";
    $tpl->keywords = "Sports exchange, cricket exchange, free exchange betting, football exchange, free bonus";
    $tpl->description = "Sports in-play exchange betting at your finger tips. Cricket exchange betting with best odds and range of markets.";
    $tpl->menu = App::Content()->menuTree(true);
    $tpl->template = "front/themes/" . $core->theme . "/inplay-view.tpl.php";
});
/*
//Financial Markets
$router->match('GET|POST', '/chat-markets', function () use ($tpl, $core){
$tpl = App::View(BASEPATH . 'view/');
$tpl->dir = "front/themes/" . $core->theme . "/";
$tpl->title = "Chat Markets";
$tpl->keywords = "Forex betting, stocks betting, spread betting, financial betting";
$tpl->description = "Cricmarkets offers you a stake betting on financial products including forex, stocks, crypto currency, commodities and among others";
$tpl->menu = App::Content()->menuTree(true);
$tpl->template = "front/themes/" . $core->theme . "/_chat_markets.tpl.php";
});
*/
//index events or events view
$router->match('GET|POST', '/events', function () use ($tpl, $core){
    $tpl = App::View(BASEPATH . 'view/');
    $tpl->dir = "front/themes/" . $core->theme . "/";
    $tpl->title = "Events view";
    $tpl->keywords = "Full odds events view";
    $tpl->description = "Sportsbook full events view on parlays and single bet";
    $tpl->menu = App::Content()->menuTree(true);
    $tpl->template = "front/themes/" . $core->theme . "/index_events.tpl.php";
});

//casino
$router->match('GET|POST', '/casino', function () use ($tpl, $core){
    $tpl = App::View(BASEPATH . 'view/');
    $tpl->dir = "front/themes/" . $core->theme . "/";
    $tpl->title = "Casino live dealers";
    $tpl->keywords = "Casino exchange, live casino dealers cricmarkets, cricmarkets casino, casino dealers";
    $tpl->description = "Play live casino dealers with cricmarkets. With large range of casino events and live dealers, you have enormous amount of opportunity to make money online.";
    $tpl->menu = App::Content()->menuTree(true);
    $tpl->template = "front/themes/" . $core->theme . "/_casino.tpl.php";
});

//slot
$router->match('GET|POST', '/slot', function () use ($tpl, $core){
    $tpl = App::View(BASEPATH . 'view/');
    $tpl->dir = "front/themes/" . $core->theme . "/";
    $tpl->title = "Slot Games";
    $tpl->keywords = "Slot exchange, games exchange, slot betting, slot games free, free games betting";
    $tpl->description = "Play slot games and make money online. Cricmarkets has hundreds of slot games to play with. Try your luck with us, today";
    $tpl->menu = App::Content()->menuTree(true);
    $tpl->template = "front/themes/" . $core->theme . "/_slot.tpl.php";
});

//Virtual Racsing
/*
$router->match('GET|POST', '/virtual-racing', function () use ($tpl, $core){
$tpl = App::View(BASEPATH . 'view/');
$tpl->dir = "front/themes/" . $core->theme . "/";
$tpl->title = "Virtual Racing";
$tpl->keywords = "Horse racing cricmarkets, virtual racing games, racing games, free racing";
$tpl->description = "Our virtual horse racing is simple yet highly entertaining game. Try our virtual horse racing today.";
$tpl->menu = App::Content()->menuTree(true);
$tpl->template = "front/themes/" . $core->theme . "/_virtual_racing.tpl.php";
});


  //agent registration
$router->match('GET|POST', '/agent-registration', function () use ($tpl, $core)
 {
$tpl = App::View(BASEPATH . 'view/');
$tpl->dir = "front/themes/" . $core->theme . "/";
$tpl->title = "Registration";
$tpl->keywords = null;
$tpl->description = null;
$tpl->menu = App::Content()->menuTree(true);

     $tpl->template = "front/themes/" . $core->theme . "/agent-registration.php";
 });

 */

//agent registration
$router->match('GET|POST', '/agent-registration', function () use ($tpl, $core)
{
    $tpl = App::View(BASEPATH . 'view/');
    $tpl->dir = "front/themes/" . $core->theme . "/";
    $tpl->title = "Registration";
    $tpl->keywords = null;
    $tpl->description = null;
    $tpl->menu = App::Content()->menuTree(true);

    $tpl->template = "front/themes/" . $core->theme . "/agent-registration.php";
});



//pages
$router->match('GET|POST', '/' . $core->pageslug . '/([a-z0-9_-]+)', 'Front@Page');

//login page
$router->match('GET|POST', '/' . $core->system_slugs->login[0]->{'slug' . Lang::$lang}, 'Front@Login');

//register page
$router->match('GET|POST', '/' . $core->system_slugs->register[0]->{'slug' . Lang::$lang}, 'Front@Register');

//search page
$router->match('GET|POST', '/' . $core->system_slugs->search[0]->{'slug' . Lang::$lang}, 'Front@Search');

//sitemap page
$router->match('GET|POST', '/' . $core->system_slugs->sitemap[0]->{'slug' . Lang::$lang}, 'Front@Sitemap');

//privacy page
$router->match('GET|POST', '/' . $core->system_slugs->policy[0]->{'slug' . Lang::$lang}, 'Front@Privacy');

//account page
$router->mount('/' . $core->system_slugs->account[0]->{'slug' . Lang::$lang}, function() use ($router, $tpl) {
    $router->match('GET|POST', '/', 'Front@Dashboard');
    $router->get('/affiliates', 'Front@History');
    $router->get('/settings', 'Front@Settings');
    $router->get('/validate', 'Front@Validate');
});

//activation page
$router->match('GET|POST', '/' . $core->system_slugs->activate[0]->{'slug' . Lang::$lang}, 'Front@Activation');

//profile page
$router->match('GET|POST', '/' . $core->system_slugs->profile[0]->{'slug' . Lang::$lang} . '/([a-z0-9_-]+)', 'Front@Profile');

//modules
include_once (FMODPATH . "routes.php");

//logout
$router->get('/logout', function()
{
    App::Auth()->logout();
    Url::redirect(SITEURL . '/');
});

//404
$router->set404(function () use($core, $router)
{
    $tpl = App::View(BASEPATH . 'view/');
    $tpl->core = App::Core();
    $tpl->dir = $router->segments[0] == "admin" ? "admin/" : "front/themes/" . $tpl->core->theme . "/404/";
    $tpl->segments = $router->segments;
    $tpl->menu = App::Content()->menuTree(true);
    $tpl->data = null;
    $tpl->core = $core;
    $tpl->title = Lang::$word->META_ERROR;
    $tpl->keywords = null;
    $tpl->description = null;
    $tpl->template = $router->segments[0] == "admin" ? 'admin/404.tpl.php' : "front/themes/" . $tpl->core->theme . "/404/404.tpl.php";
    echo $tpl->render();
});

// Maintenance mode
if ($core->offline == 1 && !App::Auth()->is_Admin() && !preg_match("#admin/#", $_SERVER['REQUEST_URI'])) {
    Url::redirect(SITEURL . "/maintenance.php");
    exit;
}

// Run router
$router->run(function () use($tpl, $router)
{
    $tpl->segments = $router->segments;
    $tpl->core = App::Core();
    Content::$segments = $router->segments;
    echo $tpl->render();
});
  
