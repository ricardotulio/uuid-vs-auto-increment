# UUID vs Auto Increment

A proposta desse repositório é apresentar uma discussão sobre duas formas de gerar chaves primárias para identificação de entidades em banco de dados. 

## Chave primária

Bancos de dados relacionais são grafos onde os nós são chamados de entidades e as arestas são chamadas de relacionamentos. Para expressar uma relação entre duas entidades, precisamos de uma forma única de identificar uma entidade.

Podemos usar *chaves semânticas*, onde um atributo é utilizado para identificar a entidade, por exemplo: E-mail, nome de usuário ou CPF.

Chaves técnicas são chaves que não tem relação nenhuma com a entidade e são criadas no momento da persistência da  entidade.

## O que é uma chave Auto Incremental?

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

## Quais são os problemas que o Auto Increment pode me trazer?

### Auto Increment não é único entre tabelas

Auto Increment pode identificar uma entidade dentro do escopo de uma tabela, mas apenas isso. Caso você queira fazer alguma mudança no sa sua estrutura de armazenamento que precise unir duas tabelas, você precisará rever e atualizar as chaves primárias e suas relações.


### Expõe informações sensíveis da sua aplicação

Expõe uma informação sensível da sua aplicação, permitindo adivinhar facilmente quais são os registros anteriores e sucessores.

### É preciso uma operação atômica para obter-e-incrementar

Para manter a integridade de registros que utilizam `auto_increment`, seu banco de dados deve executar atomicamente duas operações, sendo uma para obter o último valor utilizado e outra para incrementar o próximo valor.

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

## UUID (Universally Unique IDentifier)

Você já deve ter visto algum identificador como `c0b656b1-7351-4dc2-84c8-62a2afb41e6` em algum lugar. Isso é um UUID.

UUID é uma forma de garantir que sua entidade terá uma identificação única entre tabelas e instâncias de bancos de dados.

## UUID em MySQL

O MySQL possui uma função chamada [UUID](https://dev.mysql.com/doc/refman/5.7/en/miscellaneous-functions.html#function_uuid), responsável por gerar um UUID. O problema dessa função é que ela gera um valor literal, o que não é interessante quando pensamos em persistência.

```sql
mysql> SELECT uuid();
+--------------------------------------+
| uuid()                               |
+--------------------------------------+
| 6f20d381-27bd-11e7-9247-0242ac110002 |
+--------------------------------------+
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

```php
<?php


```

# Bibliografia

https://www.clever-cloud.com/blog/engineering/2015/05/20/why-auto-increment-is-a-terrible-idea/
https://en.wikipedia.org/wiki/Universally_unique_identifier#Random_UUID_probability_of_duplicates
http://kccoder.com/mysql/uuid-vs-int-insert-performance/
http://krow.livejournal.com/497839.html
http://stackoverflow.com/questions/30461895/the-differences-between-int-and-uuid-in-mysql