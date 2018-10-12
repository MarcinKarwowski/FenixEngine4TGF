var pageObj = {
    blockClick: false
};

/*
 * settings = {'formName': 'name', 'postData' => {}}
 * postData - nadpisuje dane z forma
 */
function ajaxLoad(url, settings, blockClick, callback)
{
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
    if (settings.postData)
    {
        postData = settings.postData;
    }

    if (pageObj.blockClick === false)
    {
        if (blockClick) pageObj.blockClick = true;
        $.ajaxq('default', {
            type: "POST",
            dataType: "json",
            url: url,
            data: postData,
            success: function(data) {
                if (data.error)
                {
                    jAlert(data.error);
                    pageObj.blockClick = false;
                }
                else
                {
                    pageObj.blockClick = false;
                    if (callback) callback(data);
                }
            },
            error: function() {
                pageObj.blockClick = false;
            }
        });
    }
}

function openAjaxModal(url, modalName, templateName, callback)
{
    $.ajaxq('standard', {
        url: url,
        context: document.body
    }).done(function(data) {
        if (data.contents.error)
        {
            jAlert(data.contents.error);
        }
        else
        {
            var template = $.templates(templateName);
            var htmlOutput = template.render(data.contents);
            $(modalName+" .modal-body").html(htmlOutput);
            $(modalName+" .modal-title").html(data.contents.modalTitle);
            $(modalName+" #modal-save-button").attr("onclick", "document.getElementById('modalForm').submit();");
            // ajaxLoad('"+data.contents.modalConfirm+"', {'formName': 'modalForm'}, true, function() {})

            $(modalName).modal();
            if (callback) callback(data);
        }
    });
}

$(function() {
    $("[data-mask]").inputmask();

    //Make the dashboard widgets sortable Using jquery UI
    $(".connectedSortable").sortable({
        placeholder: "sort-highlight",
        connectWith: ".connectedSortable",
        handle: ".box-header, .nav-tabs",
        forcePlaceholderSize: true,
        zIndex: 999999
    });
    $(".connectedSortable .box-header, .connectedSortable .nav-tabs-custom").css("cursor", "move");
});