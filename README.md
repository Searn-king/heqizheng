## 📐 勾股CMS3.0
![输入图片说明](https://www.gougucms.com/storage/image/slogo.jpg)

### ✅ 相关链接
- 项目会不定时进行更新，**建议⭐star⭐和👁️watch👁️一份**。
- 演示地址：https://www.gougucms.com
- 文档地址：[https://blog.gougucms.com/home/book/detail/bid/1.html](https://blog.gougucms.com/home/book/detail/bid/1.html)
- 开发交流QQ群：24641076

### ⭕ 开源项目
1. [开源项目系列：勾股OA —— OA协同办公系统框架](https://gitee.com/gougucms/office)
2. [开源项目系列：勾股CMS —— CMS内容管理系统框架](https://gitee.com/gougucms/gougucms)
3. [开源项目系列：勾股BLOG —— 个人&工作室博客系统](https://gitee.com/gougucms/blog)
4. [开源项目系列：勾股DEV —— 项目研发管理系统](https://gitee.com/gougucms/dev)
5. [开源项目系列：勾股Admin —— 基于Layui的Web UI解决方案。](https://gitee.com/gouguopen/guoguadmin.git)

### 📋 系统介绍

- 勾股CMS是一套基于ThinkPHP6 + MySql + Layui 开发的轻量级、高性能极速后台开发框架。
- 通用型的后台权限管理框架，各管理模块操作简单，开箱即用。支持前后台用户的操作记录覆盖跟踪。
- 系统整合了易用、且功能强大的富文本编辑器（TinyMCE）与现今流行的Mardown编辑器（Editor.md）于自身，可在后台配置根据习惯切换不同的编辑器。**封装和优化后，支持截屏复制粘贴上传图片，支持拖拽上传图片。**
- 系统易于功能扩展，方便二次开发，帮助开发者简单高效降低二次开发的成本，满足专注业务深度开发的需求。
- 可以快速基于此系统进行ThinkPHP6的快速开发，免去每次都写一次后台基础的痛苦，让开发更多关注业务逻辑。既能快速提高开发效率，帮助公司节省人力成本，同时又不失灵活性。
- 可去版权，真正意义的永久免费，可商用的后台开发框架。

### ✳️ 功能矩阵

![功能导图](https://www.gougucms.com/storage/image/gougucms2.0.png "功能导图")
系统后台集成了主流的通用功能，如：登录验证、系统配置、操作日志管理、角色权限管理、功能管理（后台菜单管理）、导航设置、网站地图、轮播广告、TAG关键字管理、搜索关键字管理、文件上传、数据备份/还原、文章功能、商品功能、单页面管理、用户管理、用户操作日志、用户注册/登录、 API接口等。更多的个性化功能可以基于当前系统便捷做二次开发。

具体功能如下：

~~~
系统
│        		
├─系统管理           		
│  ├─系统配置
│  ├─功能模块
│  ├─功能节点
│  ├─权限角色
│  ├─管 理 员
│  ├─操作日志
│  ├─数据备份
│  ├─数据还原
│
├─基础数据
│  ├─导航设置
│  ├─网站地图
│  ├─轮播广告
│  ├─SEO关键字
│  ├─搜索关键词 
│ 
├─平台用户
│  ├─用户等级
│  ├─用户管理
│  ├─操作记录
│  ├─操作日志
│
├─资讯中心
│  ├─文章分类
│  ├─文章列表
│
├─商品中心
│  ├─商品分类
│  ├─商品列表
│
├─单 页 面
│  ├─单页面列表
│  ├─商业智能
├─...
~~~

### 📚 安装教程

**一、服务器。**

服务器最低配置：
~~~
    1核CPU (建议2核+)
    1G内存 (建议4G+)
    1M带宽 (建议3M+)
~~~
服务器运行环境要求：
~~~
    PHP >= 7.1  
    Mysql >= 5.5.0 (需支持innodb引擎)  
    Apache 或 Nginx  
    PDO PHP Extension  
    MBstring PHP Extension  
    CURL PHP Extension  
    Composer (用于管理第三方扩展包)
~~~

**二、系统安装**

**方式一：完整包安装**

   第一步：前往官网下载页面 (https://www.gougucms.com) 下载完整包解压到你的项目目录 

   第二步：添加虚拟主机并绑定到项目的public目录    

   第三步：访问 http://www.yoursite.com/install/index 进行安装 


**方式二：命令行安装（推荐）**

推荐使用命令行安装，因为采用命令行安装的方式可以和勾股CMS随时保持更新同步。使用命令行安装请提前准备好Git、Composer。

Linux下，勾股CMS的安装请使用以下命令进行安装。  

第一步：克隆勾股CMS到你本地  
    git clone https://gitee.com/gougucms/gougucms.git  

第二步：进入目录  
    cd gougucms  
    
第三步：下载PHP依赖包 【PS：如果是php8.0的环境，先用根目录的composer.php8.json替换覆盖composer.json后再安装】
    
composer install  
    
第四步：添加虚拟主机并绑定到项目的public目录  
    
第五步：访问 http://www.yoursite.com/install/index 进行安装

⚠️ **注意：安装过程中，系统会自动创建数据库，请确保填写的数据库用户的权限可创建数据库，如果权限不足，请先手动创建空的数据库，然后填写刚创建的数据库名称和用户名也可完成安装。** 

🔺 **提醒：安装过程中，如果进度条卡住，一般都是数据库写入权限或者安装环境配置问题，请注意检查。遇到问题请到QQ群：24641076 反馈** 

✅ **PS：如需要重新安装，请删除目录里面 config/install.lock 的文件，即可重新安装。** 

**三、伪静态配置**

**Nginx**
修改nginx.conf 配置文件 加入下面的语句。
~~~
    location / {
        if (!-e $request_filename){
        rewrite  ^(.*)$  /index.php?s=$1  last;   break;
        }
    }
~~~

**Apache**
把下面的内容保存为.htaccess文件放到应用入 public 文件的同级目录下。
~~~
    <IfModule mod_rewrite.c>
    Options +FollowSymlinks -Multiviews
    RewriteEngine On
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^(.*)$ index.php?/$1 [QSA,PT,L]
    </IfModule>
~~~


### ❓ 常见问题

1.  安装失败，可能存在php配置文件禁止了putenv 和 proc_open函数。解决方法，查找php.ini文件位置，打开php.ini，搜索 disable_functions 项，看是否禁用了putenv 和 proc_open函数。如果在禁用列表里，移除putenv proc_open然后退出，重启php即可。

2.  如果安装后打开页面提示404错误，请检查服务器伪静态配置，如果是宝塔面板，网站伪静态请配置使用thinkphp规则。

3.  如果提示当前权限不足，无法写入配置文件config/database.php，请检查database.php是否可读，还有可能是当前安装程序无法访问父目录，请检查PHP的open_basedir配置。

4.  如果composer install失败，请尝试在命令行进行切换配置到国内源，命令如下composer config -g repo.packagist composer https://mirrors.aliyun.com/composer/。

5.  访问 http://www.yoursite.com/install/index ，请注意查看伪静态请配置是否设置了thinkphp规则。

6.  遇到问题请到QQ群：24641076 反馈。

### 🖼️ 截图预览

|页面截图      |    部分截图|
| :--------: | :--------:|
| ![功能导图](https://www.gougucms.com/storage/image/p1.png "功能导图")|![功能导图](https://www.gougucms.com/storage/image/p2.png "功能导图")|
|![功能导图](https://www.gougucms.com/storage/image/p3.png "功能导图")|![功能导图](https://www.gougucms.com/storage/image/p4.png "功能导图")|

### ⭐ 开源助力
- 勾股CMS遵循Apache2开源协议发布，并提供免费使用。 
- 开源的系统少不了大家的参与，如果大家在体验的过程中发现有问题或者BUG，请到Issue里面反馈，谢谢！
- 毫无保留的真开源，如果觉得勾股CMS不错，不要吝啬您的赞许和鼓励，请给我们⭐ STAR ⭐吧！

### 👍 支持我们
- If the project is very helpful to you, you can buy the author a cup of coffee☕.
- 如果这个项目对您有帮助，可以请作者喝杯咖啡吧☕

|支付宝      |    微信|
| :--------: | :--------:|
| <img src="https://www.gougucms.com/static/home/images/zfb.png" width="300"  align=center />|<img src="https://www.gougucms.com/static/home/images/wx.png" width="300"  align=center />|