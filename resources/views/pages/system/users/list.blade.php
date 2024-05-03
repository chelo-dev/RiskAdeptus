@extends('layouts.master')
@section('title', 'Dashboard')
@section('container')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Gestion</a></li>
                    <li class="breadcrumb-item active">Usuarios</li>
                </ol>
            </div>
            <h4 class="page-title">Usuarios</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">

        <div class="card">
            <div class="card-header">
                <h4>Lista de usuarios</h4>
            </div>
            <div class="card-body">
                <div class="row">

                    <div class="col-12">
                        <div class="page-title-box">
                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item">
                                        <a href="#!">Nuevo <i class="mdi mdi-plus mr-1"></i></a>
                                    </li>
                                    <li class="breadcrumb-item">
                                        <div class="dropdown float-left">
                                            <a href="#" class="dropdown-toggle arrow-none" data-toggle="dropdown" aria-expanded="true">
                                                Exportar <i class="mdi mdi-download mr-1"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-left" x-placement="bottom-end" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(-140px, 22px, 0px);">
                                                <a href="#!" class="dropdown-item"> <i class="mdi mdi-file-pdf-outline mr-1"></i> PDF</a>
                                                <a href="#!" class="dropdown-item"> <i class="mdi mdi-file-excel-outline mr-1"></i> Excel</a>
                                            </div>
                                        </div>
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <table id="listUser" class="table dt-responsive nowrap w-100">
                            <thead>
                                <tr>
                                    <th>Acciones</th>
                                    <th>Estatus</th>
                                    <th>Nombre Completo</th>
                                    <th>Rol</th>
                                    <th>Email</th>
                                    <th>Telefono</th>
                                    <th>Departamento</th>
                                    <th>Area</th>
                                    <th>Fecha de registro</th>
                                </tr>
                            </thead>
                        </table>
                    </div>

                </div>

            </div>
        </div>

    </div>
</div>

@section('js')
    <script type="text/javascript">
        "use strict";
        
        $(document).ready(function() {
            $('#listUser').DataTable({
                "processing": true,
                "serverSide": true,
                "stateSave": true,
                "responsive": true,
                "order": [[1, 'DESC']],
                "lengthMenu": [[20, 25, 50, 100, -1], [20, 25, 50, 100, 'TODO']],
                "language": {
                    url: "{{ asset('json/datatable-es.json') }}"
                },
                "ajax": "{{ route('listUser') }}",
                "columns": [
                    {"data": "options"},
                    {"data": "is_active"},
                    {"data": "name"},
                    {"data": "role"},
                    {"data": "email"},
                    {"data": "phone"},
                    {"data": "department"},
                    {"data": "position"},
                    {"data": "created_at"}
                ],
                drawCallback: function() {
                    $(".dataTables_paginate > .pagination").addClass("pagination-rounded")
                }
            });
        });
    </script>
@endsection

@endsection