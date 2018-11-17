var pageObj = {
    blockClick: false,
    counterIsOn: false
};

var sceditoroptions = {
    emoticonsEnabled: false,
    plugins: "bbcode",
    style: "/assets/templates/game/default/sceditor.css",
    toolbar: 'bold,italic,underline|left,center,right|image,link,color|quote|source',
    autoUpdate: true,
    id: 'sceditor_frame'
};

function checkenter(event, form) {
    if (event.keyCode == 13 && document.getElementById('enterCheckbox').checked) {
        $(form).submit();
    }
}

/*
 * settings = {'formName': 'name', 'postData' => {}}
 * postData - nadpisuje dane z forma
 */
function ajaxLoad(url, settings, blockClick, callback) {
    var postData = {};
    if (settings.formName) {
        var dane = $("#" + settings.formName).serializeArray();
        for (var i in dane) {
            var n = dane[i].name;
            var v = dane[i].value;
            if (typeof postData[n] === 'undefined') {
                if (n.match(/\[(.*)\]/)) {
                    postData[n] = [];
                    postData[n].push(v);
                }
                else postData[n] = v;
            }
            else {
                postData[n].push(v);
            }
        }
    }
    if (settings.postData) {
        postData = settings.postData;
    }

    if (pageObj.blockClick === false) {
        if (blockClick) pageObj.blockClick = true;
        $.ajaxq('default', {
            type: "POST",
            dataType: "json",
            url: url,
            data: postData,
            success: function (data) {
                if (data.ajax && data.ajax.error) {
                    jAlert(data.ajax.error);
                    pageObj.blockClick = false;
                }
                else {
                    pageObj.blockClick = false;
                    if (callback) callback(data);
                }
            },
            error: function () {
                pageObj.blockClick = false;
            }
        }).fail(function () {
            if (callback) callback('failed');
        });
    }
}

/* Custom google map start function */
function additionMapStart() {
    google.maps.event.addListener(map, "click", addPoint);
}

function openAjaxModal(url, modalName, templateName, callback) {
    $.ajaxq('standard', {
        url: url,
        context: document.body
    }).done(function (data) {
        if (data.contents.error) {
            jAlert(data.contents.error);
        }
        else {
            var template = $.templates(templateName);
            var htmlOutput = template.render(data.contents);
            $(modalName + " .modal-body").html(htmlOutput);
            $(modalName + " .modal-title").html(data.contents.modalTitle);
            $(modalName + " #modal-save-button").attr("onclick", "document.getElementById('modalForm').submit();");
            // ajaxLoad('"+data.contents.modalConfirm+"', {'formName': 'modalForm'}, true, function() {})

            $(modalName).modal({backdrop: false});
            if (callback) callback(data);
        }
    });
}

function fenixrefres() {
    ajaxLoad('/game/refresh/data', {postData: {fenixengine: fenixengine}}, false, function (data) {
        var nmsg = $('.interface_new_msg');
        if (data.newmsg > 0) nmsg.html(data.newmsg).show();
        else if (data.newmsg == 0) nmsg.html(0).hide();
        var nlogs = $('.interface_new_logs');
        if (data.newlogs > 0) nlogs.html(data.newlogs).show();
        else if (data.newlogs == 0) nlogs.html(0).hide();

        if (data.chat) {
            $('#chat_desc').html(data.chat.desc);
            if (data.chat.posts) {
                var chat_messages = $('#chat_display_msg');
                $.each(data.chat.posts, function (index, value) {
                    chat_messages.prepend('<div msg="' + value.id + '" class="chat_msg row_' + value.id + '"> <img src="' + value.avatar + '" style="width: 50px; float: left; margin: 10px;" /> <div style="margin-top: 10px;">' + value.date + ' - <a href="/game/profile/show/' + value.writer + '">' + value.name + '</a> ' + (value.candel == 1 ? '<i class="fa fa-times candel"></i>' : '') + '</div> <div class="chat-message">' + value.msg + '</div> </div>');
                });
            }
            if (data.chat.users) {
                var chaters = $('#chat_chaters');
                chaters.html('');
                $.each(data.chat.users, function (index, value) {
                    chaters.append('<li class="chat_chater"><a  href="/game/profile/show/' + value.id + '">' + value.name + '</a> </li>');
                });
            }
        }
        else if (data.index) {

        }

        if (data.ego) {
            if (data.ego.forumnoreads) $('.notifications-menu span').html(data.ego.forumnoreads);

            if (data.ego.charsonline)
            {
                var characters_online = $('#characters_online');
                characters_online.html('');
                var count = 0;
                $.each(data.ego.charsonline, function(index, value) {
                    characters_online.append('<div style="float: left;width: 100%; margin-bottom: 5px; text-align: left;"><i class="fa fa-angle-right"></i> <a href="/game/profile/show/'+value.id+'">'+value.name+' ['+value.acc+']</a></div>');
                    count = count + 1;
                });
                $('#characters_online_count').html(count);
            }
        }
    });
}

