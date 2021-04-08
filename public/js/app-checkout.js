(function(){

    var Total = Number($("#totalFee").val());
    var subtotal = Number($("#subtotal").val());

    const DATA_URL = "/get-shipping-cost-fee";
    const DATA_METHOD = "POST";

    $(".shaddress").change(function()
    {
        if($(this).prop("checked") == true) 
        {
            var state = $(this).attr("atr-state");
            if(state != undefined)
            {
                $.ajax({url:DATA_URL, method: DATA_METHOD, data: {state},
                    beforeSend: function() {
                        $(".formloader").fadeIn();
                    },
                    success: function(response) {
                        if(response != null) 
                        {
                            response = JSON.parse(response)
                            if(response.success) {
                                Total = Number((response.data.fee + subtotal).toFixed(2));
                                $("#shippingFee").html(formatCurrency(response.data.fee));
                                $("#tAmount").html(formatCurrency(Total));
                                $("#PayAmountCard").html(formatCurrency(Total));
                                $("#PayAmountWallet").html(formatCurrency(Total));
                            }
                            else if(response.error) {
            
                            }
                        }

                        $(".formloader").fadeOut();
                    }
                });
            }
        }
    });

    $("#state").change(function(){
        var state = $(this).val();

        if(state != "") {
            $.ajax({url:DATA_URL, method: DATA_METHOD, data: {state},
                beforeSend: function() {
                    $(".formloader").fadeIn();
                },
                success: function(response) {
                    if(response != null) 
                    {
                        response = JSON.parse(response)
                        if(response.success) {
                            Total = Number((response.data.fee + subtotal).toFixed(2));
                            $("#shippingFee").html(formatCurrency(response.data.fee));
                            $("#tAmount").html(formatCurrency(Total));
                            $("#PayAmountCard").html(formatCurrency(Total));
                            $("#PayAmountWallet").html(formatCurrency(Total));
                        }
                        else if(response.error) {
        
                        }
                    }

                    $(".formloader").fadeOut();
                }
            });
        }

    });

    function formatCurrency(amount, format = 'ng-NG') {
        var $nF = new Intl.NumberFormat(format, {  }).format(amount);
        if($nF.indexOf(".") == -1) {
            $nF = $nF + ".00";
        }
        return $nF;
    }

    $("#py_card").change(function(){
        if($(this).prop("checked") == true) {
            $("#CardName").attr("validate-field", "true").attr("validation-message", "Card holder name is required *");
            $("#CardNumber").attr("validate-field", "true").attr("validation-message", "Card number is required *");
            $("#CardPin").attr("validate-field", "true").attr("validation-message", "Card pin is required *");
            $("#ExpDate").attr("validate-field", "true").attr("validation-message", "Card expiry date is required *");
            $("#CVV").attr("validate-field", "true").attr("validation-message", "CVV is required *");
        }
    });

    $("#PayAmountCard").html(formatCurrency(Total));

    $("#py_wallet").change(function(){
        if($(this).prop("checked") == true) 
        {
            $("#CardName").removeAttr("validate-field validation-message");
            $("#CardNumber").removeAttr("validate-field validation-message");
            $("#CardPin").removeAttr("validate-field validation-message");
            $("#ExpDate").removeAttr("validate-field validation-message");
            $("#CVV").removeAttr("validate-field validation-message");

            $.ajax({url: "/secure/checkout/get-wallet-balance", method:"GET", data:{Total},
                success: function(response) {
                    if(response != null) {
                        response = JSON.parse(response);
                        if(response.success) {
                            $("#WalletBalance").html(formatCurrency(response.wallet.balance));
                            if(response.insufficient) {
                                $(".error-box").html('<div class="alert alert-custom alert-outline-2x alert-outline-danger fade show mb-5" role="alert">\
                                    <div class="alert-icon"><i class="flaticon-warning"></i></div>\
                                    <div class="alert-text mt-1">\
                                        <span>Insuffient fund in your wallet. Top up your wallet to complete this order.\
                                        <a href="/uac/wallet" class="text-underline" target="_blank"> Top up here</</span>\
                                    </div>\
                                </div>');
                            }
                        }
                    }
                }
            });

            $("#PayAmountWallet").html(formatCurrency(Total));
        }
    });

    // const PAYMENT_URL = "/secure/checkout/card/payment";

    // $("#SCForm").submit(function(event)
    // {
    //     event.preventDefault();

    //     var SCForm = $(this);
    //     var validation = true;

    //     $(this).children().find('[validate-field="true"]').each(function () {
    //         $(this).css("border-color", "#dcdcdc").siblings('[validation-result="true"]').remove();

    //         if ($(this).val() == "") {
    //             var message = $(this).attr("validation-message");
    //             $(this).css("border-color", "red").after('<small class="text-danger" validation-result="true">' + message + '</small>');
    //             validation = false;
    //         }
    //     });


    //     if (validation) 
    //     {
    //         var data = new FormData(this),
    //             url = SCForm.attr("action"),
    //             method = SCForm.attr("method");

    //         var ExpDate = $("#ExpDate").val().split("/");
    //             ExpMonth = ExpDate[0];
    //             ExpYear = ExpDate[1];

    //             data.append("ExpMonth", ExpMonth);
    //             data.append("ExpYear", ExpYear);

    //         $.ajax({ url, method, data, cache: false, contentType: false, processData: false,
    //             beforeSend: function() {
    //                 $(".checkoutloader").fadeIn();
    //                 $("#checkStatus").html('<small>Processing Order..</small>');
    //             },
    //             success: function(response) {
    //                 // console.log(response);
    //                 if(response != null) {
    //                     response = JSON.parse(response);

    //                     if(response.success == true) {
    //                         if(response.data.payment == "Card") {
    //                             data = response.data.card;
    //                             $.ajax({ url: PAYMENT_URL, method, data,
    //                                 beforeSend: function() {
    //                                     $("#checkStatus").html('<small>Initiating Payment..</small>');
    //                                 },
    //                                 success: function(response) {
    //                                     if(response != null) {
    //                                         response = JSON.parse(response);
    //                                         // console.log(response);
    //                                         if(response.success) {
    //                                             ValidateTokenForm();
    //                                         }
    //                                         else if(response.error) {
    //                                             ErrorAlert(response.error.message);
    //                                         }
    //                                     }
    //                                 }
    //                             });

    //                         }

    //                     }
    //                     else if(response.error) {
    //                         ErrorAlert(response.error.message);
    //                     }
    //                 }
    //             }
    //         });
    //     }
    // });

    // function ValidateTokenForm() {
    //     $("#CheckInnerContent").html('<form id="ValidateTokenForm" class="checkout-form">\
    //         <div class="text-center form-group"><h3>Validate OTP</h3></div>\
    //         <div class="text-center form-group">\
    //             <input type="text" name="OTP" id="OTP" class="form-control" autocomplete="off">\
    //         </div>\
    //         <div class="text-center form-group">\
    //             <button class="btn btn-primary border-50">Validate</button>\
    //         </div>\
    //     </form>');
        
    //     const TOKEN_URL = "/secure/checkout/token/validation";

    //     $("#ValidateTokenForm").submit(function(event){

    //         event.preventDefault();
    //         $("OTP").siblings("small").remove();

    //         var OTP = $("#OTP").val();
    //         if(OTP == "") {
    //             $("#OTP").after('<small class="text-danger">Kindly enter a valid otp to continue.</small>');
    //         }
    //         else {
    //             $.ajax({ url: TOKEN_URL, method: "POST", data: {_token:OTP}, cache: false, contentType: false, processData: false,
    //                 beforeSend: function(){
                        
    //                 },
    //                 success: function(response) {
    //                     // console.log(response);
    //                     if(response != null) {
    //                         response = JSON.parse(response);

    //                         if(response.success) {

    //                         }
    //                     }
    //                 }
    //             });
    //         }
    //     });
    // }

    // function ErrorAlert(message) {
    //     $("#CheckInnerContent").html('<div class="alert alert-custom alert-outline-2x alert-outline-danger fade show mb-5" role="alert">\
    //         <div class="alert-icon"><i class="flaticon-warning"></i></div>\
    //         <div class="alert-text mt-1">\
    //             <span>' + message + '</span>\
    //         </div>\
    //     </div>');
    // }

})();