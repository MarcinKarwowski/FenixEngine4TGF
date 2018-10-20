{% extends "../../../templates/admin.volt" %}

{% block pageContent %}
    {{ form('class': 'form-horizontal', 'id': 'edituser-form', 'action': '') }}
    <!-- Prepended text-->
    <div class="form-group">
        <div class="col-md-12">
            <div class="input-group">
                                        <span class="input-group-addon"><span
                                                    class="glyphicon glyphicon-circle"></span> {{ t['users-name'] }}</span>
                {{ forms.get('edituser').render('name', ['class': 'form-control', 'placeholder': t['users-name']]) }}
            </div>

        </div>
    </div>
    <div class="form-group">
        <div class="col-md-12">
            <div class="input-group">
                                        <span class="input-group-addon"><span
                                                    class="glyphicon glyphicon-circle"></span> {{ t['users-group_label'] }}</span>
                {{ forms.get('edituser').render('group_id', ['class': 'form-control', 'placeholder': t['users-group_label']]) }}
            </div>

        </div>
    </div>
    <div class="form-group">
        <div class="col-md-12">
            <div class="input-group">
                                        <span class="input-group-addon"><span
                                                    class="glyphicon glyphicon-circle"></span> {{ t['users-email'] }}</span>
                {{ forms.get('edituser').render('email', ['class': 'form-control', 'placeholder': t['users-email']]) }}
            </div>

        </div>
    </div>
    <div class="form-group">
        <div class="col-md-12">
            <div class="input-group">
                                        <span class="input-group-addon"><span
                                                    class="glyphicon glyphicon-circle"></span> {{ t['users-active'] }}</span>
                {{ forms.get('edituser').render('active', ['class': 'form-control', 'placeholder': t['users-active']]) }}
            </div>

        </div>
    </div>

    <!-- Button -->
    <div class="form-group">
        <label class="col-md-4 control-label" for="singlebutton"></label>

        <div class="col-md-4" style="text-align: center;">
            {{ forms.get('edituser').render(t['save']) }}
            <a href="/admin/users" class="btn btn-primary">{{ t['back'] }}</a>
        </div>
    </div>
    {{ forms.get('edituser').render('csrf', ['value': csrfToken]) }}
    </form>
    <br />
    <br />
    <h3>{{ t['users-characters'] }}</h3>
    <div class="box">
        <div class="box-body">
            <table id="dataTableData" class="table table-bordered table-striped" cellspacing="0" width="100%"
                   style="visibility: hidden;">
                <thead>
                <tr>
                    <th>{{ t['characters-id'] }}</th>
                    <th>{{ t['characters-name'] }}</th>
                    <th>{{ t['characters-level'] }}</th>
                    <th>{{ t['characters-gold'] }}</th>
                    <th>{{ t['delete'] }}</th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach (Main\Models\Characters::find(['conditions' => 'users_id = ?1', 'bind' => [ 1 => $userObj->id ], 'order' => 'id ASC']) as $data) {
                ?>
                <tr>
                    <th>{{ data.id }}</th>
                    <td style="font-weight: bold;">
                        <a href="/admin/users/showchar/{{ data.id }}">{{ data.name }}</a>
                    </td>
                    <th>{{ data.level }}</th>
                    <th>{{ data.gold }}</th>
                    <td><a href="/admin/characters/delete/{{ data.id }}" class="btn btn-danger btn-sm"
                           type="button">{{ t['delete'] }}</a></td>
                </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
{% endblock %}

{% block addJS %}
    <script type="text/javascript">
        $(document).ready(function () {
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
                    { "sType": "html", "aTargets": [0] }
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
