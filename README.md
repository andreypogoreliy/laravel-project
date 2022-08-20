#1 indexes
Селективность просматривал таким запросом
~~~~sql
SELECT column_name, COUNT(*) FROM `tabledata` GROUP BY `column_name`;
~~~~
Селективность выше у колонки firstname, чем у age. 
Для запросов 
~~~~sql
select * from users where first_name = 'Wasy' AND last_name = 'Wasyliy';
~~~~
можно создать составной индекс firstname_lastname, в explain c ним rows=1, без него rows=1000(в таблице всего 1000 записей)
~~~~sql
Create index firstname_lastname on userdata(firstname,lastname);
~~~~


Для запросов
~~~~sql
SELECT * FROM userdata WHERE firstname = 'Ellis' and age > 20 AND lastname = 'Cormier';
~~~~
нет смысла создавать индекс age_firstname_lastname, row будет равно 1000. Есть смысл создать firstname_age_lastname или firstname_lastname_age, тогда row будет равно 1

Для запросов
~~~~sql
select * from userdata where age < 20 AND gender = 'M' ORDER BY lastname;
~~~~
будет лучше работать индекс gender_age


Для запросов
~~~~sql
select distinct email from userdata where firstname not in('Ellis', 'Myrtle', 'Pietro');
~~~~
для оператора not in не вижу смысла создавать индекс
