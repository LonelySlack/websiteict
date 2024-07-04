SELECT emp_num_seq.CURRVAL FROM dual;

SELECT emp_num_seq.NEXTVAL FROM dual;

ALTER SEQUENCE emp_num_seq INCREMENT BY -1;

ALTER SEQUENCE emp_num_seq INCREMENT BY 1;

select * FROM EMPLOYEE;
select * FROM BRANCH;
select * FROM ITEM;
select * FROM BRANCH;
select * from receipt;
select * from receiptdetail;

DELETE FROM receipt WHERE receipt_num = '13';

