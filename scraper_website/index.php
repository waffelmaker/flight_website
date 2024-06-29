<?php
include("database.php");

// Query to get the maximum price
$max_price_result = mysqli_query($conn, "SELECT MAX(price_myr_str) as max_price FROM outboundData");
$max_price_row = mysqli_fetch_assoc($max_price_result);
$max_price = $max_price_row['max_price'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flight Search</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <style>
        
        .sidenav {
            height: 100%;
            width: 220px;
            position: fixed;
            z-index: 1;
            top: 0;
            left: 0;
            background-color: #191c1a;
            overflow-x: hidden;
            padding-top: 20px;
            color: white;
            display: flex; 
            flex-direction: column; 
            align-items: center; 
        }

        .sidebar-image {
            width: 160px; 
            max-width: 160px; 
            height: 160px; 
            margin: 0 auto; 
        }

        input[type="range"] {
            width: 90%;
            margin: 10px 0;
        }

        .menu {
            width: 160px; 
            padding: 10px;
            margin: 5px;
            border: none;
            border-radius: 5px;
        }

        .search-form {
            display: flex; 
            flex-direction: column; 
            align-items: center; 
            width: 100%; 
        }

        .search-form input[type="date"],
        .search-form input[type="submit"] {
            padding: 10px;
            margin: 5px;
            border: none;
            border-radius: 5px;
        }

        .search-form input[type="date"] {
            width: 160px;
        }

        .search-form input[type="submit"] {
            background-color: #FCE205;
            color: white;
            cursor: pointer;
            color: #000;
        }

        /* CSS for results */
        .results {
            margin-top: 20px;
            margin-left: 220px;
            padding: 20px;
            background-color: #fff;
        }

        .result-item {
            border: 1px solid lightgrey;
            padding: 10px;
            margin-bottom: 10px;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        html, body {
            height: 100%;
        }
        
        .wrapper {
            width: 100%;
            background: #fff;
            border-radius: 5px;
            border: 1px solid lightgrey;
            border-top: 0px;
            margin-top: 20px;
        }
        
        .wrapper .title {
            background: #007bff;
            color: #fff;
            font-size: 16px;
            font-weight: 500;
            line-height: 40px;
            text-align: center;
            border-bottom: 1px solid #006fe6;
            border-radius: 5px 5px 0 0;
        }
        
        .wrapper .form {
            padding: 10px;
            max-height: 90px;
            overflow-y: auto;
        }
        
        .wrapper .form .inbox {
            width: 100%;
            display: flex;
            align-items: baseline;
        }
        
        .wrapper .form .user-inbox {
            justify-content: flex-end;
            margin: 13px 0;
        }
        
        .wrapper .form .inbox .icon {
            height: 30px;
            width: 30px;
            color: #fff;
            text-align: center;
            line-height: 30px;
            border-radius: 50%;
            font-size: 18px;
            background: #007bff;
        }
        
        .wrapper .form .inbox .msg-header {
            max-width: 53%;
            margin-left: 10px;
        }
        
        .form .inbox .msg-header p {
            color: #fff;
            background: #007bff;
            border-radius: 10px;
            padding: 8px 10px;
            font-size: 12px;
            word-break: break-all;
        }
        
        .form .user-inbox .msg-header p {
            color: #333;
            background: #efefef;
        }
        
        .wrapper .typing-field {
            display: flex;
            height: 50px;
            width: 100%;
            align-items: center;
            justify-content: space-evenly;
            background: #efefef;
            border-top: 1px solid #d9d9d9;
            border-radius: 0 0 5px 5px;
        }
        
        .wrapper .typing-field .input-data {
            height: 40px;
            width: 160px;
            position: relative;
        }
        
        .wrapper .typing-field .input-data input {
            height: 100%;
            width: 100%;
            outline: none;
            border: 1px solid transparent;
            padding: 0 80px 0 15px;
            border-radius: 3px;
            font-size: 12px;
            background: #fff;
            transition: all 0.3s ease;
        }
        
        .typing-field .input-data input:focus {
            border-color: rgba(0,123,255,0.8);
        }
        
        .input-data input::placeholder {
            color: #999999;
            transition: all 0.3s ease;
        }
        
        .input-data input:focus::placeholder {
            color: #bfbfbf;
        }
        
        .wrapper .typing-field .input-data button {
            position: absolute;
            right: 5px;
            top: 50%;
            height: 30px;
            width: 50px;
            color: #fff;
            font-size: 12px;
            cursor: pointer;
            outline: none;
            opacity: 0;
            pointer-events: none;
            border-radius: 3px;
            background: #007bff;
            border: 1px solid #007bff;
            transform: translateY(-50%);
            transition: all 0.3s ease;
        }
        
        .wrapper .typing-field .input-data input:valid ~ button {
            opacity: 1;
            pointer-events: auto;
        }
        
        .typing-field .input-data button:hover {
            background: #006fef;
        }

        .airplane-container {
            position: absolute;
            left: 20px; /* Adjust left positioning as needed */
            top: 20px; /* Adjust top positioning as needed */
            width: 120px; /* Adjust size as needed */
            height: 120px; /* Adjust size as needed */
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .airplane {
            width: 50px; /* Adjust size as needed */
            height: 50px; /* Adjust size as needed */
            animation: circle 5s linear infinite;
        }

        @keyframes circle {
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(360deg);
            }
        }
    </style>
</head>
<body>

<div class="airplane-container">
    <img src="airplane.png" alt="Airplane" class="airplane">
</div>

<div class="sidenav">

<img src="logo.png" class="sidebar-image">

    <form class="search-form" method="GET" action="index.php">
        <label for="outbound_destination">Outbound Destination:</label>
        <select id="outbound_destination" name="outbound_destination" class = "menu">
            <option value="">All Destinations</option>
            <option value="SIN" <?php if(isset($_GET['outbound_destination']) && $_GET['outbound_destination'] == 'SIN') echo 'selected'; ?>>SIN</option>
            <option value="SGN" <?php if(isset($_GET['outbound_destination']) && $_GET['outbound_destination'] == 'SGN') echo 'selected'; ?>>SGN</option>
            <option value="DPS" <?php if(isset($_GET['outbound_destination']) && $_GET['outbound_destination'] == 'DPS') echo 'selected'; ?>>DPS</option>
            <option value="MNL" <?php if(isset($_GET['outbound_destination']) && $_GET['outbound_destination'] == 'MNL') echo 'selected'; ?>>MNL</option>
            <option value="PNH" <?php if(isset($_GET['outbound_destination']) && $_GET['outbound_destination'] == 'PNH') echo 'selected'; ?>>PNH</option>
            <option value="BKK" <?php if(isset($_GET['outbound_destination']) && $_GET['outbound_destination'] == 'BKK') echo 'selected'; ?>>BKK</option>
            <option value="RGN" <?php if(isset($_GET['outbound_destination']) && $_GET['outbound_destination'] == 'RGN') echo 'selected'; ?>>RGN</option>
        </select>
        <br>
        <label for="outbound_date">Outbound Date:</label>
        <br>
        <input type="date" id="outbound_date" name="outbound_date">
        <br>
        <label for="price_range">Price Range (MYR):</label>
        <input type="range" id="price_range" name="price_range" min="0" max="<?php echo $max_price + 1; ?>" value="<?php echo isset($_GET['price_range']) ? $_GET['price_range'] : $max_price + 1 ; ?>" oninput="document.getElementById('range_value').innerHTML = this.value;">
        <span id="range_value"><?php echo isset($_GET['price_range']) ? $_GET['price_range'] : $max_price + 1; ?></span>
        <br>
        <input type="submit" value="Search">
    </form>
    
    <!-- Chatbot -->
    <div class="wrapper">
        <div class="title">CABot</div>
        <div class="form">
            <div class="bot-inbox inbox">
                <div class="icon">
                    <i class="fas fa-user"></i>
                </div>
                <div class="msg-header">
                    <p>Hello there, how can I help you?</p>
                    <p>To Clarify : <br> SIN (Singapore) <br> SGN (Vietnam) <br> DPS (Bali) <br> MNL (Manila)<br> PNH (Cambodia)<br> BKK (Thailand)<br> RGN (Myanmar)</p>
                </div>
            </div>
        </div>
        <div class="typing-field">
            <div class="input-data">
                <input id="data" type="text" placeholder="Type something here.." required>
                <button id="send-btn">Send</button>
            </div>
        </div>
    </div>
</div>

<div class="results">
<?php
include("database.php");

// Check if the form has been submitted
if (isset($_GET['outbound_destination']) || isset($_GET['outbound_date'])) {
    // Initialize variables for outbound search
    $outbound_destination = isset($_GET['outbound_destination']) ? $_GET['outbound_destination'] : '';
    $outbound_date = isset($_GET['outbound_date']) ? $_GET['outbound_date'] : '';
    $price_range = isset($_GET['price_range']) ? $_GET['price_range'] : $max_price; // Use default max price if not provided

    // Outbound flight query
    $outbound_sql = "SELECT * FROM outboundData WHERE 1=1";
    if (!empty($outbound_destination)) {
        $outbound_sql .= " AND destination LIKE '%" . mysqli_real_escape_string($conn, $outbound_destination) . "%'";
    }
    if (!empty($outbound_date)) {
        $outbound_sql .= " AND date = '" . mysqli_real_escape_string($conn, $outbound_date) . "'";
    }
    if (!empty($price_range)) {
        $outbound_sql .= " AND price_myr_str <= " . mysqli_real_escape_string($conn, $price_range);
    }

    $outbound_result = mysqli_query($conn, $outbound_sql);

    echo "<h2>Flights to ASEAN Countries </h2>";
    if ($outbound_result) {
        if (mysqli_num_rows($outbound_result) > 0) {
            while ($row = mysqli_fetch_assoc($outbound_result)) {
                echo "<div class='result-item'>";
                echo "<strong>Airline :</strong> " . htmlspecialchars($row['airline']) . "<br>";
                echo "<strong>Destination :</strong> " . htmlspecialchars($row['destination']) . "<br>";
                echo "<strong>Date :</strong> " . htmlspecialchars($row['date']) . "<br>";
                echo "<strong>Price (MYR) :</strong> " . htmlspecialchars($row['price_myr_str']) . "<br>" . "<br>";
                echo "<strong>Extra Details :</strong>" . "<br>";
                echo "<strong>Flight Time :</strong> " . htmlspecialchars($row['flight_time']) . "<br>";
                echo "<strong>Duration :</strong> " . htmlspecialchars($row['duration']) . "<br>";
                echo "<strong>URL :</strong> <a href='" . htmlspecialchars($row['url']) . "' target='_blank'>View Flight</a><br>";
                echo "</div>";
            }
        } else {
            echo "No outbound flights found!";
        }
    } else {
        echo "Error executing query: " . mysqli_error($conn);
    }

    mysqli_close($conn);
}
?>
</div>

<script>
    $(document).ready(function(){
        $("#send-btn").on("click", function(){
            $text = $("#data").val();
            $msg = '<div class="user-inbox inbox"><div class="msg-header"><p>'+ $text +'</p></div></div>';
            $(".form").append($msg);
            $("#data").val('');
            
            // start ajax code
            $.ajax({
                url: 'msg.php',
                type: 'POST',
                data: 'text='+$text,
                success: function(result){
                    $replay = '<div class="bot-inbox inbox"><div class="icon"><i class="fas fa-user"></i></div><div class="msg-header"><p>'+ result +'</p></div></div>';
                    $(".form").append($replay);
                    // when chat goes down the scroll bar automatically comes to the bottom
                    $(".form").scrollTop($(".form")[0].scrollHeight);
                }
            });
        });
    });
</script>

</body>
</html>