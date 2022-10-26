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
