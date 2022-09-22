<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>En Línea Editores</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>

<body>
    <div style="width: 320px; margin: 0 auto;" class="p-2">
        <div class="text-center my-3">
            <a href="https://www.plataformaenlinea.com/2017/">
                <img src="https://www.plataformaenlinea.com/2017/resources/images/admin/start_logo.png"
                    alt="En Línea Editores Logo" style="width: 200px;"
                >
            </a>
        </div>
        <form action="<?= base_url('app/cvalidate_login') ?>" accept-charset="utf-8" method="POST">
            <div class="mb-3">
                <input type="text" name="username" value="" class="form-control form-control-lg"
                    required="required" autofocus="1" title="Escriba su nombre de usuario" placeholder="usuario">
            </div>
    
            <div class="mb-3">
                <input type="password" name="password" value="" class="form-control form-control-lg"
                    required="required" title="Escriba su contraseña" placeholder="contraseña">
            </div>
            <div class="mb-3">
                <button class="btn btn-success btn-block btn-lg" type="submit">
                    Entrar
                </button>
            </div>
        </form>
    </div>
</body>

</html>