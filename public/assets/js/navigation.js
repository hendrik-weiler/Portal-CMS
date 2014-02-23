$(function() {

    var del = new pcms.dialog($('.icon.delete'), {
        title : _prompt.header,
        text : _prompt.text,
        confirm: _prompt.ok,
        cancel  : _prompt.cancel
    });
    del.onConfirm = function(dialog_helper, event)    {
        var id = $(del.initiator).attr('data-id');
        window.location.href = _url + '/admin/navigation/delete/' + id;
    }
    del.render();

    var del = new pcms.dialog($('.icon.delete-group'), {
        title : _prompt.header,
        text : _prompt.text,
        confirm: _prompt.ok,
        cancel  : _prompt.cancel
    });
    del.onConfirm = function(dialog_helper, event)    {
        var id = $(del.initiator).attr('data-id');
        dialog_helper.post_data(_url + 'admin/navigation/group/delete', {
            id : id
        }, function(data) {
            if(data=='true') {
                dialog_helper.cancel_dialog();
                window.location.reload();
            }
        });
    }
    del.render();

    $('.open-navigationmenu').click(function() {
       var src = $(this).find('img').attr('src');
       if($('.navigationmenu').css('display') == 'block') {
           $('.navigationmenu').hide();
           $(this).find('img').attr('src', src.replace('left','right'));
           $('.navigationmenu-opened').hide();
           $('.navigationmenu-closed').show();

       } else {
           $(this).find('img').attr('src', src.replace('right','left'));
           $('.navigationmenu').show();
           $('.navigationmenu-opened').show();
           $('.navigationmenu-closed').hide();
       }
    });
    $('.navigationmenu').hide();
    $('.navigationmenu-opened').hide();
    $('.navigationmenu-closed').show();

    var maxHeight = [];
    $.each($('.globalmenu'), function(key, obj) {
        maxHeight.push($(obj).height());
    });
    maxHeight = maxHeight.sort(function(a,b) {
        return a - b;
    });
    var height = maxHeight[maxHeight.length - 1];
    $('.open-navigationmenu').height(height);
    $('.open-navigationmenu img').css('marginTop', height/2 - 7);

    $('#navigation_list').sortable({
        update: function(event, ui) {
            var data = [];
            $.each($('#navigation_list li'),function(key,value) {
                data[key] = $(this).attr('id');
            });

            $.post(_url + 'admin/navigation/order/update',{'order' : data,submenu: false});
        }
    });

    $('#navigation_list2').sortable({
        update: function(event, ui) {
            var data = [];
            $.each($('#navigation_list2 li'),function(key,value) {
                data[key] = $(this).attr('id');
            });

            $.post(_url + 'admin/navigation/order/update',{'order' : data,submenu: true});
        }
    });

    $('.icon.move').click(function(e) {
        e.preventDefault();
        return false;
    });

    var group_edit_mode = [];
    var group_edit_id = [];
    $('.icon.edit-group').click(function(e) {
        e.preventDefault();

        var label = $(this).parent().parent().find('.options-label');
        var id = label.attr('data-id');
        var src = $(this).find('img').attr('src');

        if($.type(group_edit_mode[id]) == 'undefined'){
            group_edit_mode[id] = false;
        }

        if(group_edit_mode[id] == false) {

            $(this).find('img').attr('src',src.replace('edit','save'));

            group_edit_id[id] = id;
            var a = label.find('a').clone();
            label.data('a',a);

            var input = $('<input>',{'type':'text'}).val(a.text());
            label.empty();
            label.append(input);
            input.focus();

            group_edit_mode[id] = true;

        } else {

            $(this).find('img').attr('src',src.replace('save','edit'));

            var a = label.data('a');
            var newname = label.find('input').val();
            a.text(newname);
            label.empty();
            label.append(a);
            group_edit_mode[id] = false;

            $.post(_url + 'admin/navigation/group/edit',{'id' : group_edit_id[id], 'name':newname}, function(result) {

            });
        }

        return false;
    });
});