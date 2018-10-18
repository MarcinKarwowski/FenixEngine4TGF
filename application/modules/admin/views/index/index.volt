{% extends "../../../templates/admin.volt" %}

{% block pageContent %}

    <section class="content">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-yellow">
                    <div class="inner">
                        <h3><?php echo Main\Models\Users::count() ?></h3>
                        <p>{{ t['dashboard-registered-users'] }}</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-person-add"></i>
                    </div>
                    <!-- <a class="small-box-footer" href="#">{{ t['dashboard-more-info'] }} <i class="fa fa-arrow-circle-right"></i></a>-->
                </div>
            </div><!-- ./col -->
        </div><!-- /.row -->
        <!-- Main row -->
        <div class="row">

        </div>
    </section>
{% endblock %}

{% block addJS %}
    <script type="text/javascript">
        $(function () {});
    </script>
{% endblock %}
