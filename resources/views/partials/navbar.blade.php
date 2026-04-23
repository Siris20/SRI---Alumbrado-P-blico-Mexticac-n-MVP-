<nav class="navbar">
    <!-- IZQUIERDA: LOGO + TÍTULO -->
    <div class="navbar-left">
        <img src="/images/Logo_Municipal.webp" alt="Logo Municipal" class="logo">
        <div class="title">
            <span class="line-1">Gobierno de</span>
            <span class="line-2">Mexicanacan</span>
        </div>
    </div>

    <!-- CENTRO: LINKS -->
    <div class="navbar-center">
        <a href="/" class="nav-link {{ request()->is('/') ? 'active' : '' }}">Home</a>
        <a href="/contacto" class="nav-link {{ request()->is('contacto') ? 'active' : '' }}">Contacto</a>
        <a href="/reportes" class="nav-link {{ request()->is('reportes*') ? 'active' : '' }}">Reportes</a>
    </div>

    <!-- DERECHA: LOGIN / USUARIO AUTENTICADO -->
    <div class="navbar-right">
        @guest
            <a href="{{ route('login') }}" class="login">
                <span>Login</span>
                <div class="user-icon">👤</div>
            </a>
        @else
            <div class="user-menu">
                <button class="login user-btn">
                    <span>{{ Auth::user()->name ?? Auth::user()->email }}</span>
                    <div class="user-icon">👤</div>
                </button>

                <div class="dropdown">
                    <form action="{{ route('logout') }}" method="POST" id="logout-form">
                        @csrf
                        <button type="submit" class="dropdown-item">Cerrar sesión</button>
                    </form>
                </div>
            </div>
        @endguest
    </div>
</nav>

<style>
/* ===== NAVBAR GENERAL ===== */
.navbar {
    background: #fff;
    border: 3px solid #333;
    border-radius: 15px;
    margin: 15px;
    padding: 12px 30px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

/* IZQUIERDA */
.navbar-left {
    display: flex;
    align-items: center;
    gap: 12px;
}
.logo { width: 45px; height: 45px; }
.title { display: flex; flex-direction: column; line-height: 1.1; }
.title .line-1 { font-size: 14px; font-weight: 600; }
.title .line-2 { font-size: 18px; font-weight: 700; }

/* CENTRO */
.navbar-center {
    display: flex;
    gap: 40px;
    flex: 1;
    justify-content: center;
}
.nav-link {
    text-decoration: none;
    color: #000;
    font-weight: 600;
    padding: 8px 18px;
    border-radius: 12px;
    transition: background 0.2s;
}
.nav-link:hover { background: #f0f0f0; }
.nav-link.active { background: #e53935; color: #fff; }

/* DERECHA */
.navbar-right .login {
    display: flex;
    align-items: center;
    gap: 10px;
    text-decoration: none;
    color: #000;
    font-weight: bold;
    background: none;
    border: none;
    cursor: pointer;
}
.user-icon {
    background: #1e88e5;
    color: white;
    border-radius: 50%;
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
}

/* ===== DROPDOWN ===== */
.user-menu {
    position: relative;
    display: inline-block;
}
.user-btn {
    padding: 8px 12px;
    border-radius: 12px;
    transition: background 0.2s;
}
.user-btn:hover {
    background: #f0f0f0;
}
.dropdown {
    display: none;
    position: absolute;
    right: 0;
    top: 100%;
    background: #fff;
    border: 2px solid #333;
    border-radius: 10px;
    min-width: 160px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    z-index: 1000;
    margin-top: 8px;
}
.dropdown-item {
    width: 100%;
    padding: 12px 16px;
    background: none;
    border: none;
    text-align: left;
    cursor: pointer;
    font-weight: 600;
    color: #e53935;
}
.dropdown-item:hover {
    background: #ffebee;
}

/* Mostrar dropdown al hacer hover o clic (hover es más cómodo en desktop) */
.user-menu:hover .dropdown {
    display: block;
}
</style>

<!-- Opcional: cerrar dropdown al hacer clic fuera -->
<script>
document.addEventListener('click', function(e) {
    const userMenu = document.querySelector('.user-menu');
    if (userMenu && !userMenu.contains(e.target)) {
        userMenu.querySelector('.dropdown').style.display = 'none';
    }
});

// Mostrar al hacer clic en el botón (si prefieres clic en vez de hover)
document.querySelector('.user-btn')?.addEventListener('click', function(e) {
    e.stopPropagation();
    const dropdown = this.nextElementSibling;
    dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
});
</script>