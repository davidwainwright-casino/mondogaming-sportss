<?php $db = $_SERVER['DOCUMENT_ROOT'] . "/shell/";
include_once($db . "db.php");
include_once('../../init.php');
echo '<div id="aj-fetch">';


if (isset($_POST['method']) && $_POST['method'] == 'bbalances') {
    $usid = $_POST['usid'];
    $query = "SELECT * FROM users WHERE id=$usid";
    $result = mysqli_query($conn, $query);
    $row = $result->fetch_assoc();
    $chips = $row['chips'];
    $promo = $row['promo']; ?>

    <div class="boverv"><?= Lang::$word->ACC_P2_BALANCE_OVERVIEW; ?></div></br></br>
    <div class="isportswrap">
        <div class="ilabel"><?= Lang::$word->ACC_P2_SPORTS_CASINO_GAMES_MARKETS; ?></div>
        <div class="itop1">
            <span class="xpwith"><?= Lang::$word->ACC_P2_WITHDRAWABLE; ?></span> <span class="xpnumb"><?php echo $chips; ?></span>
        </div>
        <div class="itop2">
            <span class="xpwith"><?= Lang::$word->ACC_P2_BET_CREDIT; ?></span> <span class="xpnumb"><?php echo $promo; ?></span>
        </div>
        <div class="itop3">
            <span class="xpwith tt"><?= Lang::$word->ACC_P2_TOTAL; ?></span> <span class="xpnumb tt"><?php echo round($chips + $promo, 2); ?></span>
        </div>
    </div>

    <div class="isportswrap">
        <div class="ilabel"><?= Lang::$word->ACC_P2_OTHERS_BALANCE; ?></div>
        <div class="itop1">
            <span class="xpwith"><?= Lang::$word->ACC_P2_WITHDRAWABLE; ?></span> <span class="xpnumb"><?php echo '0.00'; ?></span>
        </div>
        <div class="itop2">
            <span class="xpwith"><?= Lang::$word->ACC_P2_BET_CREDIT; ?></span> <span class="xpnumb"><?php echo '0.00'; ?></span>
        </div>
        <div class="itop3">
            <span class="xpwith tt"><?= Lang::$word->ACC_P2_TOTAL; ?></span> <span class="xpnumb tt"><?php echo '0.00'; ?></span>
        </div>
    </div>

<?php } //FOR DEPOSITS//////////////////////////////////

elseif (isset($_POST['method']) && $_POST['method'] == 'bdeposit') {
    ?>
    <ul class="depositbar">
        <li class="dpt dactive" id="localPayments"><?= Lang::$word->ACC_P2_LOCAL_PAYMENTS; ?></li>
        <!--<li class="dpt" id="intPayments">Int.Payments</li>
        <li class="dpt" id="redeemVoucher">Redeem Voucher</li>-->
        <li class="dpt" id="cryptoCurrency"><?= Lang::$word->ACC_P2_CRYPTOCURRENCY; ?></li>
        <li class="dpt" id="history"><?= Lang::$word->ACC_P2_HISTORY; ?></li>
    </ul>


    <div id="depositbox">

        <div class="depositform">

            <b style="color:#f00"><i class="icon question sign"></i></b> <?= Lang::$word->ACC_P2_LOCAL_PAYMENT_DESC; ?>

            <div style="margin-top: 20px;">
                <a type="button" data-action="profile" name="dosubmit" class="yoyo primary button"><?= Lang::$word->ACC_P2_MESSEGE_AGENT; ?></a>
            </div>
        </div>

<!--
        <div class="cccwraper">
            <span class="selnames">Select Country</span>
            <select name="country" id="cccountry">
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


        <div id="semidepostbox">
            <ul class="indpayment">
                <li id="ggpay">
                    <div class="gpay">..</div>
                </li>

                <li id="gphonepe" class="inpay">
                    <div class="ppe">..</div>
                </li>

                <li id="gpaytm" class="inpay">
                    <div class="paytm">..</div>
                </li>
            </ul>
        </div>-->

        <div id="payrespond"></div>


    </div>
<?php } /////FOR DEPOSIT LOAD MORE..
elseif (isset($_POST['method']) && $_POST['method'] == 'ldeposit') {
    $usid = $_POST['usid'];
    $rc = $_POST['rc'];
    $query = "SELECT * FROM sh_sf_deposits WHERE user_id=$usid ORDER BY date DESC LIMIT $rc, 50 ";
    $result = mysqli_query($conn, $query);
    echo '<input type="hidden" class="cfvalue" value="50">';
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $amount = $row['amount'];
            $ref = $row['transaction_id'];
            $date = date("m-d-y H:i", $row['date']);
            $type = $row['type'];
            $status = $row['status'];
            ?>

            <ul class="deptshow">
                <li>
                    <div class="ccredit"> <?php if ($status == 'Received') {
                            echo '<a>Received</a>';
                        } else {
                            echo '<a style="color:#f00">Pending</a>';
                        }; ?> <span class="deptright"><?php echo $amount; ?></span></div>
                    <div class="ccredit"> Deposit: <span class="deptright"><?php echo $type; ?></span></div>
                    <div class="ccredit"> Ref: <span class="deptright"><?php echo $ref; ?></span></div>
                    <div class="ccredit"> Date: <span class="deptright"><?php echo $date; ?></span></div>
                </li>
            </ul>


        <?php }
    }
    if ($result->num_rows > 1) {
        echo '<span id="lrembank" class="dload">Load More...</span>';
    } else {
        echo 'No more records Found..';
    }
} ///FOR TRANSFERS/////////////////////////////////////

