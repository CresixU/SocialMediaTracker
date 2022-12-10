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
        <div>
            <table class="table">
                <thead>
                <tr>
                    <th>Nazwa kanału</th>
                    <th>Tytuł</th>
                    <th>Rozpoczęty</th>
                    <th>Szczegóły</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script>
        var myData = [];

        $.ajax({
            url: `http://162.19.172.197:8080/api/v1/streaming?page=0&size=10`,
        })
            .done(res => {
              myData = res.data;
              //console.log(res.data[0]);
              for(var i=0; i<20;i++) {
                var interpolatedDate = myData[i].startAt.substr(0,10);
                var interpolatedTime = myData[i].startAt.substr(11,5);
                var streamStartTime = interpolatedDate + ' ' + interpolatedTime;
                $(".table").append( $('<tr><td><a href="'+myData[i].profile.url+'">'+myData[i].profile.name+'</a></td><td><a href="'+myData[i].url+'">'+myData[i].title+'</a></td><td>'+streamStartTime+'</td><td><a href="/streams/'+myData[i].id+'"><i class="bi bi-bar-chart-line"></i></a></td></tr>') );
              }
            });
    </script>
  </body>
</html>