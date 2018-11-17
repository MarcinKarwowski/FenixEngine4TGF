<?php foreach (Main\Models\Articles::find(array('conditions' => 'published=1',"order" => "id DESC", "limit" => 5)) as $art) { ?>
<div class="news-row">
    <div class="news-title"><a href="/news/{{ art.id }}">{{ art.title }}</a> <span style="float: right;">{{ art.getDate() }}</span></div>
    <div class="news-body">{{ art.getTextdata().text }}</div>
</div>
<?php } ?>
