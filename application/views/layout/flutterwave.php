Please wait, connecting with FlutterWave....
<html>
<head>
    <script>
        function submitPayuForm() {
            var payuForm = document.forms.payuForm;
            payuForm.submit();
        }
    </script>
</head>
<body onload="submitPayuForm()">
    <form action="https://checkout.flutterwave.com/v3/hosted/pay" method="post" name="payuForm">
		<input type="hidden" name="public_key" value="<?php echo $pubKey; ?>" />
		<input type="hidden" name="customer[email]" value="<?php echo $customer_email; ?>" />
		<input type="hidden" name="customer[name]" value="<?php echo $student_name; ?>" />
		<input type="hidden" name="tx_ref" value="<?php echo $txref; ?>" />
		<input type="hidden" name="amount" value="<?php echo $amount; ?>" />
		<input type="hidden" name="currency" value="<?php echo $currency; ?>" />
		<input type="hidden" name="redirect_url" value="<?php echo $redirect_url; ?>" />
    </form>
</body>
</html>
