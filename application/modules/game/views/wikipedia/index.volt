<h1>Wikipedia</h1>
<ul>
    <?php
    $allarts = Main\Models\Wikipedia::find(['order'=>'orderid'])-> toArray();
    $treeClass = new App\Facets\TreeView($allarts);
    foreach ($treeClass -> retArr as $data) {
    ?>
    <li>{{ data['deep'] }} <a href="/wikipedia/artykul/{{ data['id'] }}">{{ data['title'] }}</a></li>
    <?php } ?>
</ul>