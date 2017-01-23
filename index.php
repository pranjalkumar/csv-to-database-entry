<?php
if(isset($_POST['addr'])&&!empty($_POST['addr'])&&isset($_POST['max'])&&!empty($_POST['max'])&&isset($_POST['min'])&&!empty($_POST['min']))
{
    $max=$_POST['max'];
    
    $min=$_POST['min'];
    $url=$_POST['addr'];
    $file=$url;
    $csv = array_map('str_getcsv', file($file));
    array_walk($csv, function(&$a) use ($csv) {
        $a = array_combine($csv[0], $a);
    });
    array_shift($csv);
    $count=count($csv);
    if($max>$count)
    {echo "The number of rows entered is invalid";
    die();}
//    echo $csv[98]['name'];
//    echo $table;echo $min;echo $url;echo $file;
//    echo $max;
    try
    {$db=new PDO("mysql:host=localhost;dbname=excel",'root','Costa@26');
    $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    for($i=$min-1;$i<$max;$i++)
    {
        $query="Insert into excel VALUES(:sno,:name,:brand);";
        $data=$db->prepare($query);
        $data->bindParam(':sno',$csv[$i]['sno']);
        $data->bindParam(':name',$csv[$i]['name']);
        $data->bindParam(':brand',$csv[$i]['brand']);
        $data->execute();

    }


$db=null;
}catch(PDOException $e)
    {echo $e->getMessage();}
}
?>
<!DOCTYPE HTML>
<html>
<head>
    <title>csv to database</title>
    <meta charset="UTF-8">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
<div id="form">
    <form action="index.php" method="post">
        <input type="text" name="addr" id="addr">
        <label for="min" >Enter the starting row for data entry</label>
        <input type="text" name="min" id="min" placeholder="Starting row ">
        <label for="max">Enter the ending row</label>
        <input type="text" name="max" id="max" placeholder="Ending row ">
<!--        <label for="table">Enter the name of table</label>-->
<!--        <input type="text" name="table" id="table" placeholder="Table name">-->
        <button type="submit">Export the data</button>
    </form>
</div>
</body>
</html>
