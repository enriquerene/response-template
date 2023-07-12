# Response Template
A response interface builder for REST API.

## Table of Contents
- [Installation](#installation)
- [Usage](#usage)
	+ [Simples Case](#simplest-case)
	+ [Data in Response](#data-in-response)
	+ [Links Setup](#links-setup)
- [Plan](#plan)
- [Support](#support)
- [Contribute](#contribute)


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

### <a name="simplest-case"></a> Simplest Case
The simplest case is to instantiate the class ResponseTemplate with HTTP Status Code "200 Ok." and call method `build`:
```php
<?php
use RESTfulTemplate\ResponseTemplate;

$rest = new ResponseTemplate( 200 );
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
//			"method" => "GET"
//		]
// 	]
// ];
```

### <a name="data-in-response"></a> Data in Response
It's possible insert data to response at the build moment:
```php
<?php
use RESTfulTemplate\ResponseTemplate;

$product = [
	"id" => 1,
	"name" => "product-name",
	"displayName" => "Product Name",
	"price" => "14.50",
	"stock" => 20
];

$rest = new ResponseTemplate( 200 );
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
// 		"self" => [
//			"url" => "https://example.com/products/1",
//			"method" => "GET"
//		]
// 	]
// ];
```

### <a name="links-setup"></a> Links Setup
You can make use of `setLink` method to setup a link to response:
```php
<?php
use RESTfulTemplate\ResponseTemplate;

$products = [
	[
		"id" => 1,
		"name" => "product-name",
		"displayName" => "Product Name",
		"price" => "14.50",
		"stock" => 20
	],
	/* ...more ones */
];

$rest = new ResponseTemplate( 200 );
$rest = $rest->setLink( "next", "https://example.com/products?page=2" );
$response = $rest->build( $products );
// $response contains following array:
// [
// 	"status" => [
// 		"code" => 200,
// 		"message" => "Ok."
// 	],
// 	"data" => [
//		[
//			"id" => 1,
//			"name" => "product-name",
//			"displayName" => "Product Name",
//			"price" => "14.50",
//			"stock" => 20
//		],
// 		/* ...more ones */
//	],
// 	"links" => [
// 		"self" => [
//			"url" => "https://example.com/products",
//			"method" => "GET"
//		],
// 		"next" => [
//			"url" => "https://example.com/products?page=2",
//			"method" => "GET"
//		]
// 	]
// ];
```
It's possible insert a third argument into `setLink` method to define `"method"` property of link. If you want work with a POST route, for example, you probrably would like to use `$rest = $rest->setLink( "create", "/products", "POST" );`.


## <a name="plan"></a> Plan
This project aims to be up to date with standards. Future version may be aligned to [RFC 7231](https://tools.ietf.org/html/rfc7231#section-6.5.1).
Some implementation in the roadmap:
### Setting custom link key
- if a query string starting with `?` is given, the basename and path must be the same as `self url`.
- if a path is given, the basename must be the same as `self url`.
- if a basename including `.` is given, the protocol must be the same as `self url`.
- only full path including http/https protocol in the string must be placed as it is into custom key url property.

## <a name="support"></a> Support
If you need some help you can open an issue.

## <a name="contribute"></a> Contribute
Do a pull request or send email to Support.
