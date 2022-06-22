<?php
  /**
   * Stripe Form
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  if (!defined("_YOYO"))
      die('Direct access to this location is not allowed.');
?>
<div class="yoyo small compact black segment form auto" id="stripe_form">
  <form method="post" id="stripe">
    <div class="form-row">
      <div id="card-element" class="margin-bottom"></div>
      <button class="yoyo primary fluid button" id="dostripe" name="dostripe" type="submit"><?php echo Lang::$word->SUBMITP;?></button>
    </div>
    <input type="hidden" name="processStripePayment" value="1">
  </form>
  <div role="alert" id="smsgholder" class="yoyo negative text"></div>
</div>
<script type="text/javascript">
// <![CDATA[
var stripe = Stripe('<?php echo $this->gateway->extra3;?>');
var elements = stripe.elements();

var style = {
    base: {
        color: '#32325d',
        fontFamily: 'Poppins, "Helvetica Neue", Helvetica, sans-serif',
        fontSmoothing: 'antialiased',
        fontSize: '16px',
        '::placeholder': {
            color: '#aab7c4'
        }
    },
    invalid: {
        color: '#fa755a',
        iconColor: '#fa755a'
    }
};

var card = elements.create('card', {
    style: style
});

card.mount('#card-element');

card.addEventListener('change', function(event) {
    var displayError = document.getElementById('smsgholder');
    if (event.error) {
        displayError.textContent = event.error.message;
    } else {
        displayError.textContent = '';
    }
});

var form = document.getElementById('stripe');
form.addEventListener('submit', function(event) {
    event.preventDefault();

    stripe.createToken(card).then(function(result) {
        if (result.error) {
            var errorElement = document.getElementById('smsgholder');
            errorElement.textContent = result.error.message;
        } else {
            stripeTokenHandler(result.token);
        }
    });
});

function stripeTokenHandler(token) {
    var form = document.getElementById('stripe');
    var hiddenInput = document.createElement('input');
    hiddenInput.setAttribute('type', 'hidden');
    hiddenInput.setAttribute('name', 'stripeToken');
    hiddenInput.setAttribute('value', token.id);
    form.appendChild(hiddenInput);

    // Submit the form
    $("#stripe_form").addClass('loading');
    var str = $("#stripe").serialize();
    $.ajax({
        type: "post",
        dataType: 'json',
        url: "<?php echo SITEURL;?>/gateways/stripe/digishop/ipn.php",
        data: str,
        success: function(json) {
            $("#stripe_form").removeClass('loading');
            if (json.type == "success") {
                $('body')
                    .transition({
                        animation: 'scale',
                        duration: '1s',
                        onComplete: function() {
                            window.location.href = '<?php echo Url::url('/' . App::Core()->system_slugs->account[0]->{'slug' . Lang::$lang}, "digishop");?>';
                        }
                    });
            }
            if (json.message) {
                $.notice(decodeURIComponent(json.message), {
                    autoclose: 12000,
                    type: json.type,
                    title: json.title
                });
            }
        }
    });
}
// ]]>
</script>