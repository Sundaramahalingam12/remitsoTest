Instructions to run the Application


Initially Goto your local directory where you want to clone the project folder and open cmd for the directory
git clone https://github.com/Sundaramahalingam12/remitsoTest.git -b main
Then run this command to get the project folder.


Create a Database in Mysql with a name <account_management>
Ensure you have same database name in .env file
and check your localhost connection.


Then run this cmd: <php artisan migarate> 
to import the tables to datatabse


Then run this cmd: <php artisan db:seed>
to insert the Dummy users for test cases


Now get postman and use the given http request to login and generate the token
POST - http://localhost:8000/api/login


Put,
Headers - Key:Accept , Value:Application/json


Body,
Key - email , Value - admin@email.com
Key - password , Value - admin123


and also have added few users for test cased you can check with,


name => John,
email' => john@mail.com,
password' => John123,


name => Jane,
email => jane@mail.com,
password => Jane123,


Once you hit the login request with the valid credential you will get a token for the logged user like below,

{
    "access_token": "6|xGcYh6QDg1aZ74gNrXr22ME6Dn5wBCNls1m3dPTPbe078261",
    "token_type": "Bearer"
}


You can logout using this http request
POST - http://localhost:8000/api/logout


for logout and other action you need to authenticate the user so you should put the generated token at the authorization
with the type of Bearer token.



Accounts:


Now to create account for logged user
POST - http://localhost:8000/api/accounts


hit this request with POST method with the below params


account_name:My Bank account
account_type:Business
currency:USD
balance:7000


Then you will get the created account details
and copy the account number generated with luhn implementation to fetch accoount details for the next step


remember to put headers
Accept : Application/json 
to every requests


To fetch account details
GET - http://localhost:8000/api/accounts/{account_number}


To update the account details
PUT http://localhost:8000/api/accounts/{account_number}


Use params
balance:8000
currency:GBP
account_name:My Updated Bank account


To deactivate the account
DELETE - http://localhost:8000/api/accounts/{account_number} -> Deactivate account

here i just implemented a soft delete if we want to recover the deactivated account




Transaction:

POST http://localhost:8000/api/transactions -> Log a transaction (Credit/Debit)

Params,

account_number:472142932740296
amount:100
type:credit/debit
description:test description


GET http://localhost:8000/api/transactions?account_id=X&from=YYYY-MM-DD&to=YYYY-MM-DD -> Get transactions

Params format,
account_id:9e8bca78-43e4-4d31-9d77-5d72206ab06e
from:2025-03-27
to:2025-03-28


for account_id you need to put the id value where you fetched account details


{
    "id": "9e8c101c-5f8a-49be-bbae-1a3405a72fcd",
    "user_id": "91096f15-e94b-4abf-b795-8277e81ab379",
    "account_name": "new account",
    "account_number": 7269822437544597,
    "account_type": "Business",
    "currency": "USD",
    "balance": "7000.00",
    "created_at": "2025-03-29T10:27:09.000000Z",
    "updated_at": "2025-03-29T10:27:09.000000Z",
    "deleted_at": null
}