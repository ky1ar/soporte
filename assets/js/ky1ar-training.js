$( document ).ready(function() {
    
    const calendarPrev = $('#calendarPrev');
    const calendarNext = $('#calendarNext');
    const calendarToday = $('#calendarToday');
    const calendarTable = $('#calendarTable');
    const monthName = $('#monthName');

    var date = new Date();
    var today = new Date();
    date.setDate(1);
    var months = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];

    calendarPrev.click(function(){
        var offsetMonth = date.getMonth() - 1;
        if (offsetMonth > today.getMonth()) {
            loadCalendar(-1);
            calendarNext.removeClass('disabled');
        } else if (offsetMonth == today.getMonth()) {
            loadCalendar(-1);
            $(this).addClass('disabled');
        }
    });

    calendarNext.click(function(){
        var offsetMonth = date.getMonth() + 1;
        var maxMonth = today.getMonth() + 2;
        if (offsetMonth < maxMonth) {
            loadCalendar(1);
            calendarPrev.removeClass('disabled');
        } else if (offsetMonth == maxMonth) {
            loadCalendar(1);
            $(this).addClass('disabled');
        }
    });

    calendarToday.click(function(){
        loadCalendar(0);
        calendarPrev.addClass('disabled');
        calendarNext.removeClass('disabled');
    });
    
    function loadCalendar(offset) {

        if ( offset == 0) {
            date.setMonth(today.getMonth());
        } else {
            date.setMonth(date.getMonth() + offset);
        }
       
        var formatedDate = date.getFullYear() + '-' + ('0' + (date.getMonth() + 1)).slice(-2) + '-' + ('0' + date.getDate()).slice(-2);
        var splitDate = formatedDate.split('-');
        var month = splitDate[1];
        var month = months[parseInt(month, 10) - 1];
        var firstDayNum = date.getDay();

        $.ajax({
            url: 'loadCalendar',
            method: 'POST',
            data: { 
                date: formatedDate,
                day: firstDayNum 
            },
            success: function(response) {
                calendarTable.html(response);
                monthName.text(month + ' ' + date.getFullYear());
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    }

    const calendarDiv = $('#calendarDiv');
    const scheduleSelector = $('#scheduleSelector');

    $(document).on('click', '.boxDay', function() {
        
        calendarDiv.slideUp();
        scheduleSelector.slideDown();

        var dayNumber = $(this).attr('data-day');
        var temporal = date;
        temporal.setDate(dayNumber);
        var formatedDate = temporal.getFullYear() + '-' + ('0' + (temporal.getMonth() + 1)).slice(-2) + '-' + ('0' + temporal.getDate()).slice(-2);

        $.ajax({
            url: 'loadSchedule',
            method: 'POST',
            data: { 
                date: formatedDate
            },
            success: function(response) {
                scheduleSelector.html(response);
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    });

});


