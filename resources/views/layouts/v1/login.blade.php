<span class="login-page text-center">
  <main class="form-signin">
      <form class="login-form" method="post" action="{{ route('api.logar') }}">
  
          <input type="hidden" name="_token" value="{{ csrf_token() }}" />
          <h1 class="fw-bold">👨‍⚕️ {{ config('app.name') }}</h1>
          
          <h1 class="h3 mb-3 fw-normal title">Faça o login para continuar!</h1>
  
          <div class="form-group form-floating mb-3">
              <input type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="name@example.com" required="required" autofocus>
              <label for="floatingEmail">Endereço de Email</label>
          </div>
          
          <div class="form-group form-floating mb-3">
              <input type="password" class="form-control" name="senha" value="{{ old('senha') }}" placeholder="Password" required="required">
              <label for="floatingPassword">Senha</label>
          </div>
  
          <button class="w-100 btn btn-lg btn-primary" type="submit">Login</button>
      </form>
  </main>
</span>