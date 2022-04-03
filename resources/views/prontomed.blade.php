@extends('layout')

@section('content')
    <section class="my-5">
        <div id="pagina-principal" class="container">
            <div id="minhas-consultas" class="p-5 border">
                <p class="lead">Seja bem-vindo (a) <span class="medico-nome"></span>. &#128516;</p>
                <h2 class="n-consultas">Você possui &#128209; <span class="text-primary n-consultas-do-dia">0</span> consultas agendadas para hoje.</h2>
                <span class="btn btn-lg btn-primary" data-bs-toggle="modal" data-bs-target="#listar-consultas-do-dia" role="button">Listar consultas do dia  &raquo;</span>
                <span class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#listar-consultas-agendadas" role="button">Consultas agendadas (<span class="n-consultas-agendadas">0</span>)  &raquo;</span>
                <span class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#listar-consultas-passadas" role="button">Consultas passadas (<span class="n-consultas-passadas">0</span>)  &raquo;</span>
                <hr>
                <p>Em caso de dúvidas, entre em contato: <a href="mailto:loureiro.s.carlos@gmail.com" target="_self">loureiro.s.carlos@gmail.com</a></p>
            </div>
        </div>
    </section>
@endsection