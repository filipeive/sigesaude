<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="{{ route('admin.dashboard') }}" class="nav-link">Home</a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <li class="nav-item">
            <a class="nav-link" href="{{ route('logout') }}"
               onclick="event.preventDefault(); Swal.fire({title:'Terminar sessão?',text:'Tem certeza que deseja sair?',icon:'question',showCancelButton:true,confirmButtonText:'Sim, sair',cancelButtonText:'Cancelar'}).then((r)=>{if(r.isConfirmed)window.location.href='{{ route('logout') }}'});">
                <i class="fas fa-sign-out-alt"></i> Sair
            </a>
        </li>
    </ul>
</nav>