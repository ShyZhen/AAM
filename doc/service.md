## 服务项目相关

#### 项目列表 - 包括 技师下的/推荐的 服务列表
 - GET `{{aam_url}}/V1/service?page=1&technician_id=3`
 
| 参数 | 必须 | 类型 | 需要登录 | 长度 | 备注 |
|:---:|:---:|:---:|:---:|:---:|:---:|
| page  |      |     |  N  |     |   页码，默认1   |
| is_recommend  |      |     |  N  |     |   是否是推荐项目：1是,用于首页等   |
| technician_id  |      |     |  N  |     |   技师ID：是否需要特定技师的服务列表，用于技师详情页等   |
 
 - 返回值
 > HTTP/1.1 200 OK
```
{
    "code": 0,
    "message": "",
    "data": {
        "current_page": 1,
        "data": [
            {
                "id": 4,
                "uuid": "uuid-444",
                "technician_id": 2,
                "title": "2号技师的项目二",
                "thumbs": [
                    {
                        "url": "https://t.592xuexi.cn/yam/d-11-1.png",
                        "width": 750,
                        "height": 549
                    },
                    {
                        "url": "https://t.592xuexi.cn/yam/d-122-1.png",
                        "width": 750,
                        "height": 575
                    },
                    {
                        "url": "https://t.592xuexi.cn/yam/d-135-1.png",
                        "width": 750,
                        "height": 558
                    }
                ],
                "thumb_min": "https://t.592xuexi.cn/yam/d-11-1.png",
                "tour_price": 11000,
                "desc": "服务详情介绍",
                "sold_count": 30,
                "is_recommend": 1,
                "created_at": "2022-10-28T02:51:08.000000Z",
                "updated_at": "2022-10-28T02:51:10.000000Z",
                "technician": {
                    "id": 2,
                    "uuid": "uuid-222",
                    "name": "小娜",
                    "avatar": "https://image.fmock.com/MPWangzhe/hero/111.jpg"
                }
            },
            {
                "id": 3,
                "uuid": "uuid-333",
                "technician_id": 2,
                "title": "2号技师的项目1",
                "thumbs": [
                    {
                        "url": "https://t.592xuexi.cn/yam/d-11-1.png",
                        "width": 750,
                        "height": 549
                    },
                    {
                        "url": "https://t.592xuexi.cn/yam/d-122-1.png",
                        "width": 750,
                        "height": 575
                    },
                    {
                        "url": "https://t.592xuexi.cn/yam/d-135-1.png",
                        "width": 750,
                        "height": 558
                    }
                ],
                "thumb_min": "https://t.592xuexi.cn/yam/d-11-1.png",
                "tour_price": 10000,
                "desc": "服务详情介绍",
                "sold_count": 25,
                "is_recommend": 0,
                "created_at": "2022-10-28T02:51:08.000000Z",
                "updated_at": "2022-10-28T02:51:10.000000Z",
                "technician": {
                    "id": 2,
                    "uuid": "uuid-222",
                    "name": "小娜",
                    "avatar": "https://image.fmock.com/MPWangzhe/hero/111.jpg"
                }
            }
        ],
        "first_page_url": "http://api.aam-test.com:81/V1/service?page=1",
        "from": 1,
        "next_page_url": null,
        "path": "http://api.aam-test.com:81/V1/service",
        "per_page": "10",
        "prev_page_url": null,
        "to": 2
    }
}
```

------------------------------


#### 服务详情
 - GET `{{aam_url}}/V1/service/{{uuid}}`
 
| 参数 | 必须 | 类型 | 需要登录 | 长度 | 备注 |
|:---:|:---:|:---:|:---:|:---:|:---:|
| uuid  |   Y   |     |  N  |     |   服务的UUID   |
 
 - 返回值
 > HTTP/1.1 200 OK
```
{
    "data": {
        "id": 2,
        "uuid": "uuid-222",
        "technician_id": 1,
        "title": "1号技师的项目二",
        "thumbs": [
            {
                "url": "https://t.592xuexi.cn/yam/d-11-1.png",
                "width": 750,
                "height": 549
            },
            {
                "url": "https://t.592xuexi.cn/yam/d-122-1.png",
                "width": 750,
                "height": 575
            },
            {
                "url": "https://t.592xuexi.cn/yam/d-135-1.png",
                "width": 750,
                "height": 558
            }
        ],
        "thumb_min": "https://t.592xuexi.cn/yam/d-11-1.png",
        "tour_price": 9800,
        "desc": "服务详情介绍",
        "sold_count": 120,
        "is_recommend": 1,
        "created_at": "2022-10-28T02:51:08.000000Z",
        "updated_at": "2022-10-28T02:51:10.000000Z",
        "technician": {
            "id": 1,
            "uuid": "uuid-111",
            "name": "小李",
            "avatar": "https://image.fmock.com/MPWangzhe/hero/111.jpg"
        }
    },
    "code": 0,
    "message": ""
}
```

------------------------------
