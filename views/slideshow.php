<ul class="uk-slideshow" data-uk-slideshow>
    {% for image in images %}
        <li><img src="storage/shw-gallery/{{ image.filename }}" alt="{{ image.alt }}"></li>
    {% endfor %}
</ul>