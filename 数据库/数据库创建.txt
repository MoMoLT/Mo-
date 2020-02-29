create table studentsInfo
(
	学号 varchar(15) not null,
	姓名 varchar(30) not null,
	住址 varchar(50) not null,
	primary key (学号, 住址)
);

create table studentsBalance
(
	学号 varchar(15) primary key,
	卡上余额 double not null,
	水费余额 double not null
);

create table dormsInfo
(
	住址 varchar(50) primary key,
	剩余电量 double not null
);


create table questions
(
	ID int primary key auto_increment,
	联系人 varchar(50),
	联系方式 varchar(100) not null,
	问题 text not null,
	状态 int default 0
);
insert into studentsInfo values
('201655110215', '刘腾', '行健轩四栋B区105号'),
('201655110214', '胡X', '行健轩四栋B区105号'),
('201644210325', '何必', '弘毅轩二栋A区210号'),
('201733200222', '黄X', '致诚轩一栋B区222号'),
('201722120120', '小王', '致诚轩二栋A区120号'),
('201655110200', '小黑', '行健轩五栋A区420号'),
('201612321203', '小红', '行健轩一栋B区321号');


insert into studentsBalance values
('201655110215', 100.01, 100),
('201655110214', 0.02, 12.1),
('201644210325', 23.2, 110),
('201733200222', 333, 333),
('201722120120', 21, 123.23),
('201655110200', 555, 122.29),
('201612321203', 23, 145);

insert into dormsInfo values
('行健轩四栋B区105号', 321.20),
('弘毅轩二栋A区210号', 222.20),
('致诚轩一栋B区222号', 66.60),
('致诚轩二栋A区120号', 73.72),
('行健轩五栋A区420号', 444.66),
('行健轩一栋B区321号', 555.55);
