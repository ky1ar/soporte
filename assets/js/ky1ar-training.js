$( document ).ready(function() {
    
    const calendarPrev = $('#calendarPrev');
    const calendarNext = $('#calendarNext');
    
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

    const calendarNavigation = $('#calendarNavigation');
    const calendarBackDiv = $('#calendarBackDiv');
    const calendarBack = $('#calendarBack');

    const calendarSelector = $('#calendarSelector');
    const scheduleSelector = $('#scheduleSelector');
    const scheduleForm = $('#scheduleForm');

    calendarBack.click(function(){
        scheduleSelector.hide();
        calendarBackDiv.hide();
        scheduleForm.hide();

        calendarSelector.show();
        calendarNavigation.show();
    });

    const calendarTable = $('#calendarTable');
    const monthName = $('#monthName');
    
    function loadCalendar(offset) {

        currentDate.setMonth(currentDate.getMonth() + offset);
        currentDate.setDate(1);

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

    const selectedSchedule = $('#selectedSchedule');
    const selectedDate = $('#selectedDate');

    $(document).on('click', '.boxSchedule', function() {
        let date = selectedDate.data('date');
        let schedule = $(this).data('schedule');
        selectedSchedule.text(date + ' a las ' + schedule);
        scheduleSelector.hide();
        scheduleForm.show();
    });
    
    const dniRUC = $('#dniRUC');

    dniRUC.on('blur', function() {
        let dniRucVal = $(this).val();
       
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

    const machine = $('#machine');
    const suggestions = $('#suggestions');
    const machineImage = $('#machineImage');
    const machineId = $('#machineId');

    machine.keyup(function() {
        let machineVal = $(this).val();
        console.log(machineVal);
        if (machineVal.length >= 2) {
            $.ajax({
                url: 'loadMachine',
                type: 'POST',
                data: { machineVal: machineVal },
                success: function(data) {
                    suggestions.html(data);
                    $('.suggestionsRow').click(function() {
                        let sel = $(this).text();
                        let id = $(this).data('id');
                        let slug = $(this).data('slug');
                        machine.val(sel);
                        suggestions.html('');
                        machineImage.attr("src","assets/mac/" + slug + ".webp");
                        machineId.val(id);
                    });
                }
            });
        } else {
            suggestions.html('');
            machineImage.attr("src","assets/img/def.webp");
            machineId.val('');
        }
    });

    /*$('#msg-yes').on('click', function(e) {
        e.preventDefault();
        var notes = $('#msg-cmm').val();
        var changer = $('#msg-chn').val();
        var check = $('#msg-chk').prop('checked');
        
        $.ajax({
            url: 'updOrder.php',
            method: 'POST',
            data: { now_ord: now_ord, now_stt: now_ulId, notes: notes, changer: changer, check: check },
            success: function(response) {
                var jsonData = JSON.parse(response);
                if (jsonData.success) {
                    window.location.href = 'grid';
                } else {
                    err_msg.text(jsonData.message);
                }
            }
        });
        
    });*/
});


