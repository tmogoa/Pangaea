<?php
    spl_autoload_register(function($name){
        $name = strtolower($name);
        require_once("../classes/$name.class.php");
    });
    

$query = isset($_GET['q'])?filter_var($_GET['q'], FILTER_SANITIZE_STRING):"";
if(empty($query)){
    exit;
}

$conn = Utility::makeConnection();
$stmt = $conn->prepare("SELECT count(articleId) as num_of_articles from product");
$stmt->execute();
$numberOfArticles =  $stmt->fetchAll();

if(count($numberOfArticles) > 0 && $numberOfArticles = $numberOfArticles[0]){
  
 $numberOfArticles = $numberOfArticles['num_of_articles'];
 if($numberOfArticles > 0){
   
    //for displaying the query when no product is found
    $real_query = $query;
    //If it the previous array is set
    $query = preg_split('/\W/', $query, 0);
    $query = array_filter($query);
    $tempQuery = $query;
    $query = array_values(array_unique(Utility::removeStopwords($query)));
    if(count($query) == 0){
      $query = $tempQuery;
    }
    $query = array_map('PorterStemmer::Stem', $query);
    $query = array_map('strtoupper', $query);
    
    //get all products with atleast one of the query terms
    $terms = [];
    $strForSql = "keywords LIKE ";
    foreach($query as $key => $term){
      if($key == 0){
        $strForSql .= "'% ? %' ";
        $terms[] = $term;
      }else{
        $strForSql .= " OR keywords LIKE '% ? %'";
        $terms[] = $term;
      }
    }
    
    $strForSql = "($strForSql)";

    //get the docfreq of every term in the query
    $jsonQuery = json_encode($query);
    $jsonQuery = preg_replace("/[\[\]]/", "", $jsonQuery);
    
    $jsonQuery = "($jsonQuery)";
    $queryMagnitude = sqrt(count($query)); //since every term is taken with a freq of 1
    $magnitudeArray = [];
    $scores = [];

    //proceed, else nothing was found
    //get all articles that satisfy the query
    $terms[] = 'published';
    $qualifiedArticles = Utility::queryTable("articleKeywords", "*", "publishStatus= ? and($strForSql)", $terms, $conn);

    //building the SQL to obtain qualified articles
    $sqlStr = "SELECT term, docfreq ";
    if($qualifiedArticles){
      foreach($qualifiedArticles as $article){
        $articleId = $article['articleId'];
        $hyp = Utility::queryTable("index", "SUM(`$articleId` * `$articleId`) as square_sum ", "`$articleId` > ?",[0], $conn);
        $hyp = $hyp[0]['square_sum'];
        $magnitudeArray += ["$articleId" => sqrt($hyp)];
        $scores += ["$articleId" => 0];
        $sqlStr .= ", `$articleId` ";
      }
  
      $sqlStr .= " from `index` where term in $jsonQuery";
    
      $termDetail = $conn->query($sqlStr);
      
      
      if($termDetail->num_rows > 0){
          foreach($termDetail as $_termDetail){
            $currentDocFreq = $_termDetail['docfreq'];

            $idf = log10($numberOfArticles/$currentDocFreq);
  
            foreach($scores as $articleId => $score){
              $denonminator = ($queryMagnitude * $magnitudeArray[$articleId]);
              $score = acos(($idf * $_termDetail[$articleId]/$magnitudeArray[$articleId])/$denonminator);
              $scores[$articleId] += $score;
            }    
          }
     }
      asort($scores);
      $scores = array_keys($scores);
      
      //printing out the products
      //echo "<p style='display:none' id='ids'>". json_encode($scores) ."</p>";
      $sql = "SELECT * from article where articleId = ? AND publishStatus = ?";
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
        $articleId = $scores[$i];
      
        $stmt->bind_param('i', $articleId);
        $stmt->execute();
        $result = $stmt->get_result();
        $num_rows = $result->num_rows;
        $rows = $result->fetch_assoc();

        $articleId = $rows['articleId'];
        $prd_name = $rows['prd_name'];
        $prod_price = $rows['prd_price'];
        $prd_cat = $rows['prd_category'];
        $imgSrc = $rows['prd_image'];

        $stmt2->bind_param('i', $prd_cat);
        $stmt2->execute();
        $category_name = $stmt2->get_result();
        $category_name = $category_name->fetch_assoc()['category_name'];

        if(empty($imgSrc) || !file_exists("../uploads/$imgSrc")){
          $imgSrc = returnImageSrc($articleId);
        }else{
          $imgSrc ="./uploads/$imgSrc";
        }

        //check if the product is in the cart
      $addToCartText = "<button  class='order__btn' onclick = 'add_to_cart(this,$articleId, 1)'>Add to Cart</button>";

      $cartDetail = isInCart($articleId);
      if($cartDetail != false){
        $index = $cartDetail[0];
        $qty = $cartDetail[1];
        $addToCartText = "<div class='srch__cart' id='cart-idx-$index'>
        <i class='fas fa-minus-circle' onclick=\"changeQty('-', $articleId)\"></i>
        <input type='number' id='qty-$articleId' min='0' value='$qty'>
        <i class='fas fa-plus-circle' onclick=\"changeQty('+', $articleId)\"></i>
      </div>";
      }

      echo "<div class='res__item' id = \"$articleId\" >
      <img src='$imgSrc' loading='lazy' class='res__img' alt='$prd_name image'>
      <div class='res__item__wrapper'>
      <div id='v-$articleId' onclick='display_details(this)'>
      <h2>$prd_name</h2>
      <p class=\"item__price item__price__search\">Price: <span class=\"amount\">$prod_price</span> Ksh.</p>
      </div>
      <div class='flex_btn_container' id='prd-$articleId'>
      <button  class='order__btn view__btn' id='view-$articleId' onclick = 'display_details(this)'>View</button>
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