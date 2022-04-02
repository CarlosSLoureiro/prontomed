<nav class="navbar navbar-expand-lg navbar-light bg-light shadow">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">👨‍⚕️ {{ config('app.name') }}</a>
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
            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" data-bs-auto-close="outside">Pacientes</a>
            <ul class="dropdown-menu shadow">
              <li><a href="#" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#listar-pacientes">Consultar</a></li>
              <li><a href="#" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#formulario-paciente">Cadastrar</a></li>
            </ul>
          </li>
        </ul>
        <ul class="navbar-nav mr-auto mb-2 mb-lg-0 d-flex ms-auto">
          <li class="nav-item"><a class="nav-link" data-bs-toggle="modal" data-bs-target="#formulario-senha" href="#"><i class="fa-solid fa-key"></i> Alterar Senha</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ route('logout') }}">Sair <i class="fa-solid fa-arrow-right-from-bracket"></i></a></li>
        </ul>

        <!-- auth()->user()->name
        <form class="d-flex ms-auto">
            <div class="input-group">
                <input class="form-control border-0 mr-2" type="search" placeholder="Search" aria-label="Search">
                <button class="btn btn-primary border-0" type="submit">Search</button>
            </div>
        </form>
        -->
      </div>
    </div>
  </nav>