<!doctype html>
<html lang="pl">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Social media tracker</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link href="css/style.css" rel="stylesheet">
    <link href="css/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  </head>
  <body>
    <div class="container">
        <!-- NAV BAR -->
        <div class="nav d-flex justify-content-center">
            <div>
                <a href=".">LISTA KANAŁÓW</a>
            </div>
            <div>
                <a href="./streams.php">TRWAJĄCE STREAMY</a>
            </div>
        </div>
        <div class="row text-center">
            <h1><a href="#">TYTUŁ STREAMU</a></h1>
        </div>
        <div class="row">
            <div class="col text-center">
                <p>Nazwa kanału</p>
                <p id="channel-name"></p>
            </div>
            <div class="col text-center">
                <p>Źródło</p>
                <p id="channel-source"></p>
            </div>
            <div class="col text-center">
                <p>Rozpoczęty</p>
                <p id="stream-start"></p>
            </div>
            <div class="col text-center">
                <p>Zakończony</p>
                <p id="strean-end"></p>
            </div>
            <div class="col text-center">
                <p>Trwał</p>
                <p id="stream-total"></p>
            </div>
            <div class="col text-center">
                <p>Średnia ilość widzów</p>
                <p id="stream-avg-views"></p>
            </div>
            <div class="col text-center">
                <p>Największa ilość widzów</p>
                <p id="stream-top-views"></p>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
  </body>
</html>