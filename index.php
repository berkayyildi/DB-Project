
<html>
<body>

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>


<nav class="navbar navbar-light bg-light">
  <span class="navbar-brand mb-0 h1">Market System</span>
</nav>

<center>

<?php

require_once("sqliconnect.php");    //SQL Bağlantı

if(@$_POST['city']){


    echo '<table><tr><td><b>Market In City</b></td> <td><b># Sales</b></td></tr>';

    $city_id = mysqli_real_escape_string($baglan, $_POST['city']);

    $result = $baglan->query("  SELECT Init_Markets.id AS init_market_id,
                                Markets.marketname
                                FROM Init_Markets 
                                LEFT JOIN Markets ON Markets.id = Init_Markets.market_id
                                WHERE city_id = '$city_id'");


    while ($row = $result->fetch_assoc()) {

            $result2 = $baglan->query("  SELECT count(product_id) AS numofsales FROM Sales WHERE salesman_id IN(
                                            SELECT salesman_id FROM Init_Works WHERE market_id = " . $row['init_market_id'] . " ) " );
            $row2 = $result2->fetch_assoc();
            echo "<tr><td>" . $row['marketname'] . "</td><td> " . $row2['numofsales'] . "</td></tr>";                 
    }

    echo "</table>";
    
    echo "<br><br><a href='./'>Go Mainpage</a>";

}elseif(@$_POST['request'] == "BA"){    // BA REQUEST

    $result = $baglan->query("  SELECT Sales.product_id,
                                COUNT(Sales.product_id) AS frequency,
                                Products.productname
                                FROM Sales
                                LEFT JOIN Products ON Sales.product_id = Products.id
                                GROUP BY product_id
                                ORDER BY COUNT(product_id) DESC");
    echo '<table><tr><td><b>Product Name</b></td> <td><b># Sales</b></td></tr>';
    while ($row = $result->fetch_assoc()) {
                    echo "<tr><td>" . $row['productname'] . "</td><td> " . $row['frequency'] . "</td></tr>"; 
    }
    echo "</table>";
    echo "<br><br><a href='./'>Go Mainpage</a>";


}elseif(@$_POST['request'] == "BB"){    // BB REQUEST

   $result = $baglan->query("  SELECT
                                COUNT(Sales.salesman_id) AS frequency,
                                Salesmans.salesmanname
                                FROM Sales
                                LEFT JOIN Salesmans ON Sales.salesman_id = Salesmans.id
                                GROUP BY Sales.salesman_id
                                ORDER BY COUNT(Sales.salesman_id) DESC");

    echo '<table><tr><td><b>Salesman Name</b></td> <td><b># Sales</b></td></tr>';
    while ($row = $result->fetch_assoc()) {
                    echo "<tr><td>" . $row['salesmanname'] . "</td><td> " . $row['frequency'] . "</td></tr>"; 
    }
    echo "</table>";
    echo "<br><br><a href='./'>Go Mainpage</a>";


}elseif(@$_POST['request'] == "BC"){    // BC REQUEST

    $salesman_id = mysqli_real_escape_string($baglan, $_POST['salesman_id']);

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
                     echo "<td>" . $row['price'] . "</td>";
                     echo "<td>" . $row['customername'] . "</td>";
                     echo "</tr>";
     }
     echo "</table><hr>";
     echo "Seller <b>" . $salesmanname . "</b>'s Total Sales Amount: <b>" . $totalprice . " TL</b>";

     echo "<br><br><a href='./'>Go Mainpage</a>";
 
 
}elseif(@$_POST['request'] == "BD"){    // BD REQUEST

    $customer_id = (int)mysqli_real_escape_string($baglan, $_POST['customer_id']);
    
    if(!(int)$customer_id > 0){die ("Please Select Valid Customer! <br><br><a href='./'>Go Mainpage</a>");}

    echo "Custumer Invoice<hr>";

    $result = $baglan->query("  SELECT Salesmans.salesmanname,
    Products.productname,
    Products.price,
    Customers.customername,
    DATE_FORMAT(Sales.sale_date,'%d-%m-%Y') AS sale_date
    FROM Sales
    LEFT JOIN Products ON Sales.product_id = Products.id
    LEFT JOIN Customers ON Sales.customer_id = Customers.id
    LEFT JOIN Salesmans ON Sales.salesman_id = Salesmans.id
    WHERE customer_id = '$customer_id' ");

    echo '<table><tr><td><b>Product Name</b></td> <td><b>Price</b></td> <td><b>Salesman Name</b></td> </tr>';
    $totalprice = 0;
    while ($row = $result->fetch_assoc()) {
        $totalprice += $row['price'];
        $customername = $row['customername'];
        echo "<tr>";
        echo "<td>" . $row['productname'] . "</td>";
        echo "<td>" . $row['price'] . "</td>";
        echo "<td>" . $row['salesmanname'] . "</td>";
        echo "<td>" . $row['sale_date'] . "</td>";
        echo "</tr>";
    }
    echo "</table><hr>";
    echo "Customer <b>" . $customername . "</b>'s Total Invoice Amount: <b>" . $totalprice . " TL</b>";

    echo "<br><br><a href='./'>Go Mainpage</a>";

}elseif(@$_POST['request'] == "CA"){    // BD REQUEST

    /*
    SELECT Sales.salesman_id AS SALESMANID, Init_Works.id AS INITMARKETID, Init_Markets.city_id, Cities.`name`, Districts.district_name
    FROM Sales
    LEFT JOIN Init_Works ON Sales.salesman_id = Init_Works.salesman_id
    LEFT JOIN Init_Markets ON Init_Works.market_id = Init_Markets.id
    LEFT JOIN Cities ON Init_Markets.city_id = Cities.id
    LEFT JOIN Districts ON Cities.disctinct_id= Districts.id
    */


  
    $result = $baglan->query("  SELECT count(Sales.id) AS numofsales ,district_name
                                FROM Sales
                                LEFT JOIN Init_Works ON Sales.salesman_id = Init_Works.salesman_id
                                LEFT JOIN Init_Markets ON Init_Works.market_id = Init_Markets.id
                                LEFT JOIN Cities ON Init_Markets.city_id = Cities.id
                                LEFT JOIN Districts ON Cities.disctinct_id= Districts.id
                                GROUP BY Districts.id ");
    echo '<table>';
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['numofsales'] . "</td>";
        echo "<td>" . $row['district_name'] . "</td>";
        echo "</tr>";
        $data[] = $row['numofsales'];
        $labels[] = $row['district_name'];
    }
    echo "</table>";

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

}elseif(@$_POST['request'] == "CB"){    // BD REQUEST


    $result = $baglan->query("  SELECT count(Sales.id) AS numofsales ,Markets.marketname
                                FROM Sales
                                LEFT JOIN Init_Works ON Sales.salesman_id = Init_Works.salesman_id
                                LEFT JOIN Init_Markets ON Init_Works.market_id = Init_Markets.id
                                LEFT JOIN Markets ON Init_Markets.market_id = Markets.id
                                GROUP BY Markets.id ");

    echo '<table>';
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
    
    echo "<table><tr><td width='33%'>";

    echo "(A)<br><br>";

    echo "<form method='POST'>
    <input value='A' type='hidden' name='request'>
    <select name='city' onchange='this.form.submit()' >";
    $result = $baglan->query("SELECT * FROM Cities");
    
    echo '<option value="-">Select City</option>'; 
    while ($row = $result->fetch_assoc()) {
                    echo '<option value="'.$row['id'].'">'.$row['name'].'</option>'; 
    }
    echo "</select>";
    echo "</form>";

    echo "</td><td width='33%'>";

    echo '(B)<br><br>
    <form method="POST">
    <input value="BA" type="hidden" name="request">
    <button type="submit">Show Sales producy by product</button>
    </form>

    <hr>

    <form method="POST">
    <input value="BB" type="hidden" name="request">
    <button type="submit">Show # Saled Product saleman by saleman</button>
    </form>
    
    <hr>
    ';

    echo '
    <form method="POST">
    <input value="BC" type="hidden" name="request">
    <select name="salesman_id">
    ';
    
    $result = $baglan->query("SELECT * FROM Salesmans");
    echo '<option value="-">Select Salesman</option>'; 
    while ($row = $result->fetch_assoc()) {
                    echo '<option value="'.$row['id'].'">'.$row['salesmanname'].'</option>'; 
    }
    echo "</select>";

    echo '<br><br><button type="submit">Show Sale Info of Salesman</button>
    </form>
    ';




    echo '
    <hr>

    <form method="POST">
    <input value="BD" type="hidden" name="request">
    <select name="customer_id">
    ';
    
    $result = $baglan->query("SELECT * FROM Customers");
    echo '<option value="-">Select Customer</option>'; 
    while ($row = $result->fetch_assoc()) {
                    echo '<option value="'.$row['id'].'">'.$row['customername'].'</option>'; 
    }
    echo "</select>";

    echo '<br><br><button class="btn btn-primary" type="submit">Show Customer Invoice</button>
    </form>
    ';

    

    echo "
    </td><td width='33%'>
    (C)
    <br><br>

    <form method='POST'>
    <input value='CA' type='hidden' name='request'>
    <button class='btn btn-primary' type='submit'>Sales piechart per districts</button>
    </form>


    <form method='POST'>
    <input value='CB' type='hidden' name='request'>
    <br><br>
    <button class='btn btn-primary' type='submit'>Sales piechart per markets</button>
    </form>

    </td></tr></table>";
    
}

?>


</body>
</html>