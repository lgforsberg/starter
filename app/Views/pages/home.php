<?php $__view->layout('layouts/default', ['title' => $title]) ?>

<h1>Welcome</h1>
<p>This is the starter project. It's minimal, explicit, and ready for you to build on.</p>

<section class="card">
    <h2>HTMX Demo</h2>
    <p>Click the button to load a fragment from the server without a full page reload.</p>
    <button hx-get="/htmx/greeting" hx-target="#greeting-target" hx-swap="innerHTML" class="btn">
        Load Greeting
    </button>
    <div id="greeting-target" class="htmx-target"></div>
</section>
