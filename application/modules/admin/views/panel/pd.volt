{% extends "../../../templates/admin.volt" %}

{% block pageContent %}
    <div class="module_content">
        <p style="text-align: center;"></p>

        <div style="text-align: center;">
            <form action="/admin/game/panel/pd" method="POST" class="form-horizontal">
                <div class="form-group">
                    <label for="ReceiverID" class="col-sm-2 control-label">{{ t['pd-gainer'] }}</label>

                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="ReceiverName"
                               placeholder="{{ t['pd-write_nick'] }}" value="">
                        <input type="hidden" name="character" value="0" id="ReceiverID"/>
                    </div>
                </div>
                <div class="form-group">
                    <label for="messageTopic" class="col-sm-2 control-label">{{ t['pd-gain'] }}</label>

                    <div class="col-sm-10">
                        <input type="text" class="form-control"  name="pd">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-12">
                        <textarea class="form-control" rows="2" name="text" placeholder="{{ t['pd-text'] }}"></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-10 col-sm-offset-2">
                        <input type="submit" value="{{ t['send'] }}" style="width: 70px;">
                    </div>
                </div>

            </form>
        </div>
    </div>
    <div class="box">
        <div class="box-body">
            <table id="dataTableData" class="table table-bordered table-striped" cellspacing="0" width="100%"
                   style="visibility: hidden;">
                <thead>
                <tr>
                    <th class="text-center">{{ t['pd-gainer'] }}</th>
                    <th class="text-center">{{ t['pd-gain'] }}</th>
                    <th class="text-center">{{ t['pd-text'] }}</th>
                    <th>{{ t['delete'] }}</th>
                </tr>
                </thead>

                <tfoot>
                <tr>
                    <th class="text-center">{{ t['pd-gainer'] }}</th>
                    <th class="text-center">{{ t['pd-gain'] }}</th>
                    <th class="text-center">{{ t['pd-text'] }}</th>
                    <th>{{ t['delete'] }}</th>
                </tr>
                </tfoot>
                <tbody>
                <?php
                $achivs = Game\Models\Achivements::find(['order' => 'id DESC']);
                foreach ($achivs as $data) {
                ?>
                <tr>
                    <td style="font-weight: bold;"><a href="/game/profile/show/{{ data.owner.id }}">{{ data.owner.name }}</a></td>
                    <td style="font-weight: bold;">{{ data.gain }}</td>
                    <td style="font-weight: bold;">{{ data.text }}</td>
                    <td><a href="/admin/game/panel/delpd/{{ data.id }}" class="btn btn-danger btn-sm" type="button">{{ t['delete'] }}</a></td>
                </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

{% endblock %}

{% block addJS %}
    <script type="text/javascript">
        $.views.settings.allowCode = true;
        $.views.settings.delimiters("[#", "#]");

        $(document).ready(function () {
            $("#ReceiverName").autocomplete({
                source: "/game/refresh/getusers",
                minLength: 1,
                select: function (event, ui) {
                    $('#ReceiverID').attr('value', ui.item.id);
                }
            });

            $('#myModal').on('shown.bs.modal', function () {
                tinyMCE.execCommand('mceAddControl', false, 'mytextarea');
            });
            $('#myModal').on('hidden.bs.modal', function () {
                tinymce.execCommand('mceRemoveEditor', true, "mytextarea");
            });

            $('#dataTableData').DataTable({
                paginate: true,
                sort: false,
                stateSave: true,
                order: [[1, "asc"]],
                responsive: false,
                iDisplayLength: 100,
                "columnDefs": [{
                    "searchable": false,
                    "orderable": false,
                    "targets": 0
                }],
                "aoColumnDefs": [
                    {"sType": "html", "aTargets": [0]}
                ],
                language: {
                    "sProcessing": "{{ t['datatable-sProcessing'] }}",
                    "sLengthMenu": "{{ t['datatable-sLengthMenu'] }}",
                    "sZeroRecords": "{{ t['datatable-sZeroRecords'] }}",
                    "sInfoThousands": "{{ t['datatable-sInfoThousands'] }}",
                    "sInfo": "{{ t['datatable-sInfo'] }}",
                    "sInfoEmpty": "{{ t['datatable-sInfoEmpty'] }}",
                    "sInfoFiltered": "{{ t['datatable-sInfoFiltered'] }}",
                    "sInfoPostFix": "{{ t['datatable-sInfoPostFix'] }}",
                    "sSearch": "{{ t['datatable-sSearch'] }}",
                    "sUrl": "{{ t['datatable-sUrl'] }}",
                    "oPaginate": {
                        "sFirst": "{{ t['datatable-sFirst'] }}",
                        "sPrevious": "{{ t['datatable-sPrevious'] }}",
                        "sNext": "{{ t['datatable-sNext'] }}",
                        "sLast": "{{ t['datatable-sLast'] }}"
                    },
                    "sEmptyTable": "{{ t['datatable-sEmptyTable'] }}",
                    "sLoadingRecords": "{{ t['datatable-sLoadingRecords'] }}",
                    "oAria": {
                        "sSortAscending": "{{ t['datatable-sSortAscending'] }}",
                        "sSortDescending": "{{ t['datatable-sSortDescending'] }}"
                    }
                },
                "initComplete": function (settings, json) {
                    $(this).css("visibility", "visible");
                }
            });
        });
    </script>
{% endblock %}