<?php
    spl_autoload_register(function($name){
        $name = strtolower($name);
        require_once("../classes/$name.class.php");
    });
    
$conn = Utility::makeConnection();

$numberOfProd =  $conn->query("SELECT count(product_id) as num_of_products from product");

if($numberOfProd->num_rows > 0 && $numberOfProd = $numberOfProd->fetch_assoc()){
  
 $numberOfProd = $numberOfProd['num_of_products'];
 if($numberOfProd > 0){
   
    //for displaying the query when no product is found
    $real_query = $query;
    //If it the previous array is set
    $query = preg_split('/\W/', $query, 0);
    $query = array_filter($query);
    $tempQuery = $query;
    $query = array_values(array_unique(remove_stop_words($query)));
    if(count($query) == 0){
      $query = $tempQuery;
    }
    $query = array_map('PorterStemmer::Stem', $query);
    $query = array_map('strtoupper', $query);
    
    //get all products with atleast one of the query terms
    $strForSql = "keywords LIKE ";
    foreach($query as $key => $term){
      if($key == 0){
        $strForSql .= "'% $term %' ";
      }else{
        $strForSql .= " OR keywords LIKE '% $term %'";
      }
    }
    
    $strForSql = "($strForSql)";

    if(!empty($category) && $category > 1){
      $strForSql .= " AND prd_category = $category";
    }
    //get the docfreq of every term in the query
    $jsonQuery = json_encode($query);
    $jsonQuery = preg_replace("/[\[\]]/", "", $jsonQuery);
    
    $jsonQuery = "($jsonQuery)";
    $queryMagnitude = sqrt(count($query)); //since every term is taken with a freq of 1
    $magnitudeArray = [];
    $scores = [];

    //proceed, else nothing was found
    //get all products that satisfy the query
    $qualifiedProducts = queryTable("product", "*", "hide_product = 0 and ($strForSql)", $conn);

    //building the SQL to obtain qualified products
    $sqlStr = "SELECT term, docfreq ";
    if($qualifiedProducts){
      foreach($qualifiedProducts as $product){
        $product_id = $product['product_id'];
        $hyp = queryTable("inverted_index", "SUM(`$product_id` * `$product_id`) as square_sum ", "`$product_id` > 0", $conn);
        $hyp = $hyp[0]['square_sum'];
        $magnitudeArray += ["$product_id" => sqrt($hyp)];
        $scores += ["$product_id" => 0];
        $sqlStr .= ", `$product_id` ";
      }
  
      $sqlStr .= " from inverted_index where term in $jsonQuery";
    
      $termDetail = $conn->query($sqlStr);
      
      
      if($termDetail->num_rows > 0){
        while($_termDetail = $termDetail->fetch_assoc()){

          $currentDocFreq = $_termDetail['docfreq'];

          $idf = log10($numberOfProd/$currentDocFreq);

          foreach($scores as $product_id => $score){
            $denonminator = ($queryMagnitude * $magnitudeArray[$product_id]);
            $score = acos(($idf * $_termDetail[$product_id]/$magnitudeArray[$product_id])/$denonminator);
            $scores[$product_id] += $score;
          }
        }
     }
      asort($scores);
      $scores = array_keys($scores);
      
      //printing out the products
      echo "<p style='display:none' id='ids'>". json_encode($scores) ."</p>";
      $sql = "SELECT * from product where product_id = ? AND hide_product = 0";
      $stmt = $conn->prepare($sql);
      $sql2 = "SELECT category_name from product_category where category_id = ?";
      $stmt2 = $conn->prepare($sql2);
      

  
  
  $endPos = ($startPos + $intervalCount) < count($scores)?($intervalCount + $startPos): count($scores);
  $plural = count($scores)>1?"s":""; 

    ////
    if($startPos == 0){
      echo "<h3>Product$plural matching your query</h3>";
    }
    
    
    for($i = $startPos; $i < $endPos; $i++){
        $product_id = $scores[$i];
      
        $stmt->bind_param('i', $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $num_rows = $result->num_rows;
        $rows = $result->fetch_assoc();

        $product_id = $rows['product_id'];
        $prd_name = $rows['prd_name'];
        $prod_price = $rows['prd_price'];
        $prd_cat = $rows['prd_category'];
        $imgSrc = $rows['prd_image'];

        $stmt2->bind_param('i', $prd_cat);
        $stmt2->execute();
        $category_name = $stmt2->get_result();
        $category_name = $category_name->fetch_assoc()['category_name'];

        if(empty($imgSrc) || !file_exists("../uploads/$imgSrc")){
          $imgSrc = returnImageSrc($product_id);
        }else{
          $imgSrc ="./uploads/$imgSrc";
        }

        //check if the product is in the cart
      $addToCartText = "<button  class='order__btn' onclick = 'add_to_cart(this,$product_id, 1)'>Add to Cart</button>";

      $cartDetail = isInCart($product_id);
      if($cartDetail != false){
        $index = $cartDetail[0];
        $qty = $cartDetail[1];
        $addToCartText = "<div class='srch__cart' id='cart-idx-$index'>
        <i class='fas fa-minus-circle' onclick=\"changeQty('-', $product_id)\"></i>
        <input type='number' id='qty-$product_id' min='0' value='$qty'>
        <i class='fas fa-plus-circle' onclick=\"changeQty('+', $product_id)\"></i>
      </div>";
      }

      echo "<div class='res__item' id = \"$product_id\" >
      <img src='$imgSrc' loading='lazy' class='res__img' alt='$prd_name image'>
      <div class='res__item__wrapper'>
      <div id='v-$product_id' onclick='display_details(this)'>
      <h2>$prd_name</h2>
      <p class=\"item__price item__price__search\">Price: <span class=\"amount\">$prod_price</span> Ksh.</p>
      </div>
      <div class='flex_btn_container' id='prd-$product_id'>
      <button  class='order__btn view__btn' id='view-$product_id' onclick = 'display_details(this)'>View</button>
      $addToCartText
      </div>
      </div>
       </div>";
          
        }

        $stmt->close();
        $stmt2->close();
        //print the buttons
        echo "<div class='flex_btn_container mt1 mb10'>";
        if(($startPos + $numOfRes) < count($scores)){
          //show more button
          echo "<button class='btn__counter' onclick='showMore()' id='show-more'>Show more</button>";
        }
        echo "</div>";

    }
    else{
      //no products matching the query was found
      $cat_text = ($category > 1)?"In the chosen category":"";

      echo "<div class='res__item' style=\"padding: 1.125rem; color: black;\" >
      <h3 style='text-align: center;'>No results found for \"$real_query\" $cat_text</h3>
      <p class='msg'>We might have not added products that satisfy your query. This is because we are still stocking Carata. We beg your patience. You can check back in a day or two.</p>
      <p class='msg'>Sometimes the query may be the cause of no results, you can modify the query (query and category) and try again</p>
      </div>";

      insertIntoMissingQueries($real_query);
    }

  }
 }
 else{
  //no products matching the query was found
  $cat_text = ($category > 1)?"In the chosen category":"";

      echo "<div class='res__item' style=\"padding: 1.125rem; color: black;\" >
      <h3 style='text-align: center;'>No results found for \"$real_query\" $cat_text</h3>
      <p class='msg'>We might have not added products that satisfy your query. This is because we are still stocking Carata. We beg your patience. You can check back in a day or two.</p>
      <p class='msg'>Sometimes the query may be the cause of no results, you can modify the query (query and category) and try again</p>
      </div>";

      
  //save the query to add the product later

}
 
  $conn->close();

  

  exit;




?>