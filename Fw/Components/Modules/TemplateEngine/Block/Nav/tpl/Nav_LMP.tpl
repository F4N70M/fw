<div class="nav nav_default">
    <div class="nav__panel">
        <div class="nav__logo">
            <img src="{% img.src %}" alt="{% img.title %}">
        </div>
        <nav class="nav__menu">
            {% foreach::menu %}
            <a href="{% item.src %}" class="nav__item">{% item.title %}</a>
            {% endforeach %}
        </nav>
        <div class="nav__profile">
            <div>{% action::getUserName %} [{% action::getUserRank %}]</div>
            <div><a href="{% const::APP_PREFIX %}/logout">выход</a></div>
        </div>
    </div>
</div>