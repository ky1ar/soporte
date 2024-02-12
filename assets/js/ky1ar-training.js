$( document ).ready(function() {
    
    const calendarPrev = $('#calendarPrev');
    const calendarNext = $('#calendarNext');
    const calendarNavigation = $('#calendarNavigation');
    const calendarBackDiv = $('#calendarBackDiv');
    const calendarBack = $('#calendarBack');
    //const calendarToday = $('#calendarToday');

    const calendarTable = $('#calendarTable');
    const monthName = $('#monthName');

    const calendarSelector = $('#calendarSelector');
    const scheduleSelector = $('#scheduleSelector');
    const scheduleForm = $('#scheduleForm');

    var currentDate = new Date();
    var today = new Date();

    var months = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];

    calendarPrev.click(function(){
        var offsetMonth = currentDate.getMonth() - 1;
        if (offsetMonth > today.getMonth()) {
            loadCalendar(-1);
            calendarNext.removeClass('disabled');
        } else if (offsetMonth == today.getMonth()) {
            loadCalendar(-1);
            $(this).addClass('disabled');
        }
    });

    calendarNext.click(function(){
        var offsetMonth = currentDate.getMonth() + 1;
        var maxMonth = today.getMonth() + 2;
        if (offsetMonth < maxMonth) {
            loadCalendar(1);
            calendarPrev.removeClass('disabled');
        } else if (offsetMonth == maxMonth) {
            loadCalendar(1);
            $(this).addClass('disabled');
        }
    });

    calendarBack.click(function(){
        scheduleSelector.hide();
        calendarBackDiv.hide();
        scheduleForm.hide();

        calendarSelector.show();
        calendarNavigation.show();
    });

    /*calendarToday.click(function(){
        loadCalendar(0);
        calendarPrev.addClass('disabled');
        calendarNext.removeClass('disabled');
    });*/
    
    function loadCalendar(offset) {

        currentDate.setMonth(currentDate.getMonth() + offset);

        var formatedDate = currentDate.getFullYear() + '-' + ('0' + (currentDate.getMonth() + 1)).slice(-2) + '-' + ('0' + currentDate.getDate()).slice(-2);
        var splitDate = formatedDate.split('-');
        var month = splitDate[1];
        var month = months[parseInt(month, 10) - 1];
        var firstDayNum = currentDate.getDay();
        console.log(firstDayNum);
        $.ajax({
            url: 'loadCalendar',
            method: 'POST',
            data: { 
                date: formatedDate,
                day: firstDayNum 
            },
            success: function(response) {
                calendarTable.html(response);
                monthName.text(month + ' ' + currentDate.getFullYear());
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    }
    
    $(document).on('click', '.boxDay', function() {
        
        calendarSelector.hide();
        scheduleSelector.show();

        var dayNumber = $(this).attr('data-day');
        var temporal = currentDate;
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
                calendarNavigation.hide();
                calendarBackDiv.show();
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    });

    $(document).on('click', '.boxSchedule', function() {
        
        scheduleSelector.hide();
        scheduleForm.show();

        /*$.ajax({
            url: 'scheduleForm',
            method: 'POST',
            data: { 
                currentDate: formatedDate
            },
            success: function(response) {
                scheduleSelector.html(response);
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });*/
    });
    
    const dniRUC = $('#dniRUC');
    dniRUC.on('blur', function() {
        const dniRucVal = $(this).val();
        console.log(dniRucVal);
        $.ajax({
            url: 'loadUser',
            method: 'POST',
            data: { dniRucVal: dniRucVal },
            dataType: 'json',
            success: function(data) {
                if (data.ky1ar) {
                    $('#client').val(data.client);
                    $('#email').val(data.email);
                    $('#phone').val(data.phone);
                    $('#clientId').val(data.clientId);
                } else {
                    $('#client').val('');
                    $('#email').val('');
                    $('#phone').val('');
                    $('#clientId').val('');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
            }
        });
    });

});


