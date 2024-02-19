

$( document ).ready(function() {
    
    var parents = ['one', 'two', 'thr', 'for', 'fiv', 'six', 'sev', 'eig', 'nin']; 
    var parents_n = ['Recepción', 'Ingreso', 'Revisión', 'Diagnóstico','Repuesto', 'Reparación', 'Pruebas', 'Listo para Recojo', 'Entrega']; 
    
    var now_ul;
    var now_ulId;

    var now_li;
    var now_liId;
    var now_open;
    var now_ord;
    var new_ul;
    var new_full;

    var img; 
    
    var rpt_over = $('#rpt-ovr');
    var rpt_mesg = $('#rpt-msg');

    var add_form = $('#add-frm');

    var msg_ordn = $('#msg-ord');
    var msg_imag = $('#msg-img');

    var lst_time = $('#lst-tml');
    var itm_time = $('#itm-tml');

    var add_msg = $('#ky1-frm-msj');

    var err_msg = $('#errorDiv');

    var img_path = '<div class="img-flx"><img width="48" height="48" src="assets/img/';
    

    var today = new Date();
    var year = today.getFullYear();
    var month = String(today.getMonth() + 1).padStart(2, '0');
    var day = String(today.getDate()).padStart(2, '0');

    var fechaActual = year + '-' + month + '-' + day;

    $('#ky1-dte').val(fechaActual);
    $('#ky1-dte').val(fechaActual);


    $('.itm-upd').on('click', function() {

        rpt_over.fadeToggle();
        rpt_mesg.fadeToggle();
        $('.ky1-blr').toggleClass('blr-act');

        now_li = $(this).closest('li');
        now_liId = now_li.data('id');
        now_ord = now_li.data('ord');
        msg_ordn.text( now_liId );
        
        now_ul = now_li.closest('ul'); 
        now_ulId = now_ul.data('id')

        img =  img_path + (parents[now_ulId-1]) + '.svg"/><b>' + (parents_n[now_ulId-1]) + '</b></div>';
        img += img_path + 'r.svg"/></div>';
        img += img_path + (parents[now_ulId]) + '.svg"/><b>' + (parents_n[now_ulId]) + '</b></div>';
        
        msg_imag.html(img);

    });
    
    $('#msg-yes').on('click', function(e) {
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
        
    });
    
    $('#msg-nop').on('click', function() {
        rpt_over.fadeToggle();
        rpt_mesg.fadeToggle();
        $('.ky1-blr').toggleClass('blr-act');
    });
    
    $('#msg-cls').on('click', function() {
        rpt_over.fadeToggle();
        rpt_mesg.fadeToggle();
        $('.ky1-blr').toggleClass('blr-act');
    });
    
    $('.itm-cnt').on('click', function() {

        rpt_over.fadeToggle();
        $('.ky1-blr').toggleClass('blr-act');

        now_li = $(this).closest('li');
        now_liId = now_li.data('id');
        
        now_ul = now_li.closest('ul'); 
        now_ulId = now_ul.data('id')
        
        now_open = itm_time.find('ul[data-id="' + (now_ulId) + '"] li[data-id="' + (now_liId) + '"]');

        now_open.fadeToggle();
    });

    
    $('.itm-cls').on('click', function() {
        
        rpt_over.fadeToggle();
        now_open.fadeToggle();
        $('.ky1-blr').toggleClass('blr-act');

    });

    $('.flt-usr').on('click', function() {

        var usr_dat = $(this).data('usrf');
        $(this).toggleClass('flt-act');
        $('[data-usr="' + usr_dat + '"]').fadeToggle();
        
    });
   

    $('#ky1-add').on('click', function() {
        rpt_over.fadeToggle();
        add_form.fadeToggle();
        $('#ky1-ords').focus();
        $('.ky1-blr').toggleClass('blr-act');
    });

    $('#frm-cls').on('click', function() {
        rpt_over.fadeToggle();
        add_form.fadeToggle();
        $('.ky1-blr').toggleClass('blr-act');
    });

    $('#ky1-doc').on('blur', function() {
        const document = $(this).val();
    
        $.ajax({
            url: 'srcUser.php',
            method: 'POST',
            data: { document: document },
            dataType: 'json',
            success: function(data) {
                if (data.ky1ar) {
                    $('#ky1-nme').val(data.name);
                    $('#ky1-eml').val(data.email);
                    $('#ky1-phn').val(data.phone);
                    $('#ky1-cid').val(data.id);
                } else {
                    $('#ky1-nme').val('');
                    $('#ky1-eml').val('');
                    $('#ky1-phn').val('');
                    $('#ky1-cid').val('');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
            }
        });
    });

    $('#ky1-mch').keyup(function() {
        var machine = $(this).val();
        if (machine.length >= 2) {
            $.ajax({
                type: 'POST',
                url: 'srcMachine.php',
                data: { machine: machine },
                success: function(data) {
                    $('#ky1-sgs').html(data);
                    $('.frm-sgs').click(function() {
                        var sel = $(this).text();
                        var id = $(this).data('id');
                        var slug = $(this).data('slug');
                        $('#ky1-mch').val(sel);
                        $('#ky1-sgs').html('');
                        $("#ky1-mim").attr("src","assets/mac/" + slug + ".webp");
                        $('#ky1-mid').val(id);
                    });
                }
            });
        } else {
            $('#ky1-sgs').html('');
            $("#ky1-mim").attr("src","assets/img/def.webp");
            $('#ky1-mid').val('');
        }
    });

    $('#frm-nop').on('click', function() {
        rpt_over.fadeToggle();
        add_form.fadeToggle();
        $('.ky1-blr').toggleClass('blr-act');
    });
    

    $('#frm-log').submit(function(e) {
        e.preventDefault();
        var formData = $(this).serialize();
        err_msg.slideUp();

        $.ajax({
            type: 'POST',
            url: 'proLogin.php',
            data: formData,
            success: function(response) {
                var jsonData = JSON.parse(response);
                if (jsonData.success) {
                    window.location.href = 'grid';
                } else {
                    err_msg.text(jsonData.message).slideDown();
                }
            }
        });
    });

    $('#frm-cli').submit(function(e) {
        e.preventDefault();
        var orders   = $('#ky1-sor').val();
        var document = $('#ky1-sdc').val();
        err_msg.slideUp();

        if (orders.trim() !== '' && document.trim() !== '') {
            $.ajax({
                type: 'POST',
                url: 'proClient',
                data: {
                    orders: orders,
                    document: document
                },
                success: function(response) {
                    var jsonData = JSON.parse(response);
                    if (jsonData.success) {
                        window.location.href = 'order?number=' + orders;
                    } else {
                        err_msg.text(jsonData.message).slideDown();
                    }
                }
            });
        } else {
            // Mostrar un mensaje de error si los campos están vacíos
            err_msg.text('Por favor, completa ambos campos').slideDown();
        }
    });
    
     $('#ky1-src').on('input', function() {

        const text = $(this).val().toLowerCase(); 
        $('#lst-tml ul li:has(.ky1-sor)').each(function() {
            const headingText = $(this).find('.ky1-sor').text().toLowerCase();
            if (headingText.includes(text)) {
              $(this).removeClass('ky1-fcs');
            } else {
              $(this).addClass('ky1-fcs');
            }
          });

      });

    var tme_pday =  $('.tme-top');

    if( tme_pday ) {
        tme_pday.each(function() {
            var dat_pday = $(this).data('stt');
            var dat_prc = dat_pday*5;
            if( dat_prc > 100 ) dat_prc = 100;
            $(this).css('left', dat_prc +'%');
        });
    }

    $('.edt-yes').on('click', function(e) {
        e.preventDefault(); 
        
        var frm = $(this).closest('form');
        
        var orders = frm.find('.ky1-oid').val();
        var worker = frm.find('.ky1-wrk').val();
        var type = frm.find('.ky1-typ').val();
        var paid = frm.find('.ky1-pid').val();
        var origin = frm.find('.ky1-ori').val();
        
        $.ajax({
            url: 'updOrderData.php',
            method: 'POST',
            data: { orders: orders, worker: worker, type: type, paid: paid, origin: origin },
            success: function(response) {
                var jsonData = JSON.parse(response);
                if (jsonData.success) {
                    window.location.href = 'grid';
                } 
            }
        });
    });
    
     var header = $('#add-tml');
    var headerHeight = header.height();
    var lastScroll = 0;

    $(window).scroll(function() {
      var currentScroll = $(this).scrollTop();

        if (currentScroll > lastScroll) {
        // Scroll hacia abajo
        if (currentScroll > headerHeight) {
          header.addClass('ky1-fxd');
        }
      } else {
        // Scroll hacia arriba
        if (currentScroll <= headerHeight) {
          header.removeClass('ky1-fxd');
        }
      }

      lastScroll = currentScroll;
    });
    
    const previewInvoice = $("#previewInvoice");
    const invoiceFile = $("#invoiceFile");

    $(".pendingTable .preview").click(function() {
        let fileUrl = $(this).data('src');
        var fileExtension = fileUrl.split('.').pop().toLowerCase();

        if (fileExtension === 'pdf') {
            invoiceFile.html('<embed src="' + fileUrl + '" type="application/pdf" width="100%" height="500px" />');
        } else if (fileExtension.match(/(jpg|jpeg|png|gif)$/)) {
            invoiceFile.html('<img src="' + fileUrl + '" alt="Vista previa de la imagen" style="max-width: 100%; max-height: 500px;">');
        } else {
            invoiceFile.html('<p>El archivo no es compatible con la previsualización.</p>');
        }
        previewInvoice.show();
    });

    $("#previewInvoice .close").click(function() {
        previewInvoice.hide();
    });

    $(window).click(function(event) {
        if (event.target == previewInvoice[0]) {
            previewInvoice.hide();
        }
    });

    const aproveOverlay = $('#aproveOverlay');

    $(".actionButtons .aprove").click(function() {
        let selectedId = $(this).closest('.actionButtons').data('id');
        aproveOverlay.find('.aproveButtons').attr('data-id', selectedId);
        aproveOverlay.fadeToggle();
    });

    aproveOverlay.find('.modalClose, .modalCancel').click(function() {
        aproveOverlay.fadeToggle();
    });

    $(window).click(function(event) {
        if (event.target == aproveOverlay[0]) {
            aproveOverlay.fadeToggle();
        }
    });

    const aproveSubmit = $('#aproveSubmit');
    const aproveMessage = $('#aproveMessage');

    aproveSubmit.submit(function(event) {
        event.preventDefault();
        aproveMessage.slideUp();

        let scheduleId = aproveOverlay.find('.aproveButtons').data('id');
        let trainingWorker = $('#trainingWorker').val();
        let meet = $('#meet').val();

        if (!validatetrainingWorker(trainingWorker) || !validateMeet(meet)) {
            return;
        }

        let formData = new FormData();
        formData.append('scheduleId', scheduleId);
        formData.append('trainingWorker', trainingWorker);
        formData.append('meet', meet);

        $.ajax({
            url: 'updateSchedule',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                var jsonData = JSON.parse(response);
                if (jsonData.success) {
                    window.location.href = 'training';
                } else {
                    message(aproveMessage,jsonData.error);
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
    function validatetrainingWorker(trainingWorker) {
        if (trainingWorker.trim() === '') {
            message(aproveMessage, "Seleccione un responsable");
            return false;
        }
        return true;
    }
    function validateMeet(meet) {
        if (meet.trim() === '') {
            message(aproveMessage, "Ingrese un link de Google Meet");
            return false;
        }
        return true;
    }

    const rejectOverlay = $('#rejectOverlay');

    $(".actionButtons .reject").click(function() {
        let selectedId = $(this).closest('.actionButtons').data('id');
        rejectOverlay.find('.rejectButtons').attr('data-id', selectedId);
        rejectOverlay.fadeToggle();
    });

    rejectOverlay.find('.modalClose, .modalCancel').click(function() {
        rejectOverlay.fadeToggle();
    });

    $(window).click(function(event) {
        if (event.target == rejectOverlay[0]) {
            rejectOverlay.fadeToggle();
        }
    });

    const rejectSubmit = $('#rejectSubmit');
    const rejectMessage = $('#rejectMessage');

    rejectSubmit.submit(function(event) {
        event.preventDefault();
        rejectMessage.slideUp();

        let scheduleId = rejectOverlay.find('.rejectButtons').data('id');
        let rejectText = $('#rejectText').text();

        if (!validateRejectText(rejectText)) {
            return;
        }

        let formData = new FormData();
        formData.append('scheduleId', scheduleId);
        formData.append('rejectText', rejectText);

        $.ajax({
            url: 'rejectSchedule',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                var jsonData = JSON.parse(response);
                if (jsonData.success) {
                    window.location.href = 'training';
                } else {
                    message(rejectMessage,jsonData.error);
                }
            },
            error: function(xhr, status, error) {
                console.error("Error:", error);
            }
            
        });
    });
    function validateRejectText(rejectText) {
        if (rejectText.trim() === '') {
            message(rejectMessage, "El mensaje no puede quedar vacío.");
            return false;
        }
        return true;
    }
});


