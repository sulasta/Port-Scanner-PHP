<?php include "header.php"; ?>

<?php
set_time_limit(300);
$domain = '';
if (!empty($_POST['domain'])) {
    $domain = $_POST['domain'];
}
?>



<form method="post" action="<?=$_SERVER['PHP_SELF']?>">
  <div class="mb-3 mt-3">
    <label for="text" class="form-label">Domínio/IP:</label>
    <input type="text" class="form-control" value="<?=$domain?>" id="domain" placeholder="locaweb.com.br" name="domain">
  </div>
   <button type="submit" class="btn btn-primary">Les Go</button>
</form>

<hr>  


<div id="resultado">
<?php

// Scan de portas ativas 

$step = 1;
$portas = ['21', '22', '80', '443', '1194', '1433','3306','3389', '5432'];

if(!empty($_POST['domain'])) {

    $ports = $portas;
    $results = [];

    foreach($ports as $port) {
        $fp = @fsockopen($_POST['domain'], $port, $err, $err_string, 1);

        if (!$fp) {
            $results[$port] = false;
        } else {
            $results[$port] = true;
            fclose($fp);
        }
    }

    echo "<b> Checagem de portas: </b><br/>";
    foreach($results as $port => $val) {
        $service = getservbyport($port, "tcp");
        echo "Port $port ($service): ";

        if($val) {
            echo "<span style=\"color:green\">OK</span><br/>";
        } else {
            echo "<span style=\"color:red\">Inacessível</span><br/>";
        }
    }
}
?>


<?php
// Busca pelo IP do site

if(!empty($_POST['domain'])) {
    $ip = gethostbyname($domain);
    $ipreverso = gethostbyaddr($ip);
    $servidor = substr($ipreverso, 0, strpos($ipreverso, "."));
    echo "<br/><b>IP do site/Servidor </b>: $ip<br/>";

    echo "<b> DNS Reverso:</b> $ipreverso <br/>  ";
}
?>




<?php

if(!empty($_POST['domain'])) {


// Ping no site/IP
    $output = shell_exec("ping -c5 $domain");
    echo "<br/><b> Ping: </b> <br/>";
    echo "<pre>$output</pre><hr> ";

}
 ?>

<?php

if(!empty($_POST['domain'])) {
// NSLOOKUP
//Busca de MX
    if(dns_get_mx($domain,$mx_details)){
        foreach($mx_details as $key=>$value){
        echo "<b>Servidor de email </b>";
        echo "$key => $value <br>";
    }
  }

    echo "<br><br><br>";
// Busca de servidores DNS
    $ns = dns_get_record($domain, DNS_NS);
    //print_r($ns);
    echo "<b> Servidores de DNS: </b><br/>";
    echo $ns[0]["target"];
    echo "<br>";
    echo $ns[1]["target"];
    echo "<br>";
    echo $ns[2]["target"];

    echo "<br><br><br>";
// Busca de TXT
    $txt = dns_get_record($domain, DNS_TXT);
    echo "<b> Entradas TXT: </b><br/>";
    echo $txt[0]["txt"];
    echo "<br>";
    echo $txt[1]["txt"];
    echo "<br>";
    echo $txt[2]["txt"];
    echo "<br>";
    echo $txt[3]["txt"];
    echo "<br>";
    echo $txt[4]["txt"];
    echo "<br>";
    echo $txt[5]["txt"];

    echo "<br><br><br>";
// Busca de SOA
    $soa = dns_get_record($domain, DNS_SOA);
    echo "<b> SOA: </b><br/>";
    echo "Host: ";
    echo $soa[0]["host"];
    echo "<br>";
    echo "TTL: ";
    echo $soa[0]["ttl"];
    echo "<br>";
    echo "Autoridade: ";
    echo $soa[0]["mname"];
    echo "<br>";
    echo "Contato administrativo: ";
    echo $soa[0]["rname"];
    echo "<br>";
    echo "Serial: ";
    echo $soa[0]["serial"];
    echo "<br>";
    echo "Refresh: ";
    echo $soa[0]["refresh"];
    echo "<br>";
    echo "Retry: ";
    echo $soa[0]["retry"];
    echo "<br>";
    echo "Expire: ";
    echo $soa[0]["expire"];
    echo "<br>";
    echo "TTL minímo: ";
    echo $soa[0]["minimum-ttl"];



    echo "<br><br><br><hr> ";

}
 ?>

</div>
<?php include "footer.php"; ?>