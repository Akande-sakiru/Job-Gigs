<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Generate Barcode In Laravel</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">


</head>
<body>
    
    <div class="container-fluid">
        <div class="row justify-content-md-center">
            <div class="col-md-9">
                <div class="card px-5 mt-3 shadow">
                    <h1 class="text-primary pt-4 text-center mb-4">Generate Barcode in Laravel</h1>
                    
                    <form action="/post" method="POST">
                        @csrf
                        <label for="">Title:</label>
                        <input type="text" class="form-control mb-3" name="title" required>
                        <label for="">price:</label>
                        <input type="text" class="form-control mb-3" name="price" required>
                        <label for="">Description:</label>
                        <textarea name="description" class="form-control mb-3" cols="30" rows="5" required></textarea>
                        <button type="submit" class="btn btn-success col-md-3">Submit</button>
                    </form>
                </div>
            </div>
            
        </div>
    </div>
</body>
</html>