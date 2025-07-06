# Custom WHMCS API
This project provides additional API endpoints for WHMCS, allowing you to expand its functionality beyond the default offerings. It includes endpoints for managing products, services, and other resources.

## Installation
To install the custom API endpoints, follow these steps:
1. Add the files in the `includes/api/` directory to your WHMCS installation.
2. Add the hook in the `includes/hooks/` directory to register the new API endpoints.
3. Set the appropriate permissions for the API endpoints in your WHMCS admin panel.

## License
This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.

## Usage

### GetProductsActive
Currently WHMCS API GetProducts [api-reference/getproducts](https://developers.whmcs.com/api-reference/getproducts/) retrieves all products and no information regarding if the product is active or not.

So I just created my own API call to handle this and only retrieve the visible (not hidden) products.

Just upload to /includes/api .

#### Request Parameters "GetProductsActive" 

| Parameter | Type | Description | Required |
| ------ | ------ | ------ | ------ |
| action | string | “GetProductsActive” | Required |
| pid | int | Obtain a specific product id configuration. Can be a list of ids comma separated | optional |
| gid | int | Retrieve products in a specific group id | optional |

#### Response Parameters

| Parameter | Type | Description 
| ------ | ------ | ------ |
| result | string | The result of the operation: success or error |
| totalresults | int | The total number of results available | 
| startnumber | int | The starting number for the returned results |
| numreturned | int | The number of results returned |
| products | array | An array of products matching the criteria passed | 

#### Example Request (Local API)

```php
$command = 'GetProductsActive';
$postData = array(
    'pid' => '1', // or gid => '1' or both
);
$adminUsername = 'ADMIN_USERNAME'; // Optional for WHMCS 7.2 and later

$results = localAPI($command, $postData, $adminUsername);
print_r($results);
```

#### Example Request (CURL)

```php
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://www.example.com/includes/api.php');
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS,
    http_build_query(
        array(
            'action' => 'GetProductsActive',
            // See https://developers.whmcs.com/api/authentication
            'username' => 'IDENTIFIER_OR_ADMIN_USERNAME',
            'password' => 'SECRET_OR_HASHED_PASSWORD',
            'pid' => '1',
            'responsetype' => 'json',
        )
    )
);
$response = curl_exec($ch);
curl_close($ch);
```

#### Example output:
```json
{
    "result": "success",
    "totalresults": 1,
    "products": {
        "product": [
            {
                "pid": "123",
                "gid": "",
                "type": "",
                "name": "XPTO",
                "description": "",
                "module": "cpanel",
                "paytype": "recurring",
                "pricing": {
                    "EUR": {
                        "prefix": "",
                        "suffix": "€",
                        "msetupfee": "0.00",
                        "qsetupfee": "0.00",
                        "ssetupfee": "0.00",
                        "asetupfee": "0.00",
                        "bsetupfee": "0.00",
                        "tsetupfee": "0.00",
                        "monthly": "-1.00",
                        "quarterly": "-1.00",
                        "semiannually": "-1.00",
                        "annually": "40.00",
                        "biennially": "76.00",
                        "triennially": "-1.00"
                    }
                },
                "customfields": {
                    "customfield": []
                },
                "configoptions": {
                    "configoption": [{}]
                }
            }
        }
    }
```