<html>
<head>
    <style>
        input[type=url] {
            display: block;
            margin: 4px;
            width: 400px;
        }
    </style>
</head>
    <body>
        <form method="post" id="parse_form" action="parse.php">
            <h1>Введите сслыки в поля ниже:</h1>
            <button type="button" id="more">Добавить еще</button>

            <input type="url" name="links[]" />
            <input type="url" name="links[]" />
            <input type="url" name="links[]" />

            <input type="submit" value="Проверить" />
        </form>

        <script type="text/javascript" src="jquery.js" ></script>
        <script>
            $(document).ready(function(){
                $("#more").on( "click", function() {
                    $('#parse_form').find('input[type=url]').last().after('<input type="url" name="urls[]"/>');
                });
            });
        </script>

    </body>
</html>