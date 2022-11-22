## 订单相关

#### 提交预订（创建订单）
 - POST `{{aam_url}}/V1/order`

| 参数 | 必须 | 类型 | 认证 | 长度 | 备注 |
|:---:|:---:|:---:|:---:|:---:|:---:|
| service_id | Y  |  int  |  Y  |    |  项目ID  |
| technician_id | Y  |  int  |  Y  |    |  技师ID  |
| order_duration | Y  |  int  |  Y  |    |  服务时长  |
| pay_type | Y  |  int  |  Y  |    |  支付类型，1支付宝，2微信  |

 - 返回值（主要判断code=0，或者http状态码=201即为成功）
 > HTTP/1.1 200 OK
```
{
    "data": {
        "order_id": "2022112205568384235292",
        "user_id": 50373883813,
        "service_id": 2,
        "technician_id": 2,
        "order_amount": 19600,
        "status": 0,
        "pay_type": 1,
        "updated_at": "2022-11-22T08:26:08.000000Z",
        "created_at": "2022-11-22T08:26:08.000000Z",
        "id": 10
    },
    "code": 0,
    "message": ""
}
```

 > HTTP/1.1 400 500
```
  {"message" : <"message">}
```
------------------------------




#### 获取我的订单列表
 - GET `{{aam_url}}/V1/order/?page=1&order_type=5`
 - 支持分页，page默认1

| 参数 | 必须 | 类型 | 认证 | 长度 | 备注 |
|:---:|:---:|:---:|:---:|:---:|:---:|
| order_type |  Y |    |    |    |  订单类型（注意：与订单的status字段并不对应）：1全部，2待支付，3进行中，4已完成，5已过期  |

 - 返回值
 > HTTP/1.1 200 OK
```
{
    "data": {
        "current_page": 1,
        "data": [
            {
                "id": 7,
                "order_id": "2022112285736691067477",
                "user_id": 50373883813,
                "service_id": 2,
                "technician_id": 2,
                "order_duration": 0,
                "order_amount": 19600,
                "status": 0,
                "pay_type": 2,
                "created_at": "2022-11-22T02:55:36.000000Z",
                "updated_at": "2022-11-22T02:55:36.000000Z",
                "service_item": {
                    "id": 2,
                    "uuid": "uuid-222",
                    "technician_id": 1,
                    "title": "1号技师的项目二",
                    "thumb_min": "https://t.592xuexi.cn/yam/d-11-1.png",
                    "tour_price": 9800,
                    "sold_count": 120
                },
                "technician": {
                    "id": 2,
                    "uuid": "uuid-222",
                    "name": "小娜",
                    "avatar": "https://image.fmock.com/MPWangzhe/hero/111.jpg"
                }
            },
            {
                "id": 1,
                "order_id": "2022112285205943366768",
                "user_id": 50373883813,
                "service_id": 3,
                "technician_id": 2,
                "order_duration": 0,
                "order_amount": 50000,
                "status": 0,
                "pay_type": 1,
                "created_at": "2022-11-22T02:46:45.000000Z",
                "updated_at": "2022-11-22T02:46:45.000000Z",
                "service_item": {
                    "id": 3,
                    "uuid": "uuid-333",
                    "technician_id": 2,
                    "title": "2号技师的项目1",
                    "thumb_min": "https://t.592xuexi.cn/yam/d-11-1.png",
                    "tour_price": 10000,
                    "sold_count": 25
                },
                "technician": {
                    "id": 2,
                    "uuid": "uuid-222",
                    "name": "小娜",
                    "avatar": "https://image.fmock.com/MPWangzhe/hero/111.jpg"
                }
            }
        ],
        "first_page_url": "http://api.aam-test.com:81/V1/order?page=1",
        "from": 1,
        "next_page_url": null,
        "path": "http://api.aam-test.com:81/V1/order",
        "per_page": "10",
        "prev_page_url": null,
        "to": 2
    },
    "code": 0,
    "message": ""
}
```

 > HTTP/1.1 400 500
```
  {"message" : <"message">}
```
------------------------------




#### 获取我的订单详情
 - GET `{{aam_url}}/V1/order/{{order_id}}`

| 参数 | 必须 | 类型 | 认证 | 长度 | 备注 |
|:---:|:---:|:---:|:---:|:---:|:---:|
| order_id |  Y  |    |    |    |  订单order_id  |

 - 返回值
 > HTTP/1.1 200 OK
```
{
    "data": {
        "id": 1,
        "order_id": "2022112285205943366768",
        "user_id": 50373883813,
        "service_id": 3,
        "technician_id": 2,
        "order_duration": 0,
        "order_amount": 50000,
        "status": 0,
        "pay_type": 1,
        "created_at": "2022-11-22T02:46:45.000000Z",
        "updated_at": "2022-11-22T02:46:45.000000Z",
        "expire_at": "2022-10-23T02:47:56.000000Z",
        "service_item": {
            "id": 3,
            "uuid": "uuid-333",
            "technician_id": 2,
            "title": "2号技师的项目1",
            "thumb_min": "https://t.592xuexi.cn/yam/d-11-1.png",
            "tour_price": 10000,
            "sold_count": 25
        },
        "technician": {
            "id": 2,
            "uuid": "uuid-222",
            "name": "小娜",
            "avatar": "https://image.fmock.com/MPWangzhe/hero/111.jpg"
        }
    },
    "code": 0,
    "message": ""
}
```

 > HTTP/1.1 500
