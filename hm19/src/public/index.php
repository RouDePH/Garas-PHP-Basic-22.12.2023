<?php

$operId = 1;
$amount = 100;
$fee = 1000;

?><!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate"/>
    <meta http-equiv="Pragma" content="no-cache"/>
    <meta http-equiv="Expires" content="0"/>

    <title>GERC PSP3 JS LIB</title>

    <style type="text/css">
        body {
            background-color: white;
        }

        #container {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            display: flex;
            flex-direction: column;
            gap: 16px;
        }
    </style>

</head>

<body>
<div id="container"></div>
</body>

<script>
    window.env = {
        root: {
            container: "#container",
            amount: <?= $amount ?>,
            fee: <?= $fee ?>,
            buttonsStyle: {
                theme: "black"
            }
        },

        googlePay: {
            environment: "TEST",
            merchantId: "BCR2DN6T3ONJB5AW",
            merchantName: "GERC",
            gateway: "gerc",
            gatewayMerchantId: "gercmerchant"
        },

        applePay: {
            merchantId: "merchant.ua.gerc.fc",
            label: "Gerc"
        },

        onClick: async (data) => {
            console.log("onClick", JSON.stringify(data));
            return {oper_id: <?= $operId ?>};
        },

        onValidateApplePay: async (data) => {
            console.log("onValidateApplePay", JSON.stringify(data));
            return {};
        },

        onGettedToken: async (data) => {
            console.log("onGettedToken", JSON.stringify(data));
            alert(`onGettedToken\n${JSON.stringify(data)}`);
        },

        onReady: async () => {
            console.log("onReady");
        },

        onError: async (error) => {
            alert(`${error}`);
        }
    };
</script>

<script src="https://gpay.gerc.ua/v1/bundle.min.js"></script>

</html>

