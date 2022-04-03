<nav class="navbar navbar-expand-lg navbar-light bg-light shadow">
    <div class="container-fluid">
      <span class="navbar-brand clickable">👨‍⚕️ {{ config('app.name') }}</span>
      <button class="navbar-toggler collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-content">
        <div class="hamburger-toggle">
          <div class="hamburger">
            <span></span>
            <span></span>
            <span></span>
          </div>
        </div>
      </button>
      <div class="collapse navbar-collapse" id="navbar-content">
        <ul class="navbar-nav mr-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="/"><i class="fa-solid fa-house"></i> Início</a>
          </li>
          <li class="nav-item dropdown">
            <span class="nav-link clickable dropdown-toggle" data-bs-toggle="dropdown" data-bs-auto-close="outside"><i class="fas fa-user"></i> Pacientes</span>
            <ul class="dropdown-menu shadow">
              <li><span class="dropdown-item clickable" data-bs-toggle="modal" data-bs-target="#listar-pacientes"><i class="fas fa-search"></i> Consultar</span></li>
              <li><span class="dropdown-item clickable" data-bs-toggle="modal" data-bs-target="#formulario-paciente"><i class="fas fa-user-plus"></i> Cadastrar</span></li>
            </ul>
          </li>
        </ul>
        <ul class="navbar-nav mr-auto mb-2 mb-lg-0 d-flex ms-auto">
          <li class="nav-item"><span class="nav-link clickable" data-bs-toggle="modal" data-bs-target="#formulario-senha"><i class="fa-solid fa-key"></i> Alterar Senha</span></li>
          <li class="nav-item"><span class="nav-link clickable logout">Sair <i class="fa-solid fa-arrow-right-from-bracket"></i></span></li>
        </ul>
      </div>
    </div>
  </nav>