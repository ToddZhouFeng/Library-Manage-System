# Library-Manage-System
大作业



# 要实现的页面


- [x] 登录界面
    - [x] 用户的注册界面
    - [x] 用户主界面
        - [x] 个人资料界面
        - [x] 借阅界面
        - [x] 书籍详细信息界面
        - [ ] 历史书目界面
    - [x] 管理员界面
        - [ ] 超时的界面
        - [x] 用户总览
        - [x] 图书总览界面
        - [ ] 补书界面
- [ ] 操作系统说明界面



# 采用的配置

前端：Bootstrap + JS

后端：xampp（Windows） + sql server 或 lamp（Linux） + sql server



# 详细的实现过程

## 基础页面

为了让页面显得更简洁（也便于编程），对页面的样式作一些规定：

1. 页面主体由四大部分组成：导航栏、信息栏、输入框、结果框



# Trigger Used

```sql
//借书的 Trigger
CREATE TRIGGER Borrowbook
ON Borrowing
After insert 
AS
begin 
DECLARE @IS NCHAR(13),@CN INT,@RID INT
SELECT @IS=ISBN,@CN=CounterNum,@RID=ReaderID FROM INSERTED
UPDATE Books 
SET State=1
WHERE CountNumber=@CN AND ISBN=@IS;
UPDATE Readers
SET Availability=Availability-1
WHERE ReaderID=@RID
end

//还书的Trigger
CREATE TRIGGER StateRenew ON History
After insert 
AS 
begin 
declare @IS Nchar(13),@CN INT,@RID INT
SELECT @IS=ISBN,@CN=CounterNum,@RID=ReaderID FROM INSERTED
UPDATE Books
SET State=0 
WHERE ISBN=@IS AND CountNumber=@CN
DELETE FROM Borrowing WHERE ISBN=@IS AND CounterNum=@CN
UPDATE Readers
SET Availability=Availability+1
WHERE ReaderID=@RID
end
```

# 一些参考资料

php 手册：[https://php.golaravel.com/](https://php.golaravel.com/)

php 连接 sqlsrv 的方法：[https://www.cnblogs.com/weblm/p/5317664.html](https://www.cnblogs.com/weblm/p/5317664.html)

登录与退出的解决方法：[https://blog.csdn.net/qq_31906983/article/details/84074264](https://blog.csdn.net/qq_31906983/article/details/84074264)

