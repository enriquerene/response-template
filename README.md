![Response Template](response-template.logo.png)
A response interface builder for REST API.

## Table of Contents
- [Support](https://github.com/enriquerene/response-template#support)
- [Installation](https://github.com/enriquerene/response-template#installation)
- [Usage](https://github.com/enriquerene/response-template#usage)
	+ [Simples Case](https://github.com/enriquerene/response-template#simplest-case)
	+ [Data in Response](https://github.com/enriquerene/response-template#data-in-response)
- [Plan](https://github.com/enriquerene/response-template#plan)
- [Contribute](https://github.com/enriquerene/response-template#contribute)

## <a name="support"></a> Support
If you need some help you can open an issue or get in touch by email ([contato@enriquerene.com.br](mailto:contato@enriquerene.com.br)).


## <a name="installation"></a> Installation
There are some installation ways. You can choose the best way for you.

### Composer (recommended)
This way requires [Composer](https://getcomposer.org):
```bash
$ composer require restful-template/response-template
```

### Git
Clone the repo into your project:
```bash
$ git clone https://github.com/enriquerene/response-template.git
```

### Zip
Dowload the package and uncpack it into your project:
[Dowload ZIP](https://github.com/enriquerene/response-template/archive/main.zip)

## <a name="usage"></a> Usage
ResponseTemplate requires an valid HTTP Status Code. Refer to [section 10 of RFC 2616](https://tools.ietf.org/html/rfc2616#section-10).

## <a name="simplest-case"></a> Simplest Case
The simplest case is to instantiate the class ResponseTemplate with HTTP Status Code "200 Ok." and call method `build`:
```php
<?php
use RESTfulTemplate\ResponseTemplate as ResT;

$rest = new ResT( 200 );
$response = $rest->build();
// $response contains following array:
// [
// 	"status" => [
// 		"code" => 200,
// 		"message" => "Ok."
// 	],
// 	"data" => null,
// 	"links" => [
// 		"self" => [
//			"url" => "https://example.com/",
//			"methods" => "GET"
//		]
// 	]
// ];
```

## <a name="data-in-response"></a> Data in Response
It's possible insert data to response at the build moment:
```php
<?php
use RESTfulTemplate\ResponseTemplate as ResT;

$product = [
	"id" => 1,
	"name" => "product-name",
	"displayName" => "Product Name",
	"price" => "14.50",
	"stock" => 20
];

$rest = new ResT( 200 );
$response = $rest->build( $product );
// $response contains following array:
// [
// 	"status" => [
// 		"code" => 200,
// 		"message" => "Ok."
// 	],
// 	"data" => [
//		"id" => 1,
// 		"name" => "product-name",
// 		"displayName" => "Product Name",
// 		"price" => "14.50",
// 		"stock" => 20
// 	],
// 	"links" => [
// 		"self" => "https://example.com/products/1"
// 	]
// ];
```


## <a name="plan"></a> Plan
[RFC 7231](https://tools.ietf.org/html/rfc7231#section-6.5.1)
## <a name="contribute"></a> Contribute
