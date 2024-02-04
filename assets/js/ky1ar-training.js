$( document ).ready(function() {
    

    $('.ky1-slc-day').on('click', function(e) {
        e.preventDefault(); 
        
        $('.cap-cld').hide();
        $('.ky1-slc-hou').show();
        
        var frm = $(this).closest('form');
        
        var orders = frm.find('.ky1-oid').val();
        var worker = frm.find('.ky1-wrk').val();
        var type = frm.find('.ky1-typ').val();
        var paid = frm.find('.ky1-pid').val();
        var origin = frm.find('.ky1-ori').val();
        
        /*$.ajax({
            url: 'updOrderData.php',
            method: 'POST',
            data: { orders: orders, worker: worker, type: type, paid: paid, origin: origin },
            success: function(response) {
                var jsonData = JSON.parse(response);
                if (jsonData.success) {
                    window.location.href = 'grid';
                } 
            }
        });*/
    });

    
});


