<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Döviz Dönüştürücü</title>
</head>
<body>

<h2>Döviz Dönüştürücü</h2>

<?php
// API'den döviz kurlarını alacak olan fonksiyon
function getExchangeRate($from_currency, $to_currency){
    $api_key = 'YOUR_API_KEY'; // Kullanacağınız API'nin API anahtarı
    $url = "https://api.exchangerate-api.com/v4/latest/{$from_currency}";

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            "apikey: $api_key"
        ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        echo "Curl Error: " . $err;
        return null;
    } else {
        $data = json_decode($response, true);
        return $data['rates'][$to_currency];
    }
}

if(isset($_POST['convert'])){
    $from_currency = $_POST['from_currency'];
    $to_currency = $_POST['to_currency'];
    $amount = $_POST['amount'];

    $exchange_rate = getExchangeRate($from_currency, $to_currency);

    if($exchange_rate){
        $converted_amount = $amount * $exchange_rate;
        echo "<p>$amount $from_currency = $converted_amount $to_currency</p>";
    } else {
        echo "<p>Dönüştürme başarısız.</p>";
    }
}
?>

<form method="post">
    <label for="amount">Miktar:</label><br>
    <input type="text" id="amount" name="amount" required><br><br>

    <label for="from_currency">Dönüştürülecek Para Birimi:</label><br>
    <select id="from_currency" name="from_currency" required>
        <option value="USD">USD</option>
        <option value="EUR">EUR</option>
        <option value="TRY">TR</option>
        <!-- Diğer para birimlerini buraya ekleyebilirsiniz -->
    </select><br><br>

    <label for="to_currency">Dönüştürülen Para Birimi:</label><br>
    <select id="to_currency" name="to_currency" required>
        <option value="USD">USD</option>
        <option value="EUR">EUR</option>
        <option value="TRY">TR</option>
        <!-- Diğer para birimlerini buraya ekleyebilirsiniz -->
    </select><br><br>

    <input type="submit" name="convert" value="Dönüştür">
</form>

</body>
</html>
