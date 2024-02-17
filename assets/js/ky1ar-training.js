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
                var jsonData = JSON.parse(response);
                scheduleSelector.html(jsonData.html);
                calendarNavigation.hide();
                calendarBackDiv.show();
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    });

    const selectedSchedule = $('#selectedSchedule');
    const picked = $('#picked');

    $(document).on('click', '.boxSchedule', function() {
        const selectedData = $('#selectedData');
        const dateAvailable = $('#dateAvailable');
        let day = selectedData.data('day');
        let date = selectedData.data('date');
        let count = dateAvailable.val();
        let id = $(this).data('id');
        let schedule = $(this).data('schedule');

        selectedSchedule.text(day + ' - ' + schedule);
        picked.val(date);
        picked.attr('data-id', id);
        picked.attr('data-count', count);

        scheduleSelector.hide();
        scheduleForm.show();
    });

    const machine = $('#machine');
    const suggestions = $('#suggestions');
    const machineImage = $('#machineImage');
    const machineId = $('#machineId');

    machine.keyup(function() {
        let machineVal = $(this).val();
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

    const scheduleSubmit = $('#scheduleSubmit');
    const scheduleFormMessage = $('#scheduleFormMessage');

    scheduleSubmit.submit(function(event) {
        event.preventDefault();
        scheduleFormMessage.slideUp();

        let selectedDate = picked.val();
        let scheduleId = picked.data('id');
        let count = picked.data('count');
        
        var dniRUC = $('#dniRUC').val();
        var client = $('#client').val();
        let email = $('#email').val();
        let phone = $('#phone').val();
        let machine = machineId.val();
        let invoice = $('#invoice')[0].files[0];

        if (!validateDocument(dniRUC) || !validateClient(client) || !validateEmail(email) || !validatePhone(phone) || !validateInvoice(invoice) || !validateMachineId(machine)) {
            return;
        }

        let formData = new FormData();
        formData.append('scheduleId', scheduleId);
        formData.append('selectedDate', selectedDate);
        formData.append('count', count);
        formData.append('dniRUC', dniRUC);
        formData.append('client', client);
        formData.append('email', email);
        formData.append('phone', phone);
        formData.append('machineId', machine);
        formData.append('invoice', invoice);

        $.ajax({
            url: 'registerSchedule',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                var jsonData = JSON.parse(response);
                if (jsonData.success) {
                    window.location.href = 'grid';
                } else {
                    message(scheduleFormMessage,jsonData.message);
                }
            },
            error: function(xhr, status, error) {
                console.error("Error:", error);
            }
            
        });
    });

    function message(target,message) {
        target.text(message).slideDown();
    }

    function validateDocument(dniRUC) {
        if (dniRUC.length !== 8 && dniRUC.length !== 11 || !(/^\d+$/.test(dniRUC))) {
            message(scheduleFormMessage, "Ingrese un documento válido");
            return false;
        }
        return true;
    }
    function validateClient(client) {
        if (client.trim() === '') {
            message(scheduleFormMessage, "El campo del nombre no puede estar vacío");
            return false;
        }
        return true;
    }
    function validateEmail(email) {
        let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            message(scheduleFormMessage, "Ingrese un correo electrónico válido");
            return false;
        }
        return true;
    }
    function validatePhone(phone) {
        var telefonoRegex = /^\d+$/;
        if (!telefonoRegex.test(phone)) {
            message(scheduleFormMessage, "Ingrese un número de teléfono válido");
            return false;
        }
        return true;
    }
    function validateMachineId(machineId) {
        if (machineId.trim() === '') {
            message(scheduleFormMessage, "Seleccione un equipo");
            return false;
        }
        return true;
    }
    function validateInvoice(invoice) {
        if (!invoice) {
            message(scheduleFormMessage, "Seleccione un archivo");
            return false;
        }
        let fileType = invoice.type;
        if (fileType !== 'application/pdf' && !fileType.startsWith('image/')) {
            message(scheduleFormMessage, "El archivo debe ser PDF o una imagen");
            return false;
        }
        return true;
    }

   
});


