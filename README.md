# UUID vs Auto Increment

A proposta desse repositório é apresentar uma discussão sobre duas formas de gerar chaves primárias para identificação de entidades em banco de dados. 

## Chave primária

Bancos de dados relacionais são grafos onde os nós são chamados de entidades e as arestas são chamadas de relacionamentos. Para expressar uma relação entre duas entidades, precisamos de uma forma única de identificar uma entidade.

Podemos usar *chaves semânticas*, onde um atributo é utilizado para identificar a entidade, por exemplo: E-mail, nome de usuário ou CPF, ou podemos usar que não tem relação nenhuma com a entidade e são criadas no momento da persistência da  entidade, como um número sequencial ou um *hash*.

## O uso do Auto Increment

Basicamente, uma chave auto incremental é um número que é incrementado a cada vez que uma linha é inserida no baco de dados.

Por exemplo:

```sql
mysql> CREATE TABLE person (
    entity_id INTEGER PRIMARY KEY auto_increment,
    name VARCHAR(120) NOT NULL
) engine=InnoDb;
```

```sql
mysql> INSERT INTO person (name) VALUES ('John Due');
```

```sql
mysql> SELECT * FROM PERSON;
 entity_id | name
-----------+-----------
         1 | John Due
```

```sql
mysql> INSERT INTO person (name) VALUES ('Mr. Potato');
```

```sql
mysql> SELECT * FROM PERSON;
 entity_id | name
-----------+-----------
         1 | John Due
         2 | Mr. Potato
```

### Vantagens

#### Lógica de geração de chaves simples de implementar

Basicamente pego a última chave utilizada e incremento um (ou qualquer outro valor para salto)

#### É possível utilizá-la para order listas

É possível utilizar a chave primária como critério de ordenação em uma coleção de entidades.

#### Amigável

Chaves primárias `auto_increment` são mais amigáveis ao usuário. 

Ex: `https://meusite.com.br/pedidos/123`

### Problemas

#### Auto Increment não é único entre tabelas

Auto Increment pode identificar uma entidade dentro do escopo de uma tabela, mas apenas isso. Caso você queira fazer alguma mudança no sa sua estrutura de armazenamento que precise unir duas tabelas, você precisará rever e atualizar as chaves primárias e suas relações.

#### Expõe informações sensíveis da sua aplicação

Expõe uma informação sensível da sua aplicação, permitindo adivinhar facilmente o número aproximado de registros e sua taxa de crescimento.

Por exemplo:

```
GET https://www.minhaaplicação.com.br/v1/person/1

HTTP/1.1 200 Ok

BODY

{
  "entity_id": 1,
  "name": "John Due"
}
```

```
GET https://www.minhaaplicação.com.br/v1/person/2

HTTP/1.1 200 Ok

BODY

{
  "entity_id": 1,
  "name": "John Due"
}
```

#### É preciso uma operação atômica para obter-e-incrementar

Para manter a integridade de registros que utilizam `auto_increment`, seu banco de dados deve executar atomicamente duas operações, sendo uma para obter o último valor utilizado e outra para incrementar o próximo valor.

## UUID (Universally Unique IDentifier)

Você já deve ter visto algum identificador como `c0b656b1-7351-4dc2-84c8-62a2afb41e6` em algum lugar. Isso é um UUID.

O UUID foi padronizado pela OSF (Open Software Foundation) como um identificador padrão para softwares.

A intenção é realmente criar um idenficador único que possa ser compartilhado com outros softwares, ou seja, facilitar a troca de informações entre sistemas distribuídos.

UUID consiste em um número hexadecimal de 128 bits a forma de garantir que sua entidade terá uma identificação única entre tabelas e instâncias de bancos de dados.

**2^128 = 340.282.366.920.938.000.000.000.000.000.000.000.000**

### Vantagens

#### Único entre tabelas, bancos de dados ou servidores

Como a probabilidade de gerar dois UUIDs iguais é praticamente impossível, essa estratégia permite utilizar a chave primária como identificar únido de uma entidade entre vários sistemas.

#### Facilita a junção de registros entre tabelas

Se, por algum motivo, você precisar fazer a junção de registros entre duas tabelas, não haverá problemas de duplicidade entre chaves primárias.

