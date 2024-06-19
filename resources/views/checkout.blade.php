<html>
    <head>
        <script src="https://epayment.areeba.com/static/checkout/checkout.min.js"
                data-error="errorCallback"
                data-cancel="cancelCallback"
                data-complete="{{ url('/thank-you?orderId=' . $orderId . '&merchantId=' . $merchantId) }}">
            </script>

        <script type="text/javascript">
            function errorCallback(error) {
                  console.log(JSON.stringify(error));
            }
            function cancelCallback() {
                  console.log('Payment cancelled');
            }
            
            Checkout.configure({
                    session: {
                      id: '<?php echo $sessionId; ?>'
                        },
               });
        </script>
    </head>
    <body>
        <div id="embed-target"> <div>
        //Choose Embedded Page or Payment Page
        <input type="button" value="Pay with Embedded Page" onclick="Checkout.showEmbeddedPage('#embed-target');" />
        <input type="button" value="Pay with Payment Page" onclick="Checkout.showPaymentPage();" />
    </body>
</html>