-- mysql -u root -p < request.sql

create database refDB;
use refDB;
create table user (
	userid int unsigned not null auto_increment primary key,
	email varchar(100) not null,
	login varchar(50) not null,
	name varchar(50) not null,
	password char(40) not null
);
create table ref (
	refid int unsigned not null auto_increment primary key,
	userid int unsigned not null,
	initialRef varchar(2048) not null,
	shortedRef varchar(100) not null,
	title varchar(256),
	date timestamp not null,
	count int unsigned
);
create table refDates (
	redirectid int unsigned not null auto_increment primary key,
	date timestamp not null,
	refid int unsigned
);
grant select, insert, update, delete
on ref.*
to root@localhost identified by 'root';
