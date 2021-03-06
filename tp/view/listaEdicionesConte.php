{{> headerContenidista}}

<div class="form-group mt-5">
    <h2>Ediciones</h2>
    <a href="/edicion/index" class="btn btn-outline-primary mb-3 float-right">Crear nueva Edición</a>

    <table class="table table-bordered">
        <thead class="thead-dark">
        <tr>
            <th class="text-center" scope="col">Nombre</th>
            <th class="text-center" scope="col">Numero</th>
            <th class="text-center" scope="col">ejemplar</th>
            <th class="text-center" scope="col">Estado</th>
            <th class="text-center" scope="col">Editar</th>
        </tr>
        </thead>
        <tbody>
        {{#ediciones}}
        <tr>
            <td class="text-center">{{nombre}}</td>
            <th class="text-center">{{numero}}</th>
            <td class="text-center">{{ejemplar}}</td>
            <td class="text-center">
                <form action="/edicion/cambiarEstado" method="POST">
                    <input type="hidden" name="estado" value="{{estado}}">
                    <input type="hidden" name="id" value="{{id}}" >
                    {{#estado}}
                    <button type="submit" class="btn btn-success btn-sm" disabled>Activo</button>
                    {{/estado}}
                    {{^estado}}
                    <button type="submit" class="btn btn-warning btn-sm" disabled>Inactivo</button>
                    {{/estado}}
                </form>
            </td>
            <form method="post" action="/edicion/editar">
                <input type="hidden" name="id" value="{{id}}">
                <td class="text-center"><button type="submit" class="btn btn-outline-primary mb-3">Editar</button></td>
            </form>
            </td>
        </tr>
        {{/ediciones}}
        </tbody>
    </table>
    <a href="/usuario/listaConte" class="btn btn-outline-danger my-3 ml-3">Volver</a>
</div>
{{> footer}}