```
  {"message" : <"message">}
```
------------------------------





#### 删除某个未完成的订单（硬删除）
 - DELETE `{{aam_url}}/V1/order/{{order_id}}`

| 参数 | 必须 | 类型 | 认证 | 长度 | 备注 |
|:---:|:---:|:---:|:---:|:---:|:---:|
| order_id |  Y   |    |    |    |   订单order_id  |

 - 返回值
 > HTTP/1.1 200 OK
```
{
    "data": [],
    "code": 0,
    "message": "删除成功"
}
```

 > HTTP/1.1 404 500
```
  {
      "message": "只有未支付可以删除",
      "code": -1,
      "data": []
  }
```
------------------------------





#### 发起支付
 - POST `{{aam_url}}/V1/order/pay`
 - 每次支付流水2分钟有效，不得重复提交

| 参数 | 必须 | 类型 | 认证 | 长度 | 备注 |
|:---:|:---:|:---:|:---:|:---:|:---:|
| order_id |  Y  |  String  |  Y  |    |  订单order_id  |

 - 返回值
 > HTTP/1.1 200 OK
```

// 支付宝
{
    "message": "成功了！",
    "code": 0,
    "data": {
        "payment_id": "20221122055683842352922648",
        "order_str": "app_id=2021003133619168&method=alipay.trade.app.pay&charset=utf-8&sign_type=RSA2&timestamp=2022-11-22+16%3A37%3A20&version=1.0&notify_url=http%3A%2F%2Fv1.592xuexi.cn%2FV1%2Fcallback%2Falipay&biz_content=%7B%22subject%22%3A%221%5Cu53f7%5Cu6280%5Cu5e08%5Cu7684%5Cu9879%5Cu76ee%5Cu4e8c%22%2C%22body%22%3A%22%22%2C%22out_trade_no%22%3A%2220221122055683842352922648%22%2C%22total_amount%22%3A%22196%22%2C%22product_code%22%3A%22QUICK_MSECURITY_PAY%22%2C%22timeout_express%22%3A%2230m%22%2C%22passback_params%22%3A%22action%253D%22%7D&sign=6Q10IKXmBq8yJ6CWwpJD8kU01Ujj9WTaE2bUiYPlLiGtliWe9n1jcbhW5NbzyKXnZ81h5L8UrVCA4grAKiUPJhdJGcUzPonzUz5JUclAGERw7bTF9zuS0BdzB4fYI%2BWQI25LysNgW8I5tFf6gW45F2ziYWEsLV%2BeOhL7IhiAsDSvyxBxAwp7YLRmFUTUU2szQO8k%2F4AW7m7oVD1dDj3fpZ09rWgrjvHsLSeK4%2FpOIZh02avSSGUFKQa35fQGKvoRrjsKgwSjgOHKS2xl69tWvXwDv5U4pjevL0Zu1STB2BOKRoQU0TFiY94UcYY%2BY6%2F%2BHad9NOJE2R41TJjpVo9lpA%3D%3D"
    }
}

// 微信
待开发

```

 > HTTP/1.1 500 429 400 404
```
  {"message" : <"message">}
```
------------------------------


#### 取消支付(注意url参数是payment_id,由发起支付接口返回的data.payment_id)
 - DELETE `{{aam_url}}/V1/order/pay/{{payment_id}}`
 - 调取app支付后，用户点击关闭，如果走到了回调的fail方法，调用该方法，防止用户没支付还要等支付单过期才可以继续支付
 - 该方法永远返回200

| 参数 | 必须 | 类型 | 认证 | 长度 | 备注 |
|:---:|:---:|:---:|:---:|:---:|:---:|
| 无 |    |    |    |    |    |

 - 返回值
 > HTTP/1.1 200 OK
```
{
    "message": "取消成功",
    "code": 0,
    "data": []
}
```

 > HTTP/1.1 500
```
  {"message" : <"message">}
```
------------------------------




#### 申请退款（后台处理完退款之后，记得修改order_refund.status=1, order.status=3）
 - POST `{{aam_url}}/V1/order/pay/refund
 - 订单已支付状态才可以发起，不可重复发起

| 参数 | 必须 | 类型 | 认证 | 长度 | 备注 |
|:---:|:---:|:---:|:---:|:---:|:---:|
| order_id |  Y  |  String  |  Y  |    |    |

 - 返回值
 > HTTP/1.1 200 OK
```
{
    "message": "",
    "code": 0,
    "data": {
        "order_id": "2022112202294206595730",
        "payment_id": "20221122022942065957303791",
        "pay_amount": 19600,
        "user_id": 50373883813,
        "status": 0,
        "pay_type": 2,
        "updated_at": "2022-11-22T08:34:07.000000Z",
        "created_at": "2022-11-22T08:34:07.000000Z",
        "id": 3
    }
}
```

 > HTTP/1.1 500 422 429 400 404
```
  {"message" : <"message">}
```
------------------------------