function startTime() {
    var today = new Date();
    var h = today.getHours();
    var m = today.getMinutes();
    var s = today.getSeconds();
    m = checkTime(m);
    s = checkTime(s);
    document.getElementById('clock').innerHTML = h + ":" + m + ":" + s;
    var t = setTimeout(startTime, 500);
}
function checkTime(i) {
    if (i < 10) {i = "0" + i};  // add zero in front of numbers < 10
    return i;
}
function additionMapStart(points) {

}

$(function () {
    // start clock
    if ($('#clock').length > 0) startTime();

    $('#openmap').click(function(e) {
        e.preventDefault();

        if ($("#custom-window").size()) {
            $('#custom-window-content').append( '<div id="map"></div><div id="point_coords"><p class="coords" style="margin: 0;"></p> <p class="miles" style="margin: 0;"></p> </div>' );
            $('#custom-window').show();
            load();
            ajaxLoad('/game/location/coords', {}, false, function (data) {
                jArray = data.contents.coords;
                for (var n in jArray)
                {
                    var arrTemp = $.parseJSON(jArray[n].coords);
                    points = [];
                    for (var i in arrTemp.points) {
                        var lat = parseFloat(arrTemp.points[i].lat);
                        var lng = parseFloat(arrTemp.points[i].lng);
                        latlng = new google.maps.LatLng(lat, lng);
                        points[i] = latlng;
                        if (arrTemp.points.length == 1)
                        {
                            var pointIcon = "miasteczko.png";
                            if (typeof jArray[n].icon !== "undefined") var pointIcon = jArray[n].icon;
                            markers.push(addMarker(latlng,jArray[n].name,jArray[n].id, pointIcon));
                        }
                    };
                    addPolygon (points, {name: jArray[n].name, type: jArray[n].type, color: "#000000"});
                }
            });
        }
    });
    $('.openwindow').click(function(e) {
        e.preventDefault();

        var link = $(this).attr('url');

        if ($("#custom-window").size() && link) {
            $('#custom-window').show();
            ajaxLoad(link, {}, false, function (data) {
                $('#custom-window-content').html(data.contents);
            });
        }
    });
    $('#custom-window-close').click(function() {
        $('#custom-window').hide();
    });

    // load timeout content
    $.timer(0, function (timer) {
        fenixrefres();
        timer.reset(10000);
    });

    $('[data-toggle="tooltip"]').tooltip();

    // Powiadomienia
    $(document).mouseup(function (e) {
        var notify = $("#interface_notifications");
        if (!notify.is(e.target) && notify.has(e.target).length === 0) {
            $('#interface_show_new_logs').slideUp(500);
        }
    });
    $('#modulemenu li a').click(function(e) {
        var artid = parseInt($(this).attr('artid'));
        var childrens = $('.parent_'+artid);
        if (childrens.length > 0)
        {
            e.preventDefault();
            childrens.toggle();
        }
    });
    $('#interface_notifications > img').click(function (e) {
        e.preventDefault();
        var content = $(this).parent('div').find('#interface_show_new_logs');
        if (content.css('display') == 'none') {
            ajaxLoad('/game/notifications/show/1', {}, false, function (data) {
                var notifylist = content.find('#new_notifications_content');
                notifylist.html('');
                $.each(data.contents.notifications, function (index, value) {

                    var icon = '<i class="fa fa-bolt"></i>';
                    if (typeof data.contents.nicons[value.type] != 'undefined') icon = '<i class="fa ' + data.contents.nicons[value.type] + '"></i>';

                    notifylist.append('<div class="notify" style="' + (value.readed == 0 ? 'border-left: 1px solid green' : '') + '">' + icon + ' <div class="notify_title">' + value.title + '</div><div class="notify_data">' + value.date + '</div><div class="notify_content">' + (value.expiry ? translate['notify-expiry_one'] : value.text) + '</div></div>');
                });
                $('.scrollbar-dynamic').scrollbar();
                content.slideToggle(500);
            });
        }
        else {
            content.slideToggle(500);
        }
    });
});

