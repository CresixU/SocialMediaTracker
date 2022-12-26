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
    <script src="https://cdn.jsdelivr.net/combine/npm/chart.js@4.0.1,npm/chart.js@4.0.1/dist/chart.min.js,npm/chart.js@4.0.1/dist/helpers.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.0.1/dist/chart.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.0.1/dist/chart.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.0.1/dist/helpers.min.js"></script>
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
            <h1><a id="stream-title" href="#">Ładowanie danych...</a></h1>
        </div>
        <div class="row">
            <div class="col text-center">
                <p class="title">Nazwa kanału</p>
                <p><a href="" id="channel-name"></a></p>
            </div>
            <div class="col text-center">
                <p class="title">Źródło</p>
                <p id="channel-source"></p>
            </div>
            <div class="col text-center">
                <p class="title">Rozpoczęty</p>
                <p id="stream-start"></p>
            </div>
            <div class="col text-center">
                <p class="title">Zakończony</p>
                <p id="stream-end"></p>
            </div>
            <div class="col text-center">
                <p class="title">Czas trwania</p>
                <p id="stream-total"></p>
            </div>
            <div class="col text-center">
                <p class="title">Średnia ilość widzów</p>
                <p id="stream-avg-views"></p>
            </div>
            <div class="col text-center">
                <p class="title">Największa ilość widzów</p>
                <p id="stream-top-views"></p>
            </div>
        </div>
        <div>
  <canvas id="myChart"></canvas>
</div>
    <script>
        const ctx = document.getElementById('myChart');
        var myData = [];
        var streams = [];
        var time = [];
        var watchers = [];
        var avg = 0;
        var streamId = document.getElementById('web-user-id').innerHTML;
        var streamTitle, streamUrl, streamSource, channelName,channelUrl, chart;

        //Chart
        chart = new Chart(ctx, {
            type: 'bar',
            data: {
            labels: time,
            datasets: [{
                label: 'Widzowie',
                data: watchers,
                borderWidth: 1
            }]
            },
            options: {
            scales: {
                y: {
                    beginAtZero: false,
                    suggestedMin: 1
                }
            }
            }
        });
        var getData = async function ShowChart() {
            await $.ajax({
                    url: `http://192.109.244.120:8080/api/v3/watchers/stream/`+streamId,
                })
                .done(res => {
                    myData = res;
                    myData.map(e => time.push(e.at.substr(11,5)));
                    myData.map(e => watchers.push(e.watchers));
                    chart.data.datasets[0].data = watchers;
                    chart.data.labels = time;
                    chart.update();
                    console.log("Update");

                    $.ajax({
                        url: "http://192.109.244.120:8080/api/v1/streaming?page=0&size=9999",
                    })
                    .done(res => {
                        for(var i=0; i<myData.length; i++) {
                            avg+=parseInt(myData[i].watchers);
                        }
                        var stream = res.data.find(s => s.id == streamId);
                        streamTitle = stream.title;
                        streamUrl = stream.url;
                        streamSource = stream.profile.media;
                        channelName = stream.profile.name;
                        channelUrl = "./profile.php?id="+stream.profile.id+"&page=0&size=10";
                        DisplayStreamData();
                    })
                });
            }
        getData();

        setInterval(getData, 30000);

        function DisplayStreamData() {
            document.getElementById('stream-title').innerHTML = streamTitle;
            document.getElementById('stream-title').setAttribute("href", streamUrl);
            document.getElementById('channel-source').innerHTML = streamSource;
            document.getElementById('channel-name').innerHTML = channelName;
            document.getElementById('channel-name').setAttribute("href", channelUrl);
            document.getElementById('stream-avg-views').innerHTML = Math.round(avg/=myData.length);
            document.getElementById('stream-start').innerHTML = time[0];
            if(watchers[watchers.length-1] != 0) document.getElementById('stream-end').innerHTML = "Nadal trwa";
            else document.getElementById('stream-end').innerHTML = time[time.length-1];
            document.getElementById('stream-total').innerHTML = CalcData(new Date(myData[myData.length-1].at) - new Date(myData[0].at));
            document.getElementById('stream-top-views').innerHTML = Math.max.apply(Math, watchers);

            watchers = [];
            time = []; 
        }

        function CalcData(ms) {
            var hours, min, seconds = 0;
            seconds = Math.floor(ms/1000);
            min = Math.floor(seconds/60);
            hours = Math.floor(min/60);
            return hours+"h "+min%60+"m "+seconds%60%60+"s";
        }
    </script>
  </body>
</html>