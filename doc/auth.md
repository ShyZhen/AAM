## 登录/用户相关

#### login-code
 - POST `{{aam_url}}/V1/login-code`
 - 发送短信验证码

| 参数 | 必须 | 类型 | 需要登录 | 长度 | 备注 |
|:---:|:---:|:---:|:---:|:---:|:---:|
| `phone` | Y | String | N | 11 | 手机号 |

 - 返回值
 > HTTP/1.1 200 OK
```
{
    "message": "验证码已经发送成功!",
    "code": 0,
    "data": []
}
```

 > HTTP/1.1 400、403、422、500
```
  {"message" : <"message">}
```
------------------------------


#### login
 - POST `{{aam_url}}/V1/login`
 - 登录（包括自动注册）,如果注销过则无法登录,提示联系管理员

| 参数 | 必须 | 类型 | 需要登录 | 长度 | 备注 |
|:---:|:---:|:---:|:---:|:---:|:---:|
| `phone` | Y | String | N | 11 |  |
| `code` | Y | String | N | 5 |  |

 - 注意,所有需要登录的接口都应该在Header携带token,即登录接口返回的token：
 ```
   headers => [
      'Accept' => 'application/json',
      'Authorization' => 'Bearer '.$token,    // 如：Bearer 20|mVdPSB3qgmcVa7FTUzdlxOiQOcl7AfPwhNMY8e1D
   ]
 ```

 - 返回值
 > HTTP/1.1 200 OK

```
{
    "message": "",
    "code": 0,
    "data": {
        "id": 50373883813,
        "token": "20|mVdPSB3qgmcVa7FTUzdlxOiQOcl7AfPwhNMY8e1D"
    }
}
```

 > HTTP/1.1 400、403、422
```
{"message" : <"message">}
```
------------------------------


#### me
 - GET `{{aam_url}}/V1/me`
 - 获取我（当前登录者）的信息

| 参数 | 必须 | 类型 | 需要登录 | 长度 | 备注 |
|:---:|:---:|:---:|:---:|:---:|:---:|
|  无  |     |     | Y   |     |     |

 - 返回值
 > HTTP/1.1 200 OK
```
{
    "code": 0,
    "message": ""
    "data": {
        "id": 50373883813,
        "uuid": "user-b7ada867-38dc-dc24-e7e5-b7d09249612c",
        "name": "131****5720",
        "phone": "131****5720",
        "avatar": "",
        "sex": "secrecy",
        "forbidden": "none",
        "remember_token": null,
        "created_at": "2022-10-24T02:30:39.000000Z",
        "updated_at": "2022-10-24T02:30:39.000000Z"
    },
}
```

 > HTTP/1.1 401
 {"message" : <"message">}
------------------------------


#### user
 - GET `{{aam_url}}/V1/user/{{uuid}}`
 - 获取某个用户信息

| 参数 | 必须 | 类型 | 需要登录 | 长度 | 备注 |
|:---:|:---:|:---:|:---:|:---:|:---:|
| uuid | Y | String | Y | | 用户的uuid  |

 - 返回值
 > HTTP/1.1 200 OK
```
{
    "code": 0,
    "message": "",
    "data": {
        "uuid": "user-b7ada867-38dc-dc24-e7e5-b7d09249612c",
        "name": "131****5720",
        "avatar": "",
        "sex": "secrecy"
    },
}
```

 > HTTP/1.1 401、404
 {"message" : <"message">}
------------------------------
 
 
#### post-me
 - POST `{{aam_url}}/V1/me`
 - 修改个人资料
 
| 参数 | 必须 | 类型 | 需要登录 | 长度 | 备注 |
|:---:|:---:|:---:|:---:|:---:|:---:|
| sex | N | Enum(male,female,secrecy) | Y | | 性别，只有值为secrecy才可以更改，展示为男或者保密 |
| name | N | String | Y | | 昵称 |
| avatar | N | String | Y | | 头像url |
| forbidden | N | Enum(none,yes) | Y |   | 注销用户传yes，（注销成功回调后记得删除客户端本地token） |
 
 - 参数示例
```
// 没改不要传，传了就会改

// 更新多个字段
{"sex":"female", "name":"昵称","avatar":"https://image.xxx.com"}

// 更新部分字段
{"name":"新昵称2"}

// 注销
{"forbidden":"yes"}
```
 
 - 返回值
 > HTTP/1.1 200 OK
```
{
    "code": 0,
    "message": "",
    "data": {
        "id": 50373883813,
        "uuid": "user-b7ada867-38dc-dc24-e7e5-b7d09249612c",
        "name": "",
        "phone": "131****5720",
        "avatar": "https://image.xxx.com",
        "sex": "female",
        "forbidden": "yes",
        "remember_token": null,
        "created_at": "2022-10-24T02:30:39.000000Z",
        "updated_at": "2022-10-24T02:55:20.000000Z"
    }
}
```
 
 > HTTP/1.1 401
 {"message" : <"message">}
------------------------------

 
 
#### logout
 - GET `{{aam_url}}/V1/logout`
 - 登出，token失效
 
| 参数 | 必须 | 类型 | 需要登录 | 长度 | 备注 |
|:---:|:---:|:---:|:---:|:---:|:---:|
| 无  |      |     |  Y  |     |      |
 
 - 返回值
 > HTTP/1.1 200 OK
```
{
    "code": 0,
    "data": []
    "message": <"message">
}
```

------------------------------
