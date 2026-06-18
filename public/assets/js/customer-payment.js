const confirmPaymentBtn = document.getElementById('confirm_payment');
$(document).ready(function() {
    let toValidate = $('#transaction_amount_paid');
    let valid = false;
    toValidate.keyup(function () {
        if ($(this).val().length > 0) {
            $(this).data('valid', true);
        } else {
            $(this).data('valid', false);
        }
        toValidate.each(function () {
            if ($(this).data('valid') == true) {
                valid = true;
            } else {
                valid = false;
            }
        });
        if (valid === true) {
            $('#process_payment').prop('disabled', false);
        }else{
            $('#process_payment').prop('disabled', true);        
        }
    });
    $(document).on('click', '.pay-transaction', function(){
        $('#transaction_customer_id').val($(this).data('customer_id'));
        $('#customer_name_payment').val($(this).data('customer_name'));
        $('#transcaction_balance').val($(this).data('balance'));
        $('#transaction_id').val($(this).data('transaction_id'));
        
        $('#paymentModal').modal('show');
    });
    $(document).on('submit', '#submit_payment', function(e){
        e.preventDefault();
        $('#confirmPaymentModal').modal('show');
    });
});

confirmPaymentBtn.addEventListener('click', function(){
    $('#process_transaction_payment').prop('disabled', true);
    $('#confirm_transaction_payment').prop('disabled', true);
    $('#confirm_transaction_payment').attr("data-kt-indicator", "on");
    $.ajax({
        type: 'post',
        url: '/panel/cashier/payments/single/process',
        data: {
            '_token': $('input[name=_token]').val(),
            'amount_paid': $('input[name=transaction_amount_paid]').val(),
            'balance_amount': $('input[name=transcaction_balance]').val(),
            'transaction_id': $('input[name=transaction_id]').val(),
            'customer_id': $('input[name=transaction_customer_id]').val(),
        },

        success: function(data) {
            window.location.replace("/panel/cashier/payments/view/"+data.id);
        },
        error: function(data){
            const errorContainer = document.getElementById('payment_errors');
            let errors = data.responseJSON.errors;
            let errormessage = '';
            Object.keys(errors).forEach(function(key) {
                errormessage += errors[key] + '<br />'; 
            });
            errorContainer.innerHTML = ` <div class="alert alert-danger" role="alert"> ${errormessage} </div>`;
            errorContainer.hidden = false;
        }
    });
});