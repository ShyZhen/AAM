## user模型
$table->id();
$table->string('uuid', 64)->index();
$table->string('name');
$table->string('phone', 64)->default('')->index();
$table->string('avatar', 255)->default('');
$table->enum('sex', ['male', 'female', 'secrecy'])->default('secrecy');  // 默认secrecy，客户端判断如果是secrecy，则显示男并可更改；如果是其他，则不可更改。
$table->enum('forbidden', ['none', 'yes'])->default('none');  // 用户状态，yes注销，将无法登陆


## banner模型
$table->id('id');
$table->string('title', 128)->default('');
$table->string('image', 255)->default('')->comment('单张图片url,255字符以内');
$table->string('uri', 255)->default('')->comment('跳转携带的uri,255字符以内');
$table->integer('sort')->default(0)->comment('支持修改排序字段，默认asc sort,asc id');


## shop模型
$table->id();
$table->string('uuid', 64)->index();
$table->string('title');
$table->text('thumbs')->comment('店铺图,json对象,多个图片地址');
$table->string('addr', 255)->default('')->comment('详细地址');
$table->string('lon', 32)->default('')->comment('经度');
$table->string('lat', 32)->default('')->comment('纬度');
$table->string('opening_hours', 64)->default('10:00 - 23:00')->comment('营业时间');
$table->string('desc', 255)->default('')->comment('介绍');

## technician模型
$table->id();
$table->string('uuid', 64)->index();
$table->string('name', 64);
$table->string('avatar', 255)->default('');
$table->string('photo_wall', 255)->default('')->comment('图片墙背景');
$table->string('phone', 64)->default('');
$table->string('wechat', 64)->default('');
$table->unsignedBigInteger('shop_id')->index();  // 所属店铺
$table->enum('sex', ['male', 'female', 'secrecy'])->default('secrecy');
$table->date('birthday')->default('1970-01-01');
$table->string('score', 8)->comment('评分');
$table->unsignedInteger('order_count')->default(0)->comment('订单数');
$table->unsignedInteger('fans_count')->default(0)->comment('粉丝数');
$table->string('addr', 255)->default('')->comment('详细地址');
$table->string('lon', 32)->default('')->comment('经度');
$table->string('lat', 32)->default('')->comment('纬度');
$table->string('intro', 255)->default('')->comment('服务者介绍');
$table->tinyInteger('is_pretty')->default(0)->comment('是否是颜值出众的(颜值区),0否,1是');
$table->tinyInteger('is_recommend')->default(0)->comment('是否是推荐到首页,0否,1是');

## service模型
$table->id();
$table->string('uuid', 64)->index();
$table->unsignedBigInteger('technician_id')->index();  // 该服务属于哪个技师
$table->string('title', 128)->default('');
$table->text('thumbs')->comment('项目图,json对象,多个图片地址');
$table->string('thumb_min', 255)->default('')->comment('项目缩略图，单张');
$table->unsignedBigInteger('tour_price')->default(0)->comment('每小时价格:分');
$table->text('desc')->comment('项目介绍');
$table->unsignedInteger('sold_count')->default(0)->comment('已售数量');
$table->tinyInteger('is_recommend')->default(0)->comment('是否是推荐到首页,0否,1是');

## order模型
$table->id();
$table->string('order_id', 32)->index();
$table->unsignedBigInteger('user_id')->index()->comment('下单用户');
// 动态获取项目/技师数据，不再写快照
$table->unsignedBigInteger('service_id')->index()->comment('项目id');
$table->unsignedBigInteger('technician_id')->index()->comment('服务技师id');
$table->unsignedInteger('order_duration')->default(0)->comment('服务时长：小时');
$table->unsignedBigInteger('order_amount')->default(0)->comment('订单总额:分');  // 当前下单的价格，也就是当前项目*时长的价格
// 退款成功后，该字段改为3，必须先判断是1状态下才能退款！修改为4已完成之前，必须判断是已支付1状态下
$table->tinyInteger('status')->default(0)->comment('支付状态,0待支付,1已支付,2已取消,3已退款,4已完成');
$table->tinyInteger('pay_type')->default(0)->comment('支付类型，1支付宝，2微信');

## payment模型
$table->id('id');
$table->string('order_id', 32)->index()->comment('订单id');
$table->string('payment_id', 64)->index()->comment('支付单id,由order_id+随机4位组成,用来幂等防止重复支付');  // 2分钟内有该数据并且callback修改状态之前的状态status
$table->unsignedBigInteger('pay_amount')->default(0)->comment('支付总额:分');
$table->unsignedBigInteger('user_id')->index()->comment('当前支付用户');
$table->tinyInteger('status')->default(0)->comment('支付状态,0待支付,1提交支付回调完成,2取消支付(主动取消支付)');
$table->tinyInteger('pay_type')->default(0)->comment('支付类型，1支付宝，2微信');
