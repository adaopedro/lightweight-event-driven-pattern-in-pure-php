# Event-Driven Pattern em PHP Puro

Este projeto demonstra uma implementação **simples** do padrão **Event-Driven** usando **PHP puro**. A ideia é mostrar como eventos e subscribers podem ser usados para desacoplar responsabilidades em um sistema.

## 📋 Índice

- [Visão Geral](#visão-geral)
- [Estrutura do Projeto](#estrutura-do-projeto)
- [Como Funciona](#como-funciona)
- [Instalação](#instalação)
- [Uso](#uso)
- [Exemplos](#exemplos)
- [Arquitetura](#arquitetura)

## 🎯 Visão Geral

O padrão Event-Driven permite que diferentes partes de um sistema reajam a eventos específicos sem conhecer diretamente umas às outras. Neste exemplo, quando um pedido é criado, múltiplos subscribers são notificados automaticamente para executar suas respectivas tarefas.

### Vantagens do Pattern

- **Desacoplamento**: Services não precisam conhecer quem vai reagir aos eventos
- **Extensibilidade**: Novos subscribers podem ser adicionados facilmente
- **Responsabilidade Única**: Cada subscriber tem uma responsabilidade específica
- **Testabilidade**: Componentes podem ser testados isoladamente

## 📁 Estrutura do Projeto

```
├── app/
│   ├── Event/
│   │   └── OrderPlaced.php          # Evento disparado quando pedido é criado
│   ├── Subscriber/
│   │   ├── EmailNotificationSubscriber.php  # Envia notificações por email
│   │   └── LogSubscriber.php        # Registra logs do pedido
│   └── OrderService.php             # Service principal para pedidos
├── infra/
│   ├── Event/
│   │   └── ListensTo.php            # Attribute para marcar listeners
│   └── EventDispatcher.php          # Despachador de eventos
├── cmd/
│   └── place-order.php              # Script de linha de comando
├── composer.json                    # Configuração do autoloader
└── .gitignore
```

## ⚙️ Como Funciona

1. **Evento**: `OrderPlaced` é disparado quando um pedido é criado
2. **Subscribers**: Classes que "escutam" eventos específicos usando o attribute `#[ListensTo]`
3. **Dispatcher**: `EventDispatcher` gerencia o registro e execução dos subscribers
4. **Service**: `OrderService` executa a lógica de negócio e dispara eventos

### Fluxo de Execução

```
OrderService::placeOrder()
    ↓
Dispatcher::dispatch(OrderPlaced)
    ↓
EmailNotificationSubscriber::onOrderPlaced()
LogSubscriber::onOrderPlaced()
```

## 🚀 Instalação

1. Clone o repositório:
```bash
git clone <repository-url>
cd event-driven-php
```

2. Instale as dependências:
```bash
composer install
```

## 💻 Uso

Execute o comando para criar um pedido:

```bash
php cmd/place-order.php '{"orderId": "abc-123-000", "client": {"email": "john@gmail.com"}}'
```

### Saída Esperada

```
[Email Notification Subscriber] Order abc-123-000 placed! Sending email to client john@gmail.com
[Log Subscriber] Log for order abc-123-000 created!
```

## 📝 Exemplos

### Schema Completo do Pedido

```json
{
    "orderId": "abc-123-000",
    "datetime": "2024-12-07 14:30:00",
    "client": {
        "userId": 123,
        "name": "John Doe",
        "email": "john@gmail.com"
    },
    "products": [
        {
            "productId": 1,
            "description": "Produto A",
            "unitOfMeasure": "UN",
            "unitPrice": 50.00,
            "quantity": 2,
            "taxPercentage": 10.00,
            "taxType": "ICMS",
            "taxAmount": 10.00,
            "setlementAmount": 110.00
        }
    ],
    "customer": {
        "customerId": 456,
        "name": "Empresa XYZ"
    }
}
```

### Criando um Novo Subscriber

```php
<?php

namespace App\Subscriber;

use App\Event\OrderPlaced;
use Infra\Event\ListensTo;

class InventorySubscriber
{
    #[ListensTo(event: OrderPlaced::class)]
    public function onOrderPlaced(OrderPlaced $event): void
    {
        // Atualizar estoque
        echo "[Inventory] Updating stock for order {$event->payload['orderId']}\n";
    }
}
```

### Registrando o Subscriber

```php
// No place-order.php
$dispatcher->addSubscriber(new InventorySubscriber());
```

## 🏗️ Arquitetura

### Componentes Principais

**EventDispatcher**: Núcleo do sistema que:
- Registra subscribers usando Reflection API
- Identifica métodos marcados com `#[ListensTo]`
- Despacha eventos para os subscribers apropriados
- Inclui tratamento básico de erros

**ListensTo Attribute**: Marca métodos que devem ser executados quando um evento específico ocorre.

**Event Classes**: Carregam dados do evento. No exemplo, `OrderPlaced` contém o payload do pedido.

**Subscribers**: Classes que reagem aos eventos. Cada uma tem responsabilidade específica:
- `EmailNotificationSubscriber`: Envia notificações
- `LogSubscriber`: Registra logs em arquivos

### Padrões Utilizados

- **Observer Pattern**: Subscribers observam eventos
- **Dependency Injection**: EventDispatcher é injetado no OrderService
- **Attribute-Based Configuration**: `#[ListensTo]` para configuração declarativa
- **Single Responsibility**: Cada subscriber tem uma única responsabilidade

## 🔧 Personalização

### Adicionando Novos Eventos

1. Crie a classe do evento em `app/Event/`
2. Dispare o evento no service apropriado
3. Crie subscribers que escutem o novo evento

### Configuração de Logs

Os logs são salvos em `/logs/` com nomes únicos gerados por `uniqid()`. Certifique-se de que o diretório existe e tem permissões de escrita.

## 📚 Conceitos Demonstrados

- **Reflexão em PHP**: Uso da Reflection API para descobrir attributes
- **Attributes (PHP 8+)**: Configuração declarativa de event listeners
- **Autoloading PSR-4**: Organização de classes com namespaces
- **Event Sourcing básico**: Registro de eventos para auditoria
- **Separation of Concerns**: Cada subscriber tem responsabilidade específica

---

*Este é um projeto educacional para demonstrar conceitos de arquitetura orientada a eventos em PHP.*