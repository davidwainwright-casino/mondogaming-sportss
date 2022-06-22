<?php
/**
 * ARegister
 *
 * @package Yoyo Framework
 * @author yoyostack.com
 * @copyright 2015
 */

if (!defined("_YOYO"))
    die('Direct access to this location is not allowed.');
?>
<?php $usid = App::Auth()->uid;
$ob = App::Auth()->type;
$exclude_list = array("Sagent", "agent");
if (!in_array($ob, $exclude_list)) {
    echo '<div class="noexist">The page you are looking for does not exist!</div>';
    die();
} ?>

<?php //affiliates tracking
if (!isset($_SESSION['aaff']) and $_GET['aaff']) {
    $cookie_expire = time() + 60 * 60 * 1;
    $_SESSION['aaff'] = $_GET['aaff'];
    setcookie('aaff', $_SESSION['aaff'], $cookie_expire, '/', '.' . $_SERVER['HTTP_HOST']);
} else if (!isset($_SESSION['asaid']) and $_GET['asaid']) {
    $cookie_expire = time() + 60 * 60 * 1;
    $_SESSION['asaid'] = $_GET['asaid'];
    setcookie('asaid', $_SESSION['asaid'], $cookie_expire, '/', '.' . $_SERVER['HTTP_HOST']);
} ?>

<style>
    body {
        direction: rtl;
    }
</style>

<div class="supp-header">
    <ul class="suppmenu">
        <li class="mennav">
            <div class="menuv">AGENTS ONLY!</div>
        </li>
        <li class="supplogo">
            <div class="columns shrink mobile-80 phone-80">
                <a href="<?php echo SITEURL; ?>/" class="logo">
                    <?php echo ($this->core->logo) ? '<img src="' . SITEURL . '/uploads/' . $this->core->logo . '" alt="' . $this->core->company . '">' : $this->core->company; ?>
                </a>
            </div>
        </li>


        <li class="suppsubmenu">
            <div id="superuser">
  <span class="supbal">
   <?php if (App::Auth()->type == 'Sagent') {
       echo number_format((float)App::Auth()->sabal, 2, '.', '');
   } else {
       echo number_format((float)App::Auth()->afbal, 2, '.', '');
   }; ?>
  </span>
                <span class="activesuper">u</span>

            </div>
        </li>
    </ul>
</div>

<div class="supprow">
    <div class="columns align-center align-self-middle tablet-100 mobile-100 phone-100">
        <div class="yoyo-grid">
            <div class="row align-center">
                <div class="columns screen-50 tablet-80 mobile-100 phone-100">
                    <div id="regForm" style="width: 100% !important;">
                        <form method="post" id="reg_form" name="reg_form">

                            <h3 class="yoyo primary text">רישום שחקן חדש
                                <span class="yoyo semi text">
                  </span></h3>
                            <p class="margin-bottom">
                                הרשמת משתמש חדש. שים לב כי לא תוכל לערוך את המשתמש ברגע שהוא נוצר. רק חשבון מחובר יכול לערוך את הפרטים האישיים שלו.
                            </p>
                            <div class="yoyo form" style="border: none;">
                                <div class="yoyo block fields">
                                    <div class="field">
                                        <label><?php echo Lang::$word->M_EMAIL; ?>
                                            <i class="icon asterisk"></i></label>
                                        <input name="email" type="email" placeholder="<?php echo Lang::$word->M_EMAIL; ?>">
                                        <small style="opacity: .5;"><?php echo Lang::$word->M_EMAIL_DESCRIPTION; ?></small>
                                    </div>

                                    <div class="field">
                                        <label><?php echo Lang::$word->M_PASSWORD; ?>
                                            <i class="icon asterisk"></i></label>
                                        <input type="password" name="password" placeholder="********">
                                    </div>
                                </div>

                                <div class="yoyo fields">
                                    <div class="field">
                                        <label><?php echo Lang::$word->M_FNAME; ?>
                                            <i class="icon asterisk"></i></label>
                                        <input name="fname" type="text" placeholder="<?php echo Lang::$word->M_FNAME; ?>">
                                    </div>
                                    <div class="field">
                                        <label><?php echo Lang::$word->M_LNAME; ?>
                                            <i class="icon asterisk"></i></label>
                                        <input name="lname" type="text" placeholder="<?php echo Lang::$word->M_LNAME; ?>">
                                    </div>
                                </div>

                                <div class="yoyo block fields">
                                    <div class="field">
                                        <label><?php echo Lang::$word->M_R_ID; ?>
                                            <i class="icon asterisk"></i></label>
                                        <input type="text" name="real_id" placeholder="<?php echo Lang::$word->M_R_ID; ?>" onkeypress='return event.charCode >= 48 && event.charCode <= 57'>
                                        <small style="opacity: .5;"><?php echo Lang::$word->M_R_ID_DESCRIPTION; ?></small>
                                    </div>
                                </div>

                                <div class="yoyo block fields">
                                    <div class="field">
                                        <label><?php echo Lang::$word->M_PHONE; ?>
                                            <i class="icon asterisk"></i></label>
                                        <input type="text" name="phone" placeholder="<?php echo Lang::$word->M_PHONE; ?>" onkeypress='return event.charCode >= 48 && event.charCode <= 57'>
                                        <small style="opacity: .5;"><?php echo Lang::$word->M_PHONE_DESCRIPTION; ?></small>
                                    </div>
                                </div>

                                <div class="yoyo block fields">
                                    <div class="field">
                                        <label><?php echo Lang::$word->M_ADDRESS; ?>
                                            <i class="icon asterisk"></i></label>
                                        <input type="text" name="address" placeholder="<?php echo Lang::$word->M_ADDRESS; ?>">
                                        <small style="opacity: .5;"><?php echo Lang::$word->M_ADDRESS_DESCRIPTION; ?></small>
                                    </div>
                                </div>


                                <div class="yoyo block fields">
                                    <div class="field">
                                        <label><?php echo Lang::$word->M_DOB; ?>
                                            <i class="icon asterisk"></i></label>
                                        <input type="date" name="bod_date" placeholder="<?php echo Lang::$word->M_DOB; ?>">
                                        <small style="opacity: .5;"><?php echo Lang::$word->M_DOB_DESCRIPTION; ?></small>
                                    </div>
                                </div>

                                <?php echo $this->custom_fields; ?>
                                <?php if ($this->core->enable_tax): ?>
                                    <div class="yoyo fields">
                                        <div class="field">
                                            <label><?php echo Lang::$word->M_CITY; ?>
                                                <i class="icon asterisk"></i></label>
                                            <input type="text" name="city" placeholder="<?php echo Lang::$word->M_CITY; ?>">
                                        </div>
                                        <div class="field">
                                            <label><?php echo Lang::$word->M_STATE; ?>
                                                <i class="icon asterisk"></i></label>
                                            <input type="text" name="state" placeholder="<?php echo Lang::$word->M_STATE; ?>">
                                            <input type="text" name="ozz" placeholder="" hidden>
                                        </div>
                                    </div>


                                    <div class="yoyo fields">
                                        <div class="field three wide">
                                            <label>
                                                <?php echo Lang::$word->M_ZIP; ?>
                                                <i class="icon asterisk"></i></label>
                                            <input type="text" name="zip">
                                        </div>


                                        <div class="field">
                                            <label>
                                                <?php echo Lang::$word->M_COUNTRY; ?>
                                                <i class="icon asterisk"></i></label>
                                            <select name="country">
                                                <option value="BD">Bangladesh</option>
                                                <option value="IN">India</option>
                                                <option value="CA">Canada</option>
                                                <option value="GB">United Kingdom (GB)</option>
                                                <option value="US">United States</option>
                                                <option value="AF">Afghanistan</option>
                                                <option value="AL">Albania</option>
                                                <option value="DZ">Algeria</option>
                                                <option value="AS">American Samoa</option>
                                                <option value="AD">Andorra</option>
                                                <option value="AO">Angola</option>
                                                <option value="AI">Anguilla</option>
                                                <option value="AQ">Antarctica</option>
                                                <option value="AG">Antigua and Barbuda</option>
                                                <option value="AR">Argentina</option>
                                                <option value="AM">Armenia</option>
                                                <option value="AW">Aruba</option>
                                                <option value="AU">Australia</option>
                                                <option value="AT">Austria</option>
                                                <option value="AZ">Azerbaijan</option>
                                                <option value="BS">Bahamas</option>
                                                <option value="BH">Bahrain</option>
                                                <option value="BD">Bangladesh</option>
                                                <option value="BB">Barbados</option>
                                                <option value="BY">Belarus</option>
                                                <option value="BE">Belgium</option>
                                                <option value="BZ">Belize</option>
                                                <option value="BJ">Benin</option>
                                                <option value="BM">Bermuda</option>
                                                <option value="BT">Bhutan</option>
                                                <option value="BO">Bolivia</option>
                                                <option value="BA">Bosnia and Herzegowina</option>
                                                <option value="BW">Botswana</option>
                                                <option value="BV">Bouvet Island</option>
                                                <option value="BR">Brazil</option>
                                                <option value="IO">British Indian Ocean Territory</option>
                                                <option value="VG">British Virgin Islands</option>
                                                <option value="BN">Brunei Darussalam</option>
                                                <option value="BG">Bulgaria</option>
                                                <option value="BF">Burkina Faso</option>
                                                <option value="BI">Burundi</option>
                                                <option value="KH">Cambodia</option>
                                                <option value="CM">Cameroon</option>
                                                <option value="CV">Cape Verde</option>
                                                <option value="KY">Cayman Islands</option>
                                                <option value="CF">Central African Republic</option>
                                                <option value="TD">Chad</option>
                                                <option value="CL">Chile</option>
                                                <option value="CN">China</option>
                                                <option value="CX">Christmas Island</option>
                                                <option value="CC">Cocos (Keeling) Islands</option>
                                                <option value="CO">Colombia</option>
                                                <option value="KM">Comoros</option>
                                                <option value="CG">Congo</option>
                                                <option value="CK">Cook Islands</option>
                                                <option value="CR">Costa Rica</option>
                                                <option value="CI">Cote D'ivoire</option>
                                                <option value="HR">Croatia</option>
                                                <option value="CU">Cuba</option>
                                                <option value="CY">Cyprus</option>
                                                <option value="CZ">Czech Republic</option>
                                                <option value="DK">Denmark</option>
                                                <option value="DJ">Djibouti</option>
                                                <option value="DM">Dominica</option>
                                                <option value="DO">Dominican Republic</option>
                                                <option value="TP">East Timor</option>
                                                <option value="EC">Ecuador</option>
                                                <option value="EG">Egypt</option>
                                                <option value="SV">El Salvador</option>
                                                <option value="GQ">Equatorial Guinea</option>
                                                <option value="ER">Eritrea</option>
                                                <option value="EE">Estonia</option>
                                                <option value="ET">Ethiopia</option>
                                                <option value="FK">Falkland Islands (Malvinas)</option>
                                                <option value="FO">Faroe Islands</option>
                                                <option value="FJ">Fiji</option>
                                                <option value="FI">Finland</option>
                                                <option value="FR">France</option>
                                                <option value="GF">French Guiana</option>
                                                <option value="PF">French Polynesia</option>
                                                <option value="TF">French Southern Territories</option>
                                                <option value="GA">Gabon</option>
                                                <option value="GM">Gambia</option>
                                                <option value="GE">Georgia</option>
                                                <option value="DE">Germany</option>
                                                <option value="GH">Ghana</option>
                                                <option value="GI">Gibraltar</option>
                                                <option value="GR">Greece</option>
                                                <option value="GL">Greenland</option>
                                                <option value="GD">Grenada</option>
                                                <option value="GP">Guadeloupe</option>
                                                <option value="GU">Guam</option>
                                                <option value="GT">Guatemala</option>
                                                <option value="GN">Guinea</option>
                                                <option value="GW">Guinea-Bissau</option>
                                                <option value="GY">Guyana</option>
                                                <option value="HT">Haiti</option>
                                                <option value="HM">Heard and McDonald Islands</option>
                                                <option value="HN">Honduras</option>
                                                <option value="HK">Hong Kong</option>
                                                <option value="HU">Hungary</option>
                                                <option value="IS">Iceland</option>
                                                <option value="ID">Indonesia</option>
                                                <option value="IQ">Iraq</option>
                                                <option value="IE">Ireland</option>
                                                <option value="IR">Islamic Republic of Iran</option>
                                                <option value="IL">Israel</option>
                                                <option value="IT">Italy</option>
                                                <option value="JM">Jamaica</option>
                                                <option value="JP">Japan</option>
                                                <option value="JO">Jordan</option>
                                                <option value="KZ">Kazakhstan</option>
                                                <option value="KE">Kenya</option>
                                                <option value="KI">Kiribati</option>
                                                <option value="KP">Korea, Dem. Peoples Rep of</option>
                                                <option value="KR">Korea, Republic of</option>
                                                <option value="KW">Kuwait</option>
                                                <option value="KG">Kyrgyzstan</option>
                                                <option value="LA">Laos</option>
                                                <option value="LV">Latvia</option>
                                                <option value="LB">Lebanon</option>
                                                <option value="LS">Lesotho</option>
                                                <option value="LR">Liberia</option>
                                                <option value="LY">Libyan Arab Jamahiriya</option>
                                                <option value="LI">Liechtenstein</option>
                                                <option value="LT">Lithuania</option>
                                                <option value="LU">Luxembourg</option>
                                                <option value="MO">Macau</option>
                                                <option value="MK">Macedonia</option>
                                                <option value="MG">Madagascar</option>
                                                <option value="MW">Malawi</option>
                                                <option value="MY">Malaysia</option>
                                                <option value="MV">Maldives</option>
                                                <option value="ML">Mali</option>
                                                <option value="MT">Malta</option>
                                                <option value="MH">Marshall Islands</option>
                                                <option value="MQ">Martinique</option>
                                                <option value="MR">Mauritania</option>
                                                <option value="MU">Mauritius</option>
                                                <option value="YT">Mayotte</option>
                                                <option value="MX">Mexico</option>
                                                <option value="FM">Micronesia</option>
                                                <option value="MD">Moldova, Republic of</option>
                                                <option value="MC">Monaco</option>
                                                <option value="MN">Mongolia</option>
                                                <option value="MS">Montserrat</option>
                                                <option value="MA">Morocco</option>
                                                <option value="MZ">Mozambique</option>
                                                <option value="MM">Myanmar</option>
                                                <option value="NA">Namibia</option>
                                                <option value="NR">Nauru</option>
                                                <option value="NP">Nepal</option>
                                                <option value="NL">Netherlands</option>
                                                <option value="AN">Netherlands Antilles</option>
                                                <option value="NC">New Caledonia</option>
                                                <option value="NZ">New Zealand</option>
                                                <option value="NI">Nicaragua</option>
                                                <option value="NE">Niger</option>
                                                <option value="NG">Nigeria</option>
                                                <option value="NU">Niue</option>
                                                <option value="NF">Norfolk Island</option>
                                                <option value="MP">Northern Mariana Islands</option>
                                                <option value="NO">Norway</option>
                                                <option value="OM">Oman</option>
                                                <option value="PK">Pakistan</option>
                                                <option value="PW">Palau</option>
                                                <option value="PA">Panama</option>
                                                <option value="PG">Papua New Guinea</option>
                                                <option value="PY">Paraguay</option>
                                                <option value="PE">Peru</option>
                                                <option value="PH">Philippines</option>
                                                <option value="PN">Pitcairn</option>
                                                <option value="PL">Poland</option>
                                                <option value="PT">Portugal</option>
                                                <option value="PR">Puerto Rico</option>
                                                <option value="QA">Qatar</option>
                                                <option value="RE">Reunion</option>
                                                <option value="RO">Romania</option>
                                                <option value="RU">Russian Federation</option>
                                                <option value="RW">Rwanda</option>
                                                <option value="LC">Saint Lucia</option>
                                                <option value="WS">Samoa</option>
                                                <option value="SM">San Marino</option>
                                                <option value="ST">Sao Tome and Principe</option>
                                                <option value="SA">Saudi Arabia</option>
                                                <option value="SN">Senegal</option>
                                                <option value="RS">Serbia</option>
                                                <option value="SC">Seychelles</option>
                                                <option value="SL">Sierra Leone</option>
                                                <option value="SG">Singapore</option>
                                                <option value="SK">Slovakia</option>
                                                <option value="SI">Slovenia</option>
                                                <option value="SB">Solomon Islands</option>
                                                <option value="SO">Somalia</option>
                                                <option value="ZA">South Africa</option>
                                                <option value="ES">Spain</option>
                                                <option value="LK">Sri Lanka</option>
                                                <option value="SH">St. Helena</option>
                                                <option value="KN">St. Kitts and Nevis</option>
                                                <option value="PM">St. Pierre and Miquelon</option>
                                                <option value="VC">St. Vincent and the Grenadines</option>
                                                <option value="SD">Sudan</option>
                                                <option value="SR">Suriname</option>
                                                <option value="SJ">Svalbard and Jan Mayen Islands</option>
                                                <option value="SZ">Swaziland</option>
                                                <option value="SE">Sweden</option>
                                                <option value="CH">Switzerland</option>
                                                <option value="SY">Syrian Arab Republic</option>
                                                <option value="TW">Taiwan</option>
                                                <option value="TJ">Tajikistan</option>
                                                <option value="TZ">Tanzania, United Republic of</option>
                                                <option value="TH">Thailand</option>
                                                <option value="TG">Togo</option>
                                                <option value="TK">Tokelau</option>
                                                <option value="TO">Tonga</option>
                                                <option value="TT">Trinidad and Tobago</option>
                                                <option value="TN">Tunisia</option>
                                                <option value="TR">Turkey</option>
                                                <option value="TM">Turkmenistan</option>
                                                <option value="TC">Turks and Caicos Islands</option>
                                                <option value="TV">Tuvalu</option>
                                                <option value="UG">Uganda</option>
                                                <option value="UA">Ukraine</option>
                                                <option value="AE">United Arab Emirates</option>
                                                <option value="VI">United States Virgin Islands</option>
                                                <option value="UY">Uruguay</option>
                                                <option value="UZ">Uzbekistan</option>
                                                <option value="VU">Vanuatu</option>
                                                <option value="VA">Vatican City State</option>
                                                <option value="VE">Venezuela</option>
                                                <option value="VN">Vietnam</option>
                                                <option value="WF">Wallis And Futuna Islands</option>
                                                <option value="EH">Western Sahara</option>
                                                <option value="YE">Yemen</option>
                                                <option value="ZR">Zaire</option>
                                                <option value="ZM">Zambia</option>
                                                <option value="ZW">Zimbabwe</option>
                                            </select>
                                        </div>
                                    </div>
                                <?php endif; ?>


                                <!--<div class="yoyo fields">

                                    <div class="field">
                                        <label>
                                            <?php echo 'Base Currency'; ?>
                                            <i class="icon asterisk"></i></label>
                                        <select name="maincurrency" disabled>
                                            <option value="BDT">BDT</option>
                                        </select>
                                        <div class="labme"></div>

                                    </div>


                                    <div class="field three wide">
                                        <label>
                                            <?php echo 'Local Currency'; ?>
                                            <i class="icon asterisk"></i></label>
                                        <select name="ucurrency">
                                            <option value="BDT">BDT</option>
                                        </select>
                                    </div>
                                </div>-->


                                <div class="yoyo block fields">
                                    <div class="field">
                                        <label><?php echo Lang::$word->CAPTCHA; ?>
                                            <i class="icon asterisk"></i></label>
                                        <div class="yoyo right labeled fluid input">
                                            <input placeholder="<?php echo Lang::$word->CAPTCHA; ?>" name="captcha" type="text" style="text-align: right;">
                                            <span class="yoyo label" style="margin-left: 1em;    background: #eb1515;">
                        <?php echo Session::captcha(); ?>
                        </span>
                                        </div>
                                    </div>
                                    <div class="field">
                                        <div class="yoyo checkbox">
                                            <input name="agree" type="checkbox" value="1" id="agree">
                                            <label for="agree">
                                                <a href="<?php echo Url::url('/' . App::Core()->system_slugs->policy[0]->{'slug' . Lang::$lang}); ?>" class="secondary dashed"><small><?php echo Lang::$word->AGREE; ?></small></a>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <style>
                                    .yoyo.checkbox {
                                        text-align: right;
                                    }

                                    .yoyo.checkbox label:before {
                                        left: auto;
                                        right: 0;
                                    }

                                    .yoyo.checkbox label:after {
                                        right: 0.250rem;
                                    }

                                    .yoyo.checkbox {
                                        position: relative;
                                        padding: 0 2rem 4px 0;
                                        margin-bottom: 1.5em;
                                    }
                                </style>

                                <div class="yoyo fields align-middle">

                                </div>
                                <div class="field content-right">
                                    <button class="yoyo primary button" data-action="aregister" name="dosubmit" type="button" style="top: -50px;"><?php echo Lang::$word->M_SUB24; ?></button>
                                </div>
                            </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</div>


<script>


</script>


<style type="text/css">
    ul.sidenavs {
        padding: 0;
    }

    i.icon.chevron.right {
        float: right;
        margin-top: 5px;
    }

    .sidenavs li:hover {
        background: #e3e0e0;
    }

    .sidenavs li {
        position: relative;
        display: block;
        width: 100%;
        padding: 10px 15px;
        border-bottom: 1px solid #e7e7e7;
        cursor: pointer;
        color: #337ab7;
    }

    .sagentd {
        background: #e7e7e7;
        padding: 10px;
    }

    .supadm {
        font-size: 24px;
        font-weight: bold;
    }

    i.icon.users {
        font-size: 20px;
    }

    li.supplogo img {
        max-width: 100px;
    }

    .suppcol.sleft {
        background-color: #f8f8f8;
        max-width: 300px;
    }

    .supp-header {
        background-color: #1f1f1f;
        border-color: #e7e7e7;
        z-index: 1000;
        border-width: 0 0 1px;
        border-bottom: 1px solid #e7e7e7;
        position: fixed;
        width: 100%;
        padding: 10px;
    }

    ul.suppmenu {
        display: flex;
        width: 100%;
        padding: 0px;
        margin: 0px;
    }

    ul.suppmenu li {
        display: table-cell;
        width: 50%;
    }

    .menuv {
        font-size: 18px;
        font-weight: bold;
        margin-top: 5px;
        display: inline-block;
        cursor: pointer;
    }

    i.icon.reorder {
        font-size: 20px;
        position: absolute;
        margin-left: 5px;
        margin-top: 3px;
        font-weight: bold;
    }

    .menuv:hover {
        color: #f00;
    }

    li.suppsubmenu {
        text-align: right;
        padding-right: 10px;
    }

    span.supbal {
        font-size: 20px;
        font-weight: bold;
        display: inline-block;
        margin-top: 20px;
        color: #fff;
    }

    div#superuser {
        float: right;
        margin: 0;
        display: inline-block;
        padding: 0;
        line-height: 0;
        vertical-align: text-bottom;
    }

    .wrapper-sa {
        max-width: 780px;
        margin: 0 auto;
        min-height: 200px;
        margin-top: 10px;
        border-left: 5px solid #f3f3f3;
        padding-left: 5px;
    }

    .containersa {
        min-height: 100px;
        color: #fff;
    }


    .colsa {
        float: left;
        padding: 10px;
        margin: 5px;
        background: #03A9F4;
        width: calc(33.33% - 20px);
    }

    .containersa:after {
        content: "";
        display: table;
        clear: both;
    }

    .agdwn {
        border-bottom: 1px solid;
        background: #e7e7e7;
        padding: 5px 10px;
    }

    span.righelse {
        float: right;
    }

    span.usvalue {
        float: right;
        font-size: 30px;
        margin-right: 2px;
    }

    span.userle {
        float: left;
        margin-top: 10px;
    }

    div#cott .colsa {
        background: #787878;
    }

    .noteus {
        padding: 10px;
        border-radius: 5px;
        border: 1px solid #ffc409;
        margin-top: 10px;
    }

    .backmeif {
        background: #d0d0d0;
        display: inline-block;
        padding: 3px 20px;
        cursor: pointer;
        margin-bottom: 20px
    }

    .backmeif:hover {
        background: #a7a7a7;
    }


    form#reg_form {
        background: #f7f7f7;
        padding: 15px;
        border-left: 5px solid #000
    }


    * {
        box-sizing: border-box;
    }

    .suppcol {
        float: left;
        min-height: 300px;
    }

    .supprow:after {
        content: "";
        display: table;
        clear: both;
    }

    li.supplogo {
        text-align: center;
    }

    .supprow {
        margin-top: 56px;
    }

    .suppcol.sleft.nobig {
        display: none;
        width: 100% !important;
        padding: 10px !Important;
    }

    .menuv.adk:before {
        content: "X";
        margin-right: 5px;
        color: #000;
    }


    @media screen and (max-width: 799px) {
        .suppcol.sleft {
            display: none;
        }

        div#superuser {
            margin-top: 20px;
        }

        .supp-header {
            padding: 0px
        }

        .menuv {
            padding: 10px;
            margin-top: 15px;
        }

        li.supplogo img {
            max-width: 140px;
            margin-top: 3px;
        }

        span.supbal {
            font-size: 16px;
        }

        .supprow {
            margin-top: 68px;
        }

        .suppcol.sleft.shmob {
            display: block;
            width: 100% !Important;
            max-width: 799px;
        }
    }


</style>
<!-- Page Content-->
