Nanjing HROP TMC Role Booking Online(RBO)
===================================================================================

Nanjing HROP Toastmasters Club online role registion system

1. 关于配置
1) 这个网站用的是 mysql + php，所以支持这两个配置的虚拟主机都可以运行
2) 修改这几个文件，这是数据库账号配置，修改成你将用的
    config/admincfg.php
    classes/sqlutil.php
     classes/mysqlutil.php （这个文件应该不用了，不过保险起见还是改一下）
3） 修改classes/meetingdate.php文件，修改为你们俱乐部的日期
4) 将网站源文件上传，数据库文件导入
     主要数据库描述:
     members 表格： 存储的是会员的信息（其中字段ClubId是俱乐部自己给会员的编号，整数即可）
     meetingagendarols 表格：存储角色预约的信息，存储的数据格式可参考 classes/fieldcontentinagenda.php中方法getFieldContentInAgendaFromInfoProvided之前的注释
     roleNames 表格：是会议可定角色的元定义表格，IsShown字段可决定是否显示出来
     tmcclubs 表格： 这个表格的信息，可以决定在注册角色是，非本俱乐部会员可选的俱乐部列表
     exceptiondate 表格：可以关闭节假日的日期选项，或者显示为提示信息  1 为不显示   2 为现实特定信息，特定信息在exceptionalReason字段编辑

除了regularmeeting是可以自动增加记录外，其他表格，增加记录得直接编辑数据库，这部分代码还没有实现
members记录的修改，可以通过俱乐部官员在网站 www/members.php 页面登录修改

===================================================================================

hroptmc.libredesign.info
For Nanjing HROP TMC 

sstmc.libredesign.info
For Nanjing SS TMC

mandarin.libredesign.info
For Nanjing Mandarin TMC - With chinese translate

newhrop
New version of RBO under implementation

===================================================================================