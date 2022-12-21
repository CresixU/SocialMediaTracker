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
    <!-- Delete user modal -->
    <div class="modal" tabindex="-1" id="modal" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-body">
            <p>Czy chcesz usunąć tego użytkownika?</p>
          </div>
          <div class="modal-footer">
            <button type="button" id="delete_button_modal" class="btn btn-primary">Tak</button>
            <button type="button" onclick="hide()" class="btn btn-secondary" data-dismiss="modal">Anuluj</button>
          </div>
        </div>
      </div>
    </div>
    <!-- Add new user modal -->
    <div class="modal fade" id="modal_channel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <form>
          <div class="modal-content">
            <div class="modal-body">
              <div class="form-group">
                <label for="channel-name" class="w-100">Nazwa użytkownika:</label>
                <input type="text" required class="form-control w-100" id="channel-name">
                <label for="channel-url" class="w-100">Link do kanału:</label>
                <input type="text" required class="form-control w-100" id="channel-url">
                <select class="form-select w-100" required aria-label="Default select example" id="channel-platform">
                <option value="null" selected>WYBIERZ PLATFORME</option>
                <option value="YOUTUBE">YOUTUBE</option>
                <option value="TWITCH">TWITCH</option>
                <option value="FACEBOOK">FACEBOOK</option>
              </select>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" onclick="hide2()" data-dismiss="modal">Anuluj</button>
              <button type="submit" id="add_new_channel_button" class="btn btn-primary">Dodaj użytkownika</button>
            </div>
          </div>
        </form>
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
            <button class="btn-addnew-channel" id="show_modal_new_channel">DODAJ KANAŁ</button>
          </div>
          <table class="table">
            <thead>
              <tr>
                <th>Nazwa kanału</th>
                <th>ID</th>
                <th>Źródło</th>
                <th>Ostatnio sprawdzony</th>
                <th>Historia streamów</th>
                <th>Akcje</th>
              </tr>
            </thead>
            <tbody class="contenttable">
            </tbody>
          </table>
          <nav aria-label="Page navigation example">
            <ul class="pagination justify-content-center">
              
            </ul>
          </nav>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script>
        var myData, newData, newData2 = [];
        var $userDeleteId = null;
        var currentPage = 0;
        var size = 20;
        var maxPages = 99;
        var searchPhrase = "";

        //Load data on change input
        $('#searchbox').on('change', function (){
          $('tbody').empty();
          var searchPhrase = $('#searchbox').val();
          ShowData(currentPage,size,searchPhrase)
        })

        //Delete function in modal 
        $(document).ready(function() {
          $(document).on('click', '#delete_button_modal', function() {
            $.ajax({
              type: 'DELETE',
              url: 'http://192.109.244.120:8080/api/v1/profile?id='+$userDeleteId,
              success: console.log('Deleted '+$userDeleteId),
              failed: console.log('failed')
            })
            $('#modal').modal('toggle');
          })
        })

        //Clicked on trash -> Show modal
        $(document).ready(function() {
          $(document).on('click', '.delete-button', function() {
            $userDeleteId = $(this).attr('data-id');
            $('#modal').modal('toggle');
          })
        })
        //Hide modal on cancel or X
        function hide() {
          $('#modal').modal('toggle');
        }
        //Hide modal on cancel or X
        function hide2() {
          $('#modal_channel').modal('toggle');
        }

        //Add new channel function
        $("#add_new_channel_button").click( function() {
          var channelName = $("#channel-name").val();
          var channelUrl = $("#channel-url").val();
          var channelPlatform = $("#channel-platform").val();
          if(channelName === "" || channelUrl === "" || channelPlatform === "null") return;
          $.ajax({
            type: 'POST',
            url: 'http://192.109.244.120:8080/api/v1/profile/',
            dataType: 'json',
            contentType: "application/json; charset=utf-8",
            data: '[{"id": null, "name": "'+channelName+'", "url": "'+channelUrl+'", "media": "'+channelPlatform+'"}]',
            success: console.log('Successed'),
            failed: console.log('Failed')
          })
          $('#modal_channel').modal('toggle');
        })

        //Add new channel -> show modal
        $(document).ready(function() {
          $(document).on('click', '#show_modal_new_channel', function() {
            $('#modal_channel').modal('toggle');
          })
        })


        //Pagination
        function ShowData(page,size,searchPhrase) {
          var apiUrl;
          if (searchPhrase == null)
            apiUrl = "http://192.109.244.120:8080/api/v1/profile?page="+page+"&size="+size+""
          else
            apiUrl = "http://192.109.244.120:8080/api/v1/profile/"+searchPhrase+"?page="+page+"&size="+size+"";

          $.ajax({
            url: apiUrl,
          })
            .done(res => {
              $('tbody').empty();
              currentPage = page;
              maxPages = res.totalPages;
              myData = res.data;
              for(var i=0; i<size;i++) {
                var channelUniqueName = myData[i].url.substring(myData[i].url.lastIndexOf('/') + 1);
                var lastChecked = myData[i].lastChecked.substr(0,10) + ' ' + myData[i].lastChecked.substr(11,5);
                $(".table").append( $('<tr><td>'+myData[i].name+'</td><td>'+channelUniqueName+'</td><td><a href="'+myData[i].url+'">'+myData[i].media+'</a></td><td>'+lastChecked+'</td><td><a href="./profile?id='+myData[i].id+'"><i class="bi bi-card-list"></i></a></td><td><a href="#" data-id="'+myData[i].id+'" class="delete-button"><i class="bi bi-trash"></i></a></td></tr>') );
              }
              $('.pagination').empty();
              if(currentPage > 2) {
                $(".pagination").append( $('<li class="page-item"><a class="page-link" href="#" onclick="ShowData(0,'+size+','+searchPhrase+')">1</a></li>') );
                $(".pagination").append( $('<li class="page-item"><a class="page-link" href="#">...</a></li>') );
              }
              for(var i=(currentPage-2);i<=(currentPage+2);i++) {
                if(i>=0 && i<maxPages) {
                  if(i==currentPage)
                    $(".pagination").append( $('<li class="page-item"><a class="page-link current-page" style="border: 1px solid #5858db !important;border-radius: 30px !important;color: white !important;" href="#" onclick="ShowData('+i+','+size+','+searchPhrase+')">'+(i+1)+'</a></li>') );
                  else
                    $(".pagination").append( $('<li class="page-item"><a class="page-link" href="#" onclick="ShowData('+i+','+size+','+searchPhrase+')">'+(i+1)+'</a></li>') );
                }
              }
              if(maxPages>3 && currentPage < (maxPages-3)) {
                $(".pagination").append( $('<li class="page-item"><a class="page-link" href="#">...</a></li>') );
                $(".pagination").append( $('<li class="page-item"><a class="page-link" href="#" onclick="ShowData('+(maxPages-1)+','+size+','+searchPhrase+')">'+(maxPages)+'</a></li>') );
              }
              
            });
        }

        //Load data on start
        ShowData(0,20,null);
    </script>
  </body>
</html>