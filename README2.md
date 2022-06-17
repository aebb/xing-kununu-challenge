### Technical Assessment

#### Tech Stack:
- Docker
- PHP 8.1
- Symfony 6.0
- Redis
- MySQL

#### Install and Run:

Inside the app folder ```docker-compose up -d```

PHP Container access: ```docker exec -it task-xing /bin/bash```

Database access: ```docker exec -it database-xing mysql -u root -proot database-xing```

##### Install dependencies

```composer update -vvv```

##### Create the database (if not created automatically)
```php bin/console doctrine:database:create```

##### Run the migrations
```php bin/console doctrine:migration:migrate```

##### Add some sample data to the database (load data from data.json, may take a few seconds)
```php bin/console doctrine:fixtures:load```

##### Run consumers
```
php bin/console messenger:consume async -vv
```

#### Tests:

##### To run the tests

```vendor/bin/phpunit -c phpunit.xml ./tests```

#### Quality Tools:

##### Run code beautifier

```vendor/bin/phpcbf```

##### Run code sniffer

```vendor/bin/phpcs```

##### Run mess detector

```vendor/bin/phpmd ./src text ./phpmd.xml```

composer.json also contains shortcuts for these commands

        "test-unit": "vendor/bin/phpunit -c phpunit.xml ./tests/Unit",
        "test-integration":"vendor/bin/phpunit -c phpunit.xml ./tests/Integration",
        "run-tests": [
            "@test-unit",
            "@test-integration"
        ],
        "phpcs": "vendor/bin/phpcs",
        "phpcbf": "vendor/bin/phpcbf",
        "phpmd": "vendor/bin/phpmd ./src text ./phpmd.xml"

#### Solution:

##### PHP:
- PSR-12 Standard
- Endpoints require token authentication from a (see config/packages/security.yaml)
- POST /review (Create a review)
```
curl --location --request POST 'http://localhost:8080/review' \
--header 'X-AUTH-TOKEN: user1token' \
--header 'Content-Type: application/json' \
--data-raw '{
   "title": "overall good company",
   "pro": "great salary, great benefits",
   "contra": "something bad",
   "suggestions": "be better",
   "company": 1,
   "rating": {
       "culture": 1,
       "management": 2,
       "workLifeBalance": 1,
       "careerDevelopment": 1 
   }
}' | python -mjson.tool
```

Creating a new review sends a message to a queueing service (redis) that gets picked up by a php consumer
The message is used to calculate the new company rating and sentiment analysis


- GET /company/best (List the top 10 companies)

Optional query parameters: start, count, search(by company name)
```
curl --location --request GET 'http://localhost:8080/company/best?start=1&count=5&search=company' \
--header 'X-AUTH-TOKEN: user1token' | python -mjson.tool
```

- Enhancement: Sentiment analysis

Sentiment analysis is performed with Valence Aware Dictionary for Sentiment Reasoning php library

Relies on a dictionary that maps lexical features to emotion intensities

##### Tests:
- PHPUnit for tests (code coverage ~100%):
1) Integration: In-memory SQLLite for persistence, In-memory transport for queue system
2) Unit: PHPUnit mock objects for dependencies:
3) Postman collection: xing-challenge.postman_collection.json

### Remarks and future work:

- Use timestamps, addresses and other information to determine if a review is abusive instead on relying on VADER alone.
- Consider calculating the rating of a company after meeting a certain number of reviews (e.g 30+)
- Consider limiting the number of reviews used to calculate the rating of a company (e.g last 100, reviews after X date...)
- Consider calculating the rating of a company every X hours instead everytime a review is added. Cache that value
