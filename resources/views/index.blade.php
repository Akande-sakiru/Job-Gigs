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
    
    <div class="container">
        <div class="row justify-content-md-center">
            <h1 class="text-danger pt-4 text-center mb-4">List of Products</h1>
            <hr>
            <div class="pb-2">
                <a href="/create" class="btn btn-success">New Post</a>
                <div>
                    <table class="table table-hover">

                        <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Title</th>
                                <th scope="col">Price</th>
                                <th scope="col">Barcode</th>
                                <th scope="col">Description</th>
                            </tr>

                        </thead>
                        <tbody>
                            @foreach ($products as $product)
                                
                                <tr>
                                    <td>{{$product->id}}</td>
                                    <td>{{$product->title}}</td>
                                    <td>{{$product->price}}</td>
                                    <td>{!! DNS2D::getBarCodeHTML("$product->product_code",'QRCODE') !!}
                                        p - {{ $product->product_code }}
                                    </td>
                                    <td>{{$product->description}}</td>
                                </tr>

                            @endforeach
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>  