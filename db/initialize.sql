/* 
	Path:/Applications/MAMP/htdocs/individual_production/db
	Path:C:\xampp\htdocs\xampp\individual_production\db
*/

drop table if exists room_reservation;
drop table if exists customer_info;
drop table if exists room_info;
drop table if exists room_grade;

CREATE TABLE room_reservation ( reservation_id integer primary key autoincrement, customer_id integer, room_id integer, checkin_date text, checkout_date text );
CREATE TABLE customer_info ( customer_id integer primary key autoincrement, customer_name text, customer_phone_number text, customer_email text, customer_password text );
CREATE TABLE room_info ( room_id integer primary key autoincrement, room_num integer, number_of_people integer, room_grade_id integer );
CREATE TABLE room_grade ( room_grade_id integer primary key autoincrement, room_grade_name text, fee_per_person integer );

insert into room_reservation ( customer_id, room_id, checkin_date, checkout_date ) values ( 2, 1, "2019-02-15", "2019-02-16" );
insert into room_reservation ( customer_id, room_id, checkin_date, checkout_date ) values ( 2, 1, "2019-02-10", "2019-02-11" );
insert into room_reservation ( customer_id, room_id, checkin_date, checkout_date ) values ( 2, 1, "2019-02-11", "2019-02-12" );
insert into room_reservation ( customer_id, room_id, checkin_date, checkout_date ) values ( 3, 2, "2019-02-15", "2019-02-17" );
insert into room_reservation ( customer_id, room_id, checkin_date, checkout_date ) values ( 4, 3, "2019-02-15", "2019-02-18" );

insert into customer_info ( customer_name, customer_phone_number, customer_email, customer_password ) values ( "管理者", "000-0000", "administrator", "admin" );
insert into customer_info ( customer_name, customer_phone_number, customer_email, customer_password ) values ( "太郎", "000-0000", "taro", "taro" );
insert into customer_info ( customer_name, customer_phone_number, customer_email, customer_password ) values ( "次郎", "000-0000", "jiro", "jiro" );
insert into customer_info ( customer_name, customer_phone_number, customer_email, customer_password ) values ( "三郎", "000-0000", "saburo", "saburo" );

insert into room_info ( room_num, number_of_people, room_grade_id ) values ( 111, 1, 1 );
insert into room_info ( room_num, number_of_people, room_grade_id ) values ( 112, 1, 1 );
insert into room_info ( room_num, number_of_people, room_grade_id ) values ( 113, 1, 1 );
insert into room_info ( room_num, number_of_people, room_grade_id ) values ( 121, 1, 2 );
insert into room_info ( room_num, number_of_people, room_grade_id ) values ( 122, 1, 2 );
insert into room_info ( room_num, number_of_people, room_grade_id ) values ( 123, 1, 2 );
insert into room_info ( room_num, number_of_people, room_grade_id ) values ( 131, 1, 3 );
insert into room_info ( room_num, number_of_people, room_grade_id ) values ( 132, 1, 3 );
insert into room_info ( room_num, number_of_people, room_grade_id ) values ( 133, 1, 3 );
insert into room_info ( room_num, number_of_people, room_grade_id ) values ( 211, 2, 1 );
insert into room_info ( room_num, number_of_people, room_grade_id ) values ( 212, 2, 1 );
insert into room_info ( room_num, number_of_people, room_grade_id ) values ( 213, 2, 1 );
insert into room_info ( room_num, number_of_people, room_grade_id ) values ( 221, 2, 2 );
insert into room_info ( room_num, number_of_people, room_grade_id ) values ( 222, 2, 2 );
insert into room_info ( room_num, number_of_people, room_grade_id ) values ( 223, 2, 2 );
insert into room_info ( room_num, number_of_people, room_grade_id ) values ( 231, 2, 3 );
insert into room_info ( room_num, number_of_people, room_grade_id ) values ( 232, 2, 3 );
insert into room_info ( room_num, number_of_people, room_grade_id ) values ( 233, 2, 3 );
insert into room_info ( room_num, number_of_people, room_grade_id ) values ( 311, 3, 1 );
insert into room_info ( room_num, number_of_people, room_grade_id ) values ( 312, 3, 1 );
insert into room_info ( room_num, number_of_people, room_grade_id ) values ( 313, 3, 1 );
insert into room_info ( room_num, number_of_people, room_grade_id ) values ( 321, 3, 2 );
insert into room_info ( room_num, number_of_people, room_grade_id ) values ( 322, 3, 2 );
insert into room_info ( room_num, number_of_people, room_grade_id ) values ( 323, 3, 2 );
insert into room_info ( room_num, number_of_people, room_grade_id ) values ( 331, 3, 3 );
insert into room_info ( room_num, number_of_people, room_grade_id ) values ( 332, 3, 3 );
insert into room_info ( room_num, number_of_people, room_grade_id ) values ( 333, 3, 3 );

insert into room_grade ( room_grade_name, fee_per_person ) values ( "B", 3000 );
insert into room_grade ( room_grade_name, fee_per_person ) values ( "A", 5000 );
insert into room_grade ( room_grade_name, fee_per_person ) values ( "S", 10000 );