<?php
require_once "pdo.php";
session_start();

if(!empty($_POST["action"])) {
    if($_POST["action"] == "add") {
        if(!empty($_POST["quantity"])) {
            $stmt = $pdo->prepare("SELECT * FROM cart WHERE cart_id=:cid");
            $stmt->bindValue(':cid', $_POST['cart_id']);
            $stmt->execute();
            $arr_stmt = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            $stmt_arr = array($arr_stmt[0]["cart_id"]=>array('item'=>$arr_stmt[0]["item"], 'type'=>$arr_stmt[0]["type"], 'qty'=>$_POST["quantity"]));
            
            if(!empty($_SESSION["cart_item"])) {
                if(in_array($arr_stmt[0]["cart_id"],$_SESSION["cart_item"])) {
                    foreach($_SESSION["cart_item"] as $k => $v) {
                            if($arr_stmt[0]["cart_id"] == $k)
                                $_SESSION["cart_item"][$k]["qty"] = $_POST["quantity"];
                    }
                } else {
                    $_SESSION["cart_item"] = array_merge($_SESSION["cart_item"],$stmt_arr);
                }
            } else {
                $_SESSION["cart_item"] = $arr_stmt;
            }
        }
    }
    else if($_POST["action"] == "remove"){
		if(!empty($_SESSION["cart_item"])) {
			foreach($_SESSION["cart_item"] as $k => $v) {
					if($_POST["cart_id"] == $k)
						unset($_SESSION["cart_item"][$k]);
					if(empty($_SESSION["cart_item"]))
						unset($_SESSION["cart_item"]);
			}
		}
	}else if($_POST["action"] == "empty"){
        unset($_SESSION["cart_item"]);
    }
}


?>


<?php
if(isset($_SESSION["cart_item"])){
    $item_total = 0;
?>	
<table cellpadding="10" cellspacing="1">
<tbody>
    <tr>
        <th><strong>Item</strong></th>
        <th><strong>Type</strong></th>
        <th><strong>Quantity</strong></th>
    </tr>	
    <?php		
        foreach ($_SESSION["cart_item"] as $item){
        ?>
            <tr>
                <td><strong><?php echo(htmlentities($item["item"])); ?></strong></td>
                <td><?php echo(htmlentities($item["type"])); ?></td>
                <td><?php echo(htmlentities($item["qty"])); ?></td>
                <td><a onClick="cartAction('remove','<?php echo $item["cart_id"]; ?>')">Remove Item</a></td>
				</tr>
            </tr>
            <?php
        }
        ?>
    </tbody>
</table>		
  <?php
}
?>

