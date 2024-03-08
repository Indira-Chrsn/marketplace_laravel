<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
    </head>
    <body>
        <h2>Add Product</h2>
        <br>

        <form action="{{ route('products.store') }}" method="POST">
            @csrf
        
            <label for="name">Name: </label>
            <input type="text" name="name" value="name">

            <label for="price">price: </label>
            <input type="text" name="price" value="price">

            <label for="stock">stock: </label>
            <input type="text" name="stock" value="stock">

            <input type="submit" value="submit">
        </form>

    </body>
</html>