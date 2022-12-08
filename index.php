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
    <div class="modal" tabindex="-1" id="modal" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <p>Czy chcesz usunąć tego użytkownika?</p>
          </div>
          <div class="modal-footer">
            <button type="button" id="delete_button_modal" class="btn btn-primary">Tak</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Anuluj</button>
          </div>
        </div>
      </div>
    </div>
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
          <div>
            <input type="text" id="searchbox" placeholder="Znajdź kanał">
            <button class="btn-addnew-channel">DODAJ KANAŁ</button>
          </div>
          <table class="table">
            <thead>
              <tr>
                <th>Nazwa kanału</th>
                <th>ID</th>
                <th>Źródło</th>
                <th>Historia streamów</th>
                <th>Akcje</th>
              </tr>
            </thead>
            <tbody class="table">
              <tr>
                <td>Nazwa</td>
                <td>@nazwaid</td>
                <td><a href="#">YOUTUBE</a></td>
                <td><a href="#"><i class="bi bi-card-list"></i></a></td>
                <td><a href="#"><i class="bi bi-trash"></i></a></td>
              </tr>
              <tr>
                <td>Nazwa</td>
                <td>@nazwaid</td>
                <td><a href="#">TWITCH</a></td>
                <td><a href="#"><i class="bi bi-card-list"></i></a></td>
                <td><a href="#"><i class="bi bi-trash"></i></a></td>
              </tr>
              <tr>
                <td>Nazwa</td>
                <td>@nazwaid</td>
                <td><a href="#">YOUTUBE</a></td>
                <td><a href="#"><i class="bi bi-card-list"></i></a></td>
                <td><a href="#"><i class="bi bi-trash"></i></a></td>
              </tr>
            </tbody>
          </table>
          <nav aria-label="Page navigation example">
            <ul class="pagination justify-content-center">
              <li class="page-item disabled">
                <a class="page-link" href="#" tabindex="-1">Previous</a>
              </li>
              <li class="page-item"><a class="page-link" href="#">1</a></li>
              <li class="page-item"><a class="page-link" href="#">2</a></li>
              <li class="page-item"><a class="page-link" href="#">3</a></li>
              <li class="page-item">
                <a class="page-link" href="#">Next</a>
              </li>
            </ul>
          </nav>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script>
        var myData, newData = [];
        var $userDeleteId = null;
        //Load data on change input
        $('#searchbox').on('change', function (){
        $.ajax({
            url: `http://162.19.172.197:8080/api/v1/profile?page=0&size=20`,
        })
            .done(res => {
              newData = res.data;
              console.log(res.data[0]);
              for(var i=0; i<20;i++) {
                console.log(myData[i].name);
                $(".table").append( $('<tr><td>'+myData[i].name+'</td><td></td></tr>') );
              }
            });
        })
        //Startup load data
        $.ajax({
            url: `http://162.19.172.197:8080/api/v1/profile?page=0&size=20`,
        })
            .done(res => {
              myData = res.data;
              for(var i=0; i<20;i++) {
                var channelUniqueName = myData[i].url.substring(myData[i].url.lastIndexOf('/') + 1);
                $(".table").append( $('<tr><td>'+myData[i].name+'</td><td>'+channelUniqueName+'</td><td><a href="'+myData[i].url+'">'+myData[i].media+'</a></td><td><a href="/streams/profile/'+myData[i].id+'"><i class="bi bi-card-list"></i></a></td><td><a href="#" data-id="'+myData[i].id+'" class="delete-button"><i class="bi bi-trash"></i></a></td></tr>') );
              }
            });

        //Delete function in modal -> Error 405 not allowed    
        $(document).ready(function() {
          $(document).on('click', '#delete_button_modal', function() {
            $.ajax({
              type: "DELETE",
              url: "/api/v1/profile/?id="+$userDeleteId,
              success: console.log('Deleted'+$userDeleteId),
              failed: console.log('failed')
            })
            $('#modal').modal('toggle');
          })
        })

        //Clicked on trash -> delete user's button (Show modal)
        $(document).ready(function() {
          $(document).on('click', '.delete-button', function() {
            $userDeleteId = $(this).attr('data-id');
            $('#modal').modal('toggle');
          })
        })


    </script>
  </body>
</html>