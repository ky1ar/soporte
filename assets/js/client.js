$( document ).ready(function() {
    
    const searchOrderMessage = $('#searchOrderMessage');
    const searchOrder = $('#searchOrder');

    function message(target,message) {
        target.text(message).slideDown();
    }

    searchOrder.submit(function(event) {
        event.preventDefault();
        searchOrderMessage.slideUp();

        var orderNumber   = $('#orderNumber').val();
        var document = $('#document').val();
        
        if (!validateorderNumber(orderNumber) || !validateDocument(document)) {
            return;
        }

        $.ajax({
            type: 'POST',
            url: 'routes/searchOrder',
            data: {
                orderNumber: orderNumber,
                document: document
            },
            success: function(response) {
                var jsonData = JSON.parse(response);
                if (jsonData.success) {
                    window.location.href = 'order?number=' + orderNumber;
                } else {
                    message(searchOrderMessage,jsonData.message);
                }
            },
            error: function(xhr, status, error) {
                console.error("Error:", error);
            }
        });

    });
    
    /* Validations */

    function validateDocument(document) {
        if (!(document.length === 8 || document.length === 11) || !(/^\d+$/.test(document))) {
            message(scheduleFormMessage, "Ingrese un documento válido");
            return false;
        }
        return true;
    } 
    function validateorderNumber(orderNumber) {
        if (orderNumber.trim() === '' || !(/^\d+$/.test(orderNumber))) {
            message(scheduleFormMessage, "Ingrese un número de orden válido");
            return false;
        }
        return true;
    }
});


