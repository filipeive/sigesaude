{{-- Página de Gestão de Disciplinas --}}
@extends('adminlte::page')
@section('title', 'Gestão de Disciplinas')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="m-0 text-dark">Gestão de Disciplinas</h1>
            <ol class="breadcrumb mt-2">
                <li class="breadcrumb-item"><a href="{{ url('/painel') }}"><i class="fas fa-home"></i> Home</a></li>
                <li class="breadcrumb-item active">Disciplinas</li>
            </ol>
        </div>
        <a href="{{ route('admin.disciplinas.create') }}" class="btn btn-primary">
            <i class="fas fa-book-medical mr-1"></i> Nova Disciplina
        </a>
    </div>
@endsection

@section('content')
    {{-- Alertas de Sistema --}}
    <div id="alerts-container">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        @foreach (['error', 'updated', 'deleted'] as $msg)
            @if (session($msg))
                <div class="alert alert-{{ $msg == 'error' ? 'danger' : ($msg == 'deleted' ? 'warning' : 'info') }} alert-dismissible fade show"
                    role="alert">
                    <i
                        class="fas fa-{{ $msg == 'error' ? 'times' : ($msg == 'deleted' ? 'trash' : 'info') }}-circle mr-2"></i>
                    {{ session($msg) }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
        @endforeach
    </div>

    {{-- Card Principal --}}
    <div class="card card-outline card-primary">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="card-title">
                    <i class="fas fa-book mr-2"></i> Lista de Disciplinas
                </h3>
                <div class="card-tools">
                    <form action="{{ route('admin.disciplinas.index') }}" method="GET">
                        <div class="input-group input-group-sm" style="width: 250px;">
                            <input type="text" name="search" id="search" class="form-control float-right"
                                placeholder="Pesquisar disciplina..." value="{{ request('search') }}">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-default">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th style="width: 10px">#</th>
                            <th><i class="fas fa-book mr-1"></i>Nome</th>
                            <th><i class="fas fa-graduation-cap mr-1"></i>Curso</th>
                            <th><i class="fas fa-layer-group mr-1"></i>Nível</th>
                            <th><i class="fas fa-chalkboard-teacher mr-1"></i>Docente</th>
                            <th style="width: 150px">Ações</th>
                        </tr>
                    </thead>
                    <tbody id="disciplina-list">
                        @forelse ($disciplinas as $disciplina)
                            <tr>
                                <td>{{ $disciplina->id }}</td>
                                <td>{{ $disciplina->nome }}</td>
                                <td>{{ $disciplina->curso->nome }}</td>
                                <td>{{ $disciplina->nivel->nome }}</td>
                                <td>{{ $disciplina->docente->user->name ?? 'Não definido' }}</td>
                                <td class="text-right">
                                    <div class="btn-group">
                                        <a href="{{ route('admin.disciplinas.show', $disciplina->id) }}" class="btn btn-info btn-sm"
                                            title="Visualizar">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.disciplinas.edit', $disciplina->id) }}" class="btn btn-warning btn-sm"
                                            title="Editar">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                        <form class="d-inline" action="{{ route('admin.disciplinas.destroy', $disciplina->id) }}"
                                            method="POST"
                                            onsubmit="return confirm('Tem certeza que deseja excluir esta disciplina?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="Excluir">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-search mr-2"></i>Nenhuma disciplina encontrada
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer clearfix">
            <div class="float-right" id="pagination">
                {{ $disciplinas->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
@endsection

@section('css')
    <style>
        .table td,
        .table th {
            vertical-align: middle;
        }

        .btn-group .btn {
            margin-right: 2px;
        }

        .badge {
            padding: 6px 10px;
            font-weight: 500;
        }

        #alerts-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1050;
            min-width: 300px;
        }

        .alert {
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
    </style>
@endsection

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('search');
            const disciplinaList = document.getElementById('disciplina-list');
            const pagination = document.getElementById('pagination');
            let searchTimeout;

            // Função para buscar disciplinas com debounce
            const fetchDisciplinas = (query = '') => {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    fetch(`{{ route('admin.disciplinas.index') }}?search=${query}`, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            disciplinaList.innerHTML = data.disciplinas.data.map(disciplina => `
                    <tr>
                        <td>${disciplina.id}</td>
                        <td>${disciplina.nome}</td>
                        <td>${disciplina.curso.nome}</td>
                        <td>${disciplina.nivel.nome}</td>
                        <td>${disciplina.docente ? disciplina.docente.user.name : 'Não definido'}</td>
                        <td class="text-right">
                            <div class="btn-group">
                                <a href="/admin/disciplinas/${disciplina.id}" class="btn btn-info btn-sm" title="Visualizar">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="/admin/disciplinas/${disciplina.id}/edit" class="btn btn-warning btn-sm" title="Editar">
                                    <i class="fas fa-pencil-alt"></i>
                                </a>
                                <form class="d-inline" action="/admin/disciplinas/${disciplina.id}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir esta disciplina?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" title="Excluir">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                `).join('') || `
                    <tr>
                        <td colspan="6" class="text-center py-4">
                            <div class="text-muted">
                                <i class="fas fa-search mr-2"></i>Nenhuma disciplina encontrada
                            </div>
                        </td>
                    </tr>
                `;

                            pagination.innerHTML = data.pagination;
                        })
                        .catch(error => console.error('Erro ao buscar disciplinas:', error));
                }, 300);
            };

            // Listener para digitação no campo de busca
            searchInput.addEventListener('input', (event) => {
                fetchDisciplinas(event.target.value);
            });

            // Auto-hide para alertas após 5 segundos
            setTimeout(() => {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(alert => {
                    const bsAlert = new bootstrap.Alert(alert);
                    setTimeout(() => bsAlert.close(), 5000);
                });
            }, 100);
        });
    </script>
@endsection