#### É possível gerar o ID de qualquer lugar (banco de dados ou aplicação)

A estratégia de geração de UUIDs pode ficar no banco de dados ou na aplicação.

#### Quase impossível de colidir

Se fossem gerados 1 bilhão de UUIDs por segundo nos próximos 100 anos, a probabilidade de gerar um UUID duplicado seria de 50%.

### Desvantagens

#### UUID não é nativo no MySQL

O MySQL permite gerar UUID a partir da função UUID(), mas não possui um tipo nativo para persistir UUID, e para isso precisamos recorrer a algumas artimanhas.

#### Pode prejudicar a performance do seu banco de dados



#### Não é possível utilizá-lo como forma de ordenar registros

Como o UUID é randomico, não é possível utilizá-lo como forma de order os registros na ordem em que eles foram inseridos, apesar de isso ser facilmente resolvido criando atributos `created` e `updated`.

#### Não é amigável para o usuário

`http://example.com/user/035a46e0655011ddad8b0800200c9a66/appointment/2b1f4dc3565b42439e0e9f15e24cb377`

## UUID no MySQL

O MySQL possui uma função chamada [UUID](https://dev.mysql.com/doc/refman/5.7/en/miscellaneous-functions.html#function_uuid), responsável por gerar um UUID.

```sql
mysql> SELECT uuid();
+--------------------------------------+
| uuid()                               |
+--------------------------------------+
| 6f20d381-27bd-11e7-9247-0242ac110002 |
+--------------------------------------+
1 row in set (0.00 sec)
```

Podemos utilizar o UUID criando um atributo do tipo `CHAR(32)`.

```sql
mysql> CREATE TABLE person (
    entity_id CHAR(16),
    name VARCHAR(120) NOT NULL,
    primary key (entity_id)
) engine=InnoDb;
```

E podemos inserir dessa forma:

```sql
mysql> INSERT INTO person values (UUID(), 'John Due');
```

O problema dessa abordagem é que ela gera um valor literal e isso pode prejudiar a performance do banco de dados (MELHORAR ESSA ARGUMENTAÇÃO).

Uma outra forma de utilizar o UUID em MySQL é persistí-lo em forma de binário. Podemos utilizar um atributo do tipo `BINARY(16)` para armazenar nossa chave.

Para isso isso, precisamos de uma função que remova os caracteres *dash's* do nosso UUID.

```sql
mysql> SELECT REPLACE(UUID(), '-', '');
+----------------------------------+
| REPLACE(UUID(), '-', '')         |
+----------------------------------+
| 3a994fd428aa11e7a3950242ac110002 |
+----------------------------------+
1 row in set (0.00 sec)
```

```sql
mysql> DELIMITER //
CREATE DEFINER=`root`@`localhost` FUNCTION `binary_uuid`()
RETURNS binary(16) DETERMINISTIC
RETURN UNHEX(REPLACE(UUID(), '-', ''))
//
DELIMITER ;
```

## UUID na aplicação

### PHP

```shell
$ composer require ramsey/uuid
```

```php
<?php

use Ramsey\Uuid\Uuid;

require_once __DIR__.'/vendor/autoload.php';

// Generate a version 4 (random) UUID object
$uuid4 = Uuid::uuid4();
echo $uuid4->toString() . "\n"; // 25769c6c-d34d-4bfe-ba98-e0ee856f3e7a
```

### JavaScript

```
$ npm install node-uuid
```

```js
var uuid = require('node-uuid');
var uuid4 = uuid.v4();

console.log(uuid); // 32a4fbed-676d-47f9-a321-cb2f267e2918
```

# Bibliografia

https://www.clever-cloud.com/blog/engineering/2015/05/20/why-auto-increment-is-a-terrible-idea/
https://en.wikipedia.org/wiki/Universally_unique_identifier#Random_UUID_probability_of_duplicates
http://kccoder.com/mysql/uuid-vs-int-insert-performance/
http://krow.livejournal.com/497839.html
http://stackoverflow.com/questions/30461895/the-differences-between-int-and-uuid-in-mysql
https://packagist.org/packages/ramsey/uuid
https://jaydson.com/uuid-identificador-unico-universal/