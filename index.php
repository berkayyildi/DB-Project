
<html>
<body>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/css/bootstrap-select.min.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/bootstrap-select.min.js"></script>

<nav class="navbar navbar-light bg-light">
  <span class="navbar-brand mb-0 h1">Market System</span>
  <span class="navbar-text">
      Berkay YILDIZ
    </span>
</nav>

<center>

<?php

require_once("sqliconnect.php");    //SQL Bağlantı

$request = @$_POST['request'];

if($request == "A"){

    echo '<table><tr><td><b>Market In City</b></td> <td><b># Sales</b></td></tr>';

    $city_id = mysqli_real_escape_string($baglan, $_POST['city']);

    $result = $baglan->query("  SELECT Init_Markets.id AS init_market_id,
                                Markets.marketname
                                FROM Init_Markets 
                                LEFT JOIN Markets ON Markets.id = Init_Markets.market_id
                                WHERE city_id = '$city_id'");

    $allsalesincity = 0;

    while ($row = $result->fetch_assoc()) {

            $result2 = $baglan->query("  SELECT count(product_id) AS numofsales FROM Sales WHERE salesman_id IN(
                                            SELECT salesman_id FROM Init_Works WHERE market_id = " . $row['init_market_id'] . " ) " );
            $row2 = $result2->fetch_assoc();
            $allsalesincity += $row2['numofsales'];
            $aa[] = $row['marketname'];
            $bb[] = $row2['numofsales'];
            echo "<tr><td>" . $row['marketname'] . "</td><td> " . $row2['numofsales'] . "</td></tr>";    
    }


    for( $j = 0; $j<5; $j++ ){

        echo "   
        <div class='progress' style='width: 30% ; height: 20px;'>
            <div class='progress-bar' role='progressbar' style='width: " . ($bb[$j]/$allsalesincity)*100 . "%' aria-valuenow='" . 
            ($bb[$j]/$allsalesincity)*100 . "' aria-valuemin='0' aria-valuemax='100'>$aa[$j] </div>
        </div>
        ";  
    }


    echo "</table>";
    
    echo "<br><br><a href='./'>Go Mainpage</a>";

    
}elseif($request == "B"){

    $market_id = mysqli_real_escape_string($baglan, $_POST['market']);

 
    $marketname = $baglan->query("  SELECT marketname FROM Markets WHERE id = $market_id LIMIT 1");
    $singlerow = $marketname->fetch_assoc();
    echo "<h2>" . $singlerow['marketname'] . " Market</h2><hr>";
  

    echo'
    <form method="POST">
    <input value="BA" type="hidden" name="request">
    <input value="'.$market_id.'" type="hidden" name="market">
    <button class="btn btn-primary" type="submit">Show Sales product by product</button>
    </form>

    <hr>

    <form method="POST">
    <input value="BB" type="hidden" name="request">
    <input value="'.$market_id.'" type="hidden" name="market">
    <button class="btn btn-primary" type="submit">Show # Saled Product saleman by saleman</button>
    </form>
    
    <hr>
    ';

    echo '
    <form method="POST">
    <input value="BC" type="hidden" name="request">
    <input value="'.$market_id.'" type="hidden" name="market">
    <select class="selectpicker" data-live-search="true" data-style="btn-success" name="salesman_id">
    ';
    
    $result = $baglan->query("  SELECT * FROM Salesmans WHERE id IN(
                                    SELECT salesman_id FROM Init_Works WHERE market_id IN (
                                        SELECT id FROM Init_Markets WHERE market_id = $market_id)
                                    )");
    echo '<option value="-">Select Salesman</option>'; 
    while ($row = $result->fetch_assoc()) {
                    echo '<option value="'.$row['id'].'">'.$row['salesmanname'].'</option>'; 
    }
    echo "</select>";

    echo '<br><br><button class="btn btn-primary" type="submit">Show Sale Info of Salesman</button>
    </form>
    ';


    echo '
    <hr>

    <form method="POST">
    <input value="BD" type="hidden" name="request">
    <input value="'.$market_id.'" type="hidden" name="market">
    <select class="selectpicker" data-live-search="true" data-style="btn-success" name="customer_id">
    ';
    
    $result = $baglan->query("  SELECT Customers.id, Customers.customername, Salesmans.salesmanname, Init_Markets.market_id FROM Sales
                                LEFT JOIN Customers ON Sales.customer_id = Customers.id
                                LEFT JOIN Salesmans ON Sales.salesman_id = Salesmans.id
                                LEFT JOIN Init_Works ON Sales.salesman_id = Init_Works.salesman_id
                                LEFT JOIN Init_Markets ON Init_Works.market_id = Init_Markets.id
                                WHERE Init_Markets.market_id = $market_id
                                GROUP BY Customers.id");
    echo '<option value="-">Select Customer</option>'; 
    while ($row = $result->fetch_assoc()) {
                    echo '<option value="'.$row['id'].'">'.$row['customername'].'</option>'; 
    }
    echo "</select>";

    echo '<br><br><button class="btn btn-primary" type="submit">Show Customer Invoice</button>
    </form>
    ';

    echo "<br><br><a href='./'>Go Mainpage</a>";


}elseif($request == "BA"){    // BA REQUEST

    $market_id = mysqli_real_escape_string($baglan, $_POST['market']);
    
    $marketname = $baglan->query("  SELECT marketname FROM Markets WHERE id = $market_id LIMIT 1");
    $singlerow = $marketname->fetch_assoc();
    echo "<h2>" . $singlerow['marketname'] . " Market</h2><hr>";

    $result = $baglan->query("  SELECT Sales.product_id,
                                COUNT(Sales.product_id) AS frequency,
                                Products.productname
                                FROM Sales
                                LEFT JOIN Products ON Sales.product_id = Products.id
                                
                                LEFT JOIN Salesmans ON Sales.salesman_id = Salesmans.id
                                LEFT JOIN Init_Works ON Sales.salesman_id = Init_Works.salesman_id
                                LEFT JOIN Init_Markets ON Init_Works.market_id = Init_Markets.id
                                WHERE Init_Markets.market_id = '$market_id'
                                GROUP BY product_id
                                ORDER BY COUNT(product_id) DESC");
                                
    echo '<table><tr><td><b>Product Name</b></td> <td><b># Sales</b></td></tr>';
    while ($row = $result->fetch_assoc()) {
                    echo "<tr><td>" . $row['productname'] . "</td><td> " . $row['frequency'] . "</td></tr>"; 
    }
    echo "</table>";
    echo "<br><br><a href='./'>Go Mainpage</a>";


}elseif($request == "BB"){    // BB REQUEST

    $market_id = mysqli_real_escape_string($baglan, $_POST['market']);

    $result = $baglan->query("  SELECT
                                COUNT(Sales.salesman_id) AS frequency,
                                Salesmans.salesmanname
                                FROM Sales
                                LEFT JOIN Salesmans ON Sales.salesman_id = Salesmans.id

                                LEFT JOIN Init_Works ON Sales.salesman_id = Init_Works.salesman_id
                                LEFT JOIN Init_Markets ON Init_Works.market_id = Init_Markets.id
                                WHERE Init_Markets.market_id = '$market_id'
                                GROUP BY Sales.salesman_id
                                ORDER BY COUNT(Sales.salesman_id) DESC");

    echo '<table><tr><td><b>Salesman Name</b></td> <td><b># Sales</b></td></tr>';
    while ($row = $result->fetch_assoc()) {
                    echo "<tr><td>" . $row['salesmanname'] . "</td><td> " . $row['frequency'] . "</td></tr>"; 
    }
    echo "</table>";
    echo "<br><br><a href='./'>Go Mainpage</a>";


}elseif($request == "BC"){    // BC REQUEST

    $salesman_id = mysqli_real_escape_string($baglan, $_POST['salesman_id']);
    $market_id = mysqli_real_escape_string($baglan, $_POST['market']);

    if(!(int)$salesman_id > 0){die ("Please Select Valid Salesman! <br><br><a href='./'>Go Mainpage</a>");}

    $result = $baglan->query("  SELECT Salesmans.salesmanname,
                                Products.productname,
                                Products.price,
                                Customers.customername
                                FROM Sales
                                LEFT JOIN Products ON Sales.product_id = Products.id
                                LEFT JOIN Customers ON Sales.customer_id = Customers.id
                                LEFT JOIN Salesmans ON Sales.salesman_id = Salesmans.id
                                WHERE salesman_id = '$salesman_id' ");
                            
     echo '<table><tr><td><b>Product Name</b></td> <td><b>Price</b></td> <td><b>Customer Name</b></td> </tr>';
     $totalprice = 0;
     while ($row = $result->fetch_assoc()) {
        $totalprice += $row['price'];
        $salesmanname = $row['salesmanname'];
                     echo "<tr>";
                     echo "<td>" . $row['productname'] . "</td>";
                     echo "<td>" . $row['price'] . " TL</td>";
                     echo "<td>" . $row['customername'] . "</td>";
                     echo "</tr>";
     }
     echo "</table><hr>";
     echo "Seller <b>" . $salesmanname . "</b>'s Total Sales Amount: <b>" . $totalprice . " TL</b>";

     echo "<br><br><a href='./'>Go Mainpage</a>";
 
 
}elseif($request == "BD"){    // BD REQUEST

    $customer_id = (int)mysqli_real_escape_string($baglan, $_POST['customer_id']);
    $market_id = mysqli_real_escape_string($baglan, $_POST['market']);
    
    if(!(int)$customer_id > 0){die ("Please Select Valid Customer! <br><br><a href='./'>Go Mainpage</a>");}

    echo "<h2>Custumer Invoice</h2><hr>";

    $result = $baglan->query("  SELECT Salesmans.salesmanname,
    Products.productname,
    Products.price,
    Customers.customername,
    Markets.marketname AS marketname,
    Markets.id AS marketid,
    DATE_FORMAT(Sales.sale_date,'%d-%m-%Y') AS sale_date
    FROM Sales
    LEFT JOIN Products ON Sales.product_id = Products.id
    LEFT JOIN Customers ON Sales.customer_id = Customers.id
        
    LEFT JOIN Salesmans ON Sales.salesman_id = Salesmans.id
    LEFT JOIN Init_Works ON Sales.salesman_id = Init_Works.salesman_id
    LEFT JOIN Init_Markets ON Init_Works.market_id = Init_Markets.id
    LEFT JOIN Markets ON Init_Markets.market_id = Markets.id
    WHERE customer_id = '$customer_id' ");

    echo '<table><tr><td><b>Market Name</b></td><td><b>Product Name</b></td> <td><b>Price</b></td> <td><b>Salesman</b><td><b>Date</b></td></td> </tr>';
    $totalprice = 0;
    $totalitem = 0;
    while ($row = $result->fetch_assoc()) {
        $totalprice += $row['price'];
        $totalitem++;
        $customername = $row['customername'];
        if ( $row['marketid'] == $market_id ){  $color = " bgcolor='#00FF00"; } else { $color = ""; }
        echo "<tr $color'>";
        echo "<td>" . $row['marketname'] . "</td>";
        echo "<td>" . $row['productname'] . "</td>";
        echo "<td>" . $row['price'] . " TL</td>";
        echo "<td>" . $row['salesmanname'] . "</td>";
        echo "<td>" . $row['sale_date'] . "</td>";
        echo "</tr>";
    }
    echo "</table><hr>";
    echo "Customer <b>" . $customername . "</b>'s Total Invoice Amount: <b>" . $totalprice . " TL</b> with <b>$totalitem</b> Item<br>";
    echo "<font color='gray' size='2'>(Items which bought in selected market has been marked as green)</font>";

    echo "<br><br><a href='./'>Go Mainpage</a>";

}elseif($request == "CA"){    // BD REQUEST
  
    $result = $baglan->query("  SELECT count(Sales.id) AS numofsales ,district_name
                                FROM Sales
                                LEFT JOIN Init_Works ON Sales.salesman_id = Init_Works.salesman_id
                                LEFT JOIN Init_Markets ON Init_Works.market_id = Init_Markets.id
                                LEFT JOIN Cities ON Init_Markets.city_id = Cities.id
                                LEFT JOIN Districts ON Cities.disctinct_id= Districts.id
                                GROUP BY Districts.id ");
    echo '<table><tr align="center"><td><b># Sales</b></td><td><b>District</b></td>';
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['numofsales'] . "</td>";
        echo "<td>" . $row['district_name'] . "</td>";
        echo "</tr>";
        $data[] = $row['numofsales'];
        $labels[] = $row['district_name'];
    }
    echo "</table><hr>";

    $labels = json_encode($labels);
    $data = json_encode($data);

    echo "

    <script src='https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.bundle.min.js'></script>
    <canvas id='myChart' width='800' height='400'></canvas>
    <script>
    

    var ctx = document.getElementById('myChart');
    var myChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: $labels,
            datasets: [{
                data:  $data,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)'
                ],
                borderColor: [
                    'rgba(255,99,132,1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
                    responsive: false
        }
    });
    </script>

    <br><br><a href='./'>Go Mainpage</a>
";

}elseif($request == "CB"){    // BD REQUEST


    $result = $baglan->query("  SELECT count(Sales.id) AS numofsales ,Markets.marketname
                                FROM Sales
                                LEFT JOIN Init_Works ON Sales.salesman_id = Init_Works.salesman_id
                                LEFT JOIN Init_Markets ON Init_Works.market_id = Init_Markets.id
                                LEFT JOIN Markets ON Init_Markets.market_id = Markets.id
                                GROUP BY Markets.id ");

    echo '<table><tr align="center"><td><b># Sales</b></td><td><b>Market</b></td>';
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['numofsales'] . "</td>";
        echo "<td>" . $row['marketname'] . "</td>";
        echo "</tr>";
        $data[] = $row['numofsales'];
        $labels[] = $row['marketname'];
    }
    echo "</table><hr>";


    $labels = json_encode($labels);
    $data = json_encode($data);

    echo "

    <script src='https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.bundle.min.js'></script>
    <canvas id='myChart' width='800' height='400'></canvas>
    <script>
    

    var ctx = document.getElementById('myChart');
    var myChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: $labels,
            datasets: [{
                data:  $data,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(53, 254, 125, 0.2)',
                    'rgba(200, 100, 80, 0.2)',
                    'rgba(100, 100, 30, 0.2)',
                    'rgba(95, 50, 64, 0.2)',
                    'rgba(123, 190, 70, 0.2)'
                ],
                borderColor: [
                    'rgba(255, 99, 132,1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(53, 254, 125, 1)',
                    'rgba(200, 100, 80, 1)',
                    'rgba(100, 100, 30, 1)',
                    'rgba(95, 50, 64, 1)',
                    'rgba(123, 190, 70, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
                    responsive: false
        }
    });
    </script>

    <br><br><a href='./'>Go Mainpage</a>
";


}else{
    
    echo "<table><tr><td width='30%'>";

    echo "(A)<br><br>";
    echo "Select City to show sales in city<br><br>";
    echo "<form method='POST'>
    <input value='A' type='hidden' name='request'>
    <select class='selectpicker' data-live-search='true' data-style='btn-primary' name='city' onchange='this.form.submit()' >";
    $result = $baglan->query("SELECT * FROM Cities");
    
    echo '<option value="-">Select City</option>'; 
    while ($row = $result->fetch_assoc()) {
                    echo '<option value="'.$row['id'].'">'.$row['name'].'</option>'; 
    }
    echo "</select>";
    echo "</form>";

    echo "</td><td width='30%'>";
    
    echo '(B)<br><br>';

    echo "Select Market to get detailed info<br><br>
    <form method='POST'>
    <input value='B' type='hidden' name='request'>
    <select class='selectpicker' data-style='btn-info' name='market' onchange='this.form.submit()' >";
    $result = $baglan->query("SELECT * FROM Markets");
    
    echo '<option value="-">Market Brands</option>'; 
    while ($row = $result->fetch_assoc()) {
                    echo '<option value="'.$row['id'].'">'.$row['marketname'].'</option>'; 
    }
    echo "</select>";
    echo "</form>";

    echo "
    </td><td width='30%'>
    (C)<br><br>

    <form method='POST'>
    <input value='CA' type='hidden' name='request'>
    <button class='btn btn-success' type='submit'>Sales piechart per districts</button>
    </form>


    <form method='POST'>
    <input value='CB' type='hidden' name='request'>
    <button class='btn btn-warning' type='submit'>Sales piechart per markets</button>
    </form>

    </td></tr></table>";
    
}

?>

</body>
</html>