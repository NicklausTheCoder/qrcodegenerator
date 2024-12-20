
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"></script>


<div class="container text-center">
    <div class="row">



        @foreach ($qrCodes as $item)

    
            <div class="col-md-2">
                <a href="" id="container{{$loop->index}}">
                    {{ $item }}
                </a>
                <button class="mt-2 btn btn-info text-light" onclick="downloadSVG({{$loop->index}})">Download SVG</button>
            </div>


            <script>
                function downloadSVG(index) {
                    const svg = document.getElementById('container' + index).innerHTML;
                    const blob = new Blob([svg], { type: 'image/svg+xml' });
                    const element = document.createElement("a");
                    element.download = "w3c.svg";
                    element.href = window.URL.createObjectURL(blob);
                    element.click();
                    element.remove();
                }
            </script>


        @endforeach






    </div>
</div>



