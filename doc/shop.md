## 店铺相关

#### 获取店铺列表
 - GET `{{aam_url}}/V1/shops?page=1`
 
| 参数 | 必须 | 类型 | 需要登录 | 长度 | 备注 |
|:---:|:---:|:---:|:---:|:---:|:---:|
| page  |   N   |     |  N  |     |   页码，默认1，每页10条   |
 
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
                "id": 2,
                "uuid": "uuid-222",
                "title": "本草纲目2号",
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
                "addr": "北京市朝阳区航空路28号",
                "lon": "116.397128",
                "lat": "39.916527",
                "opening_hours": "10:00 - 23:00",
                "desc": "店铺详细介绍，不超过200字",
                "created_at": "2022-10-25T06:18:01.000000Z",
                "updated_at": "2022-10-25T06:18:03.000000Z"
            },
            {
                "id": 1,
                "uuid": "uuid-111",
                "title": "本草纲目",
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
                "addr": "北京市朝阳区航空路28号",
                "lon": "116.397128",
                "lat": "39.916527",
                "opening_hours": "10:00 - 23:00",
                "desc": "店铺详细介绍，不超过200字",
                "created_at": "2022-10-25T06:18:01.000000Z",
                "updated_at": "2022-10-25T06:18:03.000000Z"
            }
        ],
        "first_page_url": "http://api.aam-test.com:81/V1/shops?page=1",
        "from": 1,
        "next_page_url": null,
        "path": "http://api.aam-test.com:81/V1/shops",
        "per_page": "10",
        "prev_page_url": null,
        "to": 2
    }
}
```

------------------------------


#### 获取店铺详情
 - GET `{{aam_url}}/V1/shop/{{uuid}}`
 
| 参数 | 必须 | 类型 | 需要登录 | 长度 | 备注 |
|:---:|:---:|:---:|:---:|:---:|:---:|
| uuid  |   Y   |     |  N  |     |   店铺的UUID   |
 
 - 返回值
 > HTTP/1.1 200 OK
```
{
    "code": 0,
    "message": "",
    "data": {
        "id": 1,
        "uuid": "uuid-111",
        "title": "本草纲目",
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
        "addr": "北京市朝阳区航空路28号",
        "lon": "116.397128",
        "lat": "39.916527",
        "opening_hours": "10:00 - 23:00",
        "desc": "店铺详细介绍，不超过200字",
        "created_at": "2022-10-25T06:18:01.000000Z",
        "updated_at": "2022-10-25T06:18:03.000000Z"
    }
}
```

------------------------------
