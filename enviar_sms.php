




<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="robots" content="follow">

    <!--Uikit JS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/uikit@3.4.2/dist/css/uikit.min.css" />
    <title>Alertas SMS</title>
</head>

<body>

<div class="uk-section uk-container">
    <div class="uk-grid uk-child-width-1-3@s uk-child-width-1-1" uk-grid>
        <form class="uk-form-stacked js-enviar" >

            <h2>Envio alertas SMS resultados COVID- 19</h2>

             <div class="uk-margin">
                <label class="uk-form-label" for="form-stacked-text">Checar folios con:</label>
                <div class="uk-form-controls">
                    <input class="uk-input" id="NombreDBref" type="text" required="required" placeholder="">
                </div>
            </div>
            
            <div class="uk-margin">
                <label class="uk-form-label" for="form-stacked-text">Nombre Base de Datos</label>
                <div class="uk-form-controls">
                    <input class="uk-input" id="NombreDB" type="text" required="required" placeholder="">
                </div>
            </div>
            
            <div class="uk-margin">
                <label class="uk-form-label" for="form-stacked-text">Guardar registro como:</label>
                <div class="uk-form-controls">
                    <input class="uk-input" id="NombreArchivo" type="text" required="required" placeholder="nombre.txt">
                </div>
            </div>

            <div class="uk-margin uk-alert uk-alert-danger js-error" style="display: none">
            test
            </div>

            <div class="uk-margin">
                <button class="uk-button uk-button-default" type="submit">Enviar</button>
            </div>

        </form>
    </div>
 

</div>
<?php require_once 'inc/footer.php'; ?>
</body>
</html>