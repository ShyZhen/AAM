#### 技师列表
 - GET `{{aam_url}}/V1/technician?page=1`
 
| 参数 | 必须 | 类型 | 需要登录 | 长度 | 备注 |
|:---:|:---:|:---:|:---:|:---:|:---:|
| page  |      |     |  N  |     |   页码，默认1   |
| is_pretty  |      |     |  N  |     |   是否是颜值专区，1是   |
| is_recommend  |      |     |  N  |     |   是否是推荐技师，1是 (用于首页推荐，不确定是技师还是项目，都做了推荐筛选)   |
 
 - 返回值
 > HTTP/1.1 200 OK
```
{
    "data": {
        "code": 0,
        "message": "",
        "current_page": 1,
        "data": [
            {
                "id": 2,
                "uuid": "uuid-222",
                "name": "小娜",
                "avatar": "https://image.fmock.com/MPWangzhe/hero/111.jpg",
                "photo_wall": "https://image.fmock.com/MPWangzhe/draw/20210520-ynxd-xq.jpg",
                "phone": "123****2312",
                "wechat": "15****",
                "shop_id": 2,
                "sex": "male",
                "birthday": "2000-10-25",
                "score": "5.0",
                "order_count": 55,
                "fans_count": 66,
                "addr": "深圳市福田区香蜜湖",
                "lon": "12.55555",
                "lat": "11.11234",
                "intro": "介绍，一段简短的介绍文本",
                "is_pretty": 1,
                "created_at": "2022-10-25T08:10:38.000000Z",
                "updated_at": "2022-10-25T08:10:40.000000Z",
                "shop": {
                    "id": 2,
                    "uuid": "uuid-222",
                    "title": "本草纲目2号"
                },
                "labels": [
                    {
                        "id": 2,
                        "title": "手机认证",
                        "key": "phone",
                        "style": 1,
                        "created_at": "2022-10-25T10:15:54.000000Z",
                        "updated_at": "2022-10-25T10:15:56.000000Z",
                        "pivot": {
                            "technician_id": 2,
                            "label_id": 2
                        }
                    },
                    {
                        "id": 4,
                        "title": "店铺认证",
                        "key": "shop",
                        "style": 1,
                        "created_at": "2022-10-25T10:16:18.000000Z",
                        "updated_at": "2022-10-25T10:16:20.000000Z",
                        "pivot": {
                            "technician_id": 2,
                            "label_id": 4
                        }
                    }
                ]
            },
            {
                "id": 1,
                "uuid": "uuid-111",
                "name": "小李",
                "avatar": "https://image.fmock.com/MPWangzhe/hero/111.jpg",
                "photo_wall": "https://image.fmock.com/MPWangzhe/draw/20210520-ynxd-xq.jpg",
                "phone": "123****2312",
                "wechat": "wx****",
                "shop_id": 1,
                "sex": "male",
                "birthday": "2000-10-25",
                "score": "4.8",
                "order_count": 12,
                "fans_count": 22,
                "addr": "深圳市南山区深圳湾",
                "lon": "12.55555",
                "lat": "11.11234",
                "intro": "介绍，一段简短的介绍文本",
                "is_pretty": 0,
                "created_at": "2022-10-25T08:10:38.000000Z",
                "updated_at": "2022-10-25T08:10:40.000000Z",
                "shop": {
                    "id": 1,
                    "uuid": "uuid-111",
                    "title": "本草纲目"
                },
                "labels": [
                    {
                        "id": 1,
                        "title": "身份认证",
                        "key": "identity",
                        "style": 1,
                        "created_at": "2022-10-25T10:15:31.000000Z",
                        "updated_at": "2022-10-25T10:15:33.000000Z",
                        "pivot": {
                            "technician_id": 1,
                            "label_id": 1
                        }
                    },
                    {
                        "id": 2,
                        "title": "手机认证",
                        "key": "phone",
                        "style": 1,
                        "created_at": "2022-10-25T10:15:54.000000Z",
                        "updated_at": "2022-10-25T10:15:56.000000Z",
                        "pivot": {
                            "technician_id": 1,
                            "label_id": 2
                        }
                    },
                    {
                        "id": 4,
                        "title": "店铺认证",
                        "key": "shop",
                        "style": 1,
                        "created_at": "2022-10-25T10:16:18.000000Z",
                        "updated_at": "2022-10-25T10:16:20.000000Z",
                        "pivot": {
                            "technician_id": 1,
                            "label_id": 4
                        }
                    },
                    {
                        "id": 3,
                        "title": "微信认证",
                        "key": "wechat",
                        "style": 2,
                        "created_at": "2022-10-25T10:16:06.000000Z",
                        "updated_at": "2022-10-25T10:16:08.000000Z",
                        "pivot": {
                            "technician_id": 1,
                            "label_id": 3
                        }
                    }
                ]
            }
        ],
        "first_page_url": "http://api.aam-test.com:81/V1/technician?page=1",
        "from": 1,
        "next_page_url": null,
        "path": "http://api.aam-test.com:81/V1/technician",
        "per_page": "10",
        "prev_page_url": null,
        "to": 2
    }
}
```

------------------------------


#### 技师详情
 - GET `{{aam_url}}/V1/technician/{{uuid}}`
 
| 参数 | 必须 | 类型 | 需要登录 | 长度 | 备注 |
|:---:|:---:|:---:|:---:|:---:|:---:|
| uuid  |   Y   |     |  N  |     |   技师的UUID   |
 
 - 返回值
 > HTTP/1.1 200 OK
```
{
    "data": {
        "id": 2,
        "uuid": "uuid-222",
        "name": "小娜",
        "avatar": "https://image.fmock.com/MPWangzhe/hero/111.jpg",
        "photo_wall": "https://image.fmock.com/MPWangzhe/draw/20210520-ynxd-xq.jpg",
        "phone": "123****2312",
        "wechat": "15****",
        "shop_id": 2,
        "sex": "male",
        "birthday": "2000-10-25",
        "score": "5.0",
        "order_count": 55,
        "fans_count": 66,
        "addr": "深圳市福田区香蜜湖",
        "lon": "12.55555",
        "lat": "11.11234",
        "intro": "介绍，一段简短的介绍文本",
        "is_pretty": 1,
        "created_at": "2022-10-25T08:10:38.000000Z",
        "updated_at": "2022-10-25T08:10:40.000000Z",
        "shop": {
            "id": 2,
            "uuid": "uuid-222",
            "title": "本草纲目2号"
        },
        "labels": [
            {
                "id": 2,
                "title": "手机认证",
                "key": "phone",
                "style": 1,
                "created_at": "2022-10-25T10:15:54.000000Z",
                "updated_at": "2022-10-25T10:15:56.000000Z",
                "pivot": {
                    "technician_id": 2,
                    "label_id": 2
                }
            },
            {
                "id": 4,
                "title": "店铺认证",
                "key": "shop",
                "style": 1,
                "created_at": "2022-10-25T10:16:18.000000Z",
                "updated_at": "2022-10-25T10:16:20.000000Z",
                "pivot": {
                    "technician_id": 2,
                    "label_id": 4
                }
            }
        ]
    },
    "code": 0,
    "message": ""
}
```

------------------------------



#### 关注/取消关注 某个技师
 - POST `{{aam_url}}/V1/follow/{{T-uuid}}`
 
| 参数 | 必须 | 类型 | 需要登录 | 长度 | 备注 |
|:---:|:---:|:---:|:---:|:---:|:---:|
| T-uuid  |   Y   |     |  Y  |     |   技师的UUID   |
 
 - 返回值
 > HTTP/1.1 200 OK
```
{
    "data": [],
    "code": 0,
    "message": "关注成功"
}
{
    "data": [],
    "code": 0,
    "message": "已取消关注"
}
```

 > HTTP/1.1 404 OK
```
{
    "data": [],
    "code": -1,
    "message": "该技师不存在"
}
```

------------------------------




#### 获取我关注的技师列表
 - GET `{{aam_url}}/V1/follow/list/{{uuid}}`
 
| 参数 | 必须 | 类型 | 需要登录 | 长度 | 备注 |
|:---:|:---:|:---:|:---:|:---:|:---:|
| uuid  |   Y   |     |  Y  |     |   当前用户的UUID（因为支持查看其他用户，所以有这个参数）   |
| page  |   N   |     |  Y  |     |   页码，默认1   |
 
 - 返回值
 > HTTP/1.1 200 OK
```
{
    "code": 0,
    "message": "",
    "data": [
        {
            "id": 1,
            "uuid": "uuid-111",
            "name": "小李",
            "avatar": "https://image.fmock.com/MPWangzhe/hero/111.jpg",
            "inMyFollows": true
        },
        {
            "id": 2,
            "uuid": "uuid-222",
            "name": "小娜",
            "avatar": "https://image.fmock.com/MPWangzhe/hero/111.jpg",
            "inMyFollows": true
        }
    ]
}
```

 > HTTP/1.1 404 OK
```
{
    "data": [],
    "code": -1,
    "message": "该用户不存在"
}
```

------------------------------




#### 获取我与当前技师的关注状态
 - GET `{{aam_url}}/V1/follow/list/{{T-uuid}}`
 
| 参数 | 必须 | 类型 | 需要登录 | 长度 | 备注 |
|:---:|:---:|:---:|:---:|:---:|:---:|
| T-uuid  |   Y   |     |  Y  |     |   技师的uuid   |
 
 - 返回值
 > HTTP/1.1 200 OK
```
{
    "code": 0,
    "message": "",
    "data": {
        "inMyFollows": true    // true 就是已关注，false就是未关注
    }
}
```

 > HTTP/1.1 404 OK
```
{
    "data": [],
    "code": -1,
    "message": "该技师不存在"
}
```

------------------------------
