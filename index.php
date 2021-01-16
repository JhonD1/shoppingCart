<?php
require_once "pdo.php";
session_start();
?>
<html>
<h1>Welcome to JD's Shopping Cart Database</h1>
<head>
    <title>Jhon Daroing's Shopping Cart</title>
    <script src="https://code.jquery.com/jquery-2.1.1.min.js" type="text/javascript"></script>
    
    <script>
    function cartAction(action,product_cart_id) {
        var queryString = "";
        if(action != "") {
            if(action == "add"){
                queryString = 'action='+action+'&cart_id='+ product_cart_id+'&quantity='+$("#qty_"+product_cart_id).val();
            }else if(action == "remove"){
                queryString = 'action='+action+'&cart_id='+ product_cart_id;
            }else if(action == "empty"){
                queryString = 'action='+action;
            }
        }
        
        jQuery.ajax({
        url: "add.php",
        data:queryString,
        type: "POST",
        success:function(data){
            $("#cart-item").html(data);
            if(action != "") {
                switch(action) {
                    case "add":
                        $("#add_"+product_cart_id).hide();
                        $("#added_"+product_cart_id).show();
                    break;
                    case "remove":
                        $("#add_"+product_cart_id).show();
                        $("#added_"+product_cart_id).hide();
                    break;
                    case "empty":
                        $(".btnAddAction").show();
                        $(".btnAdded").hide();
				    break;
                }	 
            }
        },
        error:function (){

        }
        });
    }
    </script>
</head>
<body>
<?php
// if ( isset($_SESSION['error']) ) {
//     echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
//     unset($_SESSION['error']);
// }
// if ( isset($_SESSION['success']) ) {
//     echo '<p style="color:green">'.$_SESSION['success']."</p>\n";
//     unset($_SESSION['success']);
// }
// ?>

<div id="product-grid">
<div class="txt-heading">Products</div>
<?php
$stmt = $pdo->query("SELECT * FROM cart");
$stmt->execute();
$stmt_arr = $stmt->fetchAll(\PDO::FETCH_ASSOC);
if (!empty($stmt_arr)) { 
    foreach($stmt_arr as $key=>$value){
?>
    <div class="product-item">
        <form id="frmCart">
        Item:<?php echo $stmt_arr[$key]["item"]; ?>
        Type:<div><strong><?php echo $stmt_arr[$key]["type"]; ?></strong></div>
        <div><input type="text" id="qty_<?php echo $stmt_arr[$key]["cart_id"]; ?>" name="quantity" value="1" size="2" />
        <?php
            $in_session = "0";
            if(!empty($_SESSION["cart_item"])) {
                $session_cart_id_array = array_keys($_SESSION["cart_item"]);
                if(in_array($stmt_arr[$key]["cart_id"],$session_cart_id_array)) {
                    $in_session = "1";
                }
            }
        ?>
        <input type="button" id="add_<?php echo $stmt_arr[$key]["cart_id"]; ?>" value="Add to cart" class="btnAddAction cart-action" onClick = "cartAction('add','<?php echo $stmt_arr[$key]["cart_id"]; ?>')" <?php if($in_session != "0") { ?>style="display:none" <?php } ?> />
        <input type="button" id="added_<?php echo $stmt_arr[$key]["cart_id"]; ?>" value="Added" class="btnAdded" <?php if($in_session != "1") { ?>style="display:none" <?php } ?> />
        </div>
        </form>
    </div>
<?php
        }
}
?>
</div>
<div class="clear-float"></div>
<div id="shopping-cart">
<div class="txt-heading">Shopping Cart <a id="btnEmpty" class="cart-action" onClick="cartAction('empty','');">Empty Cart</a></div>
<div id="cart-item"></div>
</div>
<script>
$(document).ready(function () {
cartAction('','');
})
</script>
</body>
</html>