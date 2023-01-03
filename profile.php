<!doctype html>
<html lang="pl">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Social media tracker</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link href="css/style.css" rel="stylesheet">
    <link href="css/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <style>
        a {
            text-decoration: none !important;
            color: rgb(238, 238, 238) !important;
            transition: 0.5s;
        }
        .title {
            font-weight: 700;
            font-size: 120%;
        }
    </style>
  </head>
  <body>
    <?php echo "<span id='web-user-id' style='display: none;'>" . $_GET['id']."</span>";?>
    <?php $api = require("config.php"); ?>
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
        <div class="row text-center mt-3 mb-5">
            <h1><a id="user-name" href="#">Ładowanie danych...</a></h1>
        </div>
        <table class="table">
            <thead>
              <tr>
                <th>Nazwa</th>
                <th>Rozpoczęty</th>
                <th>Zakończony</th>
                <th>Czas trwania</th>
                <th>Szczegóły</th>
              </tr>
            </thead>
            <tbody class="contenttable">
            </tbody>
          </table>
          <nav>
            <ul class="pagination justify-content-center">
              
            </ul>
          </nav>
    <div>
</div>
    <script>
        var profileId = document.getElementById('web-user-id').innerHTML;
        var myData = [];
        var currentPage = 0;
        var size = 20;
        var maxPages = 99;
        var api_url = "<?php echo $api ?>";

        //Pagination
        function ShowData(page,size) {
            $.ajax({
                url: api_url+"/api/v1/streaming/profile?id="+profileId+"&page="+page+"&size="+size,
            })
                .done(res => {
                $('tbody').empty();
                currentPage = page;
                maxPages = res.totalPages;
                myData = res.data;
                if(myData.length == 0) {
                    document.getElementById('user-name').innerHTML = "Ten użytkownik nie robił jeszcze streamów";
                    return;
                }
                for(var i=0; i<size;i++) {
                    var duration;
                    var endedAt;
                    if(!myData[i]) break;

                    var startedAt = myData[i].startAt.substr(11,5)+"<br>"+myData[i].startAt.substr(0,10);
                    if(!myData[i].endAt || myData[i].endAt == undefined) {
                        duration = CalcData(new Date(Date.now()) - new Date(myData[i].startAt));
                        endedAt = "Nadal trwa";
                    }
                    else {
                        duration = CalcData(new Date(myData[i].endAt) - new Date(myData[i].startAt));
                        endedAt = myData[i].endAt.substr(11,5)+"<br>"+myData[i].endAt.substr(0,10);
                    }
                    
                    $(".table").append( $('<tr><td><a href="'+myData[i].url+'">'+myData[i].title+'</a></td><td>'+startedAt+'</td><td>'+endedAt+'</td><td>'+duration+'</td><td><a href="./stream.php?id='+myData[i].id+'"><i class="bi bi-card-list"></i></a></td></tr>') );
                }
                document.getElementById('user-name').innerHTML = myData[0].profile.name;
                document.getElementById('user-name').setAttribute("href", myData[0].profile.url);

                $('.pagination').empty();
                if(currentPage > 2) {
                    $(".pagination").append( $('<li class="page-item"><a class="page-link" href="#" onclick="ShowData(0,'+size+')">1</a></li>') );
                    $(".pagination").append( $('<li class="page-item"><a class="page-link">...</a></li>') );
                }
                for(var i=(currentPage-2);i<=(currentPage+2);i++) {
                    if(i>=0 && i<maxPages) {
                    if(i==currentPage)
                        $(".pagination").append( $('<li class="page-item"><a class="page-link current-page" style="border: 1px solid #5858db !important;border-radius: 30px !important;color: white !important;" href="#" onclick="ShowData('+i+','+size+')">'+(i+1)+'</a></li>') );
                    else
                        $(".pagination").append( $('<li class="page-item"><a class="page-link" href="#" onclick="ShowData('+i+','+size+')">'+(i+1)+'</a></li>') );
                    }
                }
                if(currentPage < (maxPages-3)) {
                    $(".pagination").append( $('<li class="page-item"><a class="page-link">...</a></li>') );
                    $(".pagination").append( $('<li class="page-item"><a class="page-link" href="#" onclick="ShowData('+(maxPages-1)+','+size+')">'+(maxPages)+'</a></li>') );
                }
                
                });
            }
        
        function CalcData(ms) {
            var hours, min, seconds = 0;
            seconds = Math.floor(ms/1000);
            min = Math.floor(seconds/60);
            hours = Math.floor(min/60);
            return hours+"h "+min%60+"m "+seconds%60%60+"s";
        }

        //Load data on start
        ShowData(0,20,null);
    </script>
  </body>
</html>