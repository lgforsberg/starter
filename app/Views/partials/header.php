<header class="site-header" x-data="{ open: false }">
    <div class="container header-inner">
        <a href="/" class="logo">Starter</a>
        <nav class="nav" :class="{ 'nav--open': open }">
            <a href="/">Home</a>
            <a href="/about">About</a>
            <a href="/contact">Contact</a>
        </nav>
        <button class="nav-toggle" @click="open = !open" aria-label="Toggle menu">
            <span></span><span></span><span></span>
        </button>
    </div>
</header>
