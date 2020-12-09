<!DOCTYPE html>
<!--pass no karanyuuryoku nitaisite nanimokaesanai monndai -->
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_5-1</title>
    <style>article{margin:5px;}
        b{color:red;}
        .red{color:red;}
    </style>
    
</head>
<body>
        <h2>2ch的なもの</h2>
    <?php function passErr(){echo <<<EOD
    <script>
    const er= document.createElement("div");
    er.innerHTML= "<b>wrong pass word<b>";
    const aboveForm= document.getElementsByTagName("form")[0];
    document.body.insertBefore(er,aboveForm);</script>
    EOD;} ?>
    <form action="" method="post">
    	<h4>投稿フォーム</h4>
        <input type="text" name="messe" id="toukou" value="" required="required" >
    <input type="text" name="name" id="name" value="名無し" required="required" >
    <input type="hidden" value="" id="hiddenNum" name="chEdit" >
    <input type="password" name="passin" id="passIn" placeholder="pass" >
    <input type="submit" value="投稿" name="submit" >
    </form>
     <form action="" method="post">
     <h4>削除する</h4>
        <input type="text" name="killNum" 
        placeholder="番号を記入" required="required" >
 
        <input type="password" name="passDel" placeholder="pass" >
        <input type="submit" value="削除" name="submit" >
    </form>
    <form action="" method="post">
    <h4>編集番号</h4>
    <input type="text" name="editNum" value="" required="required" >
    <input type="password" name="passEd" placeholder="pass" >
        
    <input type="submit" value="呼出" name="submit" >
    </form>
  

 
    
    <?php
    $dsn = 'xxxxxxxxxx';
	$user = 'xxxxxx';
	$password = 'pass';
	$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

 $killNum=0;$altNum=0;
    
if(isset($_POST["messe"]) && $_POST["messe"]!=""){
        
$comment=mb_convert_encoding($_POST["messe"],"UTF-8");
    if(mb_strlen($comment)>=100){
        echo 'there are too long commnent<br>'.mb_strlen($comment)."charcters<br>";
    }else{
$time=date("Y/m/d/ h:i:s");
$name=$_POST["name"]; $pass=$_POST["passin"];
        
    if($_POST["chEdit"]!=""){
        $id=$_POST["chEdit"];
        $sql='UPDATE testrom SET time=:time,name=:name,comment=:comment,pass=:pass WHERE id=:id';
        $stmt=$pdo->prepare($sql);
        $stmt->bindParam(':id',$id,PDO::PARAM_STR);   
    }else{
        $stmt = $pdo->prepare("INSERT INTO testrom (time,name, comment,pass) VALUES (:time,:name, :comment,:pass)");
    }
$stmt->bindParam(':name', $name,PDO::PARAM_STR);
$stmt->bindParam(':comment', $comment,PDO::PARAM_STR);
$stmt -> bindParam(':time',$time,PDO::PARAM_STR);
$stmt -> bindParam(':pass',$pass,PDO::PARAM_STR);    
$stmt -> execute();
echo "<span style='color:red;'>".$comment . "　を受け付けました<br></span>"; 

     }//char count check
}
     
    if(isset($_POST["killNum"])){
    $passDel=$_POST["passDel"];
        $killNum=$_POST["killNum"];
       $sql="SELECT pass FROM testrom WHERE id=:id";   
    $stmt=$pdo->prepare($sql);
    $stmt->bindParam(':id',$killNum,PDO::PARAM_INT);
    $stmt->execute();                                
    $result=$stmt->fetchAll(); 
       // var_dump($result[0][0]);
    if($result[0][0]==Null){echo "<b>".$killNum."  was not exist</b><br>";}
    else if($result[0][0]==$passDel){
        $sql="DELETE FROM testrom WHERE id=:id";    
        $stmt=$pdo->prepare($sql);
    $stmt->bindParam(':id',$killNum,PDO::PARAM_INT);
        $stmt->execute();
        echo "<span class='red'>".$killNum."was deleted<br></span>";
        }
    else{
        echo "<b>password was not correct</b><br>";
        }
    }
    if(isset($_POST["editNum"])){
 $editNum=$_POST["editNum"];$passEd=$_POST["passEd"];
   $a='select * from testrom where id=:id';
    $sql=$pdo->prepare($a);
    $sql->bindParam(":id",$editNum,PDO::PARAM_INT);
    
    $sql->execute();
	$content = $sql->fetchAll();
        if(empty($content[0]))
            echo "<b>not exist number</b><br>";
        else{
	   $row=$content[0];
        if($passEd==$row[4]&&$passEd!=Null){
            editorMode($row,$editNum);
        }else{
            echo "<b>password was not correct</b><br>";
            }
        }
 	}    

 
    
    	
	$sql = 'SELECT * FROM testrom';
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){
        $sentence=explode("<>",$row["comment"]);
		//$rowの中にはテーブルのカラム名が入る
		echo $row['id'].',';
        echo $row["time"].",";
		echo $row['name'].',<br>';
        foreach($sentence as $line)
		echo $line.'<br>';
        echo $row['pass'];
	echo "<hr>";
	}
       
  
 function console_log( $data ){
  echo '<script>';
  echo 'console.log('. json_encode( $data ) .')';
  echo '</script>';
}//console出力用
function editorMode($unitArray,$editNum){
    console_log($editNum);
        echo <<<EOD
        <script>
        document.getElementById('name').value="$unitArray[2]";
        document.getElementById('toukou').value="$unitArray[3]";
        document.getElementById('passIn').value="$unitArray[4]";
    document.getElementById('hiddenNum').value="$unitArray[0]";</script>
    EOD;
    }
    
    ?>
</body>
</html>
