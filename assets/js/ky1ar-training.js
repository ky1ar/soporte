$( document ).ready(function() {
    
    const calendarPrev = $('#calendarPrev');
    const calendarNext = $('#calendarNext');

    calendarPrev.click(function(){
        loadCalendar(-1);
    });

    calendarNext.click(function(){
        loadCalendar(1);
    });

    /*$('.hdr-mdl').click(function(){
        loadCalendar(0);
    });*/
    var date = new Date();
    date.setDate(1);
    var months = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];
   // var firstDayNum = date.getDay() + 1; 

    function loadCalendar(offset) {
        var today = new Date();
        var offsetMoth = date.getMonth() + offset;
        if (offsetMoth < today.getMonth() || offsetMoth > (today.getMonth() + 2)) {
            return;
        }

        date.setMonth(date.getMonth() + offset);

        var formatedDate = date.getFullYear() + '-' + ('0' + (date.getMonth() + 1)).slice(-2) + '-' + ('0' + date.getDate()).slice(-2);
        var splitDate = formatedDate.split('-');
        var month = splitDate[1];
        var month = months[parseInt(month, 10) - 1];
        
        console.log(formatedDate);
        /*$.ajax({
            url: 'reload',
            method: 'POST',
            data: { fecha: formatedDate },
            success: function(response) {
                $('.lst-dat').html(response);
                $('.hdr-mdl').text(t_date);
                $('.hdr-mdl').attr('data-day', formatedDate);
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });*/
    }

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


