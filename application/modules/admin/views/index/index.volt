{% extends "../../../templates/admin.volt" %}

{% block pageContent %}

    {% if (configureGame) %}
        <div style="padding: 10px;">{{ t['dashboard-lets-configure'] }}</div>

        {% if (games|length == 0) %}
            <h1>{{ t['dashboard-no-games'] }}</h1>
        {% else %}
            {% for game in games %}
                <div class="col-lg-4 col-xs-6">
                    <div class="small-box box bg-aqua">
                        <div class="inner">
                            <h3>{{ game.title }}</h3>

                            <p>{{ game.describe }}</p>
                        </div>
                        <div class="icon">
                            <img src="{{ game.assetsUrl }}{{ game.screenshot }}"/>
                        </div>
                        <a class="small-box-footer" href="/admin/chose-game/{{ game.folder }}">
                            {{ t['chose'] }} <i class="fa fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
            {% endfor %}
        {% endif %}
    {% else %}
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
    {% endif %}
{% endblock %}

{% block addJS %}
<script type="text/javascript">
    $(function()
    {
        $('a.small-box-footer').click(function(e)
        {
            e.preventDefault();
            $(this).parent('div').append('<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>');
            var url = $(this).attr('href');
            setTimeout(function() { window.location.href = url; },2000);
        });
    });
</script>
{% endblock %}