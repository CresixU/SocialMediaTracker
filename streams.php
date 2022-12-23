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
        <nav aria-label="Page navigation example">
          <ul class="pagination justify-content-center">
              
          </ul>
        </nav>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script>
        var myData = [];
        var currentPage = 0;
        var size = 10;
        var maxPages = 99;

         //Pagination
         function ShowData(page,size) {
          currentPage = page;
          $.ajax({
            url: "http://192.109.244.120:8080/api/v1/streaming?page="+page+"&size="+size,
          })
            .done(res => {
              $('tbody').empty();
              maxPages = res.totalPages;
              myData = res.data;
              for(var i=0; i<size;i++) {
                var streamStartTime = myData[i].startAt.substr(0,10) + ' ' + myData[i].startAt.substr(11,5);
                $(".table").append( $('<tr><td><a href="'+myData[i].profile.url+'">'+myData[i].profile.name+'</a></td><td><a href="'+myData[i].url+'">'+myData[i].title+'</a></td><td>'+streamStartTime+'</td><td><a href="./stream.php?id='+myData[i].id+'"><i class="bi bi-bar-chart-line"></i></a></td></tr>') );
              }
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

        //Load data on start
        ShowData(0,10);
    </script>
  </body>
</html>