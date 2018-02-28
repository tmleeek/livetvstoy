<?php
/*
$fromDate = "2015-07-28 00:00:00";
$toDate = "2015-07-30 00:00:00";

 echo $to_date = date('Y-m-d h:i:s A', strtotime("now") - 60 * 60 * 5); echo "\n";

echo $from_date = date('Y-m-d h:i:s A', strtotime("now") - 60 * 60 * 6); echo "\n";



echo date("Y-m-d H:i:s");*/
//$data_json = json_encode();
/*$data='{
  "offerId": "54F6AAF98CFC428BB366765286085840",
  "quantity": 1,
  "configId": "7DC0CDE4A40D4F72943DE2B2237C2965",
  "vendorId": "6DC0CDt4A40D4F72943DE2B2237C2965",
  "price": "50.00",
  "unitprice": "50.00",
  "customAttributes": [
    {
      "key": "Color",
      "value": "blue",
      "type": "DISPLAY",
      "group": 1
    },
    {
      "key": "Metal",
      "value": "steel",
      "type": "DISPLAY",
      "group": 2
    },
    {
      "key": "PERSONALIZED_IMAGE_URL",
      "value": "http://ll-us-i5.wal.co/dfw/dce07b8c-86f2/k2-_72d50f90-c24c-46f5-a6aa-68ac80030f57.v1. jpg-576dda55b153a2176f3ad33096fa95ca3b4a503b-webp-450x450.webp",
      "type": "IMAGE_URL"
    }
  ]
}';*/
$array1='{
    "offerId": "7DC0CDE4A40D4F72943DE2B2237C2965",
    "quantity": 1,
    "configId": "7DC0CDE4A40D4F72943DE2B2237C2965",
    "vendorId": "6DC0CDt4A40D4F72943DE2B2237C2965",
    "price": "50.00",
    "unitprice": "50.00",
    "customAttributes": [
        {
            "key": "Color",
            "value": "blue",
            "type": "DISPLAY",
            "group": 1
        },
        {
            "key": "Metal",
            "value": "steel",
            "type": "DISPLAY",
            "group": 2
        },
        {
            "key": "PERSONALIZED_IMAGE_URL",
            "value": "http://www.limogesjewelry.com/media/catalog/product/L1593119050GL.gif",
            "type": "IMAGE_URL"
        }
    ]
}';
$data = json_encode($array1);

$url='http://www-e16.walmart.com/cart/89624f23-ea2f-48cc-a999-22d7d44d716f/items';
/*$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response  = curl_exec($ch);
curl_close($ch);
echo $response;*/
$ch = curl_init();
/*curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
    'Content-Type: application/json',                                                                                
    'Content-Length: ' . strlen($data)                                                                       
));       



$output = curl_exec($ch);*/




curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_ENCODING, '');
//curl_setopt($ch,CURLOPT_FOLLOWLOCATION,true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT"); // note the PUT here

curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_HEADER, false);
//curl_setopt($ch,CURLOPT_FOLLOWLOCATION,true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
    'Content-Type: application/json',                                                                                
    'Content-Length: ' . strlen($data)                                                                       
));     

// execute the request
if(curl_exec($ch) === false)
{
    echo 'Curl error: ' . curl_error($ch);
}
else
{
    echo 'Operation completed without any errors';
}
$output = curl_exec($ch);





//echo "testttt";
// output the profile information - includes the header

echo $output;



?>