elseif (isset($_POST['method']) && $_POST['method'] == 'btransfer') {
    ?>
    <div class="dptext"><?= Lang::$word->TRANSFER_CREDIT; ?></div>
    <div class="depositform xp">
        <h6><?= Lang::$word->TRANSFER_CREDIT_TO_USERS; ?></h6>
        <?= Lang::$word->TRANSFER_NOTE_CONTENT; ?></br>
        <h5><?= Lang::$word->TRANSFER_NOTE_CONTENT_2; ?></h5>
        <button id="myBtn"><?= Lang::$word->TRANSFER_NOTE_CONTENT_3; ?></button>
    </div>


    <div class="_mandeposit"
    <h2><?= Lang::$word->TRANSFER_USE_SA_DASHBOARD_FOR_TRANSFERS; ?></h2>
    </div>

<?php } /////FOR TRANSFER LOAD MORE..
elseif (isset($_POST['method']) && $_POST['method'] == 'ltransfer') {
    $usid = $_POST['usid'];
    $rc = $_POST['rc'];
    $query = "SELECT * FROM sh_sf_transfers WHERE user_id=$usid ORDER BY date DESC LIMIT $rc, 20";
    $result = mysqli_query($conn, $query);
    echo '<input type="hidden" class="cfvalue" value="2">';
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $id = $row['id'];
            $amount = $row['amount'];
            $ref = $row['transaction_id'];
            $date = date("m-d-y H:i", $row['date']);
            $sendto = $row['send_to'];
            ?>

            <ul class="deptshow">
                <li class="idto-<?php echo $id; ?>">
                    <div class="ccredit"><?= Lang::$word->TRANSFER_A2A_TRANSFER; ?><span class="deptright"><?php echo $amount; ?></span></div>
                    <div class="ccredit"> <?= Lang::$word->TRANSFER_SENT_TO; ?> <span class="deptright"><?php echo $sendto; ?></span></div>
                    <div class="ccredit"> <?= Lang::$word->TRANSFER_REF; ?> <span class="deptright"><?php echo $ref; ?></span></div>
                    <div class="ccredit"> <?= Lang::$word->TRANSFER_DATE; ?> <span class="deptright"><?php echo $date; ?></span></div>
                </li>
            </ul>


        <?php }
    }
    if ($result->num_rows > 1) {
        echo '<div id="lrembank" class="tload">'. Lang::$word->TRANSFER_LOAD_MORE .'</div>';
    } else {
        echo 'No more records Found..';
    }
} //FOR DELETE PENDING REQUEST
elseif (isset($_POST['method']) && $_POST['method'] == 'cpending') {
    $idto = $_POST['idto'];
    $usid = $_POST['usid'];
    $query = "DELETE FROM sh_sf_deposits WHERE id = '$idto' AND user_id='$usid' AND status = 'Pending' ";
    $isDeleted = mysqli_query($conn, $query);

} ///FOR WITHDRAWAL/////////////////////////////////////

