# ParseTable

Classe PHP construida utilizando a biblioteca Guzzle e hQuery. Essa classe realiza o scraping de uma tabela html e retorna os dados em formato de array. Essa classe considera a primeira linha da tabela como chave dos valores.

- Parseia tabelas com a estrutura:
```
<tbody><tr><td></td></tr></tbody>
```

## Instalação

- Clonar o repositório do projeto
```
git clone https://github.com/dandico23/ParseTable.git
```
- Instalar as dependências do projeto

```
composer install
```


## Exemplo de uso

```
$example = new ParseTable();
$example->getContent('http://www.guiatrabalhista.com.br/guia/salario_minimo.htm');
$data = $example->getDataTable();
```