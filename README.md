# Matrix Multiplication APP

A Lumen app that helps authenticated users to multiply 2 matrices and return the result matrix content in Excel Coulumn format(eg: 1 -> A, 26 -> Z, 27 -> AA). The app has 3 endpoints:
### Registration(/api/register): 
 to register a new user.
 takes name,  email and password in the request and returns a user object with a success message.
### Login(/api/login): 
to login a certain user.
takes email and password in the request and returns a JWT token to be used later for user authentication.
### Matrices multiplication(/api/multiply): 
To allow an authenticated registered user to multiply 2 matrices.
takes a json array of 2 matrices: matrixA, and matrixB with the JWT Bearer Token attached in the header for authorization. 
Returns the result of the multiplication of 

## Getting Started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes.

### Prerequisites

all the necessary requirements for a Lumen service to run on a machine including but not limited to:.
PHP >= 7.2
OpenSSL PHP Extension
PDO PHP Extension
Mbstring PHP Extension
Mysql >= 5.7
Composer (Dependency Manager for PHP)
Postman (To test the endpoints)


### Installing


clone the app using this command

```
git clone ?????????????
```

run composer install command

```
composer install
```

Rename .env.example to .env and make sure you have your database ready and put all the credentials in .env file accordingly.

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=
```

## Running the app

After successfully installing Postman, please import the following collection: 

```
https://www.getpostman.com/collections/f4f23f2c3972e17f6663
```

Once imported, you may find 3 request samples:

-  Register a new user.
-  Login an existing user.
-  Sending a matrix multiplication request.

each of the requests include sample data. You may play around with the requests as you wish.

**Note**
In order to send a matrix multiplication request, make sure to copy the JWT token returned in the response of the login request, and paste it in the header under Authorization; if not, you won't be authorized to make the request.


## Running the unit tests

the MatrixTest.php includes the testMultiply function with a data provider that includes several tests including:
- valid data for correct calculations.
- data with missing required keys.
- data with matrices size mismatch.
- data with non numeric values in the matrices.
- data with negative numbers in the matrices.

To run the tests, please run the following command:

```
vendor/bin/phpunit --testdox
```


## Author

* **Alladin Saoudi** 