elseif (isset($_POST['method']) && $_POST['method'] == 'bwithdraw') {
    ?>
    <div class="dptext"><?= Lang::$word->WITHDRAWAL_HISTORY_TITLE; ?></div>
    <div class="depositform" style="border: 1px solid #ff9f1b;">
        <h6><?= Lang::$word->WITHDRAWAL_HISTORY_H6; ?></h6>
        <?= Lang::$word->WITHDRAWAL_HISTORY_DESC; ?></br>
        </br>

        <b><?= Lang::$word->WITHDRAWAL_HISTORY_DESC2; ?></b>
        </br>
        </br>
        <button id="myBtn"><?= Lang::$word->WITHDRAWAL_HISTORY_MORE; ?></button>
    </div>


    <div class="infcontent">
        <?php $binfo = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM sh_sf_users_bank WHERE user_id= " . $_POST['usid'] . ""));
        $ubb = mysqli_fetch_assoc(mysqli_query($conn, "SELECT chips FROM users WHERE id= " . $_POST['usid'] . ""));
        $bankinfo = $binfo['user_id'];
        if (!empty($bankinfo)) {
            echo  Lang::$word->PAYMENT_BANK_CONTENT.'</br></br>';
            echo  Lang::$word->PAYMENT_BANK_NAME;
            echo '<b><input class="bins" id="bankName" placeholder="Bank Name" value="' . $binfo['bank_name'] . '"></b></br><div id="shubank"></div></br>';
            echo  Lang::$word->PAYMENT_BANK_NUM;
            echo '<b><input class="bins" id="walletAddress" placeholder="account number" value="' . $binfo['wallet_address'] . '"></b></br>';

        } else {
            echo '<span class="errwarn"><i class="icon warning sign"></i> '. Lang::$word->PAYMENT_BANK_CONTENT_ERROR .':</span>.' . Lang::$word->PAYMENT_BANK_CONTENT_ERROR_TEXT;;
        } ?>
    </div>


    <div class="avfortrans">
        <?= Lang::$word->AVAILABLE_FOR_WITHDRAWALS; ?> : <b><?php echo $ubb['chips']; ?></b>
    </div>


    <div id="manwithdraw"><?= Lang::$word->WITHDRAWAL_FORM; ?></div> </br></br>

    <div id="mancashback"></div>


    <h5 class="dphist"><?= Lang::$word->WITHDRAWAL_HISTORY; ?></h5>
    <?php
    $usid = $_POST['usid'];
    $query = "SELECT * FROM sh_sf_withdraws WHERE user_id=$usid ORDER BY date DESC LIMIT 50";
    $result = mysqli_query($conn, $query);
    echo '<input type="hidden" class="cfvalue" value="20">';
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $id = $row['id'];
            $amount = $row['amount'];
            $ref = substr(str_shuffle("TROM9737XQWDG48URPLEE945Y"), 0, 20);;
            $date = date("m-d-y H:i", $row['date']);
            $type = $row['type'];
            $status = $row['status'];
            $sfrom = $row['send_from'];
            ?>

            <ul class="deptshow k">
                <li class="idto-<?php echo $id; ?>">
                    <div class="ccredit"> <?php if ($status == 'Processed') {
                            echo '<a>'. Lang::$word->WITHDRAWAL_PROCESSED.'</a>';
                        } else {
                            echo '<a style="color:#f00">'. Lang::$word->WITHDRAWAL_PROCESSING.'</a>';
                            echo '<span class="wpending" id="' . $id . '" title="cancel request">X</span>';
                        }; ?> <span class="deptright"><?php echo $amount; ?></span></div>
                    <div class="ccredit"> FROM: <?php echo $sfrom; ?>
                        <span class="deptright"><?php echo $type; ?></span></div>
                    <div class="ccredit"> Ref: <span class="deptright"><?php echo $ref; ?></span></div>
                    <div class="ccredit"> Date: <span class="deptright"><?php echo $date; ?></span></div>
                </li>
            </ul>


        <?php }
    } else {
        echo '<div style="padding:10px">'. Lang::$word->WITHDRAWAL_NO_ACTIVE_RECORDS_FOUND.'</div>';
    }

    if ($result->num_rows > 1) {
        echo '<span id="lrem" class="wload">'. Lang::$word->WITHDRAWAL_LOAD_MORE .'</span>';
    }

} //loadmore withdrawal
elseif (isset($_POST['method']) && $_POST['method'] == 'lwithdraw') {
    $usid = $_POST['usid'];
    $rc = $_POST['rc'];
    $query = "SELECT * FROM sh_sf_withdraws WHERE user_id=$usid ORDER BY date DESC LIMIT $rc, 50";
    $result = mysqli_query($conn, $query);
    echo '<input type="hidden" class="cfvalue" value="50">';
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $id = $row['id'];
            $amount = $row['amount'];
            $ref = substr(str_shuffle("TROM9737XQWDG48URPLEE945Y"), 0, 20);;
            $date = date("m-d-y H:i", $row['date']);
            $type = $row['type'];
            $status = $row['status'];
            $sfrom = $row['send_from'];
            ?>

            <ul class="deptshow k">
                <li class="idto-<?php echo $id; ?>">
                    <div class="ccredit"> <?php if ($status == 'Processed') {
                            echo '<a>'. Lang::$word->WITHDRAWAL_PROCESSED .'</a>';
                        } else {
                            echo '<a style="color:#f00">'. Lang::$word->WITHDRAWAL_PROCESSING.'</a>';
                            echo '<span class="cpending" id="' . $id . '" title="cancel request">X</span>';
                        }; ?> <span class="deptright"><?php echo $amount; ?></span></div>
                    <div class="ccredit"> FROM: <?php echo $sfrom; ?>
                        <span class="deptright"><?php echo $type; ?></span></div>
                    <div class="ccredit"> Ref: <span class="deptright"><?php echo $ref; ?></span></div>
                    <div class="ccredit"> Date: <span class="deptright"><?php echo $date; ?></span></div>
                </li>
            </ul>


        <?php }
    } else {
        echo '<div style="padding:10px">'. Lang::$word->WITHDRAWAL_NO_MORE_ACTIVE_RECORDS_FOUND.'</div>';
    }

    if ($result->num_rows > 1) {
        echo '<span id="lrem" class="wload">'. Lang::$word->WITHDRAWAL_LOAD_MORE.'.</span>';
    }


} ?>
</div>