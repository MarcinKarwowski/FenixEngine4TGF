<div style="color: #9acfea; text-align: center; margin-bottom: 20px;">
    <p>Copyright © 2015 <a href="https://hexengine.pl/">Hexen Engine Team</a> All rights reserved.</p>
    <p>Engine ver.: {{ config.game.engineVer }}{% if auth['group'] == 'Admin' %} - <a href="/admin">Panel Administratora</a>{% endif %}</p>
    <p>Czas ładowania strony: {{ scriptTime }} | Zajęta pamieć: {{ scriptMemory }} MB</p>
</div>
