{% extends "admin.volt" %}

{% block pageContent %}
    <script>
        var tinyMCConfig = {
            selector: '#mytextarea',mode : 'none',theme: 'modern',width: 550,height: 500,menubar : false,language : 'pl',
            plugins: [
                'advlist autolink link image lists charmap print preview hr anchor pagebreak',
                'searchreplace wordcount visualblocks visualchars insertdatetime media nonbreaking spellchecker',
                'table contextmenu directionality emoticons paste textcolor responsivefilemanager colorpicker codemirror'],
            toolbar1: 'pastetext searchreplace code | undo redo | visualblocks preview removeformat charmap | hr | link unlink anchor ',
            toolbar2: '| bold italic underline strikethrough | subscript superscript | alignleft aligncenter alignright alignjustify ',
            toolbar3: '| image responsivefilemanager | bullist numlist outdent indent | forecolor backcolor ',
            toolbar4: '| fontsizeselect styleselect | table ',
            codemirror: {
                indentOnInit: true, // Whether or not to indent code on init.
                path: 'codemirror-5', // Path to CodeMirror distribution
                config: {           // CodeMirror config object
                    mode: 'htmlmixed',
                    lineNumbers: true
                },
                jsFiles: ['mode/css/css.js', 'mode/xml/xml.js', 'mode/htmlmixed/htmlmixed.js', 'mode/htmlembedded/htmlembedded.js', 'mode/javascript/javascript.js', 'mode/xml/xml.js'],
                cssFiles: ['theme/eclipse.css', 'theme/elegant.css', 'theme/neat.css', 'theme/colorforth.css']
            },
            image_advtab: true,
            relative_urls: false,

            external_filemanager_path:'/assets/scripts/filemanager/',
            filemanager_title:'Manager Plików' ,
            filemanager_sort_by: 'date',
            filemanager_descending: 'ascending',
            external_plugins: { 'filemanager' : 'plugins/responsivefilemanager/plugin.min.js'}
        };
    </script>
    <!-- Button trigger modal -->
    <button type="button" class="btn btn-primary btn-lg" onclick="openAjaxModal('/admin/game/panel/locations/0', '#myModal', '#locationTemplate', function() { tinymce.init(tinyMCConfig); })" style="float: right; position: relative; top: -50px;">
        {{ t['locations-add'] }}
    </button>
    <div class="box">
        <div class="box-body">
            <table id="dataTableData" class="table table-bordered table-striped" cellspacing="0" width="100%" style="visibility: hidden;">
                <thead>
                <tr>
                    <th>{{ t['articles-title'] }}</th>
                    <th>{{ t['game-panel_edit'] }}</th>
                    <th>{{ t['delete'] }}</th>
                </tr>
                </thead>

                <tfoot>
                <tr>
                    <th>{{ t['articles-title'] }}</th>
                    <th>{{ t['game-panel_edit'] }}</th>
                    <th>{{ t['delete'] }}</th>
                </tr>
                </tfoot>
                <tbody>
                <?php
                $allarts = Game\Models\Locations::find(['order'=>'id'])-> toArray();
                $treeClass = new App\Facets\TreeView($allarts);
                foreach ($treeClass -> retArr as $data) {
                ?>
                <tr>
                    <td style="font-weight: bold;">{{ data['deep'] }} {{ data['name'] }} <input type="hidden" class="locCoords" value='{{ data['coords'] }}'/></td>
                    <td><a onclick="openAjaxModal('/admin/game/panel/locations/{{ data['id'] }}', '#myModal', '#locationTemplate', function() { tinymce.init(tinyMCConfig); })" class="btn btn-primary btn-sm" type="button">{{ t['edit'] }}</a></td>
                    <td><a href="/admin/game/panel/dellocations/{{ data['id'] }}" class="btn btn-danger btn-sm" type="button">{{ t['delete'] }}</a></td>
                </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <script id="locationTemplate" type="text/x-jsrender">
        <div class="art_edit_form">
            <form  action="/admin/game/panel/locations/[#* if(data.obj.id) { #]/[#>obj.id#][#* } #]" method="post" class="" id="modalForm" name="modalForm">
                    <div class="form-group">
                      <label>{{ t['locations-name'] }}</label>
                      <input type="text" value="[#>obj.name#]" name="title" class="form-control input-sm">
                    </div>
                    <div class="form-group">
                      <label>Lokacja nadrzędna</label>
                      <select class="form-control" name="parent">
                      <option value="0">Brak</option>
                      <?php
                            foreach ($treeClass -> retArr as $data) {
                            if (in_array($data['type'], ['SHOP'])) continue;
                      ?>
                        <option value="{{ data['id'] }}" [#* if(data.obj.parent_id=={{ data['id'] }}) { #]selected="selected"[#* } #]>{{ data['deep'] }} {{ data['name'] }}</option>
                      <?php } ?>
                      </select>
                    </div>
                    <div class="form-group">
                      <label>Typ</label>
                      <select id="location_type" class="form-control" name="type">
                      <option value="CONTENER" [#* if(data.obj.type=='CONTENER') { #]selected="selected"[#* } #]>Obszar</option>
                      <option value="LOCATION" [#* if(data.obj.type=='LOCATION') { #]selected="selected"[#* } #]>Lokacja</option>
                      <option value="SHOP" [#* if(data.obj.type=='SHOP') { #]selected="selected"[#* } #]>Sklep</option>
                      </select>
                    </div>
                    <div id="mapka" style="width: 100%; height: 400px; display: none;">
                        <div style="width: 100%; height: 100%;" id="map"></div>
                        <input type="hidden" value="[#>obj.coords#]" name="coords">
                    </div>
                    <div class="form-group">
                        <label>{{ t['description'] }}</label>
                        <div style="position: relative;">
                            <textarea id="mytextarea" name="text">[#* if(data.obj.text) { #][#>obj.text#][#* } #] </textarea>
                        </div>
                    </div>
            </form>
        </div>
    </script>
{% endblock %}

{% block addJS %}
    <script type="text/javascript">
        $.views.settings.allowCode = true;
        $.views.settings.delimiters("[#","#]");

        function additionMapStart() {
            if ($('input[name="coords"]').val() != '') {
                var objCache = JSON.parse($('input[name="coords"]').val());
                if (!objCache.color) objCache.color = '#ffe4b5';
                for (var i in objCache.points) {
                    latlng = new google.maps.LatLng(objCache.points[i].lat, objCache.points[i].lng);
                    path.insertAt(i, latlng);
                    marker = new google.maps.Marker({
                        position: latlng,
                        map: map,
                        draggable: true
                    });
                    markers[i] = marker;
                    markers[i].setTitle("#" + (parseInt(i) + 1));
                    markerEvents(marker);
                };
                map.setCenter(markers[0].getPosition());
                poly.setOptions({
                    fillColor: objCache.color
                });
                // draw other polys
                $('input.locCoords').each(function(key, value) {
                    var arrTemp = $.parseJSON($(value).val());
                    points = [];
                    for (var i in arrTemp.points) {
                        points[i] = new google.maps.LatLng(arrTemp.points[i].lat, arrTemp.points[i].lng);
                    }
                    addPolygon (points, {name: 'NAME', type: 'CONTENER', color: "#000000"});
                });
            }
            poly.setMap(map);
            poly.setPath(path);

            google.maps.event.addListener(map, 'click', managePolys);
            google.maps.event.addListener(poly, 'click', managePolys);
        }

        $(document).ready(function() {
            {% if config.game.params.mapOn == 1 %}
            $('#myModal').on('change', 'select[name="rgb"]', function() {
                poly.setOptions({
                    fillColor: "#" + $(this).val()
                });
                objCache.color = poly.fillColor;
                $('input[name="coords"]').val(JSON.stringify(objCache));
            });
            $('#myModal').on('change', '#location_type', function() {
                if ($(this).val().match(/CONTENER/)) {
                    $('#mapka').show();
                    load();
                }
                else if ($(this).val().match(/(LOCATION)/)) {
                    $('#mapka').show();
                    load();
                    if (path.length > 1) {
                        var tmp = path.getAt(0);
                        path.clear();
                        path.insertAt(0, tmp);
                        poly.setPaths(new google.maps.MVCArray([path]));
                        for (var i in markers) {
                            if (i != 0)
                                markers[i].setMap(null);
                        }
                        markers.splice(1, markers.length - 1);
                        objCache.points.splice(1, objCache.points.length - 1);
                        $('input[name="coords"]').val(JSON.stringify(objCache));
                    }
                }
                else {
                    $('#mapka').hide();
                    $('input[name="coords"]').val('');
                    path.clear();
                    poly.setPaths(new google.maps.MVCArray([path]));
                    for (var i in markers)
                        markers[i].setMap(null);
                    markers = [];
                }
            });
            {% endif %}
            $(document).on('focusin', function(e) {
                if ($(e.target).closest(".mce-window").length) {
                    e.stopImmediatePropagation();
                }
            });
            $('#myModal').on('shown.bs.modal', function() {
                tinyMCE.execCommand('mceAddControl', false, 'mytextarea');
                {% if config.game.params.mapOn == 1 %}
                if ($('#location_type').val().match(/(LOCATION|CONTENER)/)) {
                    $('#mapka').show();
                    load();
                }
                {% endif %}
            });
            $('#myModal').on('hidden.bs.modal', function () {
                tinymce.execCommand('mceRemoveEditor',true,"mytextarea");
            });

            $('#dataTableData').DataTable( {
                paginate: true,
                sort: false,
                stateSave: true,
                order: [[ 1, "asc" ]],
                responsive: false,
                iDisplayLength: 100,
                "columnDefs": [ {
                    "searchable": false,
                    "orderable": false,
                    "targets": 0
                } ],
                "aoColumnDefs": [
                    { "sType": "html", "aTargets": [ 0 ] }
                ],
                language: {
                    "sProcessing":   "{{ t['datatable-sProcessing'] }}",
                    "sLengthMenu":   "{{ t['datatable-sLengthMenu'] }}",
                    "sZeroRecords":  "{{ t['datatable-sZeroRecords'] }}",
                    "sInfoThousands":  "{{ t['datatable-sInfoThousands'] }}",
                    "sInfo":         "{{ t['datatable-sInfo'] }}",
                    "sInfoEmpty":    "{{ t['datatable-sInfoEmpty'] }}",
                    "sInfoFiltered": "{{ t['datatable-sInfoFiltered'] }}",
                    "sInfoPostFix":  "{{ t['datatable-sInfoPostFix'] }}",
                    "sSearch":       "{{ t['datatable-sSearch'] }}",
                    "sUrl":          "{{ t['datatable-sUrl'] }}",
                    "oPaginate": {
                        "sFirst":    "{{ t['datatable-sFirst'] }}",
                        "sPrevious": "{{ t['datatable-sPrevious'] }}",
                        "sNext":     "{{ t['datatable-sNext'] }}",
                        "sLast":     "{{ t['datatable-sLast'] }}"
                    },
                    "sEmptyTable":     "{{ t['datatable-sEmptyTable'] }}",
                    "sLoadingRecords": "{{ t['datatable-sLoadingRecords'] }}",
                    "oAria": {
                        "sSortAscending":  "{{ t['datatable-sSortAscending'] }}",
                        "sSortDescending": "{{ t['datatable-sSortDescending'] }}"
                    }
                },
                "initComplete": function(settings, json) {
                    $(this).css("visibility", "visible");
                }
            } );
        } );
    </script>
{% endblock %}
