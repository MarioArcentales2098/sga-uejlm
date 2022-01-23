<!-- CURSOS - ASIGNATURAS -->
<div class="row">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-12 col-xl-4">
                <div class="col-md-12 col-xl-12">
                    <div class="card-box tilebox-two">
                        <i class="icon-puzzle float-right text-muted"></i>
                        <h6 class="text-success text-uppercase m-b-15 m-t-10">Materias</h6>
                        <h2 class="m-b-10"><span>{{ sizeof($asignaturas) }}</span></h2>
                    </div>
                </div>
            </div>

            <div class="col-md-12 col-xl-8">
                <div class="card-box" id="container_users">
                    <table class="tablesaw table m-b-0" data-tablesaw-mode="stack">
                        <thead>
                            <tr>
                                <th>N°</th>
                                <th>Nombre</th>
                            </tr>    
                        </thead> 
                        <tbody>
                            @foreach ($asignaturas as $key => $item)                                
                            <tr>
                                <td>{{ $key+1 }}</td>
                                <td>{{ $item->nombre }}</td>
                            </tr>    
                            @endforeach
                        </tbody>   
                    <table>
                </div>
            </div>
        </div>
    </div>
</div>




@section('script-herramienta')

@endsection
