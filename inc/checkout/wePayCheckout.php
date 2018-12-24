<?php
    $amount = $_POST['amount'];
    $shippingCost = $_POST['shippingCost'];
    $itemID = $_POST['itemID'];
    $description = $_POST['item'];
    if(isset($_POST['discountID'])) {
        $discountID = $_POST['discountID'];
    } else {
        $discountID = '';
    }
    $shippingAddress = $_POST['shippingAddress'];
    // WePay PHP SDK - http://git.io/mY7iQQ
    require_once '../../vendor/autoload.php';

    //$redirectURI = 'https://localhost/nd-v1.00/placeOrder.php?itemID=';
    $redirectURI = 'https://nxtdrop.com/placeOrder.php?itemID=';

    // TEST application settings
    /*$account_id = 1329085590; // your app's account_id
    $client_id = 52447;
    $client_secret = "98ac089d97";
    $access_token = "STAGE_1a0530e17fd8ef8f110d6480bdba589784b3026f026f700ee386c55fee065736";*/ // your app's access_token

    // PRODUCTION application settings
    $account_id = 574797532; // your app's account_id
    $client_id = 95275;
    $client_secret = "c53e19b34e";
    $access_token = "PRODUCTION_0a298080db1d4a98e24be9738b7d7118ba076647f253a358d497fd8d786ab0a8"; // your app's access_token

    // change to useProduction for live environments
    //WePay::useStaging($client_id, $client_secret);
    WePay::useProduction($client_id, $client_secret);
    $wepay = new WePay($access_token);

    if($_SESSION['country'] == 'CA') {
        $currency = 'CAD';
    } else {
        $currency = 'USD';
    }

    // create the checkout
    $response = $wepay->request('checkout/create', array(
        'account_id'        => $account_id,
        'amount'            => $amount,
        'short_description' => $description,
        'type'              => 'goods',
        'currency'          => $currency,
        'fee'               => array(
            'fee_payer'     => 'payee_from_app'
        ),
        'hosted_checkout'   => array(
            'redirect_uri'      => $redirectURI.$itemID.'&amount='.$amount.'&shippingAddress='.$shippingAddress.'&shippingCost='.$shippingCost.'&discountID='.$discountID,
            'mode'              => 'iframe'
        )
    ));

    echo '<div id="wepay_checkout"></div>
    
     <script type="text/javascript">
        WePay.iframe_checkout("wepay_checkout", "'.$response->hosted_checkout->checkout_uri.'");
    </script>';
?>