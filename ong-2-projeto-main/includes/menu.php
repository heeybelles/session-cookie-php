<nav id="menu">

      <div class="topo-menu">
        <img src="img/Logo.png" alt="Logo" class="logo">

        <div id="toggle-menu">
          <i class="fa-solid fa-angles-left"></i>
        </div>
      </div>


      <ul class="lista">

        <li>
            <a href="dashboard.php" class="item <?= $pagina == 'dashboard' ? 'ativo' : '' ?>">
                <i class="fa-solid fa-house"></i> <span>Início</span>
            </a>
            </li>

            <li class="grupo">
            <a href="#" class="item menu-dropdown">
                <i class="fa-solid fa-clipboard-list"></i> <span>Cadastros</span>
                <i class="fa-solid fa-chevron-down seta"></i>
            </a>

            <ul class="submenu">
                <?php if ($nivel === 'Administrador') : ?>
                <li><a href="funcionario_create.php" class="link <?= $pagina == 'funcionario_create' ? 'ativo' : '' ?>">Funcionários</a></li>
                <?php endif; ?>

                <li><a href="add_animal.php" class="link <?= $pagina == 'add_animal' ? 'ativo' : '' ?>">Animais</a></li>
                <li><a href="voluntario_create.php" class="link <?= $pagina == 'voluntario_create' ? 'ativo' : '' ?>">Voluntários</a></li>
                <li><a href="add_adocao.php" class="link <?= $pagina == 'add_adocao' ? 'ativo' : '' ?>">Adoções</a></li>
                <li><a href="add_padrinho.php" class="link <?= $pagina == 'add_padrinho' ? 'ativo' : '' ?>">Padrinhos</a></li>
            </ul>
            </li>

            <li class="grupo">
            <a href="#" class="item menu-dropdown">
                <i class="fa-solid fa-folder-open"></i> <span>Registros</span>
                <i class="fa-solid fa-chevron-down seta"></i>
            </a>

            <ul class="submenu">

                <?php if ($nivel === 'Administrador') : ?>
                <li><a href="funcionario.php" class="link <?= $pagina == 'funcionario' ? 'ativo' : '' ?>">Funcionários</a></li>
                <?php endif; ?>

                <li><a href="animal.php" class="link <?= $pagina == 'animal' ? 'ativo' : '' ?>">Animais</a></li>
                <li><a href="voluntario.php" class="link <?= $pagina == 'voluntario' ? 'ativo' : '' ?>">Voluntários</a></li>
                <li><a href="adocao.php" class="link <?= $pagina == 'adocao' ? 'ativo' : '' ?>">Adoções</a></li>
                <li><a href="padrinho.php" class="link <?= $pagina == 'padrinho' ? 'ativo' : '' ?>">Padrinhos</a></li>
            
            </ul>
            </li>

            <li>
            <a href="estoque.php" class="item <?= $pagina == 'estoque' ? 'ativo' : '' ?>">
                <i class="fa-solid fa-boxes-stacked"></i> <span>Estoque</span>
            </a>
            </li>

            <li>
            <a href="ajustes.php" class="item <?= $pagina == 'ajustes' ? 'ativo' : '' ?>">
                <i class="fa-solid fa-gear"></i> <span>Ajustes</span>
            </a>
            </li>

      </ul>
      <div class="usuario user-dropdown" id="userCard">
        <img class="avatar avatar-user" id="userAvatar" src="<?= $fotoUsuario ?>" alt="Avatar">

        <div class="info">
          <span class="nome"><?= htmlspecialchars($usuario['nome']) ?></span>
          <span class="cargo"><?= htmlspecialchars($usuario['nivel']) ?></span>
        </div>

        <i class="fa-solid fa-chevron-down seta" style="color: #170f3bff;"></i>
      </div>

    </nav>

    <div class="dropdown-menu" id="profileMenu" role="menu" aria-hidden="true">

      <?php if ($nivel === 'Administrador') : ?>
          <a href="editar_usuario.php" class="dropdown-item" role="menuitem">Alterar dados</a>
      <?php endif; ?>
      <a href="troca_senha.php" class="dropdown-item" role="menuitem">Alterar Senha</a>
      <a href="logout.php" class="dropdown-item sair" role="menuitem">Sair</a>

    </div>
    
  <script>
  // MENU LATERAL
const toggleBtn = document.getElementById('toggle-menu');
const nav = document.querySelector('nav');

function ajustarMenuResponsivo() {
  if (window.innerWidth <= 900) {      
    nav.classList.add('encolhido');

    const icon = toggleBtn.querySelector('i');
    icon.classList.remove('fa-angles-left');
    icon.classList.add('fa-angles-right');

  } else {
    nav.classList.remove('encolhido');

    const icon = toggleBtn.querySelector('i');
    icon.classList.remove('fa-angles-right');
    icon.classList.add('fa-angles-left');
  }
}

ajustarMenuResponsivo();


window.addEventListener("resize", ajustarMenuResponsivo);

toggleBtn.addEventListener('click', () => {
  nav.classList.toggle('encolhido');

  const icon = toggleBtn.querySelector('i');
  icon.classList.toggle('fa-angles-left');
  icon.classList.toggle('fa-angles-right');
});

document.querySelectorAll('.menu-dropdown').forEach(drop => {
  drop.addEventListener('click', e => {
    e.preventDefault();
    drop.nextElementSibling.classList.toggle('mostrar');
  });
});

document.querySelectorAll('.item').forEach(item => {
  item.addEventListener('click', () => {
    document.querySelectorAll('.item').forEach(i => i.classList.remove('ativo'));
    item.classList.add('ativo');
  });
});

// DROPDOWN PERFIL
const userCard = document.getElementById("userCard");
const profileMenu = document.getElementById("profileMenu");

if (userCard && profileMenu) {

  userCard.addEventListener("click", (e) => {
    e.stopPropagation();
    profileMenu.classList.toggle("mostrar");
  });

  profileMenu.addEventListener("click", (e) => e.stopPropagation());

  document.addEventListener("click", () => {
    profileMenu.classList.remove("mostrar");
  });

  document.addEventListener("keydown", (e) => {
    if (e.key === "Escape") {
      profileMenu.classList.remove("mostrar");
    }
  });
}

// Ativar o link da página atual
const pagina = window.location.pathname.split("/").pop();

document.querySelectorAll("a[href]").forEach(link => {
  const href = link.getAttribute("href");

  if (href === pagina) {
    link.classList.add("ativo");

    const submenu = link.closest(".submenu");
    if (submenu) {
      submenu.classList.add("mostrar");

      const parentItem = submenu.previousElementSibling;
      if (parentItem) parentItem.classList.add("ativo");
    }
  }
});

  </script>
