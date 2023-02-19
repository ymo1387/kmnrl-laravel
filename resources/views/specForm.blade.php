<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        input {
            margin-bottom: 1rem;
            width: 70rem;
            height: 3rem;
            font-size: 1.5rem;
        }
    </style>
</head>
<body>
    <div style="padding: 5rem">
        <form action="" method="post">
            @csrf
            <input type="number" name="product_id" style="border:none;" required><br>
            <input type="text" name="spec[]" id="">
            <input type="text" name="spec[]" id="">
            <input type="text" name="spec[]" id="">
            <input type="text" name="spec[]" id="">
            <input type="text" name="spec[]" id="">
            <input type="text" name="spec[]" id="">
            <input type="text" name="spec[]" id="">
            <input type="text" name="spec[]" id="">
            <input type="text" name="spec[]" id="">
            <input type="text" name="spec[]" id="">
            <input type="text" name="spec[]" id="">
            <input type="text" name="spec[]" id="">
            <input type="text" name="spec[]" id="">
            <br>
            <button type="submit">Save</button>
        </form>
    </div>
</body>
</html>
