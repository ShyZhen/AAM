#### 初始化配置接口start
 - GET `{{aam_url}}/V1/start`
 
| 参数 | 必须 | 类型 | 需要登录 | 长度 | 备注 |
|:---:|:---:|:---:|:---:|:---:|:---:|
| 无  |      |     |  N  |     |      |
 
 - 返回值
 > HTTP/1.1 200 OK
```
{
    "code": 0,
    "message": "",
    "data": {
        "banner": [
            {
                "id": 1,
                "title": "标题1",
                "image": "https://image.fmock.com/MPWangzhe/draw/common/aql-sgzl.jpg",
                "uri": "https://cn.bing.com/",
                "sort": 0,
                "created_at": "2022-08-03T06:57:11.000000Z",
                "updated_at": "2022-08-03T06:57:13.000000Z"
            },
            {
                "id": 2,
                "title": "标题2",
                "image": "https://image.fmock.com/MPWangzhe/draw/common/yao-yjsl.jpg",
                "uri": "https://www.baidu.com/",
                "sort": 0,
                "created_at": "2022-08-03T06:58:02.000000Z",
                "updated_at": "2022-08-03T06:58:05.000000Z"
            }
        ],
        "setting": {
            "h5-push-mime": {
                "image": "https://img.litblc.com/2022/06/3164713865.jpg",
                "url": "https://www.litblc.com",
                "height": "100"
            },
            "promise": [
                {
                    "name": "官方保证"
                },
                {
                    "name": "平台认证"
                },
                {
                    "name": "专业品质"
                },
                {
                    "name": "服务无忧"
                }
            ]
        }
    }
}
```

------------------------------



#### 下单检查接口
 - GET `{{aam_url}}/V1/book-check`
 
| 参数 | 必须 | 类型 | 需要登录 | 长度 | 备注 |
|:---:|:---:|:---:|:---:|:---:|:---:|
| 无  |      |     |  N  |     |      |
 
 - 返回值
 > HTTP/1.1 200 OK
```
{
    "data": [],
    "code": 0,
    "message": "ok"
}

{
    "data": [],
    "code": -1,
    "message": "该服务已满请预约其它服务"
}
```

------------------------------




#### H5对接需要的配置（需要登录token，用于消息等与H5通信的）
 - GET `{{aam_url}}/V1/h5config`
 
| 参数 | 必须 | 类型 | 需要登录 | 长度 | 备注 |
|:---:|:---:|:---:|:---:|:---:|:---:|
| 无  |      |     |  N  |     |      |
 
 - 返回值
 > HTTP/1.1 200 OK
```
{
    "data": {
        "msg_url": "https://m.ituiuu.com/im?appname=AiAnMo&kefu=aianmo@126.com&key=1540183127149101156344811",
        "find_url": "https://m.ituiuu.com/find?appname=AiAnMo&kefu=aianmo@126.com&key=1540183127149101156344811"
    },
    "code": 0,
    "message": ""
}
```

------------------------------
