<?php

require_once "db_config.php";

class Helpers {
        
    public static function ReadDashboardValue($valueToRead, $email) {
        $conn = db_config::getInstance()->getConnection();
        $sql_dashboard_format = "CALL VISUALIZZA_DASHBOARD_%s('%s');";
        $sql_query_dashboard = sprintf($sql_dashboard_format, $valueToRead, $email);

        try {
            $query_result = $conn->query($sql_query_dashboard);
            $row = $query_result->fetch_assoc();
            $value = $row['VALUE'];
        } catch (mysqli_sql_exception $e) {
            $value = "??";
        }
        mysqli_close($conn);
        return $value;
    }

    public static function POST_data($url, $data) {
        // $data must be in the form "key1=value1&key2=value2"
        echo "POST<br>".$url."<br>".$data."<br>";
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $headers = array("Content-Type: application/x-www-form-urlencoded",);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

        echo  print_r($curl)."<br>";

        $resp = curl_exec($curl);

        echo print_r($resp, true)."<br>";

        curl_close($curl);
        return $resp;
    }

    public static function POST_data2($url, $postVars = array()){
        //Transform our POST array into a URL-encoded query string.
        $postStr = http_build_query($postVars);
        //Create an $options array that can be passed into stream_context_create.
        $options = array(
                'http' =>
                array(
                        'method'  => 'POST', //We are using the POST HTTP method.
                        'header'  => 'Content-type: application/x-www-form-urlencoded',
                        'content' => $postStr //Our URL-encoded query string.
                    )
        );
        //Pass our $options array into stream_context_create.
        //This will return a stream context resource.
        $streamContext  = stream_context_create($options);
        //Use PHP's file_get_contents function to carry out the request.
        //We pass the $streamContext variable in as a third parameter.
        $result = file_get_contents($url, false, $streamContext);
        //If $result is FALSE, then the request has failed.
        if($result === false){
            //If the request failed, throw an Exception containing
            //the error.
            $error = error_get_last();
            throw new Exception('POST request failed: ' . $error['message']);
        }
        //If everything went OK, return the response.
        return $result;
        }
    }
?>