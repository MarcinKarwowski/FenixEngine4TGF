{% extends "twocolumn.volt" %}

{% block menu %}

{% endblock %}

{% block content %}
    <div class="module_content news_module">

        <div style="float: left; width: auto; margin: 0px 10px 20px;" id="news_desc">{{ t['news-desc'] }}</div>

        <div class="news_display_msg">
            {% for index, item in page.items %}
                    <div class="news-title">{{ item.title }}</div>
            {% endfor %}
        </div>

        <nav style="text-align: center;">
            <ul class="pagination">
                <li>
                    <a href="/game/news/index/<?= $page->before; ?>" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
                <?php
                for ($i = 1; $i <= $page->total_pages; $i++)
                {
                echo '<li><a href="/game/news/index/'.$i.'">'.$i.'</a></li>';
                }
                ?>
                <li>
                    <a href="/game/news/index/<?= $page->next; ?>" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
{% endblock